<?php

namespace Domain\GuestUsers\Actions;

use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\DataTransferObjects\GuestUserData;

class CreateOrFindGuestUserAction
{
    public function execute(string $email, string $name): GuestUser
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
