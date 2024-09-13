<?php

namespace Domain\Events\DataTransferObjects;

use App\Http\GuestUsers\Resources\GuestUserResource;

use Domain\Events\Models\Event;

class EventOwnerDTO
{
    public static function getOwner(Event $event): ?GuestUserResource
    {
        if ($event->user) {
            return new GuestUserResource($event->user);
        }

        if ($event->guestUser) {
            return new GuestUserResource($event->guestUser);
        }

        return null;
    }
}
