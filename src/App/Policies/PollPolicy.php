<?php

namespace App\Policies;

use Domain\Events\Models\Event;
use Domain\Events\Services\EventParticipantService;
use Domain\Polls\Models\Poll;
use Domain\Users\Models\User;

class PollPolicy
{
    public function viewAny(User $user, Event $event): bool
    {
        return (new EventParticipantService())->isParticipant($event, $user);
    }

    public function view(User $user, Poll $poll): bool
    {
        return (new EventParticipantService())->isParticipant($poll->event, $user);
    }

    public function create(User $user, Event $event): bool
    {
        return (new EventParticipantService())->isHost($event, $user);
    }

    public function update(User $user, Poll $poll): bool
    {
        return (new EventParticipantService())->isHost($poll->event, $user);
    }

    public function delete(User $user, Poll $poll): bool
    {
        return (new EventParticipantService())->isHost($poll->event, $user);
    }

    public function close(User $user, Poll $poll): bool
    {
        return (new EventParticipantService())->isHost($poll->event, $user);
    }

    public function reopen(User $user, Poll $poll): bool
    {
        return (new EventParticipantService())->isHost($poll->event, $user);
    }
}
