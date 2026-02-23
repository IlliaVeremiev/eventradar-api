<?php

namespace App\Api\Impl;

use App\Api\FirecrawlApi;
use App\Dto\Firecrawl\ScrapeResponse;
use App\Utils\JsonUtils;
use GuzzleHttp\Client;

class FirecrawlApiImpl implements FirecrawlApi
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.firecrawl.url'),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function scrape(string $url): ScrapeResponse
    {
        $response = $this->client->post('v2/scrape', [
            'json' => [
                'url' => $url,
            ],
        ]);

        return ScrapeResponse::from(JsonUtils::decode($response->getBody()));
    }
}
