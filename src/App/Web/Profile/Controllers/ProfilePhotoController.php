<?php

namespace App\Web\Profile\Controllers;

use App\Web\Profile\Requests\ProfilePhotoRequest;
use Domain\Profile\Models\ProfilePhoto;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Support\Controllers\Controller;
use Support\Notification;

class ProfilePhotoController extends Controller
{
    public function store(ProfilePhotoRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        Gate::authorize('upload', [ProfilePhoto::class, $user]);

        $path = $request->file('photo')->store("profile-photos/{$user->id}", 'public');

        ProfilePhoto::create([
            'user_id' => $user->id,
            'path' => $path,
            'caption' => $request->string('caption')->toString() ?: null,
        ]);

        Notification::create('Photo added to your gallery')->send();

        return redirect()->back();
    }

    public function destroy(ProfilePhoto $photo): RedirectResponse
    {
        Gate::authorize('delete', $photo);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        Notification::create('Photo removed')->send();

        return redirect()->back();
    }
}
