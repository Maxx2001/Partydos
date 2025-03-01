<?php

namespace Domain\Profile\Actions;

use Domain\Profile\DataTransferObjects\ProfileUpdateData;
use Domain\Users\Models\User;

class UpdateProfilePictureAction
{
    public function execute(User $user, ProfileUpdateData $profileUpdateData): void
    {
        if (!$profileUpdateData->profile_photo) {
            return;
        }

        $user->updateProfilePhoto($profileUpdateData->profile_photo);
    }
}
