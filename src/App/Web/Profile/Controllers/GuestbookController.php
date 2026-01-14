<?php

namespace App\Web\Profile\Controllers;

use App\Web\Profile\Requests\GuestbookEntryRequest;
use Domain\Profile\Models\GuestbookEntry;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Support\Controllers\Controller;
use Support\Notification;

class GuestbookController extends Controller
{
    public function store(GuestbookEntryRequest $request, User $user): RedirectResponse
    {
        $author = Auth::user();

        Gate::authorize('create', [GuestbookEntry::class, $user]);

        $key = sprintf('guestbook:%d:%d', $user->id, $author->id);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return redirect()->back()->withErrors([
                'body' => 'Slow down! You can leave up to 5 krabbels per minute.',
            ]);
        }

        RateLimiter::hit($key, 60);

        GuestbookEntry::create([
            'profile_user_id' => $user->id,
            'author_user_id' => $author->id,
            'body' => $request->string('body')->toString(),
        ]);

        Notification::create('Krabbel posted!')->send();

        return redirect()->back();
    }

    public function destroy(GuestbookEntry $entry): RedirectResponse
    {
        Gate::authorize('delete', $entry);

        $entry->delete();

        Notification::create('Krabbel removed')->send();

        return redirect()->back();
    }
}
