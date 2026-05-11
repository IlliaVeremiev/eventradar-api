<?php

use App\Exceptions\AuthException;
use App\Models\User;
use App\Services\Impl\JwtServiceImpl;
use Firebase\JWT\JWT;

beforeEach(function () {
    config([
        'jwt.secret' => 'test-jwt-secret-minimum-32-characters!',
        'jwt.access_ttl' => 300,
    ]);
});

it('creates a token containing the correct user claims', function () {
    $user = new User;
    $user->id = 'user-uuid-123';
    $user->name = 'Test User';
    $user->email = 'test@example.com';

    $service = app(JwtServiceImpl::class);
    $token = $service->createToken($user);
    $payload = $service->verifyToken($token);

    expect($payload->sub)->toBe('user-uuid-123')
        ->and($payload->name)->toBe('Test User')
        ->and($payload->email)->toBe('test@example.com')
        ->and($payload->iat)->toBeInt()
        ->and($payload->exp)->toBeGreaterThan(time());
});

it('creates a token that expires after the configured access_ttl', function () {
    config(['jwt.access_ttl' => 600]);

    $user = new User;
    $user->id = 'user-uuid-456';
    $user->name = 'Another User';
    $user->email = 'another@example.com';

    $before = time();
    $service = app(JwtServiceImpl::class);
    $token = $service->createToken($user);
    $payload = $service->verifyToken($token);

    expect($payload->exp)->toBeGreaterThanOrEqual($before + 600);
});

it('throws AuthException for a malformed token', function () {
    $service = app(JwtServiceImpl::class);

    $service->verifyToken('not.a.valid.jwt');
})->throws(AuthException::class, 'Invalid or expired token');

it('throws AuthException for a token signed with a different secret', function () {
    $token = JWT::encode(['sub' => 'user-id', 'exp' => time() + 300], 'wrong-secret-minimum-32-characters!!', 'HS256');

    $service = app(JwtServiceImpl::class);
    $service->verifyToken($token);
})->throws(AuthException::class, 'Invalid or expired token');

it('throws AuthException for an expired token', function () {
    $expiredToken = JWT::encode([
        'sub' => 'user-id',
        'iat' => time() - 100,
        'exp' => time() - 1,
    ], 'test-jwt-secret-minimum-32-characters!', 'HS256');

    $service = app(JwtServiceImpl::class);
    $service->verifyToken($expiredToken);
})->throws(AuthException::class, 'Invalid or expired token');
