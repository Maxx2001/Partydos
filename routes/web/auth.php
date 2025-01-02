<?php

use App\Web\Auth\Controllers\AuthController;

Route::post('/login/authenticate', [AuthController::class, 'authenticate'])
    ->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/reset-password/{email}', [AuthController::class, 'resetPasswordEmail'])
    ->name('password.email');
Route::post('/update-password/{user}', [AuthController::class, 'resetPassword'])
    ->name('password.reset');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordPage'])
    ->name('password.reset-page');

Route::get('user/recovery/{recovery_token}', [AuthController::class, 'recovery'])->name('user-recovery');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
   Route::delete('/destroy-user', [AuthController::class, 'deleteUser'])->name('user-destroy');

   Route::post('/user/do-not-sell-my-data', [AuthController::class, 'registerNotSellDataUser'])->name('user-do-not-sell-my-data');
});

