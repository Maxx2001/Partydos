<?php

namespace App\Http\Events\Controllers;

use App\Http\Events\Requests\EventRegisterGuestRequest;
use App\Http\Events\Requests\EventStoreRequest;
use Domain\Events\Actions\EventCreateAction;
use Domain\Events\Actions\EventGenerateIcsAction;
use Domain\Events\DataTransferObjects\EventDTO;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Actions\CreateOrFindGuestUserAction;
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

        $events = $user->events()->get();
        $ownedEvents = $user->ownedEvents()->get();

        return Inertia::render('Dashboard/Index', [
            'events' => EventDTO::fromCollection($events),
            'ownedEvents' =>  EventDTO::fromCollection($ownedEvents),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Events/EventCreate');
    }

    public function store(EventStoreRequest $eventStoreRequest): RedirectResponse
    {
        $guestUser = CreateOrFindGuestUserAction::execute(
            $eventStoreRequest->name,
            $eventStoreRequest->email
        );

        $event = EventCreateAction::execute(
            $guestUser,
            $eventStoreRequest->title,
            $eventStoreRequest->description,
            $eventStoreRequest->location,
            $eventStoreRequest->startDateTime,
            $eventStoreRequest->endDateTime
        );

        return redirect()->route('events.show-invite', $event);
    }

    public function show(Event $event): Response
    {
        return Inertia::render('Events/EventShow', [
            'event' => EventDTO::fromModel($event),
        ]);
    }

    public function showInvite(Event $event): Response
    {
        return Inertia::render('Events/EventInvite', [
            'event' => EventDTO::fromModel($event),
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
        $guestUser = CreateOrFindGuestUserAction::execute(
            $eventRegisterGuestRequest->name,
            $eventRegisterGuestRequest->email
        );

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
