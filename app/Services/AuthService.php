<?php

namespace App\Services;

use App\Dto\AuthTokensDto;

interface AuthService
{
    public function googleSignIn(string $credential): AuthTokensDto;

    public function refresh(string $refreshToken): AuthTokensDto;

    public function logout(string $refreshToken): void;
}
