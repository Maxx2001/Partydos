<?php

use App\Web\Addresses\Controllers\AddressController;

Route::post('address-autocomplete', [AddressController::class, 'autocomplete'])->name('address.autocomplete');
