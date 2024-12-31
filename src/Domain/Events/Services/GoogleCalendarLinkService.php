<?php

namespace Domain\Events\Services;

use Carbon\Carbon;

class GoogleCalendarLinkService
{
    public static function generateLink($startDateTime, $endDateTime, $title, $description, $location): string
    {
        $endDateTime = $endDateTime ?: $startDateTime;

        return "https://www.google.com/calendar/render?action=TEMPLATE&text=" . urlencode($title) .
            "&dates=" . self::formatDateForGoogleCalendar($startDateTime) .
            "/" . self::formatDateForGoogleCalendar($endDateTime) .
            "&details=" . urlencode($description) .
            "&location=" . urlencode($location);
    }

    protected static function formatDateForGoogleCalendar($isoDateTime): string
    {
        return Carbon::parse($isoDateTime)->format('Ymd\THis');
    }
}
