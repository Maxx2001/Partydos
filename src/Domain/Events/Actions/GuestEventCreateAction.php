<?php

namespace Domain\Events\Actions;

use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Events\DataTransferObjects\EventStoreData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;
use Support\Actions\AttachMediaToModelAction;
use Support\Notification;
use Support\Services\DateAdjustmentService;

class GuestEventCreateAction
{
    public function execute(EventStoreData $eventStoreData, GuestUser $guestUser): Event
    {
        $eventStoreData->end_date_time = DateAdjustmentService::adjustEndDate(
            $eventStoreData->start_date_time,
            $eventStoreData->end_date_time
        );

        $event = Event::create($eventStoreData->all());
        $event->guestUser()->associate($guestUser);

        $address = (new CreateAddressAction())->execute($eventStoreData->location);
        $event->address()->save($address);

        $event->save();

        AttachMediaToModelAction::execute([$eventStoreData->image], $event, '-banner');

        Notification::create('Event created!')->send();

        return $event;
    }
}
