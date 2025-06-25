<?php

namespace Domain\Events\DataTransferObjects;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Data;

class EventInviteViewData extends Data
{
    public bool $showInviteButton = true;

    public bool $showCancelButton = false;

    public static function fromEvent(Event $event): self
    {
        $showInviteButton = true;
        $showCancelButton = false;

        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            $isParticipant = $event->users->contains($user);
            $isOwner = $event->user_id === $user->id;

            $showInviteButton = ! $isParticipant && ! $isOwner;
            $showCancelButton = $isParticipant && ! $isOwner;
        }

        return self::from([
            'showInviteButton' => $showInviteButton,
            'showCancelButton' => $showCancelButton,
        ]);
    }
} 