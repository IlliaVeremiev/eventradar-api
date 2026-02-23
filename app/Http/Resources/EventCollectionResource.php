<?php

namespace App\Http\Resources;

use App\Models\Event;
use App\Models\EventSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCollectionResource extends JsonResource
{
    public function __construct(private Event $event)
    {
        parent::__construct($event);
    }

    public function toArray(Request $request): array
    {
        $nextSession = $this->event->sessions->filter(fn (EventSession $s) => $s->date?->isAfter(Carbon::now()))->first();

        return [
            'id' => $this->event->id,
            'title' => $this->event->title,
            'image' => $this->event->image,
            'date' => $nextSession?->date,
            'startTime' => $nextSession?->start_time,
            'timezone' => $this->event->timezone,
        ];
    }
}
