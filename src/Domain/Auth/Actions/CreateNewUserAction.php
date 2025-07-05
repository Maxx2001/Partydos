<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Traits\PasswordValidationRules;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Actions\TransferEventsToUserAction;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUserAction implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @throws ValidationException
     */
    /** @param array<string, mixed> $input */
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

        $guestUser = GuestUser::where('email', $input['email'])->first();

        if (! $guestUser) {
            return $user;
        }

        TransferEventsToUserAction::execute($guestUser, $user);
        
        Auth::login($user);

        $guestUser->delete();

        return $user;
    }
}
