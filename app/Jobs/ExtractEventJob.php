<?php

namespace App\Jobs;

use App\Dto\Search\EventSearchResult;
use App\Services\EventService;
use App\Services\FirecrawlService;
use App\Services\LlmService;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExtractEventJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly EventSearchResult $searchResult
    ) {
    }

    public function handle(
        FirecrawlService $firecrawlService,
        LlmService $ollamaService,
        EventService $eventService
    ): void {
        $markdown = $firecrawlService->fetchMarkdown($this->searchResult->url);
        $response = $ollamaService->extractEvent($markdown);

        DB::transaction(function () use ($eventService, $response) {
            $event = $eventService->createEvent($response);
            $eventService->createSource($event, $this->searchResult->url);
            foreach ($response->sessions as $session) {
                $eventService->createSession($event, $session);
            }
        });
    }
}
