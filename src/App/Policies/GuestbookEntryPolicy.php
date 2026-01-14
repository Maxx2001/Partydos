<?php

namespace App\Policies;

use Domain\Profile\Models\GuestbookEntry;
use Domain\Profile\Services\FriendshipService;
use Domain\Users\Models\User;

class GuestbookEntryPolicy
{
    public function __construct(private readonly FriendshipService $friendshipService)
    {
    }

    public function viewAny(?User $user, User $profileUser): bool
    {
        $privacy = $profileUser->profile?->privacy_guestbook ?? 'public';

        if ($user && $user->id === $profileUser->id) {
            return true;
        }

        if ($privacy === 'public') {
            return true;
        }

        if ($privacy === 'private') {
            return false;
        }

        return $this->friendshipService->areFriends($user, $profileUser);
    }

    public function create(?User $user, User $profileUser): bool
    {
        if (!$user) {
            return false;
        }

        return $this->viewAny($user, $profileUser);
    }

    public function delete(User $user, GuestbookEntry $entry): bool
    {
        return $user->id === $entry->author_user_id || $user->id === $entry->profile_user_id;
    }
}
