<?php

namespace App\Policies;

use Domain\Profile\Models\ProfilePhoto;
use Domain\Profile\Services\FriendshipService;
use Domain\Users\Models\User;

class ProfilePhotoPolicy
{
    public function __construct(private readonly FriendshipService $friendshipService)
    {
    }

    public function viewAny(?User $user, User $profileUser): bool
    {
        $privacy = $profileUser->profile?->privacy_photos ?? 'friends';

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

    public function upload(User $user, User $profileUser): bool
    {
        return $user->id === $profileUser->id;
    }

    public function delete(User $user, ProfilePhoto $photo): bool
    {
        return $user->id === $photo->user_id;
    }
}
