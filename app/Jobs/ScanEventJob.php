<?php

namespace App\Jobs;

use App\Dto\Searxng\SearchResponseResult;
use App\Services\EventService;
use App\Services\FirecrawlService;
use App\Services\LlmService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ScanEventJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly SearchResponseResult $searchResult
    ) {
    }

    public function handle(
        EventService $eventService,
        FirecrawlService $firecrawlService,
        LlmService $ollamaService
    ): void {
        $markdown = $firecrawlService->fetchMarkdown($this->searchResult->url);
        $results = $ollamaService->searchEvents($this->searchResult->url, $markdown);

        foreach ($results as $result) {
            $existingEvent = $eventService->findExistingEventBySearchResult($result);
            if ($existingEvent !== null) {
                continue;
            }
            ExtractEventJob::dispatch($result);
        }
    }
}
