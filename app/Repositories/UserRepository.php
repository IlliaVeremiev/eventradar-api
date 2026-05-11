<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepository
{
    public function save(User $user): User;

    public function findByGoogleId(string $googleId): ?User;

    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;
}
