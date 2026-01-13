<?php

namespace Domain\Events\Services;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;

class EventParticipantService
{
    public function isHost(Event $event, User $user): bool
    {
        return $event->user_id === $user->id;
    }

    public function isParticipant(Event $event, User $user): bool
    {
        if ($this->isHost($event, $user)) {
            return true;
        }

        if ($event->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return $event->guestUsers()->where('email', $user->email)->exists();
    }
}
