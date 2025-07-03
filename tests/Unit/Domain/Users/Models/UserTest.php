<?php

namespace Tests\Unit\Domain\Users\Models;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Domain\Users\Models\UserNotSellData;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_attributes_are_mass_assignable_and_password_is_hashed(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'plain-password',
        ];
        $user = User::create($userData);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'name' => 'Test User']);
        $this->assertTrue(Hash::check('plain-password', $user->password));
        $this->assertEquals('Test User', $user->name);
    }

    public function test_email_verified_at_is_casted_to_datetime(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);

        $userWithoutVerification = User::factory()->create(['email_verified_at' => null]);
        $this->assertNull($userWithoutVerification->email_verified_at);
    }

    public function test_owned_events_relationship(): void
    {
        $user = User::factory()->create();
        $event1 = Event::factory()->create(['user_id' => $user->id]);
        $event2 = Event::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(HasMany::class, $user->ownedEvents());
        $this->assertCount(2, $user->ownedEvents);
        $this->assertTrue($user->ownedEvents->contains($event1));
    }

    public function test_events_relationship_for_attended_events(): void
    {
        $user = User::factory()->create();
        $event1 = Event::factory()->create();
        $user->events()->attach($event1->id);

        $this->assertInstanceOf(BelongsToMany::class, $user->events());
        $this->assertTrue($user->events->contains($event1));
    }

    public function test_user_not_sell_data_relationship(): void
    {
        $user = User::factory()->create();
        $notSellData = UserNotSellData::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(HasOne::class, $user->userNotSellData());
        $this->assertTrue($user->userNotSellData->is($notSellData));
    }

    public function test_upcoming_events_method(): void
    {
        $user = User::factory()->create();

        // Future owned event
        $futureOwned = Event::factory()->create(['user_id' => $user->id, 'start_date_time' => now()->addDays(2)]);
        // Future invited event
        $futureInvited = Event::factory()->create(['start_date_time' => now()->addDays(1)]);
        $user->events()->attach($futureInvited->id);

        // Past owned event
        Event::factory()->create(['user_id' => $user->id, 'start_date_time' => now()->subDays(1)]);
        // Past invited event
        $pastInvited = Event::factory()->create(['start_date_time' => now()->subDays(2)]);
        $user->events()->attach($pastInvited->id);

        $upcomingEvents = $user->upcomingEvents();

        $this->assertInstanceOf(EloquentCollection::class, $upcomingEvents);
        $this->assertCount(2, $upcomingEvents);
        $this->assertTrue($upcomingEvents->contains($futureOwned));
        $this->assertTrue($upcomingEvents->contains($futureInvited));
    }

    public function test_get_history_events_method(): void
    {
        $user = User::factory()->create();

        // Past owned event
        $pastOwned = Event::factory()->create(['user_id' => $user->id, 'start_date_time' => now()->subDays(2), 'end_date_time' => now()->subDay()]);
        // Past invited event
        $pastInvited = Event::factory()->create(['start_date_time' => now()->subDays(1), 'end_date_time' => now()->subHours(20) ]);
        $user->events()->attach($pastInvited->id);

        // Future owned event
        Event::factory()->create(['user_id' => $user->id, 'start_date_time' => now()->addDays(1)]);
        // Future invited event
        $futureInvited = Event::factory()->create(['start_date_time' => now()->addDays(2)]);
        $user->events()->attach($futureInvited->id);

        $historyEvents = $user->getHistoryEvents();

        $this->assertInstanceOf(EloquentCollection::class, $historyEvents);
        $this->assertCount(2, $historyEvents);
        $this->assertTrue($historyEvents->contains($pastOwned));
        $this->assertTrue($historyEvents->contains($pastInvited));
    }

    public function test_profile_photo_url_is_appended(): void
    {
        $user = User::factory()->make(); // No need to hit DB for appends test if trait works
        $userArray = $user->toArray();
        $this->assertArrayHasKey('profile_photo_url', $userArray);
    }
}
