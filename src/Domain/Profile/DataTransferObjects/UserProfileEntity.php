<?php

namespace Domain\Profile\DataTransferObjects;

use Spatie\LaravelData\Data;

class UserProfileEntity extends Data
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
        public ?string $profile_photo_path,
    )
    {
    }
}
