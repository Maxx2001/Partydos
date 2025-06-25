<?php

namespace Domain\Users\DataTransferObjects;

use Spatie\LaravelData\Data;

class GuestUserData extends Data
{
    public function __construct(
      public readonly string $email,
    ) {
    }
} 