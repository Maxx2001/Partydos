<?php

namespace Tests\Feature;

use Domain\Events\Models\Event;
use Domain\ShoppingLists\Models\ShoppingList;
use Domain\ShoppingLists\Models\ShoppingListItem;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;

    public function test_host_can_create_list_and_add_items(): void
    {
        $host = User::factory()->create();
        $event = Event::factory()->for($host)->create();

        $this->actingAs($host);

        $this->post(route('events.shopping-list.store', $event))->assertRedirect();

        $shoppingList = ShoppingList::where('event_id', $event->id)->firstOrFail();

        $this->post(route('shopping-list-items.store', $event), [
            'name' => 'Ice bags',
            'quantity' => 2,
            'unit' => 'bags',
        ])->assertRedirect();

        $this->assertDatabaseHas('shopping_list_items', [
            'shopping_list_id' => $shoppingList->id,
            'name' => 'Ice bags',
            'source' => 'host',
        ]);
    }

    public function test_guest_cannot_add_items_when_disabled(): void
    {
        $host = User::factory()->create();
        $guest = User::factory()->create();
        $event = Event::factory()->for($host)->create();

        $event->users()->attach($guest);

        $shoppingList = ShoppingList::create([
            'event_id' => $event->id,
            'guest_can_add' => false,
        ]);

        $this->actingAs($guest);

        $this->post(route('shopping-list-items.store', $event), [
            'name' => 'Chips',
        ])->assertForbidden();

        $this->assertDatabaseMissing('shopping_list_items', [
            'shopping_list_id' => $shoppingList->id,
            'name' => 'Chips',
        ]);
    }

    public function test_guest_can_add_items_when_enabled(): void
    {
        $host = User::factory()->create();
        $guest = User::factory()->create();
        $event = Event::factory()->for($host)->create();

        $event->users()->attach($guest);

        $shoppingList = ShoppingList::create([
            'event_id' => $event->id,
            'guest_can_add' => true,
        ]);

        $this->actingAs($guest);

        $this->post(route('shopping-list-items.store', $event), [
            'name' => 'Soda',
        ])->assertRedirect();

        $this->assertDatabaseHas('shopping_list_items', [
            'shopping_list_id' => $shoppingList->id,
            'name' => 'Soda',
            'source' => 'guest',
        ]);
    }

    public function test_host_can_promote_guest_item_to_main_list(): void
    {
        $host = User::factory()->create();
        $guest = User::factory()->create();
        $event = Event::factory()->for($host)->create();

        $event->users()->attach($guest);

        $shoppingList = ShoppingList::create([
            'event_id' => $event->id,
            'guest_can_add' => true,
        ]);

        $item = ShoppingListItem::create([
            'shopping_list_id' => $shoppingList->id,
            'name' => 'Snacks',
            'source' => 'guest',
            'created_by_user_id' => $guest->id,
        ]);

        $this->actingAs($host);

        $this->post(route('shopping-list-items.promote', $item))->assertRedirect();

        $this->assertDatabaseHas('shopping_list_items', [
            'id' => $item->id,
            'source' => 'host',
        ]);
    }

    public function test_guest_can_toggle_done_and_assignment_rules_are_enforced(): void
    {
        $host = User::factory()->create();
        $guest = User::factory()->create();
        $otherGuest = User::factory()->create();
        $event = Event::factory()->for($host)->create();

        $event->users()->attach([$guest->id, $otherGuest->id]);

        $shoppingList = ShoppingList::create([
            'event_id' => $event->id,
            'guest_can_add' => true,
        ]);

        $item = ShoppingListItem::create([
            'shopping_list_id' => $shoppingList->id,
            'name' => 'Plates',
            'source' => 'host',
            'status' => 'open',
        ]);

        $this->actingAs($guest);

        $this->post(route('shopping-list-items.assign-self', $item))->assertRedirect();
        $this->assertDatabaseHas('shopping_list_items', [
            'id' => $item->id,
            'assigned_to_user_id' => $guest->id,
        ]);

        $this->post(route('shopping-list-items.toggle-done', $item))->assertRedirect();
        $this->assertDatabaseHas('shopping_list_items', [
            'id' => $item->id,
            'status' => 'done',
            'done_by_user_id' => $guest->id,
        ]);

        $this->actingAs($otherGuest);
        $this->post(route('shopping-list-items.unassign', $item))->assertForbidden();
    }

    public function test_unrelated_user_cannot_view_list(): void
    {
        $host = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->for($host)->create();

        ShoppingList::create([
            'event_id' => $event->id,
            'guest_can_add' => false,
        ]);

        $this->actingAs($otherUser);

        $this->get(route('events.shopping-list.show', $event))->assertForbidden();
    }
}
