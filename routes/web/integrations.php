<?php

use App\Web\Integrations\Controllers\GoogleIntegrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/integrations/google', [GoogleIntegrationController::class, 'index'])
        ->name('integrations.google.index');
    Route::get('/integrations/google/connect', [GoogleIntegrationController::class, 'redirect'])
        ->name('integrations.google.connect');
    Route::get('/integrations/google/callback', [GoogleIntegrationController::class, 'callback'])
        ->name('integrations.google.callback');
    Route::post('/integrations/google/disconnect', [GoogleIntegrationController::class, 'disconnect'])
        ->name('integrations.google.disconnect');
    Route::post('/integrations/google/calendar', [GoogleIntegrationController::class, 'setCalendar'])
        ->name('integrations.google.calendar');
});
