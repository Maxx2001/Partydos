<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\EventStoreData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;
use Illuminate\Support\Facades\Session;
use Support\Actions\AttachMediaToModelAction;
use Support\Notification;

class GuestEventCreateAction
{
    public function execute(EventStoreData $eventStoreData, GuestUser $guestUser): Event
    {
        $event = Event::create($eventStoreData->all());
        $event->guestUser()->associate($guestUser);

        $event->save();

        AttachMediaToModelAction::execute([$eventStoreData->image], $event, '-banner');

        Notification::create('Event created!')->send();

        return $event;
    }
}
