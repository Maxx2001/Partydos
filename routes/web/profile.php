<?php

use App\Web\Profile\Controllers\FriendshipController;
use App\Web\Profile\Controllers\GuestbookController;
use App\Web\Profile\Controllers\MyProfileController;
use App\Web\Profile\Controllers\ProfilePhotoController;

Route::get('/profile', [MyProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [MyProfileController::class, 'update'])->name('profile.update');

Route::post('/profile/photos', [ProfilePhotoController::class, 'store'])->name('profile.photos.store');
Route::delete('/profile/photos/{photo}', [ProfilePhotoController::class, 'destroy'])->name('profile.photos.destroy');

Route::post('/u/{user}/guestbook', [GuestbookController::class, 'store'])->name('guestbook.store');
Route::delete('/guestbook/{entry}', [GuestbookController::class, 'destroy'])->name('guestbook.destroy');

Route::post('/u/{user}/friend-request', [FriendshipController::class, 'request'])->name('friendships.request');
Route::post('/friend-request/{friendship}/accept', [FriendshipController::class, 'accept'])->name('friendships.accept');
Route::post('/friend-request/{friendship}/block', [FriendshipController::class, 'block'])->name('friendships.block');
Route::delete('/friend-request/{friendship}', [FriendshipController::class, 'cancel'])->name('friendships.cancel');
