<?php

namespace Domain\DatePicker\DateTransferObjects;

use Spatie\LaravelData\Data;


class DateOptionData extends Data
{
    public function __construct(
        public string $date,
        public ?string $start_time,
        public ?string $end_time,
        public ?string $comment,
    ) {}
}
