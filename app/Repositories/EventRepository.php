<?php

namespace App\Repositories;

use App\Dto\Search\EventsSearchDto;
use App\Models\Event;
use App\Utils\Pageable;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventRepository
{
    public function save(Event $event): Event;

    public function findByNameAndSessionDate(string $title, Carbon $sessionDate): ?Event;

    /** @return LengthAwarePaginator<Event> */
    public function findAll(EventsSearchDto $params, Pageable $pageable): LengthAwarePaginator;

    public function getById(string $id): Event;
}
