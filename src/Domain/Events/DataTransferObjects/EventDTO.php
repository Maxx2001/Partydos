<?php

namespace Domain\Events\DataTransferObjects;

use App\Http\Events\Requests\EventStoreRequest;
use Carbon\Carbon;
use Domain\Events\Services\EventShareLinkService;
use Illuminate\Support\Collection;

class EventDTO
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

    public static function fromModel($event): self
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
            eventOwner: EventOwnerDTO::getOwner($event),
            participants: ParticipantDTO::fromCollection($event->guestUsers),
            createdAt: $event->created_at,
            updatedAt: $event->updated_at
        );
    }

//    public static function fromRequest(EventStoreRequest $request): self
//    {
//        return new self(
//            title: $request->input('title'),
//            description: $request->input('description'),
//            location: $request->input('location'),
//            startDateTime: $request->input('startDateTime'),
//            isoStartDateTime: Carbon::parse($request->input('startDateTime'))->setTimezone('UTC')->format('Ymd\THisO'),
//            endDateTime: $request->input('endDateTime'),
//            isoEndDateTime: !$request->input('endDateTime') ? null : Carbon::parse($request->input('endDateTime'))->setTimezone('UTC')->format('Ymd\THisO'),
//        );
//    }

    public static function fromCollection(Collection $events): array
    {
        return $events->map(fn($event) => self::fromModel($event))->toArray();
    }
}
