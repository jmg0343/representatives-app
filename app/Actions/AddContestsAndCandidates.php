<?php

namespace App\Actions;

use App\Models\Candidate;
use App\Models\Contest;
use App\Models\SocialMedia;

class AddContestsAndCandidates
{
    public function execute($electionData, $id)
    {
        // due to gaps in data, contests may not exist
        $electionContests = $electionData['contests'] ?? [];

        if (empty($electionContests)) {
            Contest::create([
                'election_id' => (int) $id,
                'data_available' => 0,
            ]);

            return;
        }

        foreach ($electionContests as $contest) {
            // I'm never sure what Google will send, so we cover our bases here
            $primaryParty = $contest['primaryParty'] ?? null;
            $office = $contest['office'] ?? null;
            $level = $contest['level']['0'] ?? null;
            $roles = $contest['roles']['0'] ?? null;
            $districtName = $contest['district']['name'] ?? null;
            $districtScope = $contest['district']['scope'] ?? null;
            $districtId = $contest['district']['id'] ?? null;
            $numberElected = $contest['numberElected'] ?? null;
            $numberVotingFor = $contest['numberVotingFor'] ?? null;
            $ballotPlacement = $contest['ballotPlacement'] ?? null;

            // we'll create the records if they don't already exist
            Contest::firstOrCreate(
                [
                    'election_id' => (int) $id,
                    'data_available' => 1,
                    'type' => $contest['type'],
                    'primary_party' => $primaryParty,
                    'office' => $office,
                    'level' => $level,
                    'roles' => $roles,
                    'district_name' => $districtName,
                    'district_scope' => $districtScope,
                    'district_id' => $districtId,
                    'number_elected' => $numberElected,
                    'number_voting_for' => $numberVotingFor,
                    'ballot_placement' => $ballotPlacement,
                ]
            );

            // if candidates data is available, instantiate it
            $candidates = $contest['candidates'] ?? [];

            foreach ($candidates as $candidate) {
                Candidate::firstOrCreate(
                    [
                        'name' => $candidate['name'],
                        'contest_id' => $id,
                        'party' => $candidate['party'],
                        'phone' => $candidate['phone'] ?? null,
                        'email' => $candidate['email'] ?? null,
                        'candidate_url' => $candidate['candidateUrl'] ?? null,
                    ]
                );

                $candidateSocialMedia = $candidate['channels'] ?? [];

                foreach ($candidateSocialMedia as $socialMedia) {
                    SocialMedia::firstOrCreate(
                        [
                            'name' => $socialMedia['type'],
                            'rep_name' => $candidate['name'],
                            'url' => $socialMedia['id'],
                            'handle' => null
                        ]
                    );
                }
            }
        }
    }
}