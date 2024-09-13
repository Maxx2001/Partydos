<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;

class DetermineEventOwnerAction
{
    public function execute(Event $event): string
    {
        return $event->user_id ? 'user' : 'guestUser';
    }
}
