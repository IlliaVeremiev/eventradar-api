<?php

namespace App\Repositories;

use App\Models\EventFollower;

interface EventFollowerRepository
{
    public function exists(string $userId, string $eventId): bool;

    public function save(EventFollower $follower): EventFollower;

    public function delete(string $userId, string $eventId): void;
}
