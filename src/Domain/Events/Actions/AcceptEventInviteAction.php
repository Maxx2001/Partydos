<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Support\Notification;

class AcceptEventInviteAction
{
    public function execute(Event $event, User $user): void
    {
        if ($event->users()->where('user_id', $user->id)->exists()) {
            Notification::create('You are already assigned to this event')->send();
            return;
        }

        if ($event->guestUsers()->where("email", $user->email)->exists()) {
            Notification::create('This email is already used')->send();
            return;
        }

        Notification::create('You have been registered to the event!')->send();

        $event->users()->attach($user);
    }
}
