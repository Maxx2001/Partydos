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
        if (! $eventStoreData->is_datepicker) {
            $eventStoreData->end_date_time = DateAdjustmentService::adjustEndDate(
                $eventStoreData->start_date_time,
                $eventStoreData->end_date_time
            );
        }

        $eventArray = $eventStoreData->toArray();
        $dateOptions = $eventArray['date_options'] ?? null;
        unset($eventArray['date_options']);
        $event = Event::create($eventArray);
        $event->guestUser()->associate($guestUser);

        if ($eventStoreData->location) {
            $address = (new CreateAddressAction())->execute($eventStoreData->location);
            $event->address()->save($address);
        }

        $event->save();

        if ($eventStoreData->is_datepicker && $dateOptions) {
            foreach ($dateOptions as $option) {
                $event->dateOptions()->create($option);
            }
        }

        AttachMediaToModelAction::execute([$eventStoreData->image], $event, '-banner');

        Notification::create('Event created!')->send();

        return $event;
    }
}
