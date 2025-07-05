<?php

namespace App\Web\Pages\Controllers;

use Domain\Events\DataTransferObjects\EventEntity;
use Inertia\Response;
use Inertia\ResponseFactory;
use Support\Controllers\Controller;

class PagesController extends Controller
{

    public function index(): Response|ResponseFactory
    {
        $user = auth()->user();
        $upcomingEvents = !!$user ? EventEntity::collect($user->upcomingEvents()) : [];

        return inertia('LandingsPage/Index',
        [
            'showUpcomingEvents' => !!$user,
            'events' => $upcomingEvents,
        ]);
    }

    public function features(): Response|ResponseFactory
    {
        return inertia('Features/Index');
    }

    public function contact(): Response|ResponseFactory
    {
        return inertia('Contact/Index');
    }

    public function privacyPolicy(): Response|ResponseFactory
    {
        return inertia('PrivacyPolicy/Index');
    }
}
