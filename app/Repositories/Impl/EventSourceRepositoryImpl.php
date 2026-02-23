<?php

namespace App\Repositories\Impl;

use App\Models\EventSource;
use App\Repositories\EventSourceRepository;

class EventSourceRepositoryImpl implements EventSourceRepository
{
    public function findByUrl(string $url): ?EventSource
    {
        return EventSource::whereUrl($url)->first();
    }

    public function save(EventSource $source): EventSource
    {
        $source->saveOrFail();

        return $source;
    }
}
