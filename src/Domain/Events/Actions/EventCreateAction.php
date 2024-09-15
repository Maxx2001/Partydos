<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;

class EventCreateAction
{

    public static function execute(
        GuestUser $guestUser,
        string    $title,
        ?string   $description,
        ?string   $location,
        string    $startDateTime,
        ?string   $endDateTime
    ): Event
    {
        $event = Event::create([
            'title'           => $title,
            'description'     => $description,
            'location'        => $location,
            'start_date_time' => $startDateTime,
            'end_date_time'   => $endDateTime,
        ]);

        $event->guestUser()->associate($guestUser);
        $event->save();

        return $event;
    }
}
