<?php

namespace Domain\Galleries\Actions;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetUserGalleriesAction
{
    public function execute(User $user): Collection
    {
        return $user->galleries()->withCount('items')->latest()->get();
    }
}
