<?php

namespace Database\Factories;

use Domain\Polls\Models\Poll;
use Domain\Polls\Models\PollOption;
use Domain\Polls\Models\PollVote;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Polls\Models\PollVote>
 */
class PollVoteFactory extends Factory
{
    protected $model = PollVote::class;

    public function definition(): array
    {
        return [
            'poll_id' => Poll::factory(),
            'poll_option_id' => PollOption::factory(),
            'user_id' => User::factory(),
        ];
    }
}
