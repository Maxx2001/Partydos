<?php

namespace App\Web\Pages\Controllers;

use Inertia\Response;
use Support\Controllers\Controller;

class PagesController extends Controller
{

    public function index(): Response
    {
        return inertia('LandingsPage/Index');
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
