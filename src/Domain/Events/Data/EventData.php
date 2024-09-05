<?php

namespace Domain\Events\Data;

use Domain\GuestUsers\Data\GuestUserData;
use Domain\Users\Data\UserData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class EventData extends Data
{
    public function __construct(
        public int $id,

        #[Required]
        public string $unique_identifier,

        #[Required]
        public ?int $user_id,

        #[Required]
        public ?int $guest_user_id,

        #[Required]
        public string $title,

        public ?string $description,

        public ?string $location,

        #[Required, DateFormat('Y-m-d H:i:s')]
        public string $start_date_time,

        #[Required, DateFormat('Y-m-d H:i:s')]
        public string $end_date_time,

        public UserData|GuestUserData|null $event_owner,

        #[DataCollectionOf(GuestUserData::class)]
        public ?DataCollection $guest_users,

        public ?string $share_link
    ) {}
}
