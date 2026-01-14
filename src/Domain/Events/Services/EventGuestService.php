<?php

namespace Domain\Events\Services;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;

class EventGuestService
{
    public function isGuest(User $user, Event $event): bool
    {
        return $event->users()->whereKey($user->getKey())->exists();
    }
}
