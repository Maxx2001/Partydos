<?php

namespace App\Policies;

use Domain\Events\Services\EventParticipantService;
use Domain\Polls\Models\Poll;
use Domain\Users\Models\User;

class PollVotePolicy
{
    public function vote(User $user, Poll $poll): bool
    {
        return (new EventParticipantService())->isParticipant($poll->event, $user)
            && $poll->status === 'open';
    }
}
