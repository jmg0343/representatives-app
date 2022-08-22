<?php

namespace App\Actions;

use App\Models\Division;

class AddDivisions
{
    public function execute($addressId, $divisions)
    {
        foreach ($divisions as $key => $division) {
            $insertedDivision = Division::firstOrCreate(
                ['ocd_id' => $key],
                ['name' => $division['name']]
            );

            $insertedDivision->addresses()->attach($addressId);
        }
    }
}