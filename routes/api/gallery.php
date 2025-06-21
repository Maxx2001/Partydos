<?php

use App\Api\Gallery\ApiGalleryController; // Corrected controller name
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('galleries')->name('api.galleries.')->group(function () {
    Route::get('/', [ApiGalleryController::class, 'index'])->name('index');
    Route::post('/', [ApiGalleryController::class, 'store'])->name('store');
    Route::get('/{gallery}', [ApiGalleryController::class, 'show'])->name('show');
    Route::post('/{gallery}/items', [ApiGalleryController::class, 'addGalleryItem'])->name('items.store');
    Route::post('/{gallery}/invitations', [ApiGalleryController::class, 'storeInvitation'])->name('invitations.store');
});
