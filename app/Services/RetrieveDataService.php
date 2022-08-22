<?php

namespace App\Services;

use App\Actions\AddContestsAndCandidates;
use App\Actions\AddDivisions;
use App\Actions\AddOffices;
use App\Actions\AddPollingLocations;
use App\Actions\AddSocialMedias;
use App\Models\Address;
use App\Models\Contest;
use App\Models\Election;
use App\Models\PollingLocation;
use Carbon\Carbon;

class RetrieveDataService
{
    public function getRepData($location)
    {
        // *** Remember to correct this. Search DB for address FIRST ***

        // call Google API to retrieve address info
        $googleApiService = new GoogleApiService();
        $repsInfo = $googleApiService->makeApiCall('representatives', $location);

        $addressInDatabase = Address::where('address', '=', $repsInfo['normalizedInput']['line1'])->first();

        // if not found in db, then insert all data
        if ($addressInDatabase == null) {
            $newAddressId = $this->insertAddress($repsInfo['normalizedInput']);

            $addDivisions = new AddDivisions();
            $addDivisions->execute($newAddressId, $repsInfo['divisions']);

            $addOffices = new AddOffices();
            $addOffices->execute($repsInfo['offices'], $repsInfo['officials']);

            $addSocialMedias = new AddSocialMedias();
            $addSocialMedias->execute($repsInfo['officials']);
        }
    }

    private function insertAddress($address)
    {
        $newAddressId = Address::insertGetId([
            'address' => $address['line1'],
            'city' => $address['city'],
            'state' => $address['state'],
            'zip' => $address['zip'] ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $newAddressId;
    }

    // Make this a cron-job that runs once daily
    public function getElectionData()
    {
        $googleApiService = new GoogleApiService();
        $electionsInfo = $googleApiService->makeApiCall('elections');

        if ($electionsInfo) {
            foreach ($electionsInfo as $electionInfo) {
                // Retrieve election by id or create it with attributes
                $election = Election::firstOrCreate(
                    ['election_id' => (int) $electionInfo['id']],
                    [
                        'name' => $electionInfo['name'],
                        'date' => $electionInfo['electionDay'],
                        'ocd_id' => $electionInfo['ocdDivisionId'],
                    ]
                );
            }
        }
    }

    public function getElectionDetails($location, $id)
    {
        // add address and reps to db
        $this->getRepData($location);

        // determine if contest is currently tracked
        $currentlyTrackedContests = Contest::where('election_id', '=', $id)->get();
        $dataAvailable = $currentlyTrackedContests->first()->data_available ?? null;

        // if contest is not currently tracked, call api and update db
        if ($currentlyTrackedContests->isEmpty() || $dataAvailable == 0) {
            $googleApiService = new GoogleApiService();
            $electionData = $googleApiService->makeApiCall('voterinfo', $location, $id);

            // update election table with voting info urls
            $electionDataUrl = $electionData['state'][0]['electionAdministrationBody'] ?? null;
            $electionDataUrlArray = [
                'election_info_url' => $electionDataUrl['electionInfoUrl'] ?? null,
                'election_registration_url' => $electionDataUrl['electionRegistrationUrl'] ?? null,
                'election_registration_confirmation_url' => $electionDataUrl['electionRegistrationConfirmationUrl'] ?? null,
                'absentee_voting_info_url' => $electionDataUrl['absenteeVotingInfoUrl'] ?? null,
                'voting_location_finder_url' => $electionDataUrl['votingLocationFinderUrl'] ?? null,
                'ballot_info_url' => $electionDataUrl['ballotInfoUrl'] ?? null,
            ];

            // add https to any url that is not prefixed
            foreach ($electionDataUrlArray as $key => &$value) {
                if ($value != null) {
                    $parsedUrl = parse_url($value);

                    if (!isset($parsedUrl['scheme'])) {
                        $value = "https://$value";
                    }
                }

                // insert updated URLs into election table
                Election::where('election_id', '=', $id)->update([$key => $value]);
            }

            // update contest table in db
            // this will also update candidate social media table
            $addContests = new AddContestsAndCandidates();
            $addContests->execute($electionData, $id);

            // update polling locations table in db
            $addPollingLocations = new AddPollingLocations();
            $addPollingLocations->execute($electionData, $id);
        }
    }
}