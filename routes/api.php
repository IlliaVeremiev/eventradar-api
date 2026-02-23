<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/events', [EventController::class, 'search']);
Route::get('/events/{eventId}', [EventController::class, 'getById']);
