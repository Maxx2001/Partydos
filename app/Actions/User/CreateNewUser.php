<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\GuestUser;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\ValidationException;
use App\Actions\Fortify\PasswordValidationRules;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms'    => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        /* @var User $user */
        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        /* @var GuestUser $guestUser */
        $guestUser = GuestUser::where('email', $input['email'])->first();

        if (! $guestUser) {
            return $user;
        }

        TransferEventsToUser::handle($guestUser, $user);

        $guestUser->delete();

        return $user;
    }
}
