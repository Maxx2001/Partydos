<?php

namespace Domain\Auth\DataTransferObjects;

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public bool   $remember = false,
    ) {

    }
}
