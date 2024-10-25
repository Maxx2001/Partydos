<?php

namespace Domain\GuestUsers\DataTransferObjects;

use Spatie\LaravelData\Data;

class GuestUserEntity extends Data
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
        public string $created_at,
        public string $updated_at
    )
    {

    }
}
