<?php

namespace App\Api\Events\Controllers;

use Domain\Events\Actions\AuthenticatedEventCreateAction;
use Domain\Events\Actions\AuthenticatedEventUpdateAction;
use Domain\Events\Actions\ViewEventsAction;
use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\DataTransferObjects\EventEntity;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class EventsController
{
    public function index(ViewEventsAction $viewEventsAction): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $events = $viewEventsAction->execute();
        $ownedEvents = $user->ownedEvents()->get();

        return response()->json([
            'events' => EventEntity::collect($events),
            'ownedEvents' =>  EventEntity::collect($ownedEvents),
        ]);
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json(EventEntity::from($event));
    }

    public function store(AuthenticatedEventCreateAction $authenticatedEventCreateAction, AuthenticatedEventData $authenticatedEventStoreData): JsonResponse
    {
        $event = $authenticatedEventCreateAction->execute($authenticatedEventStoreData);

        return response()->json($event);
    }

    public function update(Event $event, AuthenticatedEventUpdateData $authenticatedEventUpdateData, AuthenticatedEventUpdateAction $authenticatedEventUpdateAction): JsonResponse
    {
        $event = $authenticatedEventUpdateAction->execute($event, $authenticatedEventUpdateData);

        return response()->json($event);
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();

        return response()->json(null, 204);
    }
}
