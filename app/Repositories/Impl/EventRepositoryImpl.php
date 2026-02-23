<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EventRepositoryImpl implements EventRepository
{
    public function save(Event $event): Event
    {
        $event->saveOrFail();

        return $event;
    }

    public function findByNameAndSessionDate(string $title, Carbon $sessionDate): ?Event
    {
        return Event::query()
            ->where('title', $title)
            ->whereDate('sessions.date', $sessionDate)
            ->first();
    }

    public function findAll(): Collection
    {
        return Event::all();
    }

    public function getById(string $id): Event
    {
        return Event::findOrFail($id);
    }
}
