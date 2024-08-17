<?php

namespace App\Actions\Event;

use App\Models\Event;
use App\Models\GuestUser;

class EventCreate
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
        return Event::create([
            'title'           => $title,
            'description'     => $description,
            'location'        => $location,
            'start_date_time' => $startDateTime,
            'end_date_time'   => $endDateTime,
        ])->guestUser()->associate($guestUser)->save();

    }
}
