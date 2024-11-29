<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\AuthenticatedEventStoreData;
use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\Models\Event;
use Illuminate\Support\Facades\Session;

class AuthenticatedEventUpdateAction
{
    public function execute(Event $event, AuthenticatedEventUpdateData $authenticatedEventUpdateData): Event
    {
        $event->update($authenticatedEventUpdateData->all());
        Session::flash('event_updated');

        return $event;
    }
}
