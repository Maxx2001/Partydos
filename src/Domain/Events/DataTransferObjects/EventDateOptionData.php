<?php

namespace Domain\Events\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class EventDateOptionData extends Data
{
    public function __construct(
        #[Rule(['required', 'date'])]
        public string $start_date_time,
        #[Rule(['nullable', 'date'])]
        public ?string $end_date_time,
    ) {
    }
}
