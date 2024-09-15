<?php

namespace App\Http\Roadmap;

use Inertia\Response;
use Support\Controllers\Controller;

class RoadmapController extends Controller
{
    public function index(): Response
    {
        return inertia('Roadmap/Index');
    }
}
