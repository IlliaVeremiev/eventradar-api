<?php

namespace App\Services\Impl;

use App\Dto\Search\EventExtractionResult;
use App\Dto\Search\EventSearchResult;
use App\Services\LlmService;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

class LlmServiceImpl implements LlmService
{
    public function searchEvents(string $url, string $markdown): array
    {
        $schema = new ArraySchema(
            name: 'Events list',
            description: 'List of events extracted from the page. For aggregate pages, return all elements, for single event pages, return only one element in array.',
            items: new ObjectSchema(
                name: 'Event',
                description: 'Event entry. Event should include title and url. If no URL provided - skip event.',
                properties: [
                    new StringSchema('title', 'The full official name or title of the event.'),
                    new StringSchema('url', 'Event url. It should be taken from event link. If page is single event link - use original url.'),
                ],
                requiredFields: ['title', 'url'],
            )
        );
        $prompt = <<<PROMPT
Page includes single event description or a list of events. Extract events from the provided page.

Url: {$url}
Page:
``` markdown
{$markdown}
```
PROMPT;

        $response = Prism::structured()
            ->using(Provider::Gemini, 'gemini-2.5-flash')
            ->withSchema($schema)
            ->withPrompt($prompt)
            ->usingTemperature(0.1)
            ->asStructured();

        return EventSearchResult::collect($response->structured);
    }

    public function extractEvent(string $markdown): EventExtractionResult
    {
        $schema = new ObjectSchema(
            name: 'event',
            description: 'Structured event data extracted from source content.',
            properties: [
                new StringSchema('title', 'The full name or title of the event. Not page or article title, but Event\'s name'),
                new StringSchema('description', 'A human-readable summary or description of the event, its purpose, and what attendees can expect.', nullable: true),
                new StringSchema('image', 'Absolute URL to the primary promotional image or banner for the event.', nullable: true),
                new StringSchema('timezone', 'IANA timezone identifier for the event location, e.g. "Europe/Copenhagen" or "America/New_York".', nullable: true),
                new StringSchema('venueName', 'The name of the physical venue, building, or space where the event is held.', nullable: true),
                new StringSchema('address', 'Street-level address of the venue, e.g. "123 Main Street, Suite 4".', nullable: true),
                new StringSchema('city', 'City where the event takes place.', nullable: true),
                new StringSchema('state', 'State, province, or region where the event takes place.', nullable: true),
                new StringSchema('countryCode', 'ISO 3166-1 alpha-2 two-letter country code, e.g. "DK", "US", "GB".', nullable: true),
                new StringSchema('postalCode', 'Postal or ZIP code of the venue location.', nullable: true),
                new ArraySchema(
                    'sessions',
                    'List of sessions when event will take place. Can be single entry for single time events, multiple entries for reoccurring events or multi-day events and can be several sessions per day.',
                    items: new ObjectSchema(
                        name: 'session',
                        description: 'Session entry.',
                        properties: [
                            new StringSchema('date', 'The date of the session in ISO 8601 format, e.g. "2026-03-15".'),
                            new StringSchema('startTime', 'The start time of the session in ISO 8601 format, e.g. "19:00".', nullable: true),
                            new StringSchema('endTime', 'The end time of the session in ISO 8601 format, e.g. "21:00".', nullable: true),
                        ]
                    )
                ),
            ],
            requiredFields: ['title'],
        );

        $response = Prism::structured()
            ->using(Provider::Gemini, 'gemini-2.5-flash')
            ->withSchema($schema)
            ->withPrompt("Extract event details from the following text:\n\n{$markdown}")
            ->usingTemperature(0.1)
            ->asStructured();

        return EventExtractionResult::from($response->structured);
    }
}
