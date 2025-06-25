<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class CheckUserIsEventOwnerAction
{
    public function execute(Event $event, User $user): void
    {
        if ($event->user_id !== $user->id) {
            throw new AuthorizationException('This action is unauthorized.');
        }
    }
} 