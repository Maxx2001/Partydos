<?php

namespace Database\Factories;

use Domain\Profile\Models\GuestbookEntry;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GuestbookEntry>
 */
class GuestbookEntryFactory extends Factory
{
    protected $model = GuestbookEntry::class;

    public function definition(): array
    {
        return [
            'profile_user_id' => User::factory(),
            'author_user_id' => User::factory(),
            'body' => fake()->sentence(),
        ];
    }
}
