<?php

namespace App\Repositories\Impl;

use App\Models\EventFollower;
use App\Repositories\EventFollowerRepository;

class EventFollowerRepositoryImpl implements EventFollowerRepository
{
    public function exists(string $userId, string $eventId): bool
    {
        return EventFollower::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    }

    public function save(EventFollower $follower): EventFollower
    {
        $follower->saveOrFail();

        return $follower;
    }

    public function delete(string $userId, string $eventId): void
    {
        EventFollower::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->delete();
    }
}
