<?php

namespace App\Repositories;

use App\Models\EventSource;

interface EventSourceRepository
{
    public function findByUrl(string $url): ?EventSource;

    public function save(EventSource $source): EventSource;
}
