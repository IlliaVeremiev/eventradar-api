<?php

namespace App\Jobs;

use App\Services\SearchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SearchEventsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $location,
        private readonly int $limit = 10
    ) {
    }

    public function handle(SearchService $searchService): void
    {
        $results = $searchService->searchEventUrls($this->location)->skip($this->limit)->take($this->limit);
        foreach ($results as $result) {
            ScanEventJob::dispatch($result);
        }
    }
}
