<?php

namespace App\Api;

use App\Dto\Firecrawl\ScrapeResponse;

interface FirecrawlApi
{
    public function scrape(string $url): ScrapeResponse;
}
