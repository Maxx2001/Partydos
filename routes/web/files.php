<?php

use App\Web\Files\Controllers\FileController;

Route::get('/event-banner/{media}', [FileController::class, 'show'])
    ->where('path', '.*')->name('event-banner');
