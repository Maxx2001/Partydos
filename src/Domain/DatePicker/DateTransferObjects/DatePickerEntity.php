<?php

namespace Domain\DatePicker\DateTransferObjects;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class DatePickerEntity extends Data
{
    public function __construct(
        public int $id,
        public ?string $uniqueIdentifier,
        public int $eventId,
        public int $userId,
        public string $title,
        public ?string $description,
        public ?string $location,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {}
}
