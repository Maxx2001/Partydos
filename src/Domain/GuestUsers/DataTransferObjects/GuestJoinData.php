<?php

namespace Domain\GuestUsers\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class GuestJoinData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public string $name,
        #[Rule(['required', 'email', 'max:255'])]
        public string $email,
    ) {}
} 