<?php

namespace App\Services\Impl;

use App\Api\GoogleApi;
use App\Dto\AuthTokensDto;
use App\Exceptions\AuthException;
use App\Models\RefreshToken;
use App\Models\User;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\JwtService;
use Illuminate\Support\Str;
use RuntimeException;

class AuthServiceImpl implements AuthService
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly RefreshTokenRepository $refreshTokenRepository,
        private readonly UserRepository $userRepository,
        private readonly GoogleApi $googleApi,
    ) {
    }

    public function googleSignIn(string $credential): AuthTokensDto
    {
        try {
            $payload = $this->googleApi->verifyIdToken($credential);
        } catch (RuntimeException) {
            throw new AuthException('Invalid Google token');
        }

        if ($payload['aud'] !== config('services.google.client_id')) {
            throw new AuthException('Token audience mismatch');
        }

        if (($payload['email_verified'] ?? 'false') !== 'true') {
            throw new AuthException('Google account email is not verified');
        }

        $user = $this->findOrCreateUser($payload);

        $rawToken = Str::random(64);
        $refreshToken = new RefreshToken([
            'user_id' => $user->id,
            'token' => hash('sha256', $rawToken),
            'expires_at' => now()->addSeconds(config('jwt.refresh_ttl')),
        ]);
        $this->refreshTokenRepository->save($refreshToken);

        return new AuthTokensDto(
            accessToken: $this->jwtService->createToken($user),
            refreshToken: $rawToken,
        );
    }

    public function refresh(string $refreshToken): AuthTokensDto
    {
        $existing = $this->refreshTokenRepository->findValid($refreshToken);

        if (!$existing) {
            throw new AuthException('Invalid or expired refresh token');
        }

        $rawToken = Str::random(64);
        $existing->token = hash('sha256', $rawToken);
        $existing->expires_at = now()->addSeconds(config('jwt.refresh_ttl'));
        $this->refreshTokenRepository->save($existing);

        return new AuthTokensDto(
            accessToken: $this->jwtService->createToken($existing->user),
            refreshToken: $rawToken,
        );
    }

    public function logout(string $refreshToken): void
    {
        $existing = $this->refreshTokenRepository->findValid($refreshToken);

        if ($existing) {
            $this->refreshTokenRepository->delete($existing);
        }
    }

    private function findOrCreateUser(array $payload): User
    {
        $user = $this->userRepository->findByGoogleId($payload['sub']);

        if ($user) {
            $user->google_payload = $payload;

            return $this->userRepository->save($user);
        }

        $user = $this->userRepository->findByEmail($payload['email']);

        if ($user) {
            return $this->updateUser($user, $payload);
        }

        return $this->createUser($payload);
    }

    private function updateUser(User $user, array $payload): User
    {
        $user->google_id = $payload['sub'];
        $user->google_payload = $payload;

        return $this->userRepository->save($user);
    }

    private function createUser(array $payload): User
    {
        $user = new User;
        $user->google_id = $payload['sub'];
        $user->google_payload = $payload;
        $user->name = $payload['name'] ?? $payload['email'];
        $user->email = $payload['email'];

        return $this->userRepository->save($user);
    }
}
