<?php

namespace Domain\Addresses\DataTransferObjects;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class AddressUpdateData extends Data
{
    public function __construct(
        #[Rule(['nullable', 'int', 'max:255'])]
        public ?int $id,
        #[Rule(['string', 'max:255'])]
        public ?string $place_id,
        #[Rule(['string', 'max:255'])]
        public ?string $place,
        #[Rule(['required', 'string', 'max:255'])]
        public string $address,
    ) {}
}
