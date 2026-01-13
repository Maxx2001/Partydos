<?php

namespace App\Policies;

use Domain\Events\Services\EventParticipantService;
use Domain\Polls\Models\PollOption;
use Domain\Polls\Models\Poll;
use Domain\Users\Models\User;

class PollOptionPolicy
{
    public function create(User $user, Poll $poll): bool
    {
        $participantService = new EventParticipantService();

        if ($participantService->isHost($poll->event, $user)) {
            return true;
        }

        return $participantService->isParticipant($poll->event, $user)
            && $poll->status === 'open'
            && $poll->guests_can_add_options;
    }

    public function delete(User $user, PollOption $option): bool
    {
        return (new EventParticipantService())->isHost($option->poll->event, $user);
    }
}
