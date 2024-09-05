<?php

namespace Domain\GuestUsers\Data;

use Domain\Events\Data\EventData;
use Domain\GuestUsers\Models\GuestUser;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Carbon;

class GuestUserData extends Data
{
    public function __construct(
        public int            $id,

        #[Required]
        public string         $name,

        #[Required, Email]
        public string         $email,

        #[DataCollectionOf(EventData::class)]
        public DataCollection $owned_events,

        #[DataCollectionOf(EventData::class)]
        public DataCollection $events,

        #[Required]
        public Carbon         $created_at,

        #[Required]
        public Carbon         $updated_at
    )
    {
    }

    /**
     * Create a GuestUserData instance from a GuestUser model.
     *
     * @param GuestUser $guestUser
     * @return self
     */
    public static function fromModel(GuestUser $guestUser): self
    {
        return new self(
            id: $guestUser->id,
            name: $guestUser->name,
            email: $guestUser->email,
            owned_events: EventData::collect($guestUser->ownedEvents),
            events: EventData::collection($guestUser->events),
            created_at: $guestUser->created_at,
            updated_at: $guestUser->updated_at
        );
    }
}
