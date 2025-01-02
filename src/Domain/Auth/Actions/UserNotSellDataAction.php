<?php

namespace Domain\Auth\Actions;

use Domain\Users\Models\User;
use Domain\Users\Models\UserNotSellData;

class UserNotSellDataAction
{
    public function execute(User $user): void
    {
        UserNotSellData::create([
            'user_id' => $user->getKey(),
        ])->save();
    }
}
