<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\EventEntityData;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;

class ViewEventsAction
{
    public function execute()
    {
        /* @var User $user */
        $user = Auth::user();

        return EventEntityData::collect(
            $events = $user->events()->futureEvents()->get()
        );
    }
}
