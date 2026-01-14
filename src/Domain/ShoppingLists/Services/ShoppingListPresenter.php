<?php

namespace Domain\ShoppingLists\Services;

use Domain\ShoppingLists\Models\ShoppingList;
use Domain\ShoppingLists\Models\ShoppingListItem;
use Domain\Users\Models\User;

class ShoppingListPresenter
{
    /** @return array{list: array<string, mixed>|null, items_main: array<int, array<string, mixed>>, items_guest: array<int, array<string, mixed>>} */
    public function present(?ShoppingList $shoppingList): array
    {
        if ($shoppingList === null || !$shoppingList->exists) {
            return [
                'list' => null,
                'items_main' => [],
                'items_guest' => [],
            ];
        }

        $items = $shoppingList->items()
            ->with(['createdBy', 'assignedTo', 'doneBy', 'lastUpdatedBy'])
            ->orderByRaw("case when status = 'open' then 0 else 1 end")
            ->orderBy('created_at')
            ->get();

        $itemsMain = $items
            ->where('source', 'host')
            ->values()
            ->map(fn (ShoppingListItem $item) => $this->itemPayload($item))
            ->all();

        $itemsGuest = $items
            ->where('source', 'guest')
            ->values()
            ->map(fn (ShoppingListItem $item) => $this->itemPayload($item))
            ->all();

        return [
            'list' => [
                'id' => $shoppingList->id,
                'eventId' => $shoppingList->event_id,
                'guestCanAdd' => $shoppingList->guest_can_add,
                'createdAt' => $shoppingList->created_at?->toDateTimeString(),
                'updatedAt' => $shoppingList->updated_at?->toDateTimeString(),
            ],
            'items_main' => $itemsMain,
            'items_guest' => $itemsGuest,
        ];
    }

    /** @return array<string, mixed> */
    private function itemPayload(ShoppingListItem $item): array
    {
        return [
            'id' => $item->id,
            'shoppingListId' => $item->shopping_list_id,
            'source' => $item->source,
            'status' => $item->status,
            'name' => $item->name,
            'quantity' => $item->quantity,
            'unit' => $item->unit,
            'note' => $item->note,
            'createdAt' => $item->created_at?->toDateTimeString(),
            'updatedAt' => $item->updated_at?->toDateTimeString(),
            'doneAt' => $item->done_at?->toDateTimeString(),
            'createdBy' => $this->userPayload($item->createdBy),
            'assignedTo' => $this->userPayload($item->assignedTo),
            'doneBy' => $this->userPayload($item->doneBy),
            'lastUpdatedBy' => $this->userPayload($item->lastUpdatedBy),
        ];
    }

    /** @return array{id: int, name: string, profilePhotoUrl: string}|null */
    private function userPayload(?User $user): ?array
    {
        if ($user === null) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'profilePhotoUrl' => $user->profile_photo_url,
        ];
    }
}
