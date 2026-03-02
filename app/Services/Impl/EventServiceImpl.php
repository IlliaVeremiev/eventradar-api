<?php

namespace App\Services\Impl;

use App\Dto\Search\EventExtractionResult;
use App\Dto\Search\EventExtractionSession;
use App\Dto\Search\EventSearchResult;
use App\Http\Requests\EventsSearchRequest;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSource;
use App\Repositories\EventRepository;
use App\Repositories\EventSessionRepository;
use App\Repositories\EventSourceRepository;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Str;

class EventServiceImpl implements EventService
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly EventSourceRepository $eventSourceRepository,
        private readonly EventSessionRepository $eventSessionRepository,
    ) {
    }

    public function findExistingEventBySearchResult(EventSearchResult $searchResult): ?Event
    {
        $existingSource = $this->eventSourceRepository->findByUrl($searchResult->url);
        if (! $existingSource) {
            return null;
        }

        return $existingSource->event;
    }

    public function createEvent(EventExtractionResult $extractionResult): Event
    {
        $event = new Event;
        $event->id = Str::uuid();
        $event->title = $extractionResult->title;
        $event->description = $extractionResult->description;
        $event->image = $extractionResult->image;
        $event->timezone = $extractionResult->timezone;
        $event->venue_name = $extractionResult->venueName;
        $event->address = $extractionResult->address;
        $event->city = $extractionResult->city;
        $event->state = $extractionResult->state;
        $event->country_code = $extractionResult->countryCode;
        $event->postal_code = $extractionResult->postalCode;

        return $this->eventRepository->save($event);
    }

    public function createSource(Event $event, string $url): EventSource
    {
        $source = new EventSource;
        $source->id = Str::uuid();
        $source->event_id = $event->id;
        $source->url = $url;
        $source->domain = parse_url($url, PHP_URL_HOST);

        return $this->eventSourceRepository->save($source);
    }

    public function createSession(Event $event, EventExtractionSession $sessionData): EventSession
    {
        $session = new EventSession;
        $session->event_id = $event->id;
        $session->date = Carbon::parse($sessionData->date);
        $session->start_time = $sessionData->startTime ? Carbon::parse($sessionData->startTime) : null;
        $session->end_time = $sessionData->endTime ? Carbon::parse($sessionData->endTime) : null;

        return $this->eventSessionRepository->save($session);
    }

    public function search(EventsSearchRequest $request): LengthAwarePaginator
    {
        return $this->eventRepository->findAll($request);
    }

    public function getById(string $eventId): Event
    {
        return $this->eventRepository->getById($eventId);
    }
}
