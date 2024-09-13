<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;

class EventCreateAction
{

    public static function handle(
        GuestUser $guestUser,
        string    $title,
        ?string   $description,
        ?string   $location,
        string    $startDateTime,
        ?string   $endDateTime
    )
    {
        $event = Event::create([
            'title'           => $title,
            'description'     => $description,
            'location'        => $location,
            'start_date_time' => $startDateTime,
            'end_date_time'   => $endDateTime,
        ]);

        $event->guestUser()->associate($guestUser)->save();

        return $event;
    }
}
