<?php

namespace App\Services\Impl;

use App\Api\FirecrawlApi;
use App\Services\FirecrawlService;

class FirecrawlServiceImpl implements FirecrawlService
{
    public function __construct(
        private readonly FirecrawlApi $firecrawlApi
    ) {
    }

    public function fetchMarkdown(string $url): string
    {
        $response = $this->firecrawlApi->scrape($url);

        return $response->data->markdown;
    }
}
