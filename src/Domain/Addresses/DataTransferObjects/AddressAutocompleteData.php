<?php

namespace Domain\Addresses\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class AddressAutocompleteData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public string $input
    ) {}
}
