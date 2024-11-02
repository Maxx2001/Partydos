<?php

namespace Domain\Events\DataTransferObjects;

use Carbon\Carbon;
use Domain\Events\Models\Event;
use Domain\Events\Services\EventShareLinkService;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class EventData extends Data
{
    public function __construct(
        public int $id,
        public string $uniqueIdentifier,
        public string $title,
        public ?string $description,
        public ?string $location,
        public string $startDateTime,
        public ?string $isoStartDateTime,
        public ?string $endDateTime,
        public ?string $isoEndDateTime,
        public ?string $status,
        public string $shareLink,
        public $eventOwner,
        public array $participants,
        public Carbon $createdAt,
        public Carbon $updatedAt
    ) {}

    public static function fromModel(Event $event): self
    {
        return new self(
            id: $event->id,
            uniqueIdentifier: $event->unique_identifier,
            title: $event->title,
            description: $event->description ?? '',
            location: $event->location ?? '',
            startDateTime: $event->start_date_time,
            isoStartDateTime: Carbon::parse($event->start_date_time)->setTimezone('UTC')->format('Ymd\THisO'),
            endDateTime: $event->end_date_time,
            isoEndDateTime: !$event->end_date_time ? null : Carbon::parse($event->end_date_time)->setTimezone('UTC')->format('Ymd\THisO'),
            status: $event->status,
            shareLink: (new EventShareLinkService())->generateShareLink($event),
            eventOwner: EventOwnerData::getOwner($event),
            participants: ParticipantData::fromCollection($event->guestUsers),
            createdAt: $event->created_at,
            updatedAt: $event->updated_at
        );
    }
}
