<?php

namespace Domain\Auth\Actions;

use Domain\Users\Models\User;
use Support\Notification;

class DestroyUserAction
{
    public function execute(User $user): void
    {
        $ownedEvents = $user->ownedEvents;
        $ownedEvents->each->delete();

        $invitedEvents = $user->events;
        $invitedEvents->each(function ($event) use ($user) {
            $event->users()->detach($user);
        });

        $user->forceDelete();
    }
}
