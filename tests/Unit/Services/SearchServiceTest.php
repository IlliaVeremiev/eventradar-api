<?php

use App\Api\SearxngApi;
use App\Dto\Searxng\SearchResponse;
use App\Dto\Searxng\SearchResponseResult;
use App\Services\Impl\SearchServiceImpl;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;

it('returns a collection of search results from the searxng api', function () {
    $result1 = new SearchResponseResult('https://jazz.dk/events', 'Copenhagen Jazz', 'Jazz events in Copenhagen', 'google');
    $result2 = new SearchResponseResult('https://roskilde.dk', 'Roskilde Festival', 'Summer music festival', 'bing');

    $searchResponse = new SearchResponse(
        query: 'concerts Copenhagen',
        numberOfResults: 2,
        results: new DataCollection(SearchResponseResult::class, [$result1, $result2]),
        suggestions: [],
    );

    $this->mock(SearxngApi::class)
        ->shouldReceive('search')
        ->once()
        ->with('concerts Copenhagen')
        ->andReturn($searchResponse);

    $result = app(SearchServiceImpl::class)->searchEventUrls('concerts Copenhagen');

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->and($result->first())->toBeInstanceOf(SearchResponseResult::class)
        ->and($result->first()->url)->toBe('https://jazz.dk/events')
        ->and($result->last()->url)->toBe('https://roskilde.dk');
});

it('returns an empty collection when no results are found', function () {
    $searchResponse = new SearchResponse(
        query: 'obscure search with no results',
        numberOfResults: 0,
        results: new DataCollection(SearchResponseResult::class, []),
        suggestions: [],
    );

    $this->mock(SearxngApi::class)
        ->shouldReceive('search')
        ->once()
        ->andReturn($searchResponse);

    $result = app(SearchServiceImpl::class)->searchEventUrls('obscure search with no results');

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});
