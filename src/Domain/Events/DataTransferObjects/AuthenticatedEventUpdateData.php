<?php

namespace Domain\Events\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class AuthenticatedEventUpdateData extends Data
{
    public function __construct(
        #[Rule(['sometimes', 'string', 'max:255'])]
        public ?string $title,

        #[Rule(['sometimes', 'nullable', 'string'])]
        public ?string $description,

        #[Rule(['sometimes', 'nullable', 'string'])]
        public ?string $location,

        #[Rule(['sometimes', 'date'])]
        public ?string $start_date_time,

        #[Rule(['sometimes', 'nullable', 'date'])]
        public ?string $end_date_time,
    ) {}
}
