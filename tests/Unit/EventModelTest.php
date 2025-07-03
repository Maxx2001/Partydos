<?php

namespace Tests\Unit; // Keep original namespace if other tests depend on it

use Carbon\Carbon;
use Domain\Addresses\Models\Address;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase; // Use Laravel's TestCase

class EventModelTest extends TestCase // Extend Laravel's TestCase
{
    use RefreshDatabase; // Use RefreshDatabase for DB-dependent tests like scopes/relationships

    protected function setUp(): void
    {
        parent::setUp();
        // Set a known app.url for share_link and google_calendar_link tests
        Config::set('app.url', 'http://localhost');
    }

    public function test_event_is_fillable(): void
    {
        $event = new Event();
        $fillable = [
            'unique_identifier',
            'user_id',
            'guest_user_id',
            'title',
            'description',
            'start_date_time',
            'end_date_time',
            'canceled_at',
        ];
        $this->assertEquals($fillable, $event->getFillable());
    }

    // Accessor tests (existing ones are good, ensure they still pass)
    public function test_event_returns_formatted_date(): void
    {
        $event = new Event(['start_date_time' => '2024-12-31 20:00:00']);
        $expected = Carbon::parse('2024-12-31 20:00:00')->format('D d F');
        $this->assertEquals($expected, $event->formatted_date);
    }

    public function test_event_returns_formatted_time_with_start_only(): void
    {
        $event = new Event(['start_date_time' => '2024-12-31 20:00:00']);
        $this->assertEquals('20:00', $event->formatted_time);
    }

    public function test_event_returns_formatted_time_with_end_time(): void
    {
        $event = new Event([
            'start_date_time' => '2024-12-31 20:00:00',
            'end_date_time' => '2024-12-31 23:30:00',
        ]);
        $this->assertEquals('20:00 - 23:30', $event->formatted_time);
    }

    public function test_event_returns_formatted_time_same_start_and_end_time(): void
    {
        $event = new Event([
            'start_date_time' => '2024-12-31 20:00:00',
            'end_date_time' => '2024-12-31 20:00:00', // Same start and end
        ]);
        // Based on current logic: if ($this->end_date_time && $this->end_date_time !== $this->start_date_time)
        // it should only show start time.
        $this->assertEquals('20:00', $event->formatted_time);
    }


    public function test_event_generates_share_link(): void
    {
        $event = new Event(['unique_identifier' => 'test123456789']);
        $expectedUrl = 'http://localhost/event-invite/test123456789';
        $this->assertEquals($expectedUrl, $event->share_link);
    }

    public function test_event_returns_iso_start_date_time(): void
    {
        $event = new Event(['start_date_time' => '2024-12-31 20:00:00']);
        $this->assertEquals('2024-12-31T20:00:00', $event->iso_start_date_time);
    }

    public function test_event_returns_iso_end_date_time(): void
    {
        $event = new Event(['end_date_time' => '2024-12-31 23:30:00']);
        $this->assertEquals('2024-12-31T23:30:00', $event->iso_end_date_time);
    }

    public function test_event_returns_null_iso_end_date_time_when_no_end_date(): void
    {
        $event = new Event(['start_date_time' => '2024-12-31 20:00:00']);
        $this->assertNull($event->iso_end_date_time);
    }

    public function test_event_generates_google_calendar_link(): void
    {
        $address = Address::factory()->make(['address' => '123 Main St, Anytown']);
        $event = Event::factory()->make([ // Use factory for attributes, then override
            'title' => 'Test Event',
            'description' => 'Test Description',
            'start_date_time' => '2024-12-31 20:00:00',
            'end_date_time' => '2024-12-31 23:30:00',
        ]);
        // Manually associate the address for the accessor test, as factory might not set it up for a non-persisted model
        $event->setRelation('address', $address);


        $googleLink = $event->google_calendar_link;
        $this->assertStringContainsString('google.com/calendar', $googleLink);
        $this->assertStringContainsString('Test+Event', $googleLink);
        $this->assertStringContainsString(urlencode('123 Main St, Anytown'), $googleLink);
    }

    public function test_get_event_owner_attribute(): void
    {
        $user = User::factory()->create();
        $guestUser = GuestUser::factory()->create();

        $eventWithUser = Event::factory()->make(['user_id' => $user->id, 'guest_user_id' => null]);
        $eventWithUser->setRelation('user', $user); // Set relation for the accessor
        $this->assertSame($user, $eventWithUser->event_owner);

        $eventWithGuest = Event::factory()->make(['user_id' => null, 'guest_user_id' => $guestUser->id]);
        $eventWithGuest->setRelation('guestUser', $guestUser); // Set relation
        $this->assertSame($guestUser, $eventWithGuest->event_owner);

        // If both user and guest user are somehow set, user should take precedence by current logic `??`
        $eventWithBoth = Event::factory()->make(['user_id' => $user->id, 'guest_user_id' => $guestUser->id]);
        $eventWithBoth->setRelation('user', $user);
        $eventWithBoth->setRelation('guestUser', $guestUser);
        $this->assertSame($user, $eventWithBoth->event_owner);

        $eventWithNone = Event::factory()->make(['user_id' => null, 'guest_user_id' => null]);
        $this->assertNull($eventWithNone->event_owner);
    }

