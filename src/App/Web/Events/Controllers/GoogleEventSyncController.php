<?php

namespace App\Web\Events\Controllers;

use App\Services\Google\GoogleCalendarClient;
use Domain\Events\Actions\CheckUserIsEventOwnerAction;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Support\Controllers\Controller;

class GoogleEventSyncController extends Controller
{
    public function sync(
        Event $event,
        CheckUserIsEventOwnerAction $checkOwnerAction,
        GoogleCalendarClient $googleCalendarClient
    ): RedirectResponse {
        /** @var User $user */
        $user = Auth::user();
        $checkOwnerAction->execute($event, $user);

        if ($user->googleAccount === null) {
            return redirect()->back()->with('error', 'Connect Google before exporting.');
        }

        if (! $event->start_date_time) {
            return redirect()->back()->with('error', 'Event needs a start time before exporting.');
        }

        $googleCalendarClient->upsertEvent($user, $event);

        return redirect()->back()->with('success', 'Exported to Google Calendar.');
    }

    public function toggle(
        Event $event,
        CheckUserIsEventOwnerAction $checkOwnerAction,
        GoogleCalendarClient $googleCalendarClient
    ): RedirectResponse {
        /** @var User $user */
        $user = Auth::user();
        $checkOwnerAction->execute($event, $user);

        if ($user->googleAccount === null) {
            return redirect()->back()->with('error', 'Connect Google before enabling sync.');
        }

        if (! $event->start_date_time) {
            return redirect()->back()->with('error', 'Event needs a start time before enabling sync.');
        }

        $event->loadMissing('googleIntegration');

        if ($event->googleIntegration) {
            $event->googleIntegration->sync_enabled = ! $event->googleIntegration->sync_enabled;
            $event->googleIntegration->save();

            if ($event->googleIntegration->sync_enabled && ! $event->googleIntegration->external_event_id) {
                $googleCalendarClient->upsertEvent($user, $event);
            }
        } else {
            $integration = $googleCalendarClient->upsertEvent($user, $event);
            $integration->sync_enabled = true;
            $integration->save();
        }

        return redirect()->back()->with('success', 'Google sync preference updated.');
    }
}
