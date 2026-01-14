<?php

namespace App\Policies;

use Domain\Events\Services\EventGuestService;
use Domain\ShoppingLists\Models\ShoppingList;
use Domain\ShoppingLists\Models\ShoppingListItem;
use Domain\Users\Models\User;

class ShoppingListItemPolicy
{
    public function __construct(private EventGuestService $eventGuestService)
    {
    }

    public function create(User $user, ShoppingList $shoppingList): bool
    {
        $event = $shoppingList->event;

        if ($event === null) {
            return false;
        }

        if ($event->user_id === $user->getKey()) {
            return true;
        }

        return $shoppingList->guest_can_add
            && $this->eventGuestService->isGuest($user, $event);
    }

    public function update(User $user, ShoppingListItem $item): bool
    {
        $event = $item->shoppingList?->event;

        if ($event === null) {
            return false;
        }

        return $event->user_id === $user->getKey()
            || $this->eventGuestService->isGuest($user, $event);
    }

    public function delete(User $user, ShoppingListItem $item): bool
    {
        $event = $item->shoppingList?->event;

        if ($event === null) {
            return false;
        }

        return $event->user_id === $user->getKey();
    }

    public function promote(User $user, ShoppingListItem $item): bool
    {
        $event = $item->shoppingList?->event;

        if ($event === null) {
            return false;
        }

        return $event->user_id === $user->getKey();
    }
}
