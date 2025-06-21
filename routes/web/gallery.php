<?php

use Illuminate\Support\Facades\Route;
use App\Web\Gallery\GalleryController; // Ensure this is the correct namespace

// Ensure APP_DOMAIN is set in .env or default to 'localhost' for local dev.
// Example: pictures.yourproject.ddev.site or pictures.localhost
$domain = str_replace(['http://', 'https://'], '', config('app.url'));
// Remove port if present
$domainParts = explode(':', $domain);
$bareDomain = $domainParts[0];

// If config('app.domain') is available, it would be preferred.
// For now, derive from APP_URL or default.
$appDomain = config('app.domain', $bareDomain);


Route::domain('pictures.' . $appDomain)
    ->middleware(['web', 'auth']) // Added 'web' and 'auth' middleware
    ->group(function () {
        Route::get('/', GalleryController::class)->name('gallery.index'); // This now serves the Vue/Inertia index page
        Route::get('/galleries/{gallery}', [GalleryController::class, 'showGalleryPage'])->name('gallery.show'); // For individual gallery page
        Route::get('/google-albums', [GalleryController::class, 'listMyGooglePhotosAlbums'])->name('gallery.google_albums');
        // Future routes for store, show, etc. can be added here.
    });

// Gallery Invitation Acceptance Routes (should be accessible without auth)
use App\Web\Gallery\GalleryInvitationController;

Route::name('gallery.invitations.')->prefix('gallery-invitations')->group(function () {
    Route::get('/{token}/accept', [GalleryInvitationController::class, 'accept'])->name('accept');
    Route::get('/{token}/reject', [GalleryInvitationController::class, 'reject'])->name('reject');
});

// Route for "My Received Invitations" page
Route::get('/my-gallery-invitations', function () {
    // This assumes Inertia is configured to resolve 'Gallery/MyInvitations'
    // For non-Inertia, you might return a Blade view that mounts the Vue component.
    return \Inertia\Inertia::render('Gallery/MyInvitations');
})->middleware(['auth'])->name('gallery.my_invitations');
