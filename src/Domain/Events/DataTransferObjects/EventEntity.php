<?php

namespace Domain\Events\DataTransferObjects;

use Auth;
use Carbon\Carbon;
use Domain\Addresses\DataTransferObjects\AddressEntity;
use Domain\Addresses\Models\Address;
use Domain\Events\Models\Event;
use Domain\Files\DataTransferObjects\PictureDataEntity;
use Domain\GuestUsers\DataTransferObjects\GuestUserEntity;
use Domain\Profile\DataTransferObjects\UserProfileEntity;
use Domain\Users\DataTransferObjects\UserEntity;
use Domain\Events\DataTransferObjects\EventDateOptionEntity;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class EventEntity extends Data
{
    public function __construct(
        public int             $id,
        public string          $uniqueIdentifier,
        public string          $title,
        public ?string         $description,
        public ?string         $startDateTime = null,
        public ?string         $isoStartDateTime,
        public ?string         $endDateTime,
        public ?string         $isoEndDateTime,
        public ?string         $formattedDate,
        public ?string         $formattedTime,
        public bool            $isDatepicker,
        #[DataCollectionOf(EventDateOptionEntity::class)]
        public ?DataCollection $dateOptions,
        public ?string         $canceledAt,
        public ?string         $status,
        public string          $shareLink,
        public ?string         $googleCalendarLink,
        #[DataCollectionOf(PictureDataEntity::class)]
        public ?DataCollection $media,
        public ?AddressEntity  $address,
        public UserProfileEntity $eventOwner,
        #[DataCollectionOf(UserProfileEntity::class)]
        public ?DataCollection $invitedUsers,
        public Carbon          $createdAt,
        public Carbon          $updatedAt,
        public bool            $canEdit = false,
    )
    {
        $event   = Event::find($this->id);

        $this->filterMedia($event);
        $this->canEdit = $this->canEdit($event);
        $this->isDatepicker = (bool) $event->is_datepicker;
        $this->dateOptions = $event->is_datepicker
            ? new DataCollection(EventDateOptionEntity::class, $event->dateOptions)
            : null;
    }

    private function filterMedia(Event $event): void
    {
        $this->media = new DataCollection(
            PictureDataEntity::class,
            $event->getMedia('event-banner')
        );
    }

    private function canEdit($event): bool
    {
        if ($user = Auth::user()) {
            return $user->getKey() === $event->user_id;
        }

        return false;
    }
}
