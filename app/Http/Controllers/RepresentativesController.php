<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

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

    public function representatives()
    {
        $states = $this->states();

        return view('pages/representatives')->with(['states' => $states]);
    }

    public function findReps(Request $request)
    {
        $api_key = config('services.google.key');
        $api_url = "https://www.googleapis.com/civicinfo/v2/representatives";

        $address = $request->input('address');
        $city = str_replace(' ', '', $request->input('city'));
        $state = $request->input('state');

        $concatUrl = "$api_url?key=$api_key&address=$address$city$state";

        $repsInfo = Http::get($concatUrl)->collect();
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

                $office['official'][] = $officials[$index];
            }
                $office['officeLevel'][] = $officeLevel;
                array_push($officialsArray, $office);
        }

        $groupedOfficialsArray = collect($officialsArray)->groupBy('officeLevel');

        $data = [
            'repsInfo' => $groupedOfficialsArray,
            'states' => $this->states()
        ];

        return view('pages/representatives', $data);
    }
}
