<?php

namespace Domain\GuestUsers\Actions;

use Domain\Events\DataTransferObjects\EventRegisterGuestData;
use Domain\GuestUsers\Models\GuestUser;

class CreateOrFindGuestUserAction
{

    public function execute(EventRegisterGuestData $eventRegisterGuestData): GuestUser
    {
        return GuestUser::firstOrCreate(
            [
                'email' => $eventRegisterGuestData->email,
            ],
            [
                'name' => $eventRegisterGuestData->name,
            ]
        );
    }
}
