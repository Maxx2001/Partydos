<?php

namespace App\Web\Profile\Controllers;

use App\Web\Profile\Requests\UpdateProfileRequest;
use Domain\Profile\Models\Profile;
use Domain\Profile\Models\ProfilePhoto;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;
use Support\Notification;

class MyProfileController extends Controller
{
    public function edit(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $profile = $user->profile()->firstOrCreate([], [
            'display_name' => $user->name,
            'privacy_profile' => 'public',
            'privacy_guestbook' => 'public',
            'privacy_photos' => 'friends',
        ]);

        Gate::authorize('update', $profile);

        $photos = $user->profilePhotos()->latest()->get()->map(fn (ProfilePhoto $photo) => [
            'id' => $photo->id,
            'url' => Storage::disk('public')->url($photo->path),
            'caption' => $photo->caption,
        ]);

        return Inertia::render('Profile/Edit', [
            'profile' => [
                'id' => $profile->id,
                'display_name' => $profile->display_name,
                'tagline' => $profile->tagline,
                'bio' => $profile->bio,
                'city' => $profile->city,
                'birthdate' => optional($profile->birthdate)->toDateString(),
                'website_url' => $profile->website_url,
                'instagram_url' => $profile->instagram_url,
                'tiktok_url' => $profile->tiktok_url,
                'spotify_url' => $profile->spotify_url,
                'interests' => $profile->interests ?? [],
                'favorite_music' => $profile->favorite_music ?? [],
                'party_style_tags' => $profile->party_style_tags ?? [],
                'privacy_profile' => $profile->privacy_profile,
                'privacy_guestbook' => $profile->privacy_guestbook,
                'privacy_photos' => $profile->privacy_photos,
            ],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'profile_photo_url' => $user->profile_photo_url,
            ],
            'photos' => $photos,
            'privacyOptions' => [
                'public' => 'Public',
                'friends' => 'Friends',
                'private' => 'Private',
            ],
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $profile = $user->profile()->firstOrCreate([], [
            'display_name' => $user->name,
            'privacy_profile' => 'public',
            'privacy_guestbook' => 'public',
            'privacy_photos' => 'friends',
        ]);

        Gate::authorize('update', $profile);

        $profile->update([
            'display_name' => $request->string('display_name')->toString(),
            'tagline' => $request->string('tagline')->toString() ?: null,
            'bio' => $request->string('bio')->toString() ?: null,
            'city' => $request->string('city')->toString() ?: null,
            'birthdate' => $request->input('birthdate'),
            'website_url' => $request->string('website_url')->toString() ?: null,
            'instagram_url' => $request->string('instagram_url')->toString() ?: null,
            'tiktok_url' => $request->string('tiktok_url')->toString() ?: null,
            'spotify_url' => $request->string('spotify_url')->toString() ?: null,
            'interests' => $this->cleanTags($request->input('interests', [])),
            'favorite_music' => $this->cleanTags($request->input('favorite_music', [])),
            'party_style_tags' => $this->cleanTags($request->input('party_style_tags', [])),
            'privacy_profile' => $request->string('privacy_profile')->toString(),
            'privacy_guestbook' => $request->string('privacy_guestbook')->toString(),
            'privacy_photos' => $request->string('privacy_photos')->toString(),
        ]);

        if ($request->file('profile_photo')) {
            $user->updateProfilePhoto($request->file('profile_photo'));
        }

        Notification::create('Profile updated')->send();

        return redirect()->back();
    }

    /** @return array<int, string> */
    private function cleanTags(array $tags): array
    {
        return array_values(array_filter(array_map(static function ($tag): ?string {
            $value = is_string($tag) ? trim($tag) : '';

            return $value !== '' ? $value : null;
        }, Arr::wrap($tags))));
    }
}
