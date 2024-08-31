<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unique_identifier' => fake()->unique()->uuid(),
            'user_id'           => User::factory()->create()->getKey(),
            'title'             => fake()->sentence(),
            'description'       => fake()->paragraph(),
            'location'          => fake()->sentence(),
            'start_date_time'   => fake()->dateTime(),
            'end_date_time'     => fake()->dateTime(),
        ];
    }
}
