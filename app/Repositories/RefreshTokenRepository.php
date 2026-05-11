<?php

namespace App\Repositories;

use App\Models\RefreshToken;

interface RefreshTokenRepository
{
    public function save(RefreshToken $token): RefreshToken;

    public function findValid(string $rawToken): ?RefreshToken;

    public function delete(RefreshToken $token): void;
}
