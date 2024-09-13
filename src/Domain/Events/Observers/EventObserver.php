<?php

namespace Domain\Events\Observers;

use Domain\Events\Models\Event;
use Illuminate\Support\Str;

class EventObserver
{
    public function creating(Event $event): void
    {
        $event->unique_identifier = Str::random();
    }
}
