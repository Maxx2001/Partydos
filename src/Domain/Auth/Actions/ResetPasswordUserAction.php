<?php

namespace Domain\Auth\Actions;

use Domain\Auth\DataTransferObjects\UserResetPasswordEmailData;

use Domain\Auth\Mail\UserPasswordReset;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ResetPasswordUserAction
{
    public function execute(UserResetPasswordEmailData $resetPasswordEmailData): void
    {
        $user = User::where('email', $resetPasswordEmailData->email)->first();
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        $token = Password::createToken($user);

        $route = route('password.reset-page', ['email' => $resetPasswordEmailData->email, 'token' => $token]);

        Mail::to($user->email)->send(new UserPasswordReset($user, $route, config('auth.passwords.users.expire')));
    }
}
