<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\Models\Event;
use Illuminate\Support\Facades\Session;
use Support\Actions\AttachMediaToModelAction;
use Support\Notification;

class AuthenticatedEventUpdateAction
{
    public function execute(Event $event, AuthenticatedEventUpdateData $authenticatedEventStoreData)
    {
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
