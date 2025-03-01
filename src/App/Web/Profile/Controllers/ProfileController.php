<?php

namespace App\Web\Profile\Controllers;

use Auth;
use Domain\Profile\Actions\UpdateProfileAction;
use Domain\Profile\DataTransferObjects\ProfileUpdateData;
use Domain\Profile\DataTransferObjects\UserProfileEntity;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;

class ProfileController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Profile/Edit', [
            'user' => UserProfileEntity::from(Auth::user()->toArray()),
        ]);
    }

    public function update(ProfileUpdateData $profileUpdateData, UpdateProfileAction $updateProfileAction): RedirectResponse
    {
        $user = Auth::user();
        $updateProfileAction->execute($user, $profileUpdateData);

        return redirect()->back();
    }
}
