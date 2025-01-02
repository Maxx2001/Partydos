<?php

namespace App\Web\Auth\Controllers;

use Domain\Auth\Actions\DeleteUserAction;
use Domain\Auth\Actions\LoginAction;
use Domain\Auth\Actions\RecoverUserAction;
use Domain\Auth\Actions\RegisterNotSellDataUserAction;
use Domain\Auth\Actions\ResetPasswordAction;
use Domain\Auth\Actions\ResetPasswordUserAction;
use Domain\Auth\DataTransferObjects\LoginData;
use Domain\Auth\DataTransferObjects\ResetPasswordUserData;
use Domain\Auth\DataTransferObjects\UpdatePasswordUserData;
use Domain\Auth\DataTransferObjects\UserResetPasswordEmailData;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Support\Notification;

class AuthController
{
    public function authenticate(LoginData $loginData, LoginAction $loginAction): RedirectResponse
    {
        if ($loginAction->execute($loginData)) {
            return redirect()->route('users-events.index');
        } else {
            return redirect()
                ->back()
                ->with('status', __('auth.failed'));
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('home');
    }

    public function resetPasswordEmail(
        UserResetPasswordEmailData $resetPasswordEmailData,
        ResetPasswordUserAction $resetPasswordUserAction
    ): void
    {
        $resetPasswordUserAction->execute($resetPasswordEmailData);

        Notification::create('Password reset mail send')->send();
    }

    public function resetPasswordPage(ResetPasswordUserData $resetPasswordUserData): Response
    {
        $user = User::where('email', $resetPasswordUserData->email)->first();

        return Inertia::render('Auth/ResetPassword', [
            'token' => $resetPasswordUserData->token,
            'email' => $user->email,
            'userId' => $user->getKey(),
        ]);
    }

    public function resetPassword(
        User $user,
        ResetPasswordAction $resetPasswordAction,
        UpdatePasswordUserData $updatePasswordUserData
    ): RedirectResponse
    {
        $resetPasswordAction->reset($user, $updatePasswordUserData->toArray());

        return redirect()->route('login');
    }

    public function deleteUser(DeleteUserAction $deleteUserAction): RedirectResponse
    {
        $user = Auth::user();
        $deleteUserAction->execute($user);

        return redirect()->route('home');
    }

    public function recovery(string $token, RecoverUserAction $recoverUserAction): RedirectResponse
    {
        $recoverUserAction->execute($token);

        return redirect()->route('home');
    }

    public function registerNotSellDataUser(RegisterNotSellDataUserAction $registerNotSellDataUserAction): RedirectResponse
    {
        $user = Auth::user();
        $registerNotSellDataUserAction->execute($user);

        return redirect()->route('home');
    }
}
