<?php

use App\Models\EventFollower;
use App\Repositories\EventFollowerRepository;
use App\Services\Impl\EventFollowerServiceImpl;

// --- isFollowing ---

it('returns true when the repository reports the user is following the event', function () {
    $this->mock(EventFollowerRepository::class)
        ->shouldReceive('exists')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456')
        ->andReturnTrue();

    $result = app(EventFollowerServiceImpl::class)->isFollowing('user-uuid-123', 'event-uuid-456');

    expect($result)->toBeTrue();
});

it('returns false when the repository reports the user is not following the event', function () {
    $this->mock(EventFollowerRepository::class)
        ->shouldReceive('exists')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456')
        ->andReturnFalse();

    $result = app(EventFollowerServiceImpl::class)->isFollowing('user-uuid-123', 'event-uuid-456');

    expect($result)->toBeFalse();
});

// --- follow ---

it('saves a new follower record when the user is not yet following the event', function () {
    $savedFollower = null;

    $repo = $this->mock(EventFollowerRepository::class);
    $repo->shouldReceive('exists')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456')
        ->andReturnFalse();
    $repo->shouldReceive('save')
        ->once()
        ->andReturnUsing(function (EventFollower $follower) use (&$savedFollower) {
            $savedFollower = $follower;
            return $follower;
        });

    app(EventFollowerServiceImpl::class)->follow('user-uuid-123', 'event-uuid-456');

    expect($savedFollower)->toBeInstanceOf(EventFollower::class)
        ->and($savedFollower->user_id)->toBe('user-uuid-123')
        ->and($savedFollower->event_id)->toBe('event-uuid-456');
});

it('does not save a duplicate follower record when the user is already following the event', function () {
    $repo = $this->mock(EventFollowerRepository::class);
    $repo->shouldReceive('exists')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456')
        ->andReturnTrue();
    $repo->shouldNotReceive('save');

    app(EventFollowerServiceImpl::class)->follow('user-uuid-123', 'event-uuid-456');
});

// --- unfollow ---

it('delegates deletion to the repository when unfollowing', function () {
    $this->mock(EventFollowerRepository::class)
        ->shouldReceive('delete')
        ->once()
        ->with('user-uuid-123', 'event-uuid-456');

    app(EventFollowerServiceImpl::class)->unfollow('user-uuid-123', 'event-uuid-456');
});
