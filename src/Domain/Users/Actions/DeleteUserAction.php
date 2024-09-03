<?php

namespace Domain\Users\Actions;

use Domain\Users\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUserAction implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
