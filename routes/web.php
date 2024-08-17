<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    return Inertia::render('Welcome/Welcome');
});

Route::resource('events', EventController::class)->only('create', 'store', 'show');
Route::get('event-invite/{event:unique_identifier}', [EventController::class, 'showInvite'])->name('events.show-invite');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
