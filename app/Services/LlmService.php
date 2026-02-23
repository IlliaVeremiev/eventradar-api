<?php

namespace App\Services;

use App\Dto\Search\EventExtractionResult;
use App\Dto\Search\EventSearchResult;

interface LlmService
{
    /** @return array<EventSearchResult> */
    public function searchEvents(string $url, string $markdown): array;

    public function extractEvent(string $markdown): EventExtractionResult;
}
