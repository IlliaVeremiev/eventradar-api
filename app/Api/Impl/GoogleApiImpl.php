<?php

namespace App\Api\Impl;

use App\Api\GoogleApi;
use App\Utils\JsonUtils;
use GuzzleHttp\Client;
use RuntimeException;

class GoogleApiImpl implements GoogleApi
{
    public const string GOOGLE_API_PATH = 'https://oauth2.googleapis.com/';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::GOOGLE_API_PATH,
            'http_errors' => false,
        ]);
    }

    public function verifyIdToken(string $credential): array
    {
        $response = $this->client->get('tokeninfo', [
            'query' => ['id_token' => $credential],
        ]);

        if ($response->getStatusCode() >= 400) {
            throw new RuntimeException('Google tokeninfo request failed');
        }

        return JsonUtils::decode($response->getBody());
    }
}
