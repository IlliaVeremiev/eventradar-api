<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\UserRepository;

class UserRepositoryImpl implements UserRepository
{
    public function save(User $user): User
    {
        $user->saveOrFail();

        return $user;
    }

    public function findByGoogleId(string $googleId): ?User
    {
        return User::query()->where('google_id', $googleId)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findById(string $id): ?User
    {
        return User::query()->where('id', $id)->first();
    }
}
