<?php

namespace App\Jobs;

use App\Services\Google\GoogleCalendarClient;
use Domain\Events\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncGoogleEventJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [60, 300, 900];

    public function __construct(public int $eventId)
    {
    }

    public function handle(GoogleCalendarClient $googleCalendarClient): void
    {
        $event = Event::with(['googleIntegration', 'user.googleAccount', 'address'])
            ->find($this->eventId);

        if ($event === null) {
            return;
        }

        if ($event->googleIntegration === null || ! $event->googleIntegration->sync_enabled) {
            return;
        }

        if ($event->user === null || $event->user->googleAccount === null) {
            return;
        }

        if (! $event->start_date_time) {
            return;
        }

        $googleCalendarClient->upsertEvent($event->user, $event);
    }
}
