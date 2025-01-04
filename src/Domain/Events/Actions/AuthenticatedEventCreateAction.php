<?php

namespace Domain\Events\Actions;

use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\Models\Event;
use Support\Actions\AttachMediaToModelAction;
use Support\Notification;
use Support\Services\DateAdjustmentService;

class AuthenticatedEventCreateAction
{
    public function execute(AuthenticatedEventData $authenticatedEventStoreData)
    {
        $authenticatedEventStoreData->end_date_time = DateAdjustmentService::adjustEndDate(
            $authenticatedEventStoreData->start_date_time,
            $authenticatedEventStoreData->end_date_time
        );

        $event = Event::create($authenticatedEventStoreData->all());
        $event->user()->associate(auth()->user());

        if ($authenticatedEventStoreData->location) {
            $address = (new CreateAddressAction())->execute($authenticatedEventStoreData->location);
            $event->address()->save($address);
        }

        $event->save();

        AttachMediaToModelAction::execute([$authenticatedEventStoreData->image], $event, '-banner');

        Notification::create('Event created!')->send();

        return $event;
    }
}
