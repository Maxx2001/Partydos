<?php

namespace Domain\Profile\DataTransferObjects;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class ProfileUpdateData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        #[Rule(['image', 'mimes:jpg,png,jpeg,gif', 'max:5120'])]
        public ?UploadedFile $profile_photo,
    )
    {
    }
}
