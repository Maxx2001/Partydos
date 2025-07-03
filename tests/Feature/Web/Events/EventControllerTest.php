<?php

namespace Tests\Feature\Web\Events;

use Domain\Events\Actions\AuthenticatedEventCreateAction;
use Domain\Events\Actions\GetEventListsForUserAction;
use Domain\Events\Actions\GuestEventCreateAction;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection; // For GetEventListsForUserAction return
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    // --- Guest Event Creation ---
    public function test_can_view_guest_event_create_page(): void
    {
        $response = $this->get(route('guest-events.create'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('Events/Create'));
    }

    public function test_guest_can_create_event_with_valid_data(): void
    {
        $mockGuestEventCreateAction = Mockery::mock(GuestEventCreateAction::class);
        $createdEvent = Event::factory()->make(['id' => 1, 'unique_identifier' => 'test-uid']); // Not saved, just for redirect

        $mockGuestEventCreateAction->shouldReceive('execute')
            ->once()
            // ->with(Mockery::type(GuestEventCreateData::class)) // Check DTO type
            ->andReturn($createdEvent);
        $this->app->instance(GuestEventCreateAction::class, $mockGuestEventCreateAction);

        $eventData = [
            'name' => 'Guest User Name',
            'email' => 'guest@example.com',
            'title' => 'Guest Created Event',
            'start_date_time' => now()->addHours(2)->toDateTimeString(),
        ];

        $response = $this->post(route('guest-events.store'), $eventData);

        $response->assertRedirect(route('events.show-invite', $createdEvent));
    }

    public function test_guest_event_store_validates_required_fields(): void
    {
        // GuestEventCreateData DTO has validation rules
        $response = $this->post(route('guest-events.store'), []);
        $response->assertSessionHasErrors(['name', 'email', 'title', 'start_date_time']);
    }

    // --- Authenticated User Event Listing (Index) ---
    public function test_authenticated_user_can_view_their_events_index(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $mockEventLists = new SupportCollection([
            'invitedEvents' => new SupportCollection([]), // Mocked as empty for simplicity
            'ownedEvents' => new SupportCollection([]),
            'historyEvents' => new SupportCollection([]),
        ]);

        $mockGetEventListsAction = Mockery::mock(GetEventListsForUserAction::class);
        $mockGetEventListsAction->shouldReceive('execute')->once()->with($user)->andReturn($mockEventLists);
        $this->app->instance(GetEventListsForUserAction::class, $mockGetEventListsAction);

        $response = $this->get(route('users-events.index'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('Events/Index')
                 ->has('events') // Which is invitedEvents after DTO transformation
                 ->has('ownedEvents')
                 ->has('historyEvents')
        );
    }

    // --- Authenticated Event Creation (authenticateStore) ---
    public function test_authenticated_user_can_create_event(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $mockAuthEventCreateAction = Mockery::mock(AuthenticatedEventCreateAction::class);
        $createdEvent = Event::factory()->make(['id' => 2, 'user_id' => $user->id, 'unique_identifier' => 'auth-uid']);

        $mockAuthEventCreateAction->shouldReceive('execute')
            ->once()
            // ->with(Mockery::type(AuthenticatedEventData::class))
            ->andReturn($createdEvent);
        $this->app->instance(AuthenticatedEventCreateAction::class, $mockAuthEventCreateAction);

        $eventData = [
            'title' => 'Authenticated User Event',
            'start_date_time' => now()->addDay()->toDateTimeString(),
            'image' => UploadedFile::fake()->image('banner.jpg'), // Example with image
        ];

        $response = $this->post(route('users-events.store'), $eventData);
        $response->assertRedirect(route('events.show-invite', $createdEvent));
    }

    public function test_authenticated_event_store_validates_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('users-events.store'), []);
        // AuthenticatedEventData DTO has validation rules
        $response->assertSessionHasErrors(['title', 'start_date_time']);
    }


    // --- Show Event (Public) ---
    public function test_can_view_event_invite_page(): void
    {
        $event = Event::factory()->create(['description' => 'Test Event Description']);
        // Ensure address is loaded if EventEntity expects it from the model passed to `from`
        $event->load('address');

        $response = $this->get(route('events.show-invite', $event));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('Events/Invite')
                 ->where('event.data.id', $event->id) // EventEntity wraps in 'data'
                 ->where('event.data.title', $event->title)
                 ->has('showInviteModal') // Default value is likely false or null
                 ->has('showInviteButton')
                 ->has('showCancelButton')
                 ->where('viewData.title', $event->title) // Check viewData
                 ->where('viewData.description', $event->description)
        );
    }

    // --- Download ICS (Public) ---
    public function test_can_download_event_ics_file(): void
    {
        $event = Event::factory()->create();
        // EventGenerateIcsAction is injected, so it will be the real one unless mocked.
        // For a feature test, allowing it to run can be okay if it doesn't have heavy external deps.
        // If it did, we'd mock it:
        // $mockIcsAction = Mockery::mock(EventGenerateIcsAction::class);
        // $mockIcsAction->shouldReceive('execute')->once()->with($event)->andReturn("BEGIN:VCALENDAR...");
        // $this->app->instance(EventGenerateIcsAction::class, $mockIcsAction);

        $response = $this->get(route('event.download.ics', $event));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/calendar; charset=UTF-8'); // charset might be added by Laravel
        $response->assertHeader('Content-Disposition', 'attachment; filename="event.ics"');
        $this->assertStringContainsString("BEGIN:VCALENDAR", $response->getContent());
        $this->assertStringContainsString("SUMMARY:{$event->title}", $response->getContent());
    }

    // TODO: Add tests for edit, update, destroy, registerGuestUser, acceptInvite, cancelInvite,
    // cancelEvent, restoreEvent, authenticateAndAcceptInvite, registerAndAcceptInvite.
    // These will involve more setup for authentication, authorization (ownership checks),
    // and mocking specific actions.
}
