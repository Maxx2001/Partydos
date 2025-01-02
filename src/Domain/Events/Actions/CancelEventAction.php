<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Support\Notification;

class CancelEventAction
{
    public function execute(Event $event): void
    {
        $event->canceled_at = now();
        $event->save();

        Notification::create('Event has been canceled!')->send();
    }
}
