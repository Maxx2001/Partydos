<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\Models\Event;
use Illuminate\Support\Facades\Session;
use Support\Actions\AttachMediaToModelAction;

class AuthenticatedEventCreateAction
{
    public function execute(AuthenticatedEventData $authenticatedEventStoreData)
    {
        $event = Event::create($authenticatedEventStoreData->all());
        $event->user()->associate(auth()->user());

        $event->save();

        AttachMediaToModelAction::execute($authenticatedEventStoreData->image, $event, '-banner');

        Session::flash('event_created');

        return $event;
    }
}
