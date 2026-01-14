<?php

namespace Tests\Feature;

use Domain\Events\Models\Event;
use Domain\Polls\Models\Poll;
use Domain\Polls\Models\PollOption;
use Domain\Polls\Models\PollVote;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_host_can_create_poll_with_options(): void
    {
        $host = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $host->id]);

        $response = $this->actingAs($host)->post(route('events.polls.store', $event), [
            'question' => 'Where should we eat?',
            'description' => 'Dinner ideas',
            'vote_mode' => 'single',
            'guests_can_add_options' => true,
            'options' => ['Pizza', 'Sushi'],
        ]);

        $poll = Poll::first();

        $response->assertRedirect(route('events.polls.show', [$event, $poll]));
        $this->assertDatabaseHas('polls', [
            'event_id' => $event->id,
            'question' => 'Where should we eat?',
        ]);
        $this->assertDatabaseCount('poll_options', 2);
    }

    public function test_non_host_cannot_manage_poll_settings(): void
    {
        $host = User::factory()->create();
        $guest = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $host->id]);
        $poll = Poll::factory()->create(['event_id' => $event->id, 'created_by_user_id' => $host->id]);

        $this->actingAs($guest)
            ->post(route('events.polls.store', $event), [
                'question' => 'New poll',
                'vote_mode' => 'single',
                'options' => ['Yes', 'No'],
            ])
            ->assertForbidden();

        $this->actingAs($guest)
            ->patch(route('events.polls.update', [$event, $poll]), [
                'question' => 'Updated',
            ])
            ->assertForbidden();

        $this->actingAs($guest)
            ->post(route('events.polls.close', [$event, $poll]))
            ->assertForbidden();
    }

    public function test_participant_can_vote_when_open_and_not_when_closed(): void
    {
        $host = User::factory()->create();
        $participant = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $host->id]);
        $event->users()->attach($participant);

        $poll = Poll::factory()->create(['event_id' => $event->id, 'created_by_user_id' => $host->id]);
        $option = PollOption::factory()->create(['poll_id' => $poll->id]);

        $this->actingAs($participant)
            ->post(route('events.polls.vote', [$event, $poll]), [
                'option_id' => $option->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
            'user_id' => $participant->id,
        ]);

        $poll->update(['status' => 'closed']);

        $this->actingAs($participant)
            ->post(route('events.polls.vote', [$event, $poll]), [
                'option_id' => $option->id,
            ])
            ->assertForbidden();
    }

    public function test_single_mode_enforces_one_vote_per_participant(): void
    {
        $host = User::factory()->create();
        $participant = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $host->id]);
        $event->users()->attach($participant);

        $poll = Poll::factory()->create([
            'event_id' => $event->id,
            'vote_mode' => 'single',
            'created_by_user_id' => $host->id,
        ]);
        $optionA = PollOption::factory()->create(['poll_id' => $poll->id]);
        $optionB = PollOption::factory()->create(['poll_id' => $poll->id]);

        $this->actingAs($participant)
            ->post(route('events.polls.vote', [$event, $poll]), [
                'option_id' => $optionA->id,
            ]);

        $this->actingAs($participant)
            ->post(route('events.polls.vote', [$event, $poll]), [
                'option_id' => $optionB->id,
            ]);

        $this->assertDatabaseMissing('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $optionA->id,
            'user_id' => $participant->id,
        ]);
        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $optionB->id,
            'user_id' => $participant->id,
        ]);
        $this->assertSame(1, PollVote::where('poll_id', $poll->id)->where('user_id', $participant->id)->count());
    }

    public function test_multiple_mode_allows_multiple_votes_and_syncs(): void
    {
        $host = User::factory()->create();
        $participant = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $host->id]);
        $event->users()->attach($participant);

        $poll = Poll::factory()->create([
            'event_id' => $event->id,
            'vote_mode' => 'multiple',
            'created_by_user_id' => $host->id,
        ]);
        $optionA = PollOption::factory()->create(['poll_id' => $poll->id]);
        $optionB = PollOption::factory()->create(['poll_id' => $poll->id]);
        $optionC = PollOption::factory()->create(['poll_id' => $poll->id]);

        $this->actingAs($participant)
            ->post(route('events.polls.vote', [$event, $poll]), [
                'option_ids' => [$optionA->id, $optionB->id],
            ]);

        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $optionA->id,
            'user_id' => $participant->id,
        ]);
        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $optionB->id,
            'user_id' => $participant->id,
        ]);

        $this->actingAs($participant)
            ->post(route('events.polls.vote', [$event, $poll]), [
                'option_ids' => [$optionB->id, $optionC->id],
            ]);

        $this->assertDatabaseMissing('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $optionA->id,
            'user_id' => $participant->id,
        ]);
        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $optionB->id,
            'user_id' => $participant->id,
        ]);
        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $optionC->id,
            'user_id' => $participant->id,
        ]);
    }

    public function test_guest_can_add_option_only_when_enabled(): void
    {
        $host = User::factory()->create();
        $participant = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $host->id]);
        $event->users()->attach($participant);

        $poll = Poll::factory()->create([
            'event_id' => $event->id,
            'guests_can_add_options' => false,
            'created_by_user_id' => $host->id,
        ]);

        $this->actingAs($participant)
            ->post(route('events.polls.options.store', [$event, $poll]), [
                'text' => 'New option',
            ])
            ->assertForbidden();

        $poll->update(['guests_can_add_options' => true]);

        $this->actingAs($participant)
            ->post(route('events.polls.options.store', [$event, $poll]), [
                'text' => 'New option',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('poll_options', [
            'poll_id' => $poll->id,
            'text' => 'New option',
        ]);
    }

    public function test_non_participant_cannot_view_or_vote(): void
    {
        $host = User::factory()->create();
        $outsider = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $host->id]);
        $poll = Poll::factory()->create(['event_id' => $event->id, 'created_by_user_id' => $host->id]);
        $option = PollOption::factory()->create(['poll_id' => $poll->id]);

        $this->actingAs($outsider)
            ->get(route('events.polls.show', [$event, $poll]))
            ->assertForbidden();

        $this->actingAs($outsider)
            ->post(route('events.polls.vote', [$event, $poll]), [
                'option_id' => $option->id,
            ])
            ->assertForbidden();
    }
}
