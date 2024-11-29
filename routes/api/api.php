<?php

require __DIR__ . '/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    require __DIR__ . '/events.php';
});
