<?php

namespace App\Http\Contact;

use Inertia\Response;
use Support\Controllers\Controller;

class ContactController extends Controller
{
    public function index(): Response
    {
        return inertia('Contact/Index');
    }
}
