<?php

namespace Domain\Addresses\DataTransferObjects;
use Spatie\LaravelData\Data;

class SuggestionEntity extends Data
{
    public function __construct(
        public string $place_id,
        public string $place,
        public string $address,
    ) {}
}
