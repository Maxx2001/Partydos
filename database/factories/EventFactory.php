<?php

namespace Database\Factories;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Events\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'unique_identifier' => fake()->unique()->lexify(str_repeat('?', 20)),
            'user_id'           => User::factory(),
            'title'             => fake()->sentence(),
            'description'       => fake()->paragraph(),
            'start_date_time'   => fake()->dateTimeBetween('now', '+1 month'),
            'end_date_time'     => fake()->dateTimeBetween('+1 hour', '+1 month'),
        ];
    }
}
