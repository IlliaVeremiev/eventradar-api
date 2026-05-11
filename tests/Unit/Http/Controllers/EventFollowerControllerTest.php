<?php

use App\Http\Controllers\EventFollowerController;
use App\Models\User;
use App\Services\EventFollowerService;
use Illuminate\Http\Request;

function makeAuthenticatedRequest(User $user, string $method = 'GET', string $uri = '/test'): Request
{
    $request = Request::create($uri, $method);
    $request->setUserResolver(fn () => $user);

    return $request;
}

// --- status ---

it('returns following true when the user is following the event', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $this->mock(EventFollowerService::class)
        ->shouldReceive('isFollowing')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456')
        ->andReturnTrue();

    $request = makeAuthenticatedRequest($user);
    $response = app(EventFollowerController::class)->status($request, 'event-uuid-456');

    expect($response->getData(true))->toBe(['following' => true]);
});

it('returns following false when the user is not following the event', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $this->mock(EventFollowerService::class)
        ->shouldReceive('isFollowing')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456')
        ->andReturnFalse();

    $request = makeAuthenticatedRequest($user);
    $response = app(EventFollowerController::class)->status($request, 'event-uuid-456');

    expect($response->getData(true))->toBe(['following' => false]);
});

// --- follow ---

it('calls the service follow method and returns following true', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $this->mock(EventFollowerService::class)
        ->shouldReceive('follow')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456');

    $request = makeAuthenticatedRequest($user, 'POST');
    $response = app(EventFollowerController::class)->follow($request, 'event-uuid-456');

    expect($response->getData(true))->toBe(['following' => true]);
});

// --- unfollow ---

it('calls the service unfollow method and returns following false', function () {
    $user = new User;
    $user->id = 'user-uuid-123';

    $this->mock(EventFollowerService::class)
        ->shouldReceive('unfollow')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456');

    $request = makeAuthenticatedRequest($user, 'DELETE');
    $response = app(EventFollowerController::class)->unfollow($request, 'event-uuid-456');

    expect($response->getData(true))->toBe(['following' => false]);
});
