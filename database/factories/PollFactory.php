<?php

namespace Database\Factories;

use Domain\Events\Models\Event;
use Domain\Polls\Models\Poll;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Polls\Models\Poll>
 */
class PollFactory extends Factory
{
    protected $model = Poll::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'question' => fake()->sentence(),
            'description' => fake()->optional()->sentence(),
            'status' => 'open',
            'vote_mode' => 'single',
            'guests_can_add_options' => false,
            'created_by_user_id' => User::factory(),
        ];
    }
}
