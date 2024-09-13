<?php

namespace Domain\Events\Actions;

use Carbon\Carbon;
use Domain\Events\Models\Event;

class EventGenerateIcsAction
{
    public static function handle(Event $event): string
    {
        $startDate        = Carbon::parse($event->start_date_time)->format('Ymd\THis');
        $endDate          = Carbon::parse($event->start_date_time)->format('Ymd\THis');
        $eventName        = addslashes($event->title);
        $eventDescription = addslashes($event->description);
        $eventLocation    = addslashes($event->location);

        // this format is necessary for the ics file to function
        return "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//partydos.nl//NONSGML v1.0//EN
BEGIN:VEVENT
UID:" . uniqid() . "@partydos.nl
DTSTAMP:" . now()->format('Ymd\THis') . "
DTSTART:{$startDate}
DTEND:{$endDate}
SUMMARY:{$eventName}
DESCRIPTION:{$eventDescription}
LOCATION:{$eventLocation}
END:VEVENT
END:VCALENDAR";
    }
}
