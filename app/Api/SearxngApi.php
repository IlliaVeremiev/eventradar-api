<?php

namespace App\Api;

use App\Dto\Searxng\SearchResponse;

interface SearxngApi
{
    public function search(string $query): SearchResponse;
}
