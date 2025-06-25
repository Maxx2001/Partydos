<?php

namespace Domain\Events\DataTransferObjects;

use Domain\Addresses\DataTransferObjects\AddressCreateData;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule as ValidationRule;


class GuestEventCreateData extends Data
{
    public function __construct(
        public readonly string $name,
        #[Rule(['required', 'string', 'max:255'])]
        public string             $title,

        #[Rule(['nullable', 'string'])]
        public ?string            $description,

        public ?AddressCreateData $location,

        #[Rule(['required', 'date'])]
        public string             $start_date_time,

        #[Rule(['nullable', 'date'])]
        public ?string            $end_date_time,

        #[Rule(['image', 'mimes:jpg,png,jpeg,gif', 'max:5120'])]
        public                    $image,
        public  string $email,
    ) {
    }

    public static function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', ValidationRule::unique('users', 'email')],
        ];
    }
}
