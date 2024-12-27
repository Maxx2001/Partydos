<?php

namespace Domain\Auth\DataTransferObjects;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class ResetPasswordUserData extends Data
{
    public function __construct(
        #[FromRouteParameter('token')]
        public ?string $token,
        #[Rule(['email'])]
        public ?string $email,
    ) {
    }
}
