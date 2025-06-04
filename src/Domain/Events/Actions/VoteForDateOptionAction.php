<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\EventDateOption;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;

class VoteForDateOptionAction
{
    public function execute(EventDateOption $option, User|GuestUser $voter): void
    {
        if ($voter instanceof User) {
            if (! $option->users()->where('user_id', $voter->id)->exists()) {
                $option->users()->attach($voter);
            }
        } else {
            if (! $option->guestUsers()->where('guest_user_id', $voter->id)->exists()) {
                $option->guestUsers()->attach($voter);
            }
        }
    }
}
