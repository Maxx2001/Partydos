<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\Models\Event;
use Support\Actions\AttachMediaToModelAction;
use Support\Notification;
use Support\Services\DateAdjustmentService;

class AuthenticatedEventUpdateAction
{
    public function execute(Event $event, AuthenticatedEventUpdateData $authenticatedEventStoreData): Event
    {
        $authenticatedEventStoreData->end_date_time = DateAdjustmentService::adjustEndDate(
            $authenticatedEventStoreData->start_date_time,
            $authenticatedEventStoreData->end_date_time
        );

        $event->update($authenticatedEventStoreData->all());

        if ($authenticatedEventStoreData->remove_image) {
            $event->clearMediaCollection('event-banner');
        }

        if ($authenticatedEventStoreData->image) {
            $event->clearMediaCollection('event-banner');
            (new AttachMediaToModelAction())->execute([$authenticatedEventStoreData->image], $event, '-banner');
        }

        $event->save();
        Notification::create('Event updated!')->send();

        return $event;
    }
}
