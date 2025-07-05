<?php

namespace Domain\Events\DataTransferObjects;

use Domain\Addresses\DataTransferObjects\AddressUpdateData;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class AuthenticatedEventUpdateData extends Data
{
    public function __construct(
        #[Rule(['required', 'string', 'max:255'])]
        public ?string $title,

        #[Rule(['sometimes', 'nullable', 'string'])]
        public ?string $description,

        public ?AddressUpdateData $location,

        #[Rule(['sometimes', 'date'])]
        public ?string $start_date_time,

        #[Rule(['sometimes', 'nullable', 'date'])]
        public ?string $end_date_time,

        #[Rule(['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:5120'])]
        public UploadedFile|null $image,

        #[Rule(['sometimes', 'boolean'])]
        public ?bool $remove_image = false,

    ) {}
}
