<?php

namespace Domain\Profile\Services;

use Domain\Profile\Models\Friendship;
use Domain\Users\Models\User;

class FriendshipService
{
    public function areFriends(?User $first, ?User $second): bool
    {
        if (!$first || !$second) {
            return false;
        }

        return Friendship::query()
            ->between($first, $second)
            ->where('status', 'accepted')
            ->exists();
    }
}
