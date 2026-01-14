<?php

namespace App\Policies;

use Domain\Events\Services\EventGuestService;
use Domain\ShoppingLists\Models\ShoppingList;
use Domain\Users\Models\User;

class ShoppingListPolicy
{
    public function __construct(private EventGuestService $eventGuestService)
    {
    }

    public function view(User $user, ShoppingList $shoppingList): bool
    {
        $event = $shoppingList->event;

        if ($event === null) {
            return false;
        }

        return $event->user_id === $user->getKey()
            || $this->eventGuestService->isGuest($user, $event);
    }

    public function update(User $user, ShoppingList $shoppingList): bool
    {
        $event = $shoppingList->event;

        if ($event === null) {
            return false;
        }

        return $event->user_id === $user->getKey();
    }
}
