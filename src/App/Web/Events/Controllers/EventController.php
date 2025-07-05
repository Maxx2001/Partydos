<?php

namespace App\Web\Events\Controllers;

use Auth;
use Domain\Auth\Actions\LoginAction;
use Domain\Auth\Actions\RegisterUserAction;
use Domain\Auth\DataTransferObjects\LoginData;
use Domain\Events\Actions\AcceptEventInviteAction;
use Domain\Events\Actions\AuthenticatedEventCreateAction;
use Domain\Events\Actions\AuthenticatedEventUpdateAction;
use Domain\Events\Actions\CancelEventAction;
use Domain\Events\Actions\CheckUserIsEventOwnerAction;
use Domain\Events\Actions\DestroyEventAction;
use Domain\Events\Actions\EventGenerateIcsAction;
use Domain\Events\Actions\GetEventListsForUserAction;
use Domain\Events\Actions\GuestEventCreateAction;
use Domain\Events\Actions\RestoreEventAction;
use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\DataTransferObjects\EventEntity;
use Domain\Events\DataTransferObjects\EventInviteViewData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Actions\CreateOrFindGuestUserAction;
use Domain\Users\DataTransferObjects\RegisterUserData;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;
use Support\Notification;
use \Illuminate\Http\Response as HttpResponse;
use Domain\Events\DataTransferObjects\GuestEventCreateData;
use Domain\GuestUsers\DataTransferObjects\GuestJoinData;

class EventController extends Controller
{
    public function index(GetEventListsForUserAction $getEventListsForUserAction): Response
    {
        /** @var \Domain\Users\Models\User $user */
        $user = auth()->user();

        $eventLists = $getEventListsForUserAction->execute($user);

        return Inertia::render('Events/Index', [
            'events' => EventEntity::collect($eventLists->get('invitedEvents') ?? collect()),
            'ownedEvents' =>  EventEntity::collect($eventLists->get('ownedEvents') ?? collect()),
            'historyEvents' =>  EventEntity::collect($eventLists->get('historyEvents') ?? collect()),
        ]);
    }

    public function show(Event $event): Response
    {
        $viewData = EventInviteViewData::fromEvent($event);

        return Inertia::render('Events/Invite', [
            'event' => EventEntity::from($event->load('address')),
            'showInviteModal' => Session::get('event_created'),
            'showInviteButton' => $viewData->showInviteButton,
            'showCancelButton' => $viewData->showCancelButton,
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
        GuestEventCreateData $guestEventCreateData,
        GuestEventCreateAction $guestEventCreateAction
    ): RedirectResponse {
        $event = $guestEventCreateAction->execute($guestEventCreateData);

        return redirect()->route('events.show-invite', $event);
    }

    public function edit(Event $event, CheckUserIsEventOwnerAction $checkOwnerAction): Response
    {
        /** @var \Domain\Users\Models\User $user */
        $user = Auth::user();
        $checkOwnerAction->execute($event, $user);

        return Inertia::render('Events/Edit', [
            'event' => EventEntity::from($event->load('address'))
        ]);
    }

    public function update(
        Event $event,
        AuthenticatedEventUpdateAction $authenticatedEventUpdateAction,
        AuthenticatedEventUpdateData $authenticatedEventUpdateData,
        CheckUserIsEventOwnerAction $checkOwnerAction
    ): RedirectResponse
    {
        /** @var \Domain\Users\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $checkOwnerAction->execute($event, $user);

        $event = $authenticatedEventUpdateAction->execute($event, $authenticatedEventUpdateData);

        return redirect()->route('events.show-invite', $event);
    }

    public function destroy(Event $event, DestroyEventAction $destroyEventAction, CheckUserIsEventOwnerAction $checkOwnerAction): RedirectResponse
    {
        /** @var \Domain\Users\Models\User $user */
        $user = Auth::user();
        $checkOwnerAction->execute($event, $user);
        $destroyEventAction->execute($event);
        return redirect()->route('users-events.index');
    }

    public function authenticateStore(
        AuthenticatedEventCreateAction $authenticatedEventCreateAction,
        AuthenticatedEventData $authenticatedEventStoreData
    ): RedirectResponse
    {
        $event = $authenticatedEventCreateAction->execute($authenticatedEventStoreData);

        return redirect()->route('events.show-invite', $event);
    }

    public function registerGuestUser(
        Event $event,
        CreateOrFindGuestUserAction $createOrFindGuestUserAction,
        GuestJoinData $guestJoinData
    ): RedirectResponse
    {
        $guestUser = $createOrFindGuestUserAction->execute($guestJoinData->email, $guestJoinData->name);

        $event->guestUsers()->attach($guestUser);

        Notification::create('You have been registered to the event!')->send();

        return redirect()->back();
    }

    public function acceptInvite(Event $event): void
    {
        /** @var \Domain\Users\Models\User $user */
        $user = Auth::user();
        $event->users()->attach($user);

        Notification::create('You have been registered to the event!')->send();
    }

    public function cancelInvite(Event $event): void
    {
        /** @var \Domain\Users\Models\User $user */
        $user = Auth::user();

        $event->users()->detach($user);
        Notification::create('You have been unregistered from the event.')->send();
    }

    public function downloadEventICS(
        Event $event,
        EventGenerateIcsAction $eventGenerateIcsAction
    ): Application|HttpResponse|ResponseFactory
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

    public function authenticateAndAcceptInvite(
        Event $event,
        LoginData $loginData,
        LoginAction $loginAction,
        AcceptEventInviteAction $acceptInvite
    ): RedirectResponse
    {
        if ($loginAction->execute($loginData)) {
            /** @var \Domain\Users\Models\User $user */
            $user = Auth::user();
            $acceptInvite->execute($event, $user);
            return redirect()->back();
        } else {
            return redirect()
                ->back()
                ->with('status', __('auth.failed'));
        }
    }

    public function registerAndAcceptInvite(
        Event $event,
        RegisterUserData $registerUserData,
        RegisterUserAction $registerUserAction,
        AcceptEventInviteAction $acceptInvite): RedirectResponse
    {
        $registerUserAction->execute($registerUserData);
        
        /** @var \Domain\Users\Models\User $user */
        $user = Auth::user();
        $acceptInvite->execute($event, $user);
        
        return redirect()->back();
    }
}
