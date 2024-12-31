<?php

namespace App\Web\Pages\Controllers;

use Domain\Events\DataTransferObjects\EventEntityData;
use Inertia\Response;
use Support\Controllers\Controller;

class PagesController extends Controller
{

    public function index(): Response
    {
        $user = auth()->user();
        $upcomingEvents = !!$user ? EventEntityData::collect($user->upcomingEvents()) : [];

        return inertia('LandingsPage/Index',
        [
            'showUpcomingEvents' => !!$user,
            'events' => $upcomingEvents,
        ]);
    }

    public function features(): Response
    {
        return inertia('Features/Index');
    }

    public function contact(): Response
    {
        return inertia('Contact/Index');
    }
}
