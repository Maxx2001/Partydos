<?php

namespace Domain\Auth\Actions;

use Domain\Auth\DataTransferObjects\LoginData;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function execute(LoginData $loginData)
    {
        if (Auth::attempt($loginData->only('email', 'password')->toArray(), $loginData->remember)) {
            return redirect('/');
        }

        return false;
    }
}
