<?php

namespace Domain\DatePicker\DateTransferObjects;

use Spatie\LaravelData\Data;


class DateOptionData extends Data
{
    public function __construct(
        public string $date,
        public ?string $start_datetime,
        public ?string $end_datetime,
        public ?string $comment,
    ) {}
}
