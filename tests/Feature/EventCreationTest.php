<?php

namespace Tests\Feature;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_event(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $eventData = [
            'title' => 'Test Event',
            'description' => 'This is a test event description',
            'start_date_time' => '2024-12-31 20:00:00',
            'end_date_time' => '2025-01-01 02:00:00',
        ];

        $response = $this->post(route('users-events.store'), $eventData);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'description' => 'This is a test event description',
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_user_can_create_event(): void
    {
        $eventData = [
            'title' => 'Guest Event',
            'description' => 'Event created by guest user',
            'start_date_time' => '2024-12-31 20:00:00',
            'end_date_time' => '2025-01-01 02:00:00',
            'name' => 'Guest User',
            'email' => 'guest@example.com',
        ];

        $response = $this->post(route('guest-events.store'), $eventData);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('events', [
            'title' => 'Guest Event',
            'description' => 'Event created by guest user',
        ]);

        $this->assertDatabaseHas('guest_users', [
            'name' => 'Guest User',
            'email' => 'guest@example.com',
        ]);
    }

    public function test_event_requires_title(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventData = [
            'description' => 'Event without title',
            'start_date_time' => '2024-12-31 20:00:00',
        ];

        $response = $this->post(route('users-events.store'), $eventData);

        $response->assertSessionHasErrors('title');
    }

    public function test_event_requires_start_date(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventData = [
            'title' => 'Event without date',
            'description' => 'Event without start date',
        ];

        $response = $this->post(route('users-events.store'), $eventData);

        $response->assertSessionHasErrors('start_date_time');
    }

    public function test_event_generates_unique_identifier(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $eventData = [
            'title' => 'Test Event',
            'start_date_time' => '2024-12-31 20:00:00',
        ];

        $this->post(route('users-events.store'), $eventData);

        $event = Event::where('title', 'Test Event')->first();
        
        $this->assertNotNull($event->unique_identifier);
        $this->assertEquals(20, strlen($event->unique_identifier));
    }

    public function test_event_share_link_is_accessible(): void
    {
        $event = Event::factory()->create([
            'unique_identifier' => 'test123identifier',
        ]);

        $response = $this->get("/event-invite/{$event->unique_identifier}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Events/Invite')
                 ->has('event')
        );
    }
} 