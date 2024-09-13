<?php

namespace Domain\Events\DataTransferObjects;

use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Support\Collection;

class ParticipantDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email
    ) {}

    public static function fromModel(GuestUser|User $participant): self
    {
        return new self(
            id: $participant->id,
            name: $participant->name,
            email: $participant->email
        );
    }

    public static function fromCollection(Collection $participants): array
    {
        return $participants->map(fn($participant) => self::fromModel($participant))->toArray();
    }
}
