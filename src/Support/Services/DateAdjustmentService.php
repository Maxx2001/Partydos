<?php

namespace Support\Services;

class DateAdjustmentService
{
    /**
     * Adjust the end date if it is earlier than the start date.
     *
     * @param string|null $startDateTime
     * @param string|null $endDateTime
     * @return string|null
     */
    public static function adjustEndDate(?string $startDateTime, ?string $endDateTime): ?string
    {
        if ($endDateTime && $startDateTime > $endDateTime) {
            return date('Y-m-d H:i:s', strtotime($endDateTime . ' +1 day'));
        }

        return $endDateTime;
    }
}
