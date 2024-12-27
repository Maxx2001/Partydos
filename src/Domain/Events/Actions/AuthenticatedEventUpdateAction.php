<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\Models\Event;
use Illuminate\Support\Facades\Session;

class AuthenticatedEventUpdateAction
{
    public function execute(Event $event, AuthenticatedEventUpdateData $authenticatedEventStoreData)
    {
        $event->update($authenticatedEventStoreData->all());

        $event->save();
        Session::flash('event_updated');

        return $event;
    }
}
