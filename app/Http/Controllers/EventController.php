<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Event;
use App\Models\GuestUser;
use App\Actions\Event\EventCreate;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\EventResource;
use App\Http\Requests\EventStoreRequest;
use App\Actions\GuestUser\CreateOrFindGuestUser;
use App\Http\Requests\EventRegisterGuestRequest;

class EventController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Events/EventCreate');
    }

    public function store(EventStoreRequest $eventStoreRequest): RedirectResponse
    {
        /* @var $guestUser GuestUser */
        $guestUser = CreateOrFindGuestUser::handle($eventStoreRequest->name, $eventStoreRequest->email);

        $event = EventCreate::handle(
            $guestUser,
            $eventStoreRequest->title,
            $eventStoreRequest->description,
            $eventStoreRequest->location,
            $eventStoreRequest->startDateTime,
            $eventStoreRequest->endDateTime
        );

        return redirect()->route('events.show', $event);
    }

    public function show(Event $event): Response
    {
        return Inertia::render('Events/EventShow', [
            'event' => new EventResource($event),
        ]);
    }

    public function showInvite(Event $event): Response
    {
        return Inertia::render('EventInvite/EventInvite', [
            'event' => new EventResource($event->load('guestUsers')),
        ]);
    }

    public function registerGuestUser(Event $event, EventRegisterGuestRequest $eventRegisterGuestRequest): RedirectResponse
    {
        /* @var $guestUser GuestUser */
        $guestUser = CreateOrFindGuestUser::handle($eventRegisterGuestRequest->name, $eventRegisterGuestRequest->email);

        $event->guestUsers()->attach($guestUser);

        return redirect()->back();
    }
}
