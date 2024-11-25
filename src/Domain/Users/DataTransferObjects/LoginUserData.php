<?php

namespace Domain\Users\DataTransferObjects;

use Spatie\LaravelData\Data;

class LoginUserData extends Data
{
    public function __construct(
        public string $email,
        public string $password
    )
    {
    }
}
