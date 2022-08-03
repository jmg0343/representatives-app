<?php

namespace App\Http\Controllers;

use App\Actions\GetStateOrStates;
use App\Services\GoogleApiService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class ElectionsController extends BaseController
{
    public function elections (GoogleApiService $apiService)
    {
        $electionsInfo = $apiService->makeApiCall('elections');

        return view('pages/elections', ['elections' => $electionsInfo]);
    }

    public function electionInfo (GoogleApiService $apiService, $id, Request $request)
    {
        $address = $request->input('address');
        $city = str_replace(' ', '', $request->input('city'));
        $state = $request->input('state');
        $location = "$address$city$state";

        $electionsInfo = $apiService->makeApiCall('voterinfo', $location);

        return view('pages/electionsInfo', ['electionsInfo' => $electionsInfo]);
    }
}