    public function test_get_invited_users_attribute(): void
    {
        $event = Event::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $guestUser1 = GuestUser::factory()->create();

        $event->users()->attach([$user1->id, $user2->id]);
        $event->guestUsers()->attach($guestUser1->id);

        // Fresh instance to ensure lazy loading is tested or relations are reloaded by accessor
        $freshEvent = Event::find($event->id);
        $invitedUsers = $freshEvent->invited_users;

        $this->assertCount(3, $invitedUsers);
        $this->assertTrue($invitedUsers->contains($user1));
        $this->assertTrue($invitedUsers->contains($user2));
        $this->assertTrue($invitedUsers->contains($guestUser1));
    }

    // Relationship tests
    public function test_event_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);
        $this->assertInstanceOf(BelongsTo::class, $event->user());
        $this->assertTrue($event->user->is($user));
    }

    public function test_event_belongs_to_guest_user(): void
    {
        $guestUser = GuestUser::factory()->create();
        $event = Event::factory()->create(['guest_user_id' => $guestUser->id]);
        $this->assertInstanceOf(BelongsTo::class, $event->guestUser());
        $this->assertTrue($event->guestUser->is($guestUser));
    }

    public function test_event_belongs_to_many_users(): void
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();
        $event->users()->attach($user);
        $this->assertInstanceOf(BelongsToMany::class, $event->users());
        $this->assertTrue($event->users->contains($user));
    }

    public function test_event_belongs_to_many_guest_users(): void
    {
        $event = Event::factory()->create();
        $guestUser = GuestUser::factory()->create();
        $event->guestUsers()->attach($guestUser);
        $this->assertInstanceOf(BelongsToMany::class, $event->guestUsers());
        $this->assertTrue($event->guestUsers->contains($guestUser));
    }

    public function test_event_has_morph_one_address(): void
    {
        $event = Event::factory()->create();
        $address = Address::factory()->create([
            'addressable_id' => $event->id,
            'addressable_type' => Event::class,
        ]);
        $this->assertInstanceOf(MorphOne::class, $event->address());
        $this->assertTrue($event->address->is($address));
    }


    // Scope tests
    public function test_future_events_scope(): void
    {
        $futureEventStartsNow = Event::factory()->create(['start_date_time' => now(), 'end_date_time' => now()->addHours(1)]);
        $futureEventStartsLater = Event::factory()->create(['start_date_time' => now()->addDay(), 'end_date_time' => now()->addDay()->addHour()]);
        $ongoingEventEndsLater = Event::factory()->create(['start_date_time' => now()->subHour(), 'end_date_time' => now()->addHour()]);
        $pastEvent = Event::factory()->create(['start_date_time' => now()->subDays(2), 'end_date_time' => now()->subDay()]);
        Event::factory()->create(['start_date_time' => now()->subHour(), 'end_date_time' => null]); // Past, no end time

        $retrievedEvents = Event::futureEvents()->get();

        $this->assertTrue($retrievedEvents->contains($futureEventStartsNow));
        $this->assertTrue($retrievedEvents->contains($futureEventStartsLater));
        $this->assertTrue($retrievedEvents->contains($ongoingEventEndsLater));
        $this->assertFalse($retrievedEvents->contains($pastEvent));
        $this->assertCount(3, $retrievedEvents);
    }

    public function test_history_events_scope(): void
    {
        $futureEvent = Event::factory()->create(['start_date_time' => now()->addDay()]);
        $pastEventEnded = Event::factory()->create(['start_date_time' => now()->subDays(2), 'end_date_time' => now()->subDay()]);
        $pastEventNoEnd = Event::factory()->create(['start_date_time' => now()->subHour(), 'end_date_time' => null]);
        $ongoingEventStartedPastEndsFuture = Event::factory()->create(['start_date_time' => now()->subHour(), 'end_date_time' => now()->addHour()]);


        $retrievedEvents = Event::historyEvents()->get();

        $this->assertFalse($retrievedEvents->contains($futureEvent));
        $this->assertTrue($retrievedEvents->contains($pastEventEnded));
        $this->assertTrue($retrievedEvents->contains($pastEventNoEnd));
        $this->assertFalse($retrievedEvents->contains($ongoingEventStartedPastEndsFuture)); // Because it's not fully in the past by one of its dates
        $this->assertCount(2, $retrievedEvents);
    }

    public function test_media_conversions_method_exists(): void
    {
        // This test primarily ensures the method is defined, as actual conversion
        // testing is better suited for feature tests or specific media library tests.
        $this->assertTrue(method_exists(Event::class, 'registerMediaConversions'));
    }
}