<?php

namespace App\Http\Events\Controllers;

use Domain\Events\Actions\EventCreateAction;
use Domain\Events\Actions\EventGenerateIcsAction;
use Domain\Events\Actions\ViewEventsAction;
use Domain\Events\DataTransferObjects\EventEntityData;
use Domain\Events\DataTransferObjects\EventRegisterGuestData;
use Domain\Events\DataTransferObjects\EventStoreData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Actions\CreateOrFindGuestUserAction;
use Domain\Users\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;

class EventController extends Controller
{
    public function index(ViewEventsAction $viewEventsAction): Response
    {
        /* @var User $user */

        $events = $viewEventsAction->execute();
        $ownedEvents = $user->ownedEvents()->get();

        return Inertia::render('Dashboard/Index', [
            'events' => EventEntityData::collect($events),
            'ownedEvents' =>  EventEntityData::collect($ownedEvents),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Events/EventCreate/EventCreate');
    }

    public function store(
        EventStoreData $eventStoreData,
        CreateOrFindGuestUserAction $createOrFindGuestUserAction,
        EventCreateAction $eventCreateAction
    ): RedirectResponse
    {
        $eventRegisterGuestData = EventRegisterGuestData::from($eventStoreData);
        $guestUser = $createOrFindGuestUserAction->execute($eventRegisterGuestData);

        $event = $eventCreateAction->execute($eventStoreData, $guestUser);

        return redirect()->route('events.show-invite', $event);
    }

    public function show(Event $event): Response
    {
        return Inertia::render('Events/EventShow', [
            'event' => EventEntityData::from($event),
        ]);
    }

    public function showInvite(Event $event): Response
    {
        return Inertia::render('Events/EventInvite/EventInvite', [
            'event' => EventEntityData::from($event->load('guestUsers')),
            'showInviteModal' => Session::get('event_created'),
        ])->withViewData([
            'title' => $event->title,
            'description' => $event->description,
            'ogTitle' => $event->title,
            'ogDescription' => $event->description,
            'ogUrl' => url()->current(),
        ]);
    }

    public function registerGuestUser(
        Event $event,
        CreateOrFindGuestUserAction $createOrFindGuestUserAction,
        EventRegisterGuestData $eventRegisterGuestData
    ): RedirectResponse
    {
        $guestUser = $createOrFindGuestUserAction->execute($eventRegisterGuestData);

        $event->guestUsers()->attach($guestUser);

        return redirect()->back();
    }

    public function downloadEventICS(Event $event, EventGenerateIcsAction $eventGenerateIcsAction): Application|\Illuminate\Http\Response|ResponseFactory
    {
        $ics = $eventGenerateIcsAction->execute($event);

        return response($ics)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="event.ics"');
    }
}
