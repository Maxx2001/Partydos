<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return Inertia::render('Welcome/Welcome');
});

Route::resource('events', EventController::class)->only('create', 'store', 'show');
Route::get('event-invite/{event:unique_identifier}', [EventController::class, 'showInvite'])
    ->name('events.show-invite');
Route::post('event-register-guest/{event:unique_identifier}', [EventController::class, 'registerGuestUser'])
    ->name('events.register-guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
