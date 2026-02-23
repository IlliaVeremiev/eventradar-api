<?php

use App\Api\FirecrawlApi;
use App\Dto\Firecrawl\ScrapeResponse;
use App\Dto\Firecrawl\ScrapeResponseData;
use App\Services\Impl\FirecrawlServiceImpl;

it('returns markdown content from the firecrawl api', function () {
    $markdown = "# Event Title\n\nJoin us for an amazing event in Copenhagen.";

    $scrapeResponse = new ScrapeResponse(
        success: true,
        data: new ScrapeResponseData(markdown: $markdown, metadata: []),
    );

    $this->mock(FirecrawlApi::class)
        ->shouldReceive('scrape')
        ->once()
        ->with('https://example.com/event')
        ->andReturn($scrapeResponse);

    $result = app(FirecrawlServiceImpl::class)->fetchMarkdown('https://example.com/event');

    expect($result)->toBe($markdown);
});

it('returns the raw markdown string exactly as provided by the api', function () {
    $markdown = '**Bold text** and [a link](https://example.com)';

    $scrapeResponse = new ScrapeResponse(
        success: true,
        data: new ScrapeResponseData(markdown: $markdown, metadata: ['title' => 'Test Page']),
    );

    $this->mock(FirecrawlApi::class)
        ->shouldReceive('scrape')
        ->once()
        ->andReturn($scrapeResponse);

    $result = app(FirecrawlServiceImpl::class)->fetchMarkdown('https://example.com');

    expect($result)->toBe($markdown);
});
