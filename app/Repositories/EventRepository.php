<?php

namespace App\Repositories;

use App\Models\Event;
use Carbon\Carbon;

interface EventRepository
{
    public function save(Event $event): Event;

    public function findByNameAndSessionDate(string $title, Carbon $sessionDate): ?Event;
}
