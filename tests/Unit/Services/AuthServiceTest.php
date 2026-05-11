<?php

use App\Api\GoogleApi;
use App\Dto\AuthTokensDto;
use App\Exceptions\AuthException;
use App\Models\RefreshToken;
use App\Models\User;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\UserRepository;
use App\Services\Impl\AuthServiceImpl;
use App\Services\JwtService;

beforeEach(function () {
    config([
        'services.google.client_id' => 'test-client-id',
        'jwt.refresh_ttl' => 31536000,
    ]);
});

// --- googleSignIn ---

it('throws AuthException when the Google API rejects the credential', function () {
    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andThrow(new RuntimeException('Google tokeninfo request failed'));

    $this->mock(JwtService::class);
    $this->mock(UserRepository::class);
    $this->mock(RefreshTokenRepository::class);

    app(AuthServiceImpl::class)->googleSignIn('invalid-credential');
})->throws(AuthException::class, 'Invalid Google token');

it('throws AuthException when the token audience does not match the configured client id', function () {
    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andReturn([
            'aud' => 'wrong-client-id',
            'email_verified' => 'true',
            'sub' => 'google-id',
            'email' => 'user@example.com',
        ]);

    $this->mock(JwtService::class);
    $this->mock(UserRepository::class);
    $this->mock(RefreshTokenRepository::class);

    app(AuthServiceImpl::class)->googleSignIn('some-credential');
})->throws(AuthException::class, 'Token audience mismatch');

it('throws AuthException when the Google account email is not verified', function () {
    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andReturn([
            'aud' => 'test-client-id',
            'email_verified' => 'false',
            'sub' => 'google-id',
            'email' => 'user@example.com',
        ]);

    $this->mock(JwtService::class);
    $this->mock(UserRepository::class);
    $this->mock(RefreshTokenRepository::class);

    app(AuthServiceImpl::class)->googleSignIn('some-credential');
})->throws(AuthException::class, 'Google account email is not verified');

it('returns tokens for an existing user found by google id', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andReturn([
            'sub' => 'google-id-123',
            'aud' => 'test-client-id',
            'email' => 'user@example.com',
            'name' => 'Test User',
            'email_verified' => 'true',
        ]);

    $userRepo = $this->mock(UserRepository::class);
    $userRepo->shouldReceive('findByGoogleId')->with('google-id-123')->andReturn($user);
    $userRepo->shouldReceive('save')->once()->andReturnArg(0);

    $this->mock(JwtService::class)
        ->shouldReceive('createToken')
        ->with($user)
        ->andReturn('access-token');

    $this->mock(RefreshTokenRepository::class)
        ->shouldReceive('save')
        ->once();

    $result = app(AuthServiceImpl::class)->googleSignIn('valid-credential');

    expect($result)->toBeInstanceOf(AuthTokensDto::class)
        ->and($result->accessToken)->toBe('access-token')
        ->and($result->refreshToken)->not->toBeEmpty();
});

it('links an existing email user to their google id and returns tokens', function () {
    $payload = [
        'sub' => 'google-id-456',
        'aud' => 'test-client-id',
        'email' => 'existing@example.com',
        'name' => 'Existing User',
        'email_verified' => 'true',
    ];

    $user = new User;
    $user->id = 'user-uuid-456';

    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andReturn($payload);

    $userRepo = $this->mock(UserRepository::class);
    $userRepo->shouldReceive('findByGoogleId')->with('google-id-456')->andReturnNull();
    $userRepo->shouldReceive('findByEmail')->with('existing@example.com')->andReturn($user);
    $userRepo->shouldReceive('save')->once()->andReturnArg(0);

    $this->mock(JwtService::class)
        ->shouldReceive('createToken')
        ->andReturn('access-token');

    $this->mock(RefreshTokenRepository::class)
        ->shouldReceive('save')
        ->once();

    app(AuthServiceImpl::class)->googleSignIn('valid-credential');

    expect($user->google_id)->toBe('google-id-456');
});

it('creates a new user when no existing user is found and returns tokens', function () {
    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andReturn([
            'sub' => 'google-id-new',
            'aud' => 'test-client-id',
            'email' => 'new@example.com',
            'name' => 'New User',
            'email_verified' => 'true',
        ]);

    $savedUser = null;
    $userRepo = $this->mock(UserRepository::class);
    $userRepo->shouldReceive('findByGoogleId')->with('google-id-new')->andReturnNull();
    $userRepo->shouldReceive('findByEmail')->with('new@example.com')->andReturnNull();
    $userRepo->shouldReceive('save')->once()->andReturnUsing(function (User $user) use (&$savedUser) {
        $savedUser = $user;
        return $user;
    });

    $this->mock(JwtService::class)
        ->shouldReceive('createToken')
        ->andReturn('access-token');

    $this->mock(RefreshTokenRepository::class)
        ->shouldReceive('save')
        ->once();

    $result = app(AuthServiceImpl::class)->googleSignIn('valid-credential');

    expect($result)->toBeInstanceOf(AuthTokensDto::class)
        ->and($savedUser->google_id)->toBe('google-id-new')
        ->and($savedUser->email)->toBe('new@example.com')
        ->and($savedUser->name)->toBe('New User');
});

