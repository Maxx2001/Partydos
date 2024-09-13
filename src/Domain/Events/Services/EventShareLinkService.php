<?php

namespace Domain\Events\Services;

use Domain\Events\Models\Event;

class EventShareLinkService
{
    public function generateShareLink(Event $event): string
    {
        return config('app.url') . '/event-invite/' . $event->unique_identifier;
    }
}
