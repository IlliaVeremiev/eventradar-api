<?php

namespace App\Dto;

readonly class AuthTokensDto
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken
    ) {
    }
}
