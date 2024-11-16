<?php

use App\Web\Contact\ContactController;
use App\Web\Features\Controllers\FeaturesController;
use App\Web\Roadmap\Controller\RoadmapController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('LandingsPage/Index');
})->name('home');

Route::get('features', [FeaturesController::class, 'index'])->name('features');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::get('roadmap', [RoadmapController::class, 'index'])->name('roadmap');
