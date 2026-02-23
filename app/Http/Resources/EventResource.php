<?php

namespace App\Http\Resources;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\EventSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function __construct(private Event $event)
    {
        parent::__construct($event);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->event->id,
            'title' => $this->event->title,
            'description' => $this->event->description,
            'image' => $this->event->image,
            'timezone' => $this->event->timezone,
            'venueName' => $this->event->venue_name,
            'address' => $this->event->address,
            'city' => $this->event->city,
            'state' => $this->event->state,
            'countryCode' => $this->event->country_code,
            'postalCode' => $this->event->postal_code,
            'sessions' => $this->event->sessions->map(fn ($session) => $this->mapSession($session)),
            'sources' => $this->event->sources->map(fn ($source) => $this->mapSource($source)),
        ];
    }

    private function mapSession(EventSession $session): array
    {
        return [
            'date' => $session->date,
            'startTime' => $session->start_time,
            'endTime' => $session->end_time,
        ];
    }

    private function mapSource(EventSource $source): array
    {
        return [
            'url' => $source->url,
            'domain' => $source->domain,
        ];
    }
}
