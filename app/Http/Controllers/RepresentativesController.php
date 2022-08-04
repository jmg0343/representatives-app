<?php

namespace App\Http\Controllers;

use App\Actions\GetStateOrStates;
use App\Services\GoogleApiService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class RepresentativesController extends BaseController
{
    private function states() {
        return [
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
        ];
    }

    public function representatives(GetStateOrStates $getStates)
    {
        $states = $getStates->execute();

        return view('pages/representatives')->with(['states' => $states]);
    }

    public function findReps(Request $request, GetStateOrStates $getStates)
    {
        $address = $request->input('address');
        $city = str_replace(' ', '', $request->input('city'));
        $state = $request->input('state');

        $location = "$address$city$state";

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
