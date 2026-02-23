<?php

namespace App\Services\Impl;

use App\Api\SearxngApi;
use App\Services\SearchService;
use Illuminate\Support\Collection;

class SearchServiceImpl implements SearchService
{
    public function __construct(
        private readonly SearxngApi $searxngApi
    ) {
    }

    public function searchEventUrls(string $location): Collection
    {
        $response = $this->searxngApi->search($location);

        return collect($response->results->all());
    }
}
