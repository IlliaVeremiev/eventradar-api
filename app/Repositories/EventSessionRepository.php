<?php

namespace App\Repositories;

use App\Models\EventSession;

interface EventSessionRepository
{
    public function save(EventSession $session): EventSession;
}
