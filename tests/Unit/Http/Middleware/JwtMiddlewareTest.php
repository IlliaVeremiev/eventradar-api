<?php

use App\Exceptions\AuthException;
use App\Http\Middleware\JwtMiddleware;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\JwtService;
use Illuminate\Http\Request;

it('throws AuthException when no bearer token is present in the request', function () {
    $this->mock(JwtService::class);
    $this->mock(UserRepository::class);

    $request = Request::create('/test', 'GET');

    app(JwtMiddleware::class)->handle($request, fn () => response()->noContent());
})->throws(AuthException::class, 'Unauthenticated');

it('re-throws AuthException when the jwt service rejects the token', function () {
    $this->mock(JwtService::class)
        ->shouldReceive('verifyToken')
        ->andThrow(new AuthException('Invalid or expired token'));

    $this->mock(UserRepository::class);

    $request = Request::create('/test', 'GET');
    $request->headers->set('Authorization', 'Bearer bad-token');

    app(JwtMiddleware::class)->handle($request, fn () => response()->noContent());
})->throws(AuthException::class, 'Invalid or expired token');

it('throws AuthException when no user is found for the token subject', function () {
    $this->mock(JwtService::class)
        ->shouldReceive('verifyToken')
        ->andReturn((object) ['sub' => 'non-existent-user-id']);

    $this->mock(UserRepository::class)
        ->shouldReceive('findById')
        ->with('non-existent-user-id')
        ->andReturnNull();

    $request = Request::create('/test', 'GET');
    $request->headers->set('Authorization', 'Bearer valid-token');

    app(JwtMiddleware::class)->handle($request, fn () => response()->noContent());
})->throws(AuthException::class, 'User not found');

it('sets the authenticated user on the request and calls next when the token is valid', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $this->mock(JwtService::class)
        ->shouldReceive('verifyToken')
        ->with('valid-token')
        ->andReturn((object) ['sub' => 'user-uuid-123']);

    $this->mock(UserRepository::class)
        ->shouldReceive('findById')
        ->with('user-uuid-123')
        ->andReturn($user);

    $request = Request::create('/test', 'GET');
    $request->headers->set('Authorization', 'Bearer valid-token');

    $resolvedUser = null;
    $response = app(JwtMiddleware::class)->handle($request, function (Request $req) use (&$resolvedUser) {
        $resolvedUser = $req->user();
        return response()->noContent();
    });

    expect($resolvedUser)->toBe($user)
        ->and($response->getStatusCode())->toBe(204);
});
