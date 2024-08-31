<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Event;
use App\Models\GuestUser;

class TransferEventsToUser
{
    /**
     * Transfer the events owned by the guest user to the new user.
     *
     * @param GuestUser $guestUser
     * @param User      $user
     */
    public static function handle(GuestUser $guestUser, User $user)
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
