<?php

namespace Domain\Events\Actions;

use Domain\Users\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class GetEventListsForUserAction
{
    /** @return Collection<string, EloquentCollection<int, \Domain\Events\Models\Event>> */
    public function execute(User $user): Collection
    {
        $invitedEvents = $user->events()->with('address')->futureEvents()->get();
        $ownedEvents = $user->ownedEvents()->with('address')->futureEvents()->orderBy('start_date_time')->get();
        $historyEvents = $user->getHistoryEvents();

        return collect([
            'invitedEvents' => $invitedEvents,
            'ownedEvents' => $ownedEvents,
            'historyEvents' => $historyEvents,
        ]);
    }
} 