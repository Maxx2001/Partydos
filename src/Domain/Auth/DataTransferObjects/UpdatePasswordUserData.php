<?php

namespace Domain\Auth\DataTransferObjects;

use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class UpdatePasswordUserData extends Data
{
    public function __construct(
        #[Rule(['required'])]
        public ?string $token,
        #[Rule(['required|email'])]
        public ?string $email,
        #[Password(min: 8)]
        #[Rule(['confirmed'])]
        public ?string $password,
    ) {
    }

    /** @return array<string, array<int, string>> */
    public static function rules(): array
    {
        return [
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

}
