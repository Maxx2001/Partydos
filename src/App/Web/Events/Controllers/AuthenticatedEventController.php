<?php

namespace App\Web\Events\Controllers;

use Domain\Events\Actions\AuthenticatedEventCreateAction;
use Domain\Events\Actions\AuthenticatedEventUpdateAction;
use Domain\Events\Actions\ViewEventsAction;
use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\DataTransferObjects\EventEntityData;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedEventController
{
    public function index(ViewEventsAction $viewEventsAction): Response
    {
        /* @var User $user */
        $user = auth()->user();

        $events = $viewEventsAction->execute();
        $ownedEvents = $user->ownedEvents()->futureEvents()->orderBy('start_date_time')->get();

        return Inertia::render('Dashboard/Index', [
            'events' => EventEntityData::collect($events),
            'ownedEvents' =>  EventEntityData::collect($ownedEvents),
        ]);
    }

    public function store(AuthenticatedEventCreateAction $authenticatedEventCreateAction, AuthenticatedEventData $authenticatedEventStoreData): RedirectResponse
    {
        $event = $authenticatedEventCreateAction->execute($authenticatedEventStoreData);

        return redirect()->route('events.show-invite', $event);
    }

    public function edit(Event $event): Response
    {
        if ($event->user_id !== Auth::user()->getKey()) {
            abort(403);
        }
//        dd(EventEntityData::from($event));
        return Inertia::render('Events/EventEdit/EventEdit', [
            'event' => EventEntityData::from($event),
        ]);
    }

    public function update(
        Event $event,
        AuthenticatedEventUpdateAction $authenticatedEventUpdateAction,
        AuthenticatedEventData $authenticatedEventData
    ): RedirectResponse
    {
        if ($event->user_id !== Auth::user()->getKey()) {
            abort(403);
        }

        $event = $authenticatedEventUpdateAction->execute($event, $authenticatedEventData);

        return redirect()->route('events.show-invite', $event);
    }
}
