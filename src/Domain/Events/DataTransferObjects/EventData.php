<?php

namespace Domain\Events\DataTransferObjects;

use Carbon\Carbon;
use Domain\Events\Models\Event;
use Domain\Events\Services\EventShareLinkService;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class EventData extends Data
{
    public function __construct(
        public int $id,
        public string $uniqueIdentifier,
        public string $title,
        public ?string $description,
        public string $startDateTime,
        public ?string $isoStartDateTime,
        public ?string $endDateTime,
        public ?string $isoEndDateTime,
        public ?string $canceledAt,
        public ?string $status,
        public string $shareLink,
        public User|GuestUser $eventOwner,
        /** @var array<User|GuestUser> */
        public array $participants,
        public Carbon $createdAt,
        public Carbon $updatedAt
    ) {}
}
