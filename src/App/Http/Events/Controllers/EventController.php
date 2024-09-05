<?php

namespace App\Http\Events\Controllers;

use App\Http\Events\Requests\EventRegisterGuestRequest;
use App\Http\Events\Requests\EventStoreRequest;
use App\Http\Events\Resources\EventResource;
use Domain\Events\Actions\EventCreateAction;
use Domain\Events\Actions\EventGenerateIcsAction;
use Domain\Events\Data\EventData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Actions\CreateOrFindGuestUserAction;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Data\UserData;
use Domain\Users\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;

class EventController extends Controller
{
    public function index(): Response
    {
        /* @var User $user */
        $user = Auth::user();

        return Inertia::render('Dashboard/Index', [
            'events' => EventData::collect($user->events()->get()),
            'ownedEvents' => EventData::collect($user->ownedEvents()->get()),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Events/EventCreate');
    }

    public function store(EventStoreRequest $eventStoreRequest): RedirectResponse
    {
        /* @var $guestUser GuestUser */
        $guestUser = CreateOrFindGuestUserAction::handle($eventStoreRequest->name, $eventStoreRequest->email);

        $event = EventCreateAction::handle(
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
            'event' => new EventResource($event->load('guestUsers', 'guestUser')),
        ])->withViewData([
            'title' => $event->title,
            'description' => $event->description,
            'ogTitle' => $event->title,
            'ogDescription' => $event->description,
            'ogUrl' => url()->current(),
        ]);
    }


    public function registerGuestUser(Event $event, EventRegisterGuestRequest $eventRegisterGuestRequest): RedirectResponse
    {
        /* @var $guestUser GuestUser */
        $guestUser = CreateOrFindGuestUserAction::handle($eventRegisterGuestRequest->name, $eventRegisterGuestRequest->email);

        $event->guestUsers()->attach($guestUser);

        return redirect()->back();
    }

    public function downloadEventICS(Event $event): Application|\Illuminate\Http\Response|ResponseFactory
    {
        $ics = EventGenerateIcsAction::handle($event);

        return response($ics)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="event.ics"');
    }
}
