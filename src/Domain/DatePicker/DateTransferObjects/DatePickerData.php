<?php

namespace Domain\DatePicker\DateTransferObjects;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class DatePickerData extends Data
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $location,
//        #[Rule(['int', 'exists:events,id'])]
//        public int $event_id,
        #[DataCollectionOf(DateOptionData::class)]
        public ?DataCollection $options,

    ) {}
}
