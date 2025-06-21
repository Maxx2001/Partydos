<?php

require __DIR__ . '/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    require __DIR__ . '/events.php';
    require __DIR__ . '/gallery.php'; // Added gallery API routes
    require __DIR__ . '/me.php'; // Added 'me' specific API routes
});
