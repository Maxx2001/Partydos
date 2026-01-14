<?php

namespace App\Web\ShoppingLists\Controllers;

use Domain\Events\Models\Event;
use Domain\ShoppingLists\Models\ShoppingList;
use Domain\ShoppingLists\Services\ShoppingListPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Support\Controllers\Controller;

class ShoppingListController extends Controller
{
    public function store(Event $event): RedirectResponse
    {
        $shoppingList = $event->shoppingList ?? new ShoppingList([
            'guest_can_add' => false,
        ]);
        $shoppingList->event()->associate($event);

        Gate::authorize('update', $shoppingList);

        if (!$shoppingList->exists) {
            $shoppingList->save();
        }

        return redirect()->back();
    }

    public function update(Event $event, Request $request): RedirectResponse
    {
        $shoppingList = $event->shoppingList;

        if ($shoppingList === null) {
            abort(404);
        }

        Gate::authorize('update', $shoppingList);

        $data = $request->validate([
            'guest_can_add' => ['required', 'boolean'],
        ]);

        $shoppingList->update($data);

        return redirect()->back();
    }

    public function show(Event $event, ShoppingListPresenter $presenter): JsonResponse
    {
        $shoppingList = $event->shoppingList;

        if ($shoppingList === null) {
            $shoppingList = new ShoppingList();
            $shoppingList->event()->associate($event);
        }

        Gate::authorize('view', $shoppingList);

        return response()->json($presenter->present($shoppingList->exists ? $shoppingList : null));
    }
}
