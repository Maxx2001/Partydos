<?php

namespace Domain\Profile\DataTransferObjects;

use Spatie\LaravelData\Data;

class ProfileUpdateData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
    )
    {
    }
}
