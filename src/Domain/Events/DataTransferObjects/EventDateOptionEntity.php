<?php

namespace Domain\Events\DataTransferObjects;

use Carbon\Carbon;
use Domain\Events\Models\EventDateOption;
use Spatie\LaravelData\Data;

class EventDateOptionEntity extends Data
{
    public function __construct(
        public int $id,
        public string $startDateTime,
        public ?string $endDateTime,
        public string $formattedDate,
        public string $formattedTime,
        public int $votes
    ) {}

    public static function fromModel(EventDateOption $option): self
    {
        return new self(
            $option->id,
            $option->start_date_time,
            $option->end_date_time,
            Carbon::parse($option->start_date_time)->format('D d F'),
            $option->formatted_time,
            $option->users()->count() + $option->guestUsers()->count()
        );
    }
}
