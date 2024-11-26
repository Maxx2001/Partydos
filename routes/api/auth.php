<?php

use App\Api\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;


// add 'auth' to url  by group
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});
