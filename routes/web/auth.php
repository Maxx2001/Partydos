<?php

use App\Web\Auth\Controllers\AuthController;

Route::post('/login/authenticate', [AuthController::class, 'authenticate'])
    ->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/reset-password/{email}', [AuthController::class, 'resetPasswordEmail'])
    ->name('password.email');
Route::post('/update-password/{user}', [AuthController::class, 'resetPassword'])
    ->name('password.update');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordPage'])
    ->name('password.reset');

