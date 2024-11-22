<?php

namespace App\Web\Events\Controllers;

use Domain\Events\Actions\ViewEventsAction;
use Domain\Events\DataTransferObjects\EventEntityData;
use Domain\Users\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedEventController
{
    public function index(ViewEventsAction $viewEventsAction): Response
    {
        /* @var User $user */
        $user = auth()->user();

        $events = $viewEventsAction->execute();
        $ownedEvents = $user->ownedEvents()->get();
        
        return Inertia::render('Dashboard/Index', [
            'events' => EventEntityData::collect($events),
            'ownedEvents' =>  EventEntityData::collect($ownedEvents),
        ]);
    }
}
