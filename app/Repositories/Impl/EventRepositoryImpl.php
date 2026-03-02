<?php

namespace App\Repositories\Impl;

use App\Dto\Search\EventsSearchDto;
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

    public function findAll(EventsSearchDto $params, Pageable $pageable): LengthAwarePaginator
    {
        $query = Event::query();
        if ($params->query !== null) {
            $userQuery = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], mb_strtolower(trim($params->query)));
            $query->where('title', 'like', "%{$userQuery}%")
                ->orWhere('description', 'like', "%{$userQuery}%");
        }
        if ($params->place !== null) {
            $place = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], mb_strtolower(trim($params->place)));
            $query->where('venue_name', 'like', "%{$place}%")
                ->orWhere('address', 'like', "%{$place}%")
                ->orWhere('city', 'like', "%{$place}%")
                ->orWhere('state', 'like', "%{$place}%");
        }
        if ($params->date !== null) {
            $query->whereHas('sessions', function ($q) use ($params) {
                $q->whereDate('date', $params->date->toDateString());
            });
        } elseif ($params->future === true) {
            $today = now()->toDateString();
            $nowTime = now()->toTimeString();

            $query->whereHas('sessions', function ($q) use ($today, $nowTime) {
                $q->where(function ($sub) use ($today, $nowTime) {
                    $sub->where('date', '>', $today)
                        ->orWhere(function ($sameDay) use ($today, $nowTime) {
                            $sameDay->where('date', $today)
                                ->where('start_time', '>=', $nowTime);
                        });
                });
            });
        }
        return $query->paginate(perPage: $pageable->getSize(), page: $pageable->getPage());
    }

    public function getById(string $id): Event
    {
        return Event::findOrFail($id);
    }
}
