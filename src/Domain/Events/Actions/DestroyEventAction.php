<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Illuminate\Support\Facades\Auth;
use Support\Notification;

class DestroyEventAction
{
    public function execute(Event $event): void
    {
        if ($event->user_id !== Auth::user()->getKey()) {
            abort(403);
        }

        if (!$event->canceled_at) {
            Notification::create('Event must first be canceled!')->send();
            return;
        }

        $event->delete();
        Notification::create('Event has been deleted!')->send();
    }
}
