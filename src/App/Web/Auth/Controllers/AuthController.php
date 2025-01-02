<?php

namespace App\Web\Auth\Controllers;

use Domain\Auth\Actions\DestroyUserAction;
use Domain\Auth\Actions\LoginAction;
use Domain\Auth\Actions\ResetPasswordAction;
use Domain\Auth\Actions\ResetPasswordUserAction;
use Domain\Auth\DataTransferObjects\LoginData;
use Domain\Auth\DataTransferObjects\ResetPasswordUserData;
use Domain\Auth\DataTransferObjects\UpdatePasswordUserData;
use Domain\Auth\DataTransferObjects\UserResetPasswordEmailData;
use Domain\Auth\Mail\UserDeleteRequest;
use Domain\Auth\Mail\UserPasswordReset;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        return redirect('login');
    }

    public function resetPasswordEmail(UserResetPasswordEmailData $resetPasswordEmailData, ResetPasswordUserAction $resetPasswordUserAction): void
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

    public function resetPassword(User $user, ResetPasswordAction $resetPasswordAction, UpdatePasswordUserData $updatePasswordUserData): RedirectResponse
    {
        $resetPasswordAction->reset($user, $updatePasswordUserData->toArray());

        return redirect()->route('login');
    }

    public function deleteUser(): RedirectResponse
    {
        $user = Auth::user();
        $user->recovery_token = Str::random(64);
        $user->save();

        $user->delete();
        $user->tokens()->delete();

        Mail::to($user->email)->queue(new UserDeleteRequest($user));

        return redirect()->route('home');
    }

    public function recovery(string $token): RedirectResponse
    {
        if (
            $user = User::where('recovery_token', $token)->withTrashed()->first()
        ) {
            $user->restore();
            $user->recovery_token = null;
            $user->save();

            Notification::create('Your account is successfully restored')->send();
            return redirect()->route('home');
        }

        Notification::create('Account is not found')->send();

        return redirect()->route('home');
    }
}
