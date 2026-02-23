<?php

namespace App\Services;

use App\Dto\Search\EventExtractionResult;
use App\Dto\Search\EventExtractionSession;
use App\Dto\Search\EventSearchResult;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSource;
use Illuminate\Support\Collection;

interface EventService
{
    public function findExistingEventBySearchResult(EventSearchResult $searchResult): ?Event;

    public function createEvent(EventExtractionResult $extractionResult): Event;

    public function createSource(Event $event, string $url): EventSource;

    public function createSession(Event $event, EventExtractionSession $sessionData): EventSession;

    /** @return Collection<Event> */
    public function search(): Collection;

    public function getById(string $eventId): Event;
}
