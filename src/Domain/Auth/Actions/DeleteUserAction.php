<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Mail\UserDeleteRequest;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DeleteUserAction
{
    public function execute(User $user): void
    {
        $user->recovery_token = Str::random(64);
        $user->save();

        $user->delete();
        $user->tokens()->delete();

        Mail::to($user->email)->queue(new UserDeleteRequest($user));
    }
}
