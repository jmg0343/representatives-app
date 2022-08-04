<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleApiService
{
    const API_URL = "https://www.googleapis.com/civicinfo/v2/";

    public function makeApiCall ($type, $location = null, $elecionId = null)
    {
        $api_key = config('services.google.key');
        $url = self::API_URL . "$type?key=$api_key";

        if ($type == 'elections') {
            $response = Http::get($url)->json('elections');
            array_shift($response);

            return $response;
        }

        $url = "$url&address=$location";

        if ($elecionId != null) {
            $url = "$url&electionId=$elecionId";
        }

        return Http::get($url)->collect();
    }
}