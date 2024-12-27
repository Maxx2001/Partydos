<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Traits\PasswordValidationRules;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Support\Notification;

class ResetPasswordAction
{
    use PasswordValidationRules;

    public function reset(User $user, array $input): void
    {
        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        Notification::create("Password reset!")->send();
    }
}