it('uses the email as name when name is absent from the google payload', function () {
    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andReturn([
            'sub' => 'google-id-noname',
            'aud' => 'test-client-id',
            'email' => 'noname@example.com',
            'email_verified' => 'true',
        ]);

    $savedUser = null;
    $userRepo = $this->mock(UserRepository::class);
    $userRepo->shouldReceive('findByGoogleId')->andReturnNull();
    $userRepo->shouldReceive('findByEmail')->andReturnNull();
    $userRepo->shouldReceive('save')->once()->andReturnUsing(function (User $user) use (&$savedUser) {
        $savedUser = $user;
        return $user;
    });

    $this->mock(JwtService::class)->shouldReceive('createToken')->andReturn('token');
    $this->mock(RefreshTokenRepository::class)->shouldReceive('save')->once();

    app(AuthServiceImpl::class)->googleSignIn('valid-credential');

    expect($savedUser->name)->toBe('noname@example.com');
});

it('stores a sha256-hashed refresh token and returns the raw token in the dto', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $this->mock(GoogleApi::class)
        ->shouldReceive('verifyIdToken')
        ->andReturn([
            'sub' => 'google-id-123',
            'aud' => 'test-client-id',
            'email' => 'user@example.com',
            'name' => 'Test User',
            'email_verified' => 'true',
        ]);

    $userRepo = $this->mock(UserRepository::class);
    $userRepo->shouldReceive('findByGoogleId')->andReturn($user);
    $userRepo->shouldReceive('save')->andReturnArg(0);

    $this->mock(JwtService::class)->shouldReceive('createToken')->andReturn('access-token');

    $savedToken = null;
    $this->mock(RefreshTokenRepository::class)
        ->shouldReceive('save')
        ->once()
        ->andReturnUsing(function (RefreshToken $token) use (&$savedToken) {
            $savedToken = $token;
            return $token;
        });

    $result = app(AuthServiceImpl::class)->googleSignIn('valid-credential');

    expect($savedToken->token)->toBe(hash('sha256', $result->refreshToken))
        ->and(strlen($result->refreshToken))->toBe(64);
});

// --- refresh ---

it('throws AuthException when the refresh token is not found or expired', function () {
    $this->mock(RefreshTokenRepository::class)
        ->shouldReceive('findValid')
        ->with('expired-token')
        ->andReturnNull();

    $this->mock(JwtService::class);
    $this->mock(UserRepository::class);
    $this->mock(GoogleApi::class);

    app(AuthServiceImpl::class)->refresh('expired-token');
})->throws(AuthException::class, 'Invalid or expired refresh token');

it('rotates the refresh token and returns new access and refresh tokens', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $existingToken = new RefreshToken;
    $existingToken->token = hash('sha256', 'old-raw-token');
    $existingToken->setRelation('user', $user);

    $savedToken = null;
    $refreshTokenRepo = $this->mock(RefreshTokenRepository::class);
    $refreshTokenRepo->shouldReceive('findValid')->with('old-raw-token')->andReturn($existingToken);
    $refreshTokenRepo->shouldReceive('save')->once()->andReturnUsing(function (RefreshToken $token) use (&$savedToken) {
        $savedToken = $token;
        return $token;
    });

    $this->mock(JwtService::class)
        ->shouldReceive('createToken')
        ->with($user)
        ->andReturn('new-access-token');

    $this->mock(UserRepository::class);
    $this->mock(GoogleApi::class);

    $result = app(AuthServiceImpl::class)->refresh('old-raw-token');

    expect($result)->toBeInstanceOf(AuthTokensDto::class)
        ->and($result->accessToken)->toBe('new-access-token')
        ->and($savedToken->token)->toBe(hash('sha256', $result->refreshToken))
        ->and($savedToken->token)->not->toBe(hash('sha256', 'old-raw-token'));
});

// --- logout ---

it('deletes the refresh token on logout', function () {
    $existingToken = new RefreshToken;

    $refreshTokenRepo = $this->mock(RefreshTokenRepository::class);
    $refreshTokenRepo->shouldReceive('findValid')->with('valid-raw-token')->andReturn($existingToken);
    $refreshTokenRepo->shouldReceive('delete')->with($existingToken)->once();

    $this->mock(JwtService::class);
    $this->mock(UserRepository::class);
    $this->mock(GoogleApi::class);

    app(AuthServiceImpl::class)->logout('valid-raw-token');
});

it('does nothing on logout when the refresh token is not found', function () {
    $refreshTokenRepo = $this->mock(RefreshTokenRepository::class);
    $refreshTokenRepo->shouldReceive('findValid')->with('unknown-token')->andReturnNull();
    $refreshTokenRepo->shouldNotReceive('delete');

    $this->mock(JwtService::class);
    $this->mock(UserRepository::class);
    $this->mock(GoogleApi::class);

    app(AuthServiceImpl::class)->logout('unknown-token');
});
