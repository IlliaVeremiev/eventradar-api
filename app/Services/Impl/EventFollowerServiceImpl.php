<?php

namespace App\Services\Impl;

use App\Models\EventFollower;
use App\Repositories\EventFollowerRepository;
use App\Services\EventFollowerService;

class EventFollowerServiceImpl implements EventFollowerService
{
    public function __construct(private readonly EventFollowerRepository $eventFollowerRepository)
    {
    }

    public function isFollowing(string $userId, string $eventId): bool
    {
        return $this->eventFollowerRepository->exists($userId, $eventId);
    }

    public function follow(string $userId, string $eventId): void
    {
        if ($this->eventFollowerRepository->exists($userId, $eventId)) {
            return;
        }

        $follower = new EventFollower([
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);

        $this->eventFollowerRepository->save($follower);
    }

    public function unfollow(string $userId, string $eventId): void
    {
        $this->eventFollowerRepository->delete($userId, $eventId);
    }
}
