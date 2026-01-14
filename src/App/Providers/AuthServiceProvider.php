<?php

namespace App\Providers;

use App\Policies\GuestbookEntryPolicy;
use App\Policies\ProfilePhotoPolicy;
use App\Policies\ProfilePolicy;
use Domain\Profile\Models\GuestbookEntry;
use Domain\Profile\Models\Profile;
use Domain\Profile\Models\ProfilePhoto;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Profile::class => ProfilePolicy::class,
        GuestbookEntry::class => GuestbookEntryPolicy::class,
        ProfilePhoto::class => ProfilePhotoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
