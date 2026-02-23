<?php

use App\Dto\Search\EventExtractionResult;
use App\Dto\Search\EventExtractionSession;
use App\Dto\Search\EventSearchResult;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSource;
use App\Repositories\EventRepository;
use App\Repositories\EventSessionRepository;
use App\Repositories\EventSourceRepository;
use App\Services\Impl\EventServiceImpl;
use Spatie\LaravelData\DataCollection;

it('returns null when no source exists for the given search result url', function () {
    $this->mock(EventSourceRepository::class)
        ->shouldReceive('findByUrl')
        ->once()
        ->with('https://example.com/event')
        ->andReturnNull();

    $this->mock(EventRepository::class);
    $this->mock(EventSessionRepository::class);

    $searchResult = new EventSearchResult(title: 'Test Event', url: 'https://example.com/event');

    $result = app(EventServiceImpl::class)->findExistingEventBySearchResult($searchResult);

    expect($result)->toBeNull();
});

it('returns the event when an existing source is found for the search result url', function () {
    $event = new Event;
    $event->id = 'existing-uuid';
    $event->title = 'Existing Event';

    $source = new EventSource;
    $source->setRelation('event', $event);

    $this->mock(EventSourceRepository::class)
        ->shouldReceive('findByUrl')
        ->once()
        ->with('https://example.com/event')
        ->andReturn($source);

    $this->mock(EventRepository::class);
    $this->mock(EventSessionRepository::class);

    $searchResult = new EventSearchResult(title: 'Test Event', url: 'https://example.com/event');

    $result = app(EventServiceImpl::class)->findExistingEventBySearchResult($searchResult);

    expect($result)
        ->toBeInstanceOf(Event::class)
        ->and($result->id)->toBe('existing-uuid')
        ->and($result->title)->toBe('Existing Event');
});

it('creates an event with all fields mapped from the extraction result', function () {
    $this->mock(EventRepository::class)
        ->shouldReceive('save')
        ->once()
        ->andReturnArg(0);

    $this->mock(EventSourceRepository::class);
    $this->mock(EventSessionRepository::class);

    $extractionResult = new EventExtractionResult(
        title: 'Laravel Conference 2026',
        description: 'Annual developer conference.',
        image: 'https://example.com/banner.jpg',
        timezone: 'Europe/Copenhagen',
        venueName: 'IT University',
        address: 'Rued Langgaards Vej 7',
        city: 'Copenhagen',
        state: null,
        countryCode: 'DK',
        postalCode: '2300',
        sessions: new DataCollection(EventExtractionSession::class, []),
    );

    $event = app(EventServiceImpl::class)->createEvent($extractionResult);

    expect($event)
        ->toBeInstanceOf(Event::class)
        ->and($event->id)->not->toBeNull()
        ->and($event->title)->toBe('Laravel Conference 2026')
        ->and($event->description)->toBe('Annual developer conference.')
        ->and($event->image)->toBe('https://example.com/banner.jpg')
        ->and($event->timezone)->toBe('Europe/Copenhagen')
        ->and($event->venue_name)->toBe('IT University')
        ->and($event->city)->toBe('Copenhagen')
        ->and($event->country_code)->toBe('DK')
        ->and($event->postal_code)->toBe('2300');
});

it('creates a source with domain parsed from the url', function () {
    $this->mock(EventSourceRepository::class)
        ->shouldReceive('save')
        ->once()
        ->andReturnArg(0);

    $this->mock(EventRepository::class);
    $this->mock(EventSessionRepository::class);

    $event = new Event;
    $event->id = 'event-uuid-123';

    $source = app(EventServiceImpl::class)->createSource($event, 'https://jazz.dk/events/summer-2026');

    expect($source)
        ->toBeInstanceOf(EventSource::class)
        ->and($source->id)->not->toBeNull()
        ->and($source->event_id)->toBe('event-uuid-123')
        ->and($source->url)->toBe('https://jazz.dk/events/summer-2026')
        ->and($source->domain)->toBe('jazz.dk');
});

it('creates a session with all date and time fields parsed from the extraction session', function () {
    $this->mock(EventSessionRepository::class)
        ->shouldReceive('save')
        ->once()
        ->andReturnArg(0);

    $this->mock(EventRepository::class);
    $this->mock(EventSourceRepository::class);

    $event = new Event;
    $event->id = 'event-uuid-123';

    $sessionData = new EventExtractionSession(
        date: '2026-03-15',
        startTime: '09:00',
        endTime: '17:00',
    );

    $session = app(EventServiceImpl::class)->createSession($event, $sessionData);

    expect($session)
        ->toBeInstanceOf(EventSession::class)
        ->and($session->event_id)->toBe('event-uuid-123')
        ->and($session->date->format('Y-m-d'))->toBe('2026-03-15')
        ->and($session->start_time->format('H:i'))->toBe('09:00')
        ->and($session->end_time->format('H:i'))->toBe('17:00');
});

it('creates a session with null start and end times when not provided', function () {
    $this->mock(EventSessionRepository::class)
        ->shouldReceive('save')
        ->once()
        ->andReturnArg(0);

    $this->mock(EventRepository::class);
    $this->mock(EventSourceRepository::class);

    $event = new Event;
    $event->id = 'event-uuid-123';

    $sessionData = new EventExtractionSession(
        date: '2026-06-21',
        startTime: null,
        endTime: null,
    );

    $session = app(EventServiceImpl::class)->createSession($event, $sessionData);

    expect($session)
        ->toBeInstanceOf(EventSession::class)
        ->and($session->date->format('Y-m-d'))->toBe('2026-06-21')
        ->and($session->start_time)->toBeNull()
        ->and($session->end_time)->toBeNull();
});
