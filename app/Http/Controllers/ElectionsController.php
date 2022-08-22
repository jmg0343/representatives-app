<?php

namespace App\Http\Controllers;

use App\Actions\GetStateOrStates;
use App\Services\GoogleApiService;
use App\Services\RetrieveDataService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class ElectionsController extends BaseController
{
    public function elections (GoogleApiService $apiService)
    {
        $electionsInfo = $apiService->makeApiCall('elections');

        $retrieveDataService = new RetrieveDataService();
        $retrieveDataService->getElectionData();

        return view('pages/elections', ['elections' => $electionsInfo]);
    }

    public function electionInfo (GoogleApiService $apiService, $id, Request $request)
    {
        $address = $request->input('address');
        $city = str_replace(' ', '', $request->input('city'));
        $state = $request->input('state');
        $location = "$address $city $state";

        // create instance of RetrieveDataService and get election data
        $retrieveDataService = new RetrieveDataService();
        $retrieveDataService->getElectionDetails($location, $id);

        $electionsInfo = $apiService->makeApiCall('voterinfo', $location, $id)->toArray();

        if (isset($electionsInfo['error'])) {
            $status = isset($electionsInfo['error']['status']) ? $electionsInfo['error']['status'] : null;
            $data = [
                'error' => $electionsInfo['error'],
                'status' => $status,
                'url' => 'elections'
            ];

            return view('error', $data);
        }

        foreach ($electionsInfo['state'][0]['electionAdministrationBody'] as $key => &$electionUrl) {
            try {
                if (is_string($electionUrl)) {
                    $parsedUrl = parse_url($electionUrl);

                    if (!isset($parsedUrl['scheme']) && $key != 'name') {
                        $electionUrl = "https://$electionUrl";
                    }
                }
            } catch (Throwable $t) {
                continue;
            }
        }

        return view('pages/electionsInfo', ['electionsInfo' => $electionsInfo]);
    }
}
