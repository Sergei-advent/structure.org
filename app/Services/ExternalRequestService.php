<?php

namespace App\Services;

use GuzzleHttp\Client;

class ExternalRequestService
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function authUser($token) {
        $body = [
          'token' => $token
        ];

        $url = env('MAIN_APP_API') . '/' . env('MAIN_APP_AUTH_ENDPOINT');

        $header = [
            'Accept' =>  'application/json',
            'Content-Type' => 'application/json'
        ];

        $response = $this->client->request('POST', $url, [
            'headers' => $header,
            'json' => $body
        ]);

        return ['body' => json_decode($response->getBody()->getContents()), 'status_code' => $response->getStatusCode()];
    }
}