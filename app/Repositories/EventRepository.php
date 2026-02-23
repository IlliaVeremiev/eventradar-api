<?php

namespace App\Repositories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface EventRepository
{
    public function save(Event $event): Event;

    public function findByNameAndSessionDate(string $title, Carbon $sessionDate): ?Event;

    /** @return Collection<Event> */
    public function findAll(): Collection;

    public function getById(string $id): Event;
}
