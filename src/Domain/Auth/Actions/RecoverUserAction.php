<?php

namespace Domain\Auth\Actions;

use Domain\Users\Models\User;
use Support\Notification;

class RecoverUserAction
{
    public function execute(string $token): void
    {
        if (
            $user = User::where('recovery_token', $token)->withTrashed()->first()
        ) {
            $user->restore();
            $user->recovery_token = null;
            $user->save();

            Notification::create('Your account is successfully restored')->send();
            return;
        }

        Notification::create('Account is not found')->send();
    }
}
