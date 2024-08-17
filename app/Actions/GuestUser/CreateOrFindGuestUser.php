<?php

namespace App\Actions\GuestUser;

use App\Models\GuestUser;

class CreateOrFindGuestUser
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
