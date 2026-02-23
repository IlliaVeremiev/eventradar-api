<?php

namespace App\Services;

use App\Dto\Searxng\SearchResponseResult;
use Illuminate\Support\Collection;

interface SearchService
{
    /** @return Collection<SearchResponseResult> */
    public function searchEventUrls(string $location): Collection;
}
