<?php

namespace App\Web\Profile\Controllers;

use Domain\Profile\Models\Friendship;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Support\Controllers\Controller;
use Support\Notification;

class FriendshipController extends Controller
{
    public function request(User $user): RedirectResponse
    {
        /** @var User $requester */
        $requester = Auth::user();

        if ($requester->id === $user->id) {
            return redirect()->back();
        }

        $existing = Friendship::query()->between($requester, $user)->first();

        if ($existing) {
            return redirect()->back();
        }

        Friendship::create([
            'requester_user_id' => $requester->id,
            'addressee_user_id' => $user->id,
            'status' => 'pending',
        ]);

        Notification::create('Friend request sent')->send();

        return redirect()->back();
    }

    public function accept(Friendship $friendship): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($friendship->addressee_user_id !== $user->id) {
            abort(403);
        }

        $friendship->update(['status' => 'accepted']);

        Notification::create('Friend request accepted')->send();

        return redirect()->back();
    }

    public function block(Friendship $friendship): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!in_array($user->id, [$friendship->requester_user_id, $friendship->addressee_user_id], true)) {
            abort(403);
        }

        $friendship->update(['status' => 'blocked']);

        Notification::create('User blocked')->send();

        return redirect()->back();
    }

    public function cancel(Friendship $friendship): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($friendship->requester_user_id !== $user->id || $friendship->status !== 'pending') {
            abort(403);
        }

        $friendship->delete();

        Notification::create('Friend request canceled')->send();

        return redirect()->back();
    }
}
