<?php

use App\Api\Events\Controllers\EventsController;

Route::resource('events', EventsController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

