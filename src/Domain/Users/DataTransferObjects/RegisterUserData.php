<?php

namespace Domain\Users\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class RegisterUserData extends Data
{
    public function __construct(
        public string $name,
        #[Rule(['email', 'unique:users,email'])]
        public string $email,
        #[Password(min: 8)]
        #[Rule(['confirmed'])]
        public string $password
    )
    {
    }
}
