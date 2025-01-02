<?php

namespace Domain\Auth\Actions;

use Domain\Users\Models\User;
use Domain\Users\Models\UserNotSellData;
use Support\Notification;

class RegisterNotSellDataUserAction
{
    public function execute(User $user): void
    {
        if ($user->userNotSellData()->exists()) {
            Notification::create('You are already added to the do not sell my data list.')->send();
            return;
        }

        Notification::create('You are successfully added to the do not sell my data list.')->send();
        UserNotSellData::create([
            'user_id' => $user->getKey(),
        ])->save();
    }
}
