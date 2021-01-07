<?php

namespace App\Services;

use GuzzleHttp\Client;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response;
use RuntimeException;

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

    /**
     * @var LINEBot
     */
    private static $bot;

    public function broadcastTextMessage(string $content)
    {
        $response = self::bot()->broadcast(new TextMessageBuilder($content));
        self::assertBotResponseOk($response);
    }

    public function multicastTextMessage(array $userIds, string $content)
    {
        $response = self::bot()->multicast($userIds, new TextMessageBuilder($content));
        self::assertBotResponseOk($response);
    }

    private static function assertBotResponseOk(Response $response)
    {
        if ($response->isSucceeded()) {
            return true;
        }
        throw new RuntimeException("Line Bot Fail: {$response->getHTTPStatus()},{$response->getRawBody()}");
    }

    private static function bot(): LINEBot
    {
        if (!self::$bot) {
            $httpClient = new CurlHTTPClient(config('line-bot.channel_access_token'));
            self::$bot = new LINEBot($httpClient, ['channelSecret' => config('services.line-bot.channel_secret')]);
        }
        return self::$bot;
    }
}
