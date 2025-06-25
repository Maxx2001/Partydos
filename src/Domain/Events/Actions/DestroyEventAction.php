<?php

namespace Domain\Events\Actions;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Support\Notification;

class DestroyEventAction
{
    public function __construct(
        protected CheckUserIsEventOwnerAction $checkUserIsEventOwnerAction,
    ) {
    }

    public function execute(Event $event): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->checkUserIsEventOwnerAction->execute($event, $user);

        if (!$event->canceled_at) {
            Notification::create('Event must first be canceled!')->send();
            return;
        }

        $event->delete();
        Notification::create('Event has been deleted!')->send();
    }
}
