<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventFollowerController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/events', [EventController::class, 'search']);
Route::get('/events/{eventId}', [EventController::class, 'getById']);

Route::post('/auth/google', [AuthController::class, 'googleSignIn']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::middleware(JwtMiddleware::class)->group(function () {
    Route::get('/events/{eventId}/follow', [EventFollowerController::class, 'status']);
    Route::post('/events/{eventId}/follow', [EventFollowerController::class, 'follow']);
    Route::delete('/events/{eventId}/follow', [EventFollowerController::class, 'unfollow']);
});
