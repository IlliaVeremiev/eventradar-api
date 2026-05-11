<?php

namespace App\Services\Impl;

use App\Exceptions\AuthException;
use App\Models\User;
use App\Services\JwtService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

class JwtServiceImpl implements JwtService
{
    public function createToken(User $user): string
    {
        return JWT::encode([
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + config('jwt.access_ttl'),
        ], config('jwt.secret'), 'HS256');
    }

    public function verifyToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key(config('jwt.secret'), 'HS256'));
        } catch (Throwable $e) {
            throw new AuthException('Invalid or expired token', previous: $e);
        }
    }
}
