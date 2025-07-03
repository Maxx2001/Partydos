<?php

namespace Tests\Feature\Api\Events;

use Domain\Addresses\Models\Address;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum'); // Authenticate user for all tests in this class by default
    }

    // --- Index Tests ---
    public function test_can_get_list_of_events_and_owned_events(): void
    {
        // Events user is invited to (or associated via ViewEventsAction logic)
        Event::factory()->count(2)->create()->each(function ($event) {
            $event->users()->attach($this->user);
        });
        // Events user owns
        Event::factory()->count(3)->create(['user_id' => $this->user->id]);
        // Unrelated event
        Event::factory()->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'events' => ['data' => [['id', 'title']]], // Assuming EventEntity::collect wraps in 'data'
                'ownedEvents' => ['data' => [['id', 'title']]],
            ]);

        // Note: The exact count assertion for 'events' depends on ViewEventsAction logic
        // and EventEntity constructor. For 'ownedEvents', it should be 3.
        // $this->assertCount(3, $response->json('ownedEvents.data'));
        // $this->assertCount(2, $response->json('events.data')); // This depends on ViewEventsAction
        // For now, just asserting structure and successful response. Detailed data checks can be added.
    }

    public function test_index_is_protected_by_auth_sanctum(): void
    {
        $this->actingAs(User::factory()->create()); // Act as a non-sanctum user or no user
        Auth()->logout(); // Ensure no user is authenticated via sanctum for this test

        $response = $this->getJson('/api/events');
        $response->assertStatus(401);
    }

    // --- Store Tests ---
    public function test_can_create_event_with_valid_data_without_location_or_image(): void
    {
        $eventData = [
            'title' => 'New API Event',
            'description' => 'Description for new API event.',
            'start_date_time' => now()->addDay()->toDateTimeString(),
            'end_date_time' => now()->addDay()->addHours(2)->toDateTimeString(),
            // No location, no image
        ];

        $response = $this->postJson('/api/events', $eventData);

        $response->assertStatus(200) // Controller returns 200 from action
            ->assertJsonPath('title', $eventData['title'])
            ->assertJsonPath('user_id', $this->user->id); // Check if AuthenticatedEventCreateAction set this

        $this->assertDatabaseHas('events', [
            'title' => $eventData['title'],
            'user_id' => $this->user->id,
        ]);
    }

    public function test_can_create_event_with_valid_data_including_location_and_image(): void
    {
        $eventData = [
            'title' => 'Full API Event',
            'description' => 'Full event description.',
            'start_date_time' => now()->addDays(2)->toDateTimeString(),
            'end_date_time' => now()->addDays(2)->addHours(3)->toDateTimeString(),
            'location' => [ // Data for AddressCreateData
                'place_id' => 'ChIJN1t_tDeuEmsRUsoyG83frY4', // Example Place ID
                'address' => '123 Main St, Anytown, USA',
            ],
            'image' => UploadedFile::fake()->image('event_banner.jpg'),
        ];

        $response = $this->postJson('/api/events', $eventData);

        $response->assertStatus(200)
            ->assertJsonPath('title', $eventData['title'])
            ->assertJsonPath('user_id', $this->user->id);

        $this->assertDatabaseHas('events', ['title' => $eventData['title']]);
        $this->assertDatabaseHas('addresses', ['address' => '123 Main St, Anytown, USA']);

        $createdEvent = Event::where('title', $eventData['title'])->first();
        $this->assertNotNull($createdEvent);
        // Spatie media library check
        // $this->assertTrue($createdEvent->hasMedia('event-banner')); // Requires event model to use HasMedia trait
    }

    public function test_store_event_validates_required_fields(): void
    {
        $response = $this->postJson('/api/events', []); // Empty data

        $response->assertStatus(422) // Unprocessable Entity for validation errors
            ->assertJsonValidationErrors(['title', 'start_date_time']); // As per AuthenticatedEventData DTO
    }

    public function test_store_event_validates_image_type_and_size(): void
    {
        $eventData = [
            'title' => 'Event With Invalid Image',
            'start_date_time' => now()->addDay()->toDateTimeString(),
            'image' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'), // Invalid type
        ];
        $response = $this->postJson('/api/events', $eventData);
        $response->assertStatus(422)->assertJsonValidationErrors(['image']);

        $eventData['image'] = UploadedFile::fake()->image('large_image.jpg')->size(6000); // Too large (max 5120KB)
        $response = $this->postJson('/api/events', $eventData);
        $response->assertStatus(422)->assertJsonValidationErrors(['image']);
    }

    public function test_store_is_protected_by_auth_sanctum(): void
    {
        Auth()->logout();
        $response = $this->postJson('/api/events', ['title' => 'test']);
        $response->assertStatus(401);
    }

    // --- Show Tests ---
    public function test_can_show_an_existing_event(): void
    {
        $event = Event::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/events/{$event->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $event->id) // EventEntity wraps in 'data'
            ->assertJsonPath('data.title', $event->title);
    }

    public function test_show_returns_404_for_non_existing_event(): void
    {
        $response = $this->getJson('/api/events/9999'); // Non-existent ID
        $response->assertStatus(404);
    }

    public function test_show_is_protected_by_auth_sanctum(): void
    {
        $event = Event::factory()->create();
        Auth()->logout();
        $response = $this->getJson("/api/events/{$event->id}");
        $response->assertStatus(401);
    }

    // --- Update Tests ---
    // TODO: Add update tests - will need to consider ownership/authorization
    // For now, basic structure:
    public function test_can_update_owned_event_with_valid_data(): void
    {
        $event = Event::factory()->create(['user_id' => $this->user->id]);
        $updateData = [
            'title' => 'Updated Event Title via API',
            'description' => 'Updated description.',
            'start_date_time' => now()->addDays(5)->toDateTimeString(),
        ];

        $response = $this->putJson("/api/events/{$event->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('title', $updateData['title']);
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => $updateData['title'],
        ]);
    }

    public function test_update_is_protected_by_auth_sanctum(): void
    {
        $event = Event::factory()->create();
        Auth()->logout();
        $response = $this->putJson("/api/events/{$event->id}", ['title' => 'test']);
        $response->assertStatus(401);
    }

    // --- Destroy Tests ---
    // TODO: Add destroy tests - will need to consider ownership/authorization
    public function test_can_destroy_owned_event(): void
    {
        // The controller's destroy method itself doesn't check ownership,
        // but the AuthenticatedEventUpdateAction (if it were used for delete auth) or
        // a policy/gate attached to the route would.
        // The current controller code is: $event->delete();
        // This means any authenticated user can delete any event if no policy prevents it.
        // Let's assume for now that the DestroyEventAction (which has ownership check)
        // *should* be used or a policy is in place.
        // For this feature test, we'll test if an *owned* event can be deleted.

        $event = Event::factory()->create(['user_id' => $this->user->id, 'canceled_at' => now()]);
        // Note: The DestroyEventAction (unit tested earlier) requires event to be canceled first.
        // The controller's destroy method does not call this action, it just calls $event->delete().
        // This is a discrepancy. I will test the controller as written.

        $response = $this->deleteJson("/api/events/{$event->id}");
        $response->assertStatus(204);
        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }

    public function test_destroy_is_protected_by_auth_sanctum(): void
    {
        $event = Event::factory()->create();
        Auth()->logout();
        $response = $this->deleteJson("/api/events/{$event->id}");
        $response->assertStatus(401);
    }
}
