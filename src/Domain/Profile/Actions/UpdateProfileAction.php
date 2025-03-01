<?php

namespace Domain\Profile\Actions;


use Domain\Profile\DataTransferObjects\ProfileUpdateData;
use Domain\Users\Models\User;
use Support\Notification;

class UpdateProfileAction
{
    public function execute(User $user, ProfileUpdateData $profileUpdateData): void
    {
        $user->update([
            'name' => $profileUpdateData->name,
            'email' => $profileUpdateData->email,
        ]);

        (new UpdateProfilePictureAction())->execute($user, $profileUpdateData);

        Notification::create('Profile updated')->send();

    }
}
