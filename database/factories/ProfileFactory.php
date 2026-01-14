<?php

namespace Database\Factories;

use Domain\Profile\Models\Profile;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'display_name' => fake()->name(),
            'tagline' => fake()->optional()->sentence(),
            'bio' => fake()->optional()->paragraph(),
            'city' => fake()->optional()->city(),
            'birthdate' => fake()->optional()->date(),
            'website_url' => fake()->optional()->url(),
            'instagram_url' => fake()->optional()->url(),
            'tiktok_url' => fake()->optional()->url(),
            'spotify_url' => fake()->optional()->url(),
            'interests' => fake()->words(3),
            'favorite_music' => fake()->words(3),
            'party_style_tags' => fake()->words(3),
            'privacy_profile' => 'public',
            'privacy_guestbook' => 'public',
            'privacy_photos' => 'friends',
        ];
    }
}
