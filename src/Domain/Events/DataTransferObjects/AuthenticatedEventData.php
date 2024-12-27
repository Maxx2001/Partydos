<?php

namespace Domain\Events\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class AuthenticatedEventData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public string $title,

        #[Rule(['nullable', 'string'])]
        public ?string $description,

        #[Rule(['nullable', 'string'])]
        public ?string $location,

        #[Rule(['required', 'date'])]
        public string $start_date_time,

        #[Rule(['nullable', 'date'])]
        public ?string $end_date_time,

        #[Rule(['image', 'mimes:jpg,png,jpeg', 'max:5120'])]
        public $image,
    ) {}
}
