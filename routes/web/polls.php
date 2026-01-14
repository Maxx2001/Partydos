<?php

use App\Web\Polls\Controllers\PollController;
use App\Web\Polls\Controllers\PollOptionController;
use App\Web\Polls\Controllers\PollVoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/events/{event}/polls', [PollController::class, 'index'])->name('events.polls.index');
        Route::get('/events/{event}/polls/create', [PollController::class, 'create'])->name('events.polls.create');
        Route::post('/events/{event}/polls', [PollController::class, 'store'])->name('events.polls.store');
        Route::get('/events/{event}/polls/{poll}', [PollController::class, 'show'])->name('events.polls.show');
        Route::patch('/events/{event}/polls/{poll}', [PollController::class, 'update'])->name('events.polls.update');
        Route::post('/events/{event}/polls/{poll}/close', [PollController::class, 'close'])->name('events.polls.close');
        Route::post('/events/{event}/polls/{poll}/reopen', [PollController::class, 'reopen'])->name('events.polls.reopen');
        Route::delete('/events/{event}/polls/{poll}', [PollController::class, 'destroy'])->name('events.polls.destroy');

        Route::post('/events/{event}/polls/{poll}/options', [PollOptionController::class, 'store'])->name('events.polls.options.store');
        Route::delete('/events/{event}/polls/{poll}/options/{option}', [PollOptionController::class, 'destroy'])
            ->name('events.polls.options.destroy');

        Route::post('/events/{event}/polls/{poll}/vote', [PollVoteController::class, 'store'])->name('events.polls.vote');
    });
