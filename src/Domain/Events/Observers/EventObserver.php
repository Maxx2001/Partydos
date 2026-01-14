<?php

namespace Domain\Events\Observers;

use App\Jobs\SyncGoogleEventJob;
use Domain\Events\Models\Event;
use Illuminate\Support\Str;

class EventObserver
{
    public function creating(Event $event): void
    {
        $event->unique_identifier = Str::random(20);
    }

    public function updated(Event $event): void
    {
        $event->loadMissing('googleIntegration', 'user.googleAccount');

        if ($event->googleIntegration === null || ! $event->googleIntegration->sync_enabled) {
            return;
        }

        if ($event->user === null || $event->user->googleAccount === null) {
            return;
        }

        if (! $event->start_date_time) {
            return;
        }

        SyncGoogleEventJob::dispatch($event->id);
    }
}
