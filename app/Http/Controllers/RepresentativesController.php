<?php

namespace App\Http\Controllers;

use App\Actions\GetStateOrStates;
use App\Services\GoogleApiService;
use App\Services\RetrieveDataService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class RepresentativesController extends BaseController
{
    public function representatives(GetStateOrStates $getStates)
    {
        // call getStates action to retrieve list of states for form input
        $states = $getStates->execute();

        return view('pages/representatives')->with(['states' => $states]);
    }

    public function findReps(Request $request, GetStateOrStates $getStates)
    {
        $address = $request->input('address');
        $city = str_replace(' ', '', $request->input('city'));
        $state = $request->input('state');

        $location = "$address$city$state";

        // create instance of RetrieveDataService and get rep data
        $retrieveDataService = new RetrieveDataService();
        $retrieveDataService->getRepData($location);

        $googleApiService = new GoogleApiService();
        $repsInfo = $googleApiService->makeApiCall('representatives', $location);

        if (isset($repsInfo['error'])) {
            $status = isset($repsInfo['error']['status']) ? $repsInfo['error']['status'] : null;
            $data = [
                'error' => $repsInfo['error'],
                'status' => $status,
                'url' => 'reps'
            ];

            return view('error', $data);
        }

        $offices = collect($repsInfo['offices']);
        $officials = collect($repsInfo['officials']);

        $officialsArray = [];
        foreach ($offices as $office) {
            $officeLevel = null;
            foreach ($office['officialIndices'] as $index) {

                if ($office['levels'][0] == 'country') {
                    $officeLevel = 'federal';
                } elseif ($office['levels'][0] == 'administrativeArea1') {
                    $officeLevel = 'state';
                } else {
                    $officeLevel = 'local';
                }

                $office['official'][] = [$officials[$index], $index];
            }
                $office['officeLevel'][] = $officeLevel;
                array_push($officialsArray, $office);
        }

        $groupedOfficialsArray = collect($officialsArray)->groupBy('officeLevel');

        $data = [
            'repsInfo' => $groupedOfficialsArray,
            'states' => $getStates->execute()
        ];

        return view('pages/representatives', $data);
    }
}
