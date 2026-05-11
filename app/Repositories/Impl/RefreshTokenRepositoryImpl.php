<?php

namespace App\Repositories\Impl;

use App\Models\RefreshToken;
use App\Repositories\RefreshTokenRepository;

class RefreshTokenRepositoryImpl implements RefreshTokenRepository
{
    public function save(RefreshToken $token): RefreshToken
    {
        $token->saveOrFail();

        return $token;
    }

    public function findValid(string $rawToken): ?RefreshToken
    {
        return RefreshToken::query()
            ->where('token', hash('sha256', $rawToken))
            ->where('expires_at', '>', now())
            ->with('user')
            ->first();
    }

    public function delete(RefreshToken $token): void
    {
        $token->delete();
    }
}
