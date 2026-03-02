<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventsSearchRequest;
use App\Http\Resources\EventCollectionResource;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use App\Utils\Page;

class EventController extends Controller
{
    public function __construct(
        private readonly EventService $eventService
    ) {
    }

    public function search(EventsSearchRequest $request)
    {
        $events = $this->eventService->search($request);

        return response()->json(Page::make($events, EventCollectionResource::class));
    }

    public function getById(string $eventId)
    {
        $event = $this->eventService->getById($eventId);

        return response()->json(EventResource::make($event));
    }
}
