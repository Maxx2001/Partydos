<?php

use App\Web\Events\Controllers\EventController;
use App\Web\ShoppingLists\Controllers\ShoppingListController;
use App\Web\ShoppingLists\Controllers\ShoppingListItemController;
use Illuminate\Support\Facades\Route;

Route::resource('guest-events', EventController::class)->only('create', 'store');

Route::get('event-invite/{event:unique_identifier}', [EventController::class, 'show'])->name('events.show-invite');
Route::get('/event/{event}/download-ics', [EventController::class, 'downloadEventICS'])->name('event.download.ics');

Route::post('event-register-guest/{event:unique_identifier}', [EventController::class, 'registerGuestUser'])
    ->name('events.register-guest');

Route::post('event-accept-invite/{event:unique_identifier}', [EventController::class, 'acceptInvite'])
    ->name('events.accept-invite');

Route::post('/event/{event:unique_identifier}/authenticate', [EventController::class, 'authenticateAndAcceptInvite'])
    ->name('event.authenticate');

Route::post('/event/{event:unique_identifier}/register', [EventController::class, 'registerAndAcceptInvite'])
    ->name('event.register');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::post('users-events', [EventController::class, 'authenticateStore'])->name('users-events.store');

    Route::resource('users-events', EventController::class)
        ->parameters(['users-events' => 'event'])
        ->only('index', 'update');

    Route::get('event-invite/edit/{event:unique_identifier}', [EventController::class, 'edit'])->name('events.edit');

    Route::delete('event-cancel-invite/{event:unique_identifier}', [EventController::class, 'cancelInvite'])
        ->name('events.cancel-invite');

    Route::post('event-cancel/{event}', [EventController::class, 'cancelEvent'])->name('events.cancel');
    Route::post('event-restore/{event}', [EventController::class, 'restoreEvent'])->name('events.restore');

    Route::delete('event/{event}', [EventController::class, 'destroy'])->name('events.delete');

    Route::post('/events/{event}/shopping-list', [ShoppingListController::class, 'store'])
        ->name('events.shopping-list.store');
    Route::patch('/events/{event}/shopping-list', [ShoppingListController::class, 'update'])
        ->name('events.shopping-list.update');
    Route::get('/events/{event}/shopping-list', [ShoppingListController::class, 'show'])
        ->name('events.shopping-list.show');

    Route::post('/events/{event}/shopping-list/items', [ShoppingListItemController::class, 'store'])
        ->name('shopping-list-items.store');
    Route::patch('/shopping-list-items/{item}', [ShoppingListItemController::class, 'update'])
        ->name('shopping-list-items.update');
    Route::delete('/shopping-list-items/{item}', [ShoppingListItemController::class, 'destroy'])
        ->name('shopping-list-items.destroy');
    Route::post('/shopping-list-items/{item}/toggle-done', [ShoppingListItemController::class, 'toggleDone'])
        ->name('shopping-list-items.toggle-done');
    Route::post('/shopping-list-items/{item}/assign-self', [ShoppingListItemController::class, 'assignSelf'])
        ->name('shopping-list-items.assign-self');
    Route::post('/shopping-list-items/{item}/unassign', [ShoppingListItemController::class, 'unassign'])
        ->name('shopping-list-items.unassign');
    Route::post('/shopping-list-items/{item}/promote', [ShoppingListItemController::class, 'promoteToMain'])
        ->name('shopping-list-items.promote');
});
