<?php

use App\Web\Auth\Controllers\LoginController;

Route::post('/login/authenticate', [LoginController::class, 'authenticate'])
    ->name('login.authenticate');
