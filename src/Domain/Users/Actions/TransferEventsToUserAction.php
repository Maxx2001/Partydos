<?php

namespace Domain\Users\Actions;

use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;

class TransferEventsToUserAction
{
    public static function execute(GuestUser $guestUser, User $user): void
    {
        // Transfer the events owned by the guest user to the new user
        $ownedEvents = $guestUser->ownedEvents()->get();
        $ownedEvents->each(function (Event $event) use ($user) {
            $event->guestUser()->dissociate();
            $event->user()->associate($user);
            $event->save();
        });

        // Transfer the events the guest user is attending to the new user
        $events = $guestUser->events()->get();
        $user->events()->saveMany($events);
        $guestUser->events()->detach();
    }
}
