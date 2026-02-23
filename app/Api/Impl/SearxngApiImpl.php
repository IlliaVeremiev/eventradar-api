<?php

namespace App\Api\Impl;

use App\Api\SearxngApi;
use App\Dto\Searxng\SearchResponse;
use App\Utils\JsonUtils;
use GuzzleHttp\Client;

class SearxngApiImpl implements SearxngApi
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.searxng.url'),
        ]);
    }

    public function search(string $query): SearchResponse
    {
        $response = $this->client->get('search', [
            'query' => [
                'q' => $query,
                'format' => 'json',
                'engines' => 'google,bing,duckduckgo',
                'time_range' => 'month',
                'language' => 'en',
            ],
        ]);

        return SearchResponse::from(JsonUtils::decode($response->getBody()));
    }
}
