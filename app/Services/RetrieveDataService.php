<?php

namespace App\Services;

use App\Actions\AddDivisions;
use App\Actions\AddOffices;
use App\Actions\AddSocialMedias;
use App\Models\Address;
use App\Models\Division;
use App\Services\GoogleApiService;
use Illuminate\Support\Facades\Http;
use Exception;

class RetrieveDataService
{
    public function getData ($type, $location = null, $electionId = null)
    {
        $googleApiService = new GoogleApiService();
        $repsInfo = $googleApiService->makeApiCall('representatives', $location);

        $addressInDatabase = Address::where('address', '=', $repsInfo['normalizedInput']['line1'])->first();

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
            'zip' => $address['zip'],
        ]);

        return $newAddressId;
    }
}