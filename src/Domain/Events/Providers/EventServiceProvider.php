<?php

namespace Domain\Events\Providers;

use Domain\Events\Models\Event;
use Domain\Events\Observers\EventObserver;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::observe(EventObserver::class);
    }
}
