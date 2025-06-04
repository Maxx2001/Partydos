<?php

namespace Domain\Events\DataTransferObjects;

use Domain\Addresses\DataTransferObjects\AddressCreateData;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Data;
use Domain\Events\DataTransferObjects\EventDateOptionData;

class AuthenticatedEventData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public string             $title,

        #[Rule(['nullable', 'string'])]
        public ?string            $description,

        public ?AddressCreateData $location,

        #[Rule(['boolean'])]
        public bool               $is_datepicker = false,

        #[Rule(['nullable', 'date'])]
        public ?string            $start_date_time,

        #[Rule(['nullable', 'date'])]
        public ?string            $end_date_time,

        #[DataCollectionOf(EventDateOptionData::class)]
        public ?DataCollection    $date_options,

        #[Rule(['image', 'mimes:jpg,png,jpeg,gif', 'max:5120'])]
        public                    $image,
    ) {}
}
