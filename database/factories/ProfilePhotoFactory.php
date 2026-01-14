<?php

namespace Database\Factories;

use Domain\Profile\Models\ProfilePhoto;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProfilePhoto>
 */
class ProfilePhotoFactory extends Factory
{
    protected $model = ProfilePhoto::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'path' => 'profile-photos/sample.jpg',
            'caption' => fake()->optional()->sentence(),
        ];
    }
}
