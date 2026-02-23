<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventCollectionResource;
use App\Http\Resources\EventResource;
use App\Services\EventService;

class EventController extends Controller
{
    public function __construct(
        private readonly EventService $eventService
    ) {
    }

    public function search()
    {
        $events = $this->eventService->search();

        return response()->json(EventCollectionResource::collection($events));
    }

    public function getById(string $eventId)
    {
        $event = $this->eventService->getById($eventId);

        return response()->json(EventResource::make($event));
    }
}
