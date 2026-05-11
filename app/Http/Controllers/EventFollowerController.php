<?php

namespace App\Http\Controllers;

use App\Services\EventFollowerService;
use Illuminate\Http\Request;

class EventFollowerController extends Controller
{
    public function __construct(private readonly EventFollowerService $eventFollowerService)
    {
    }

    public function status(Request $request, string $eventId)
    {
        $following = $this->eventFollowerService->isFollowing($request->user()->id, $eventId);

        return response()->json(['following' => $following]);
    }

    public function follow(Request $request, string $eventId)
    {
        $this->eventFollowerService->follow($request->user()->id, $eventId);

        return response()->json(['following' => true]);
    }

    public function unfollow(Request $request, string $eventId)
    {
        $this->eventFollowerService->unfollow($request->user()->id, $eventId);

        return response()->json(['following' => false]);
    }
}
