<?php

use App\Api\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
//Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
