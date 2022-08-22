<?php

namespace App\Actions;

use App\Models\PollingLocation;

class AddPollingLocations
{
    public function execute($electionData, $id)
    {
        $pollingLocations = [
            'Polling' => $electionData['pollingLocations'] ?? [],
            'Early Voting' => $electionData['earlyVoteSites'] ?? [],
            'Drop Off' => $electionData['dropOffLocations'] ?? []
        ];

        foreach ($pollingLocations as $key => $locations) {
            if (!empty($locations)) {
                foreach ($locations as $location) {
                    PollingLocation::create([
                        'election_id' => $id,
                        'type' => $key,
                        'name' => $location['address']['locationName'] ?? null,
                        'address' => $location['address']['line1'] ?? null,
                        'city' => $location['address']['city'] ?? null,
                        'state' => $location['address']['state'] ?? null,
                        'zip' => $location['address']['zip'] ?? null,
                        'notes' => $location['notes'] ?? null,
                        'hours' => $location['pollingHours'] ?? null,
                        'services' => $location['voterServices'] ?? null,
                        'start_date' => $location['startDate'] ?? null,
                        'end_date' => $location['endDate'] ?? null,
                    ]);
                }
            }
        }
    }
}