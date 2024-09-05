<?php

namespace Domain\Users\Data;

use Domain\Events\Data\EventData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Carbon;

class UserData extends Data
{
    public function __construct(
        public int $id,

        #[Required]
        public string $name,

        #[Required, Email]
        public string $email,

        #[DateFormat('Y-m-d H:i:s')]
        public ?Carbon $email_verified_at,

        public ?string $profile_photo_url,

        #[DataCollectionOf(EventData::class)]
        public ?DataCollection $ownedEvents,

        #[DataCollectionOf(EventData::class)]
        public ?DataCollection $events,

        #[DateFormat('Y-m-d H:i:s')]
        public Carbon $created_at,

        #[DateFormat('Y-m-d H:i:s')]
        public Carbon $updated_at
    ) {}
}
