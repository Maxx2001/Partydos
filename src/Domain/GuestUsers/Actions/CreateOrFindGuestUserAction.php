<?php

namespace Domain\GuestUsers\Actions;

use Domain\GuestUsers\Models\GuestUser;

class CreateOrFindGuestUserAction
{

    public static function handle(string $name, string $email)
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
