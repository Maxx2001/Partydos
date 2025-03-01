<?php

use App\Web\Profile\Controllers\ProfileController;

Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
