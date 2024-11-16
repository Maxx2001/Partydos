<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\EventStoreData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;
use Illuminate\Support\Facades\Session;

class GuestEventCreateAction
{
    public function execute(EventStoreData $eventStoreData, GuestUser $guestUser): Event
    {
        $event = Event::create($eventStoreData->all());
        $event->guestUser()->associate($guestUser);

        $event->save();
        Session::flash('event_created');

        return $event;
    }
}
