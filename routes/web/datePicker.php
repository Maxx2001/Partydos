<?php

use App\Web\DatePicker\Controllers\DatePickerController;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::post(
        '/date-picker',
        [DatePickerController::class, 'store']
    )->name('date-polls.store');


});
