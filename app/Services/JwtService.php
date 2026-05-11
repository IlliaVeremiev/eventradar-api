<?php

namespace App\Services;

use App\Models\User;

interface JwtService
{
    public function createToken(User $user): string;

    public function verifyToken(string $token): ?object;
}
