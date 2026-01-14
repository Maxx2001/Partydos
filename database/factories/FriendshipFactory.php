<?php

namespace Database\Factories;

use Domain\Profile\Models\Friendship;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Friendship>
 */
class FriendshipFactory extends Factory
{
    protected $model = Friendship::class;

    public function definition(): array
    {
        return [
            'requester_user_id' => User::factory(),
            'addressee_user_id' => User::factory(),
            'status' => 'pending',
        ];
    }
}
