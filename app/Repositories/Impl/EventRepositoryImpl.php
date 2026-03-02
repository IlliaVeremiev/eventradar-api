<?php

namespace App\Repositories\Impl;

use App\Models\Event;
use App\Repositories\EventRepository;
use App\Utils\Pageable;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function findAll(Pageable $pageable): LengthAwarePaginator
    {
        return Event::query()->paginate(perPage: $pageable->getSize(), page: $pageable->getPage());
    }

    public function getById(string $id): Event
    {
        return Event::findOrFail($id);
    }
}
