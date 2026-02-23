<?php

namespace App\Console\Commands;

use App\Jobs\SearchEventsJob;
use Illuminate\Console\Command;

class CrawlEvents extends Command
{
    protected $signature = 'events:crawl
                            {city : City to search for events}
                            {--limit=10 : Max pages to crawl}';

    protected $description = 'Search for local events using SearXNG, crawl results, and analyze with LLM';

    public function handle(): void
    {
        $city = $this->argument('city');
        $limit = (int) $this->option('limit');
        SearchEventsJob::dispatch($city, $limit);
    }
}
