<?php

namespace Domain\GuestUsers\Actions;

use Domain\GuestUsers\Models\GuestUser;

class CreateOrFindGuestUserAction
{

    public static function execute(string $name, string $email): GuestUser
    {
        return GuestUser::firstOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $name,
            ]
        );
    }
}
