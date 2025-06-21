<?php

use Illuminate\Support\Facades\Route;
use App\Api\Me\MyGalleryInvitationsController;

Route::middleware(['auth:sanctum'])->prefix('me')->name('api.me.')->group(function () {
    // Route for fetching gallery invitations specific to the authenticated user
    Route::get('/gallery-invitations', [MyGalleryInvitationsController::class, 'index'])->name('gallery_invitations.index');

    // Other /me/ routes can be added here, e.g., /me/profile, /me/settings
});
