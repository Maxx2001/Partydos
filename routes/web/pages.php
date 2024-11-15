<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Contact\ContactController;
use App\Http\Features\FeaturesController;
use App\Http\Roadmap\RoadmapController;

Route::get('/', function () {
    return Inertia::render('LandingsPage/Index');
})->name('home');



Route::get('features', [FeaturesController::class, 'index'])->name('features');
Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::get('roadmap', [RoadmapController::class, 'index'])->name('roadmap');
