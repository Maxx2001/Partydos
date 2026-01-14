<?php

namespace Tests\Feature;

use App\Jobs\SyncGoogleEventJob;
use App\Models\EventIntegration;
use App\Models\GoogleAccount;
use App\Services\Google\GoogleCalendarClient;
use App\Services\Google\GoogleOAuthService;
use App\Services\Google\GoogleOAuthUserData;
use Carbon\Carbon;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GoogleCalendarIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_callback_stores_google_account_tokens(): void
    {
        $user = User::factory()->create();

        $this->mock(GoogleOAuthService::class, function ($mock): void {
            $mock->shouldReceive('fetchUserData')
                ->once()
                ->andReturn(new GoogleOAuthUserData(
                    googleUserId: 'google-123',
                    email: 'user@example.com',
                    accessToken: 'access-token',
                    refreshToken: 'refresh-token',
                    expiresAt: Carbon::now()->addHour(),
                ));
        });

        $this->actingAs($user)
            ->withSession(['google_oauth_state' => 'state-token'])
            ->get(route('integrations.google.callback', [
                'state' => 'state-token',
                'code' => 'auth-code',
            ]))
            ->assertRedirect(route('integrations.google.index'));

        $this->assertDatabaseHas('google_accounts', [
            'user_id' => $user->id,
            'google_user_id' => 'google-123',
            'email' => 'user@example.com',
            'calendar_id' => 'primary',
        ]);
    }

    public function test_calendar_selection_persists(): void
    {
        $user = User::factory()->create();
        GoogleAccount::create([
            'user_id' => $user->id,
            'google_user_id' => 'google-123',
            'email' => 'user@example.com',
            'access_token' => 'access-token',
            'refresh_token' => 'refresh-token',
            'token_expires_at' => Carbon::now()->addHour(),
            'calendar_id' => 'primary',
        ]);

        $this->actingAs($user)
            ->post(route('integrations.google.calendar'), [
                'calendar_id' => 'work-calendar',
            ])
            ->assertRedirect(route('integrations.google.index'));

        $this->assertDatabaseHas('google_accounts', [
            'user_id' => $user->id,
            'calendar_id' => 'work-calendar',
        ]);
    }

    public function test_manual_sync_creates_event_integration(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        GoogleAccount::create([
            'user_id' => $user->id,
            'google_user_id' => 'google-123',
            'email' => 'user@example.com',
            'access_token' => 'access-token',
            'refresh_token' => 'refresh-token',
            'token_expires_at' => Carbon::now()->addHour(),
            'calendar_id' => 'primary',
        ]);

        $integration = EventIntegration::create([
            'event_id' => $event->id,
            'provider' => 'google',
            'external_calendar_id' => 'primary',
            'external_event_id' => 'google-event-1',
            'sync_enabled' => true,
        ]);

        $this->mock(GoogleCalendarClient::class, function ($mock) use ($event, $user, $integration): void {
            $mock->shouldReceive('upsertEvent')
                ->once()
                ->with($user, $event)
                ->andReturn($integration);
        });

        $this->actingAs($user)
            ->post(route('events.google.sync', ['event' => $event->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('event_integrations', [
            'event_id' => $event->id,
            'provider' => 'google',
            'external_event_id' => 'google-event-1',
        ]);
    }

    public function test_event_update_dispatches_sync_job_when_enabled(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        GoogleAccount::create([
            'user_id' => $user->id,
            'google_user_id' => 'google-123',
            'email' => 'user@example.com',
            'access_token' => 'access-token',
            'refresh_token' => 'refresh-token',
            'token_expires_at' => Carbon::now()->addHour(),
            'calendar_id' => 'primary',
        ]);

        EventIntegration::create([
            'event_id' => $event->id,
            'provider' => 'google',
            'external_calendar_id' => 'primary',
            'external_event_id' => 'google-event-1',
            'sync_enabled' => true,
        ]);

        $event->update(['title' => 'Updated title']);

        Queue::assertPushed(SyncGoogleEventJob::class, function (SyncGoogleEventJob $job) use ($event) {
            return $job->eventId === $event->id;
        });
    }

    public function test_disconnect_removes_google_account_and_integrations(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        GoogleAccount::create([
            'user_id' => $user->id,
            'google_user_id' => 'google-123',
            'email' => 'user@example.com',
            'access_token' => 'access-token',
            'refresh_token' => 'refresh-token',
            'token_expires_at' => Carbon::now()->addHour(),
            'calendar_id' => 'primary',
        ]);

        EventIntegration::create([
            'event_id' => $event->id,
            'provider' => 'google',
            'external_calendar_id' => 'primary',
            'external_event_id' => 'google-event-1',
            'sync_enabled' => true,
        ]);

        $this->actingAs($user)
            ->post(route('integrations.google.disconnect'))
            ->assertRedirect(route('integrations.google.index'));

        $this->assertDatabaseMissing('google_accounts', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('event_integrations', [
            'event_id' => $event->id,
            'provider' => 'google',
        ]);
    }
}
