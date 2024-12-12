<?php

namespace App\Web\Auth\Controllers;

use Domain\Auth\Actions\LoginAction;
use Domain\Auth\DataTransferObjects\LoginData;
use Illuminate\Http\RedirectResponse;

class LoginController
{
    public function authenticate(LoginData $loginData, LoginAction $loginAction): RedirectResponse
    {
        if ($loginAction->execute($loginData)) {
            return redirect('/');
        } else {
            return redirect()
                ->back()
                ->with('status', __('auth.failed'));
        }
    }
}
