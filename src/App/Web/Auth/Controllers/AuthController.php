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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Contracts\DeletesUsers;
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

    public function forgotPassword(
        UserResetPasswordEmailData $resetPasswordEmailData,
        ResetPasswordUserAction $resetPasswordUserAction
    ): RedirectResponse
    {
        $resetPasswordUserAction->execute($resetPasswordEmailData);

        return redirect()->back()->with('status', 'Password reset link sent to your email address.');
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

    public function deleteUser(Request $request, DeletesUsers $deleter): RedirectResponse
    {
        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => [__('The provided password does not match your current password.')]
            ]);
        }

        $deleter->delete($user);

        Auth::logout();

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
