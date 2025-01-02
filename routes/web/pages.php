<?php

use App\Web\Pages\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PagesController::class, 'index'])->name('home');
Route::get('/features', [PagesController::class, 'features'])->name('features');
Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [PagesController::class, 'privacyPolicy'])->name('privacy-policy');

