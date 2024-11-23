<?php

use App\Web\Events\Controllers\AuthenticatedEventController;
use App\Web\Events\Controllers\GuestEventController;
use Illuminate\Support\Facades\Route;

Route::resource('guest-events', GuestEventController::class)->only('create', 'store', 'show');

Route::get('event-invite/{event:unique_identifier}', [GuestEventController::class, 'showInvite'])->name('events.show-invite');
Route::get('/event/{event}/download-ics', [GuestEventController::class, 'downloadEventICS'])->name('event.download.ics');

Route::post('event-register-guest/{event:unique_identifier}', [GuestEventController::class, 'registerGuestUser'])
    ->name('events.register-guest');

Route::post('event-accept-invite/{event:unique_identifier}', [GuestEventController::class, 'acceptInvite'])
    ->name('events.accept-invite');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        Route::resource('users-events', AuthenticatedEventController::class)->only('index', 'store');
});
