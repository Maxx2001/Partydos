<?php

namespace App\Policies;

use Domain\Profile\Models\Profile;
use Domain\Profile\Services\FriendshipService;
use Domain\Users\Models\User;

class ProfilePolicy
{
    public function __construct(private readonly FriendshipService $friendshipService)
    {
    }

    public function view(?User $user, Profile $profile): bool
    {
        return $this->canAccess($user, $profile->user, $profile->privacy_profile);
    }

    public function update(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    private function canAccess(?User $viewer, User $profileUser, string $privacy): bool
    {
        if ($viewer && $viewer->id === $profileUser->id) {
            return true;
        }

        if ($privacy === 'public') {
            return true;
        }

        if ($privacy === 'private') {
            return false;
        }

        return $this->friendshipService->areFriends($viewer, $profileUser);
    }
}
