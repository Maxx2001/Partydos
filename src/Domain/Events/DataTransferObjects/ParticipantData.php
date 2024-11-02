<?php

namespace Domain\Events\DataTransferObjects;

use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Support\Collection;

class ParticipantData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email
    ) {}
}
