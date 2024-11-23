<?php

namespace Domain\Events\DataTransferObjects;

use Carbon\Carbon;
use Domain\GuestUsers\DataTransferObjects\GuestUserEntity;
use Domain\Users\DataTransferObjects\UserEntity;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class EventEntityData extends Data
{
    public function __construct(
        public int             $id,
        public string          $uniqueIdentifier,
        public string          $title,
        public ?string         $description,
        public ?string         $location,
        public string          $startDateTime,
        public ?string         $isoStartDateTime,
        public ?string         $endDateTime,
        public ?string         $isoEndDateTime,
        public ?string         $formattedDate,
        public ?string         $formattedTime,
        public ?string         $status,
        public string          $shareLink,
        public ?string         $googleCalendarLink,
        public UserEntity      $eventOwner,
//        #[DataCollectionOf(GuestUserEntity::class)]
        public                 $invitedUsers,
        public Carbon          $createdAt,
        public Carbon          $updatedAt
    )
    {
    }
}
