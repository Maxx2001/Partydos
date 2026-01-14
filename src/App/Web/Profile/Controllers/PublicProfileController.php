<?php

namespace App\Web\Profile\Controllers;

use Domain\Profile\Models\Friendship;
use Domain\Profile\Models\GuestbookEntry;
use Domain\Profile\Models\Profile;
use Domain\Profile\Models\ProfilePhoto;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;

class PublicProfileController extends Controller
{
    public function show(User $user): Response
    {
        $viewer = Auth::user();

        $profile = $user->profile()->firstOrCreate([], [
            'display_name' => $user->name,
            'privacy_profile' => 'public',
            'privacy_guestbook' => 'public',
            'privacy_photos' => 'friends',
        ]);

        Gate::authorize('view', $profile);

        $friendship = $viewer
            ? Friendship::query()->between($viewer, $user)->first()
            : null;

        $friendshipState = [
            'id' => $friendship?->id,
            'status' => $friendship?->status ?? 'none',
            'is_requester' => $friendship && $viewer && $friendship->requester_user_id === $viewer->id,
            'is_addressee' => $friendship && $viewer && $friendship->addressee_user_id === $viewer->id,
        ];

        $canViewPhotos = Gate::forUser($viewer)->allows('viewAny', [ProfilePhoto::class, $user]);
        $canViewGuestbook = Gate::forUser($viewer)->allows('viewAny', [GuestbookEntry::class, $user]);
        $canWriteGuestbook = Gate::forUser($viewer)->allows('create', [GuestbookEntry::class, $user]);

        $photos = $canViewPhotos
            ? $user->profilePhotos()->latest()->get()->map(fn (ProfilePhoto $photo) => [
                'id' => $photo->id,
                'url' => Storage::disk('public')->url($photo->path),
                'caption' => $photo->caption,
            ])
            : collect();

        $guestbookEntries = $canViewGuestbook
            ? $user->guestbookEntries()
                ->latest()
                ->with('author.profile')
                ->get()
                ->map(fn (GuestbookEntry $entry) => [
                    'id' => $entry->id,
                    'body' => $entry->body,
                    'created_at' => $entry->created_at->toDateTimeString(),
                    'author' => [
                        'id' => $entry->author->id,
                        'display_name' => $entry->author->profile?->display_name ?? $entry->author->name,
                        'avatar' => $entry->author->profile_photo_url,
                    ],
                    'can_delete' => $viewer
                        ? $viewer->id === $entry->author_user_id || $viewer->id === $entry->profile_user_id
                        : false,
                ])
            : collect();

        return Inertia::render('Profile/PublicShow', [
            'profileUser' => [
                'id' => $user->id,
                'display_name' => $profile->display_name,
                'tagline' => $profile->tagline,
                'bio' => $profile->bio,
                'city' => $profile->city,
                'birthdate' => optional($profile->birthdate)->toDateString(),
                'avatar' => $user->profile_photo_url,
                'interests' => $profile->interests ?? [],
                'favorite_music' => $profile->favorite_music ?? [],
                'party_style_tags' => $profile->party_style_tags ?? [],
                'links' => [
                    'website_url' => $profile->website_url,
                    'instagram_url' => $profile->instagram_url,
                    'tiktok_url' => $profile->tiktok_url,
                    'spotify_url' => $profile->spotify_url,
                ],
                'privacy' => [
                    'photos' => $profile->privacy_photos,
                    'guestbook' => $profile->privacy_guestbook,
                ],
            ],
            'photos' => $photos,
            'guestbookEntries' => $guestbookEntries,
            'friendship' => $friendshipState,
            'stats' => [
                'events_hosted_count' => $user->ownedEvents()->count(),
                'events_attended_count' => $user->events()->count(),
            ],
            'permissions' => [
                'canViewPhotos' => $canViewPhotos,
                'canViewGuestbook' => $canViewGuestbook,
                'canWriteGuestbook' => $canWriteGuestbook,
                'isOwner' => $viewer && $viewer->id === $user->id,
            ],
        ]);
    }
}
