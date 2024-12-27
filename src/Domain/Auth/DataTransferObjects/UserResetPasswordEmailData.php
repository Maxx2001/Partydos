<?php

namespace Domain\Auth\DataTransferObjects;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class UserResetPasswordEmailData extends Data
{
    public function __construct(
        public string $email,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email'),
            ],
        ];
    }
}
