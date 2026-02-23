<?php

use App\Dto\Search\EventExtractionResult;
use App\Dto\Search\EventSearchResult;
use App\Services\Impl\LlmServiceImpl;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Testing\StructuredResponseFake;

it('returns array of event search results extracted by the llm', function () {
    $fake = Prism::fake([
        StructuredResponseFake::make()->withStructured([
            ['title' => 'Copenhagen Jazz Festival', 'url' => 'https://jazz.dk/summer'],
            ['title' => 'Roskilde Festival', 'url' => 'https://roskilde-festival.dk'],
        ]),
    ]);

    $results = app(LlmServiceImpl::class)->searchEvents(
        url: 'https://example.com/events',
        markdown: '# Events happening this summer',
    );

    $fake->assertCallCount(1);

    expect($results)
        ->toBeArray()
        ->toHaveCount(2)
        ->and($results[0])->toBeInstanceOf(EventSearchResult::class)
        ->and($results[0]->title)->toBe('Copenhagen Jazz Festival')
        ->and($results[0]->url)->toBe('https://jazz.dk/summer')
        ->and($results[1]->title)->toBe('Roskilde Festival');
});

it('returns structured event extraction result from the llm', function () {
    $fake = Prism::fake([
        StructuredResponseFake::make()->withStructured([
            'title' => 'Laravel Conference 2026',
            'description' => 'Annual developer conference for PHP enthusiasts.',
            'image' => 'https://example.com/banner.jpg',
            'timezone' => 'Europe/Copenhagen',
            'venueName' => 'IT University of Copenhagen',
            'address' => 'Rued Langgaards Vej 7',
            'city' => 'Copenhagen',
            'state' => null,
            'countryCode' => 'DK',
            'postalCode' => '2300',
            'sessions' => [
                ['date' => '2026-03-15', 'startTime' => '09:00', 'endTime' => '17:00'],
                ['date' => '2026-03-16', 'startTime' => '09:00', 'endTime' => '16:00'],
            ],
        ]),
    ]);

    $result = app(LlmServiceImpl::class)->extractEvent('# Laravel Conference 2026 markdown content here');

    $fake->assertCallCount(1);

    expect($result)
        ->toBeInstanceOf(EventExtractionResult::class)
        ->and($result->title)->toBe('Laravel Conference 2026')
        ->and($result->description)->toBe('Annual developer conference for PHP enthusiasts.')
        ->and($result->city)->toBe('Copenhagen')
        ->and($result->countryCode)->toBe('DK')
        ->and($result->postalCode)->toBe('2300')
        ->and($result->timezone)->toBe('Europe/Copenhagen')
        ->and($result->sessions->count())->toBe(2);
});
