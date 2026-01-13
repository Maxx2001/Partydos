<?php

namespace Database\Factories;

use Domain\Polls\Models\Poll;
use Domain\Polls\Models\PollOption;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Polls\Models\PollOption>
 */
class PollOptionFactory extends Factory
{
    protected $model = PollOption::class;

    public function definition(): array
    {
        return [
            'poll_id' => Poll::factory(),
            'text' => fake()->words(3, true),
            'created_by_user_id' => User::factory(),
        ];
    }
}
