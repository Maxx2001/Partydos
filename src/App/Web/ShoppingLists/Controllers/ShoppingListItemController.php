<?php

namespace App\Web\ShoppingLists\Controllers;

use Domain\Events\Models\Event;
use Domain\ShoppingLists\Models\ShoppingListItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Support\Controllers\Controller;

class ShoppingListItemController extends Controller
{
    public function store(Event $event, Request $request): RedirectResponse
    {
        $shoppingList = $event->shoppingList;

        if ($shoppingList === null) {
            abort(404);
        }

        Gate::authorize('create', [ShoppingListItem::class, $shoppingList]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:20'],
            'note' => ['nullable', 'string', 'max:240'],
        ]);

        $user = $request->user();
        $source = $event->user_id === $user->getKey() ? 'host' : 'guest';

        if ($source === 'guest' && !$shoppingList->guest_can_add) {
            abort(403);
        }

        $shoppingList->items()->create([
            'name' => $data['name'],
            'quantity' => $data['quantity'] ?? null,
            'unit' => $data['unit'] ?? null,
            'note' => $data['note'] ?? null,
            'source' => $source,
            'created_by_user_id' => $user->getKey(),
            'last_updated_by_user_id' => $user->getKey(),
        ]);

        return redirect()->back();
    }

    public function update(ShoppingListItem $item, Request $request): RedirectResponse
    {
        $item->loadMissing('shoppingList.event');

        Gate::authorize('update', $item);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:20'],
            'note' => ['nullable', 'string', 'max:240'],
            'assigned_to_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $user = $request->user();
        $event = $item->shoppingList->event;
        $isHost = $event->user_id === $user->getKey();

        $updates = [];

        if ($isHost) {
            if (array_key_exists('name', $data)) {
                $updates['name'] = $data['name'];
            }

            if (array_key_exists('quantity', $data)) {
                $updates['quantity'] = $data['quantity'];
            }

            if (array_key_exists('unit', $data)) {
                $updates['unit'] = $data['unit'];
            }

            if (array_key_exists('assigned_to_user_id', $data)) {
                $updates['assigned_to_user_id'] = $data['assigned_to_user_id'];
            }
        }

        if (array_key_exists('note', $data)) {
            $canEditNote = $isHost
                || $item->assigned_to_user_id === $user->getKey()
                || $item->created_by_user_id === $user->getKey();

            if (!$canEditNote) {
                abort(403);
            }

            $updates['note'] = $data['note'];
        }

        if ($updates !== []) {
            $updates['last_updated_by_user_id'] = $user->getKey();
            $item->update($updates);
        }

        return redirect()->back();
    }

    public function toggleDone(ShoppingListItem $item, Request $request): RedirectResponse
    {
        $item->loadMissing('shoppingList.event');

        Gate::authorize('update', $item);

        $user = $request->user();

        if ($item->status === 'done') {
            $item->update([
                'status' => 'open',
                'done_by_user_id' => null,
                'done_at' => null,
                'last_updated_by_user_id' => $user->getKey(),
            ]);
        } else {
            $item->update([
                'status' => 'done',
                'done_by_user_id' => $user->getKey(),
                'done_at' => now(),
                'last_updated_by_user_id' => $user->getKey(),
            ]);
        }

        return redirect()->back();
    }

    public function assignSelf(ShoppingListItem $item, Request $request): RedirectResponse
    {
        $item->loadMissing('shoppingList.event');

        Gate::authorize('update', $item);

        $user = $request->user();

        $item->update([
            'assigned_to_user_id' => $user->getKey(),
            'last_updated_by_user_id' => $user->getKey(),
        ]);

        return redirect()->back();
    }

    public function unassign(ShoppingListItem $item, Request $request): RedirectResponse
    {
        $item->loadMissing('shoppingList.event');

        Gate::authorize('update', $item);

        $user = $request->user();
        $event = $item->shoppingList->event;
        $isHost = $event->user_id === $user->getKey();

        if (!$isHost && $item->assigned_to_user_id !== $user->getKey()) {
            abort(403);
        }

        $item->update([
            'assigned_to_user_id' => null,
            'last_updated_by_user_id' => $user->getKey(),
        ]);

        return redirect()->back();
    }

    public function promoteToMain(ShoppingListItem $item, Request $request): RedirectResponse
    {
        $item->loadMissing('shoppingList.event');

        Gate::authorize('promote', $item);

        $user = $request->user();

        $item->update([
            'source' => 'host',
            'last_updated_by_user_id' => $user->getKey(),
        ]);

        return redirect()->back();
    }

    public function destroy(ShoppingListItem $item): RedirectResponse
    {
        $item->loadMissing('shoppingList.event');

        Gate::authorize('delete', $item);

        $item->delete();

        return redirect()->back();
    }
}
