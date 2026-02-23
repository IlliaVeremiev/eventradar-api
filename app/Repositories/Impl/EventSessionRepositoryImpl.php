<?php

namespace App\Repositories\Impl;

use App\Models\EventSession;
use App\Repositories\EventSessionRepository;

class EventSessionRepositoryImpl implements EventSessionRepository
{
    public function save(EventSession $session): EventSession
    {
        $session->saveOrFail();

        return $session;
    }
}
