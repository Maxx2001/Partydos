<?php

namespace App\Web\Events\Controllers;

use Auth;
use Domain\Events\Actions\AuthenticatedEventCreateAction;
use Domain\Events\Actions\AuthenticatedEventUpdateAction;
use Domain\Events\Actions\CancelEventAction;
use Domain\Events\Actions\DestroyEventAction;
use Domain\Events\Actions\EventGenerateIcsAction;
use Domain\Events\Actions\GuestEventCreateAction;
use Domain\Events\Actions\RestoreEventAction;
use Domain\Events\Actions\ViewEventsAction;
use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\DataTransferObjects\EventEntity;
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
use Support\Notification;

class EventController extends Controller
{
    public function index(ViewEventsAction $viewEventsAction): Response
    {
        /* @var User $user */
        $user = auth()->user();

        $invitedEvents = $user->events()->with('address')->futureEvents()->get();
        $ownedEvents = $user->ownedEvents()->with('address')->futureEvents()->orderBy('start_date_time')->get();
        $historyEvents = $user->getHistoryEvents();

        return Inertia::render('Events/Index', [
            'events' => EventEntity::collect($invitedEvents),
            'ownedEvents' =>  EventEntity::collect($ownedEvents),
            'historyEvents' =>  EventEntity::collect($historyEvents),
        ]);
    }

    public function show(Event $event): Response
    {
        $showInviteButton = true;
        $showCancelButton = false;

        if ($user = Auth::user()) {
            $eventUsers = $event->users()->get();
            $showInviteButton = !$eventUsers->contains($user) && $event->user_id !== $user->id;
            $showCancelButton = $eventUsers->contains($user) && $event->user_id !== $user->id;
        }

        return Inertia::render('Events/Invite', [
            'event' => EventEntity::from($event->load('address')),
            'showInviteModal' => Session::get('event_created'),
            'showInviteButton' => $showInviteButton,
            'showCancelButton' => $showCancelButton,
        ])->withViewData([
            'title' => $event->title,
            'description' => $event->description,
            'ogTitle' => $event->title,
            'ogDescription' => $event->description,
            'ogUrl' => url()->current(),
        ]);
    }

     public function create(): Response
    {
        return Inertia::render('Events/Create');
    }

    public function store(
        EventStoreData $eventStoreData,
        CreateOrFindGuestUserAction $createOrFindGuestUserAction,
        GuestEventCreateAction $guestEventCreateAction
    ): RedirectResponse
    {
        $eventRegisterGuestData = EventRegisterGuestData::from($eventStoreData);
        $guestUser = $createOrFindGuestUserAction->execute($eventRegisterGuestData);

        $event = $guestEventCreateAction->execute($eventStoreData, $guestUser);

        return redirect()->route('events.show-invite', $event);
    }

    public function edit(Event $event): Response
    {
        if ($event->user_id !== Auth::user()->getKey()) {
            abort(403);
        }

        return Inertia::render('Events/Edit', [
            'event' => EventEntity::from($event->load('address'))
        ]);
    }

    public function update(
        Event $event,
        AuthenticatedEventUpdateAction $authenticatedEventUpdateAction,
        AuthenticatedEventUpdateData $authenticatedEventUpdateData
    ): RedirectResponse
    {
        if ($event->user_id !== \Illuminate\Support\Facades\Auth::user()->getKey()) {
            abort(403);
        }

        $event = $authenticatedEventUpdateAction->execute($event, $authenticatedEventUpdateData);

        return redirect()->route('events.show-invite', $event);
    }

    public function destroy(Event $event, DestroyEventAction $destroyEventAction): RedirectResponse
    {
        $destroyEventAction->execute($event);
        return redirect()->route('users-events.index');
    }

    public function authenticateStore(AuthenticatedEventCreateAction $authenticatedEventCreateAction, AuthenticatedEventData $authenticatedEventStoreData): RedirectResponse
    {
        $event = $authenticatedEventCreateAction->execute($authenticatedEventStoreData);

        return redirect()->route('events.show-invite', $event);
    }

    public function registerGuestUser(
        Event $event,
        CreateOrFindGuestUserAction $createOrFindGuestUserAction,
        EventRegisterGuestData $eventRegisterGuestData
    ): RedirectResponse
    {
        $guestUser = $createOrFindGuestUserAction->execute($eventRegisterGuestData);

        $event->guestUsers()->attach($guestUser);

        Notification::create('You have been registered to the event!')->send();

        return redirect()->back();
    }

    public function acceptInvite(Event $event): void
    {
        $user = Auth::user();
        $event->users()->attach($user);

        Notification::create('You have been registered to the event!')->send();
    }

    public function cancelInvite(Event $event): void
    {
        $user = Auth::user();

        $event->users()->detach($user);
        Notification::create('You have been unregistered from the event.')->send();
    }

    public function downloadEventICS(Event $event, EventGenerateIcsAction $eventGenerateIcsAction): Application|\Illuminate\Http\Response|ResponseFactory
    {
        $ics = $eventGenerateIcsAction->execute($event);

        return response($ics)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="event.ics"');
    }

    public function cancelEvent(CancelEventAction $cancelEventAction, Event $event): RedirectResponse
    {
        $cancelEventAction->execute($event);

        return redirect()->back();
    }

    public function restoreEvent(RestoreEventAction $restoreEventAction, Event $event): RedirectResponse
    {
        $restoreEventAction->execute($event);

        return redirect()->back();
    }
}
