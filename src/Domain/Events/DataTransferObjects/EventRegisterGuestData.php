<?php

namespace Domain\Events\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule as ValidationRule;


class EventRegisterGuestData extends Data
{
    public function __construct(
        public string $name,
        #[Rule(['required', 'email', 'max:255'])]
        public string $email
    ){}

    public static function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255', ValidationRule::unique('users', 'email')],
        ];
    }
}
