<?php

namespace App\Actions;

use App\Models\Office;

class AddOffices
{
    public function execute($officeRecords, $officials)
    {
        foreach ($officeRecords as $officeRecord) {
            foreach ($officeRecord['officialIndices'] as $officialIndex) {
                foreach ($officials as $index => $official) {
                    $office = new Office;

                    $findOfficeInDb = $office::where('rep_name', '=', $official['name'])->first();

                    if ($findOfficeInDb == null) {
                        $office->name = $officeRecord['name'];
                        $office->ocd_id = $officeRecord['divisionId'];
                        $office->level = $officeRecord['levels']['0'] ?? null;

                        if ($officialIndex == $index) {
                            $office->rep_name = $official['name'];
                            $office->party = $official['party'] ?? 'N/A';
                            $office->phone = $official['phones'][0] ?? 'N/A';
                            $office->address = $official['geocodingSummaries'][0]['queryString'] ?? 'N/A';
                            $office->email = $official['emails'][0] ?? 'N/A';
                            $office->image = $official['photoUrl'] ?? null;
                            $office->save();
                        }
                    }
                }
            }
        }
    }
}