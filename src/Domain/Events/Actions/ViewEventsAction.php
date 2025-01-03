<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\EventEntity;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;

class ViewEventsAction
{
    public function execute()
    {
        /* @var User $user */
        $user = Auth::user();

        return EventEntity::collect(
            $events = $user->events()->futureEvents()->get()
        );
    }
}
