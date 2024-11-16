<?php

namespace App\Web\Features\Controllers;

use Inertia\Response;
use Support\Controllers\Controller;

class FeaturesController extends Controller
{
    public function index(): Response
    {
        return inertia('Features/Index');
    }
}
