<?php

namespace Tests\Unit\Domain\GuestUsers\Models;

use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_attributes_are_mass_assignable(): void
    {
        $data = [
            'name' => 'Test Guest',
            'email' => 'guest@example.com',
        ];
        // GuestUser::unguard(); // Should not be needed due to $guarded = []
        $guestUser = GuestUser::create($data);
        // GuestUser::reguard();

        $this->assertDatabaseHas('guest_users', $data);
        $this->assertEquals('Test Guest', $guestUser->name);
        $this->assertEquals('guest@example.com', $guestUser->email);
    }

    public function test_owned_events_relationship(): void
    {
        $guestUser = GuestUser::factory()->create();
        $event1 = Event::factory()->create(['guest_user_id' => $guestUser->id, 'user_id' => null]); // Ensure user_id is null if guest owns
        $event2 = Event::factory()->create(['guest_user_id' => $guestUser->id, 'user_id' => null]);

        $this->assertInstanceOf(HasMany::class, $guestUser->ownedEvents());
        $this->assertCount(2, $guestUser->ownedEvents);
        $this->assertTrue($guestUser->ownedEvents->contains($event1));
        $this->assertTrue($guestUser->ownedEvents->contains($event2));
    }

    public function test_events_relationship_for_attended_events(): void
    {
        $guestUser = GuestUser::factory()->create();
        $event1 = Event::factory()->create();
        $event2 = Event::factory()->create();

        $guestUser->events()->attach([$event1->id, $event2->id]);

        $this->assertInstanceOf(BelongsToMany::class, $guestUser->events());
        $this->assertCount(2, $guestUser->events);
        $this->assertTrue($guestUser->events->first()->is($event1) || $guestUser->events->first()->is($event2));
        $this->assertTrue($guestUser->events->contains($event1));
        $this->assertTrue($guestUser->events->contains($event2));
    }
}
