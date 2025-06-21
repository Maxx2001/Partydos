<?php

require __DIR__ . '/events.php';
require __DIR__ . '/pages.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/files.php';
require __DIR__ . '/addresses.php';
require __DIR__ . '/gallery.php';

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    require __DIR__ . '/profile.php';
});
