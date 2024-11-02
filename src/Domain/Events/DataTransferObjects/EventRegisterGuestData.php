<?php

namespace Domain\Events\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class EventRegisterGuestData extends Data
{
    public function __construct(
        public string $name,
        #[Rule(['email'])]
        public string $email
    ){}
}
