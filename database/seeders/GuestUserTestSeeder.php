<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\GuestUser;
use Illuminate\Database\Seeder;

class GuestUserTestSeeder extends Seeder
{
    public function run(): void
    {
        $guestUser = GuestUser::firstOrCreate(
            [
                'email' => 'max.felis11@gmail.com',
            ],
            [
                'name' => 'Max Felis',
            ]
        );

        /* @var Event $event */
        $event = Event::create([
            'title'           => 'Test event',
            'description'     => 'Test description',
            'location'        => 'Test location',
            'start_date_time' => Carbon::now()->addDays(1)->toDateTimeString(),
            'end_date_time'   => Carbon::now()->addDays(1)->addHours(2)->toDateTimeString(),
        ]);

        $event->guestUser()->associate($guestUser)->save();

        /* @var Event $event */
        $event = Event::Factory()->create();
        $event->guestUsers()->attach($guestUser);
    }
}
