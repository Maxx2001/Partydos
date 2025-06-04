<?php

use App\Web\Events\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::resource('guest-events', EventController::class)->only('create', 'store');

Route::get('event-invite/{event:unique_identifier}', [EventController::class, 'show'])->name('events.show-invite');
Route::get('/event/{event}/download-ics', [EventController::class, 'downloadEventICS'])->name('event.download.ics');

Route::post('event-register-guest/{event:unique_identifier}', [EventController::class, 'registerGuestUser'])
    ->name('events.register-guest');

Route::post('event-accept-invite/{event:unique_identifier}', [EventController::class, 'acceptInvite'])
    ->name('events.accept-invite');

Route::post('/event/{event:unique_identifier}/authenticate', [EventController::class, 'authenticateAndAcceptInvite'])
    ->name('event.authenticate');

Route::post('/event/{event:unique_identifier}/register', [EventController::class, 'registerAndAcceptInvite'])
    ->name('event.register');

Route::post('event/{event:unique_identifier}/date-option/{option}/vote', [EventController::class, 'voteDateOption'])
    ->name('event.date-option.vote');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::post('users-events', [EventController::class, 'authenticateStore'])->name('users-events.store');

    Route::resource('users-events', EventController::class)
        ->parameters(['users-events' => 'event'])
        ->only('index', 'update');

    Route::get('event-invite/edit/{event:unique_identifier}', [EventController::class, 'edit'])->name('events.edit');

    Route::delete('event-cancel-invite/{event:unique_identifier}', [EventController::class, 'cancelInvite'])
        ->name('events.cancel-invite');

    Route::post('event-cancel/{event}', [EventController::class, 'cancelEvent'])->name('events.cancel');
    Route::post('event-restore/{event}', [EventController::class, 'restoreEvent'])->name('events.restore');

    Route::delete('event/{event}', [EventController::class, 'destroy'])->name('events.delete');
});
