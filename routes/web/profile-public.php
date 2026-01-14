<?php

use App\Web\Profile\Controllers\PublicProfileController;

Route::get('/u/{user}', [PublicProfileController::class, 'show'])->name('profiles.show');
