<?php

namespace App\Services;

interface EventFollowerService
{
    public function isFollowing(string $userId, string $eventId): bool;

    public function follow(string $userId, string $eventId): void;

    public function unfollow(string $userId, string $eventId): void;
}
