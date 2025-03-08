<?php

namespace Domain\Auth\Actions;

use Domain\Auth\DataTransferObjects\LoginData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginAction
{
    public function execute(LoginData $loginData): bool
    {
        if (!Auth::attempt($loginData->only('email', 'password')->toArray(), $loginData->remember)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.')
            ]);
            
        }

        return true;
    }
}
