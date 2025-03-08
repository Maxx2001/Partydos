<?php

namespace Domain\DatePicker\DateTransferObjects;

use Carbon\Carbon;
use Spatie\LaravelData\Data;


class DateOptionEntity extends Data
{
    public function __construct(
        public int $id,
        public int $date_picker_id,
        public string $date,
        public ?string $start_time,
        public ?string $end_time,
        public ?string $comment,

    ) {}
}
