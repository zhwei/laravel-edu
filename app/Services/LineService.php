<?php

namespace App\Services;

use GuzzleHttp\Client;

class LineService
{
    public function verify(string $token)
    {
        $client = new Client();
        $verifyResp = $client->post('https://api.line.me/oauth2/v2.1/verify', [
            'form_params' => [
                'id_token' => $token,
                'client_id' => config('services.line.channel_id'),
            ]
        ]);
        $data = json_decode($verifyResp->getBody()->getContents(), true);
        return $data['sub'];
    }
}
