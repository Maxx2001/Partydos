<?php

namespace Domain\Events\Actions;

use Domain\Events\DataTransferObjects\AuthenticatedEventStoreData;
use Domain\Events\Models\Event;
use Illuminate\Support\Facades\Session;

class AuthenticatedEventCreateAction
{
    public function execute(AuthenticatedEventStoreData $authenticatedEventStoreData)
    {
        $event = Event::create($authenticatedEventStoreData->all());
        $event->user()->associate(auth()->user());

        $event->save();
        Session::flash('event_created');

        return $event;
    }
}
