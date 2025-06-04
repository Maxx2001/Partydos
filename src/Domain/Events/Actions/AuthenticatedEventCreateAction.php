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
        if (! $authenticatedEventStoreData->is_datepicker) {
            $authenticatedEventStoreData->end_date_time = DateAdjustmentService::adjustEndDate(
                $authenticatedEventStoreData->start_date_time,
                $authenticatedEventStoreData->end_date_time
            );
        }

        $eventArray = $authenticatedEventStoreData->toArray();
        $dateOptions = $eventArray['date_options'] ?? null;
        unset($eventArray['date_options']);

        $event = Event::create($eventArray);
        $event->user()->associate(auth()->user());

        if ($authenticatedEventStoreData->location) {
            $address = (new CreateAddressAction())->execute($authenticatedEventStoreData->location);
            $event->address()->save($address);
        }

        $event->save();

        if ($authenticatedEventStoreData->is_datepicker && $dateOptions) {
            foreach ($dateOptions as $option) {
                $event->dateOptions()->create($option);
            }
        }

        AttachMediaToModelAction::execute([$authenticatedEventStoreData->image], $event, '-banner');

        Notification::create('Event created!')->send();

        return $event;
    }
}
