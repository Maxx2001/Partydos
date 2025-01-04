<?php

namespace Domain\Addresses\DataTransferObjects;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class AddressCreateData extends Data
{
    public function __construct(
        #[Rule(['string', 'max:255'])]
        public ?string $place_id,
        #[Rule(['required', 'string', 'max:255'])]
        public ?string $address,
    ) {}
}
