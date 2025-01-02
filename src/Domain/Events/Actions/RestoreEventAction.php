<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Support\Notification;

class RestoreEventAction
{
    public function execute(Event $event): void
    {
        $event->canceled_at = null;
        $event->save();

        Notification::create('Event has been restored!')->send();
    }
}
