<?php

namespace Tests\Feature\Web\Pages;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PagesControllerTest extends TestCase
{
    use RefreshDatabase;

    // --- Index (Home) Page Tests ---
    public function test_index_page_renders_for_guest_user(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('LandingsPage/Index')
                 ->where('showUpcomingEvents', false)
                 ->where('events', []) // Expect empty array for guests
        );
    }

    public function test_index_page_renders_for_authenticated_user_with_upcoming_events(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some events for the user
        // Invited upcoming event
        $invitedEvent = Event::factory()->create(['start_date_time' => now()->addDays(1)]);
        $user->events()->attach($invitedEvent);

        // Owned upcoming event
        $ownedEvent = Event::factory()->create(['user_id' => $user->id, 'start_date_time' => now()->addDays(2)]);

        // Past event (should not be included in upcoming)
        Event::factory()->create(['user_id' => $user->id, 'start_date_time' => now()->subDays(1)]);


        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('LandingsPage/Index')
                 ->where('showUpcomingEvents', true)
                 ->has('events.data', 2) // EventEntity::collect wraps in 'data'
                 ->where('events.data.0.id', $invitedEvent->id) // Order might depend on upcomingEvents() logic
                 ->where('events.data.1.id', $ownedEvent->id)
        );
    }

    public function test_index_page_renders_for_authenticated_user_with_no_upcoming_events(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // No upcoming events created for the user

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('LandingsPage/Index')
                 ->where('showUpcomingEvents', true)
                 ->has('events.data', 0) // EventEntity::collect wraps in 'data'
        );
    }

    // --- Features Page Test ---
    public function test_features_page_renders_correctly(): void
    {
        $response = $this->get(route('features'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('Features/Index'));
    }

    // --- Contact Page Test ---
    public function test_contact_page_renders_correctly(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('Contact/Index'));
    }

    // --- Privacy Policy Page Test ---
    public function test_privacy_policy_page_renders_correctly(): void
    {
        $response = $this->get(route('privacy-policy'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('PrivacyPolicy/Index'));
    }
}
