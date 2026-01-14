<?php

namespace Support\Providers;

use App\Policies\PollOptionPolicy;
use App\Policies\PollPolicy;
use App\Policies\PollVotePolicy;
use Domain\Polls\Models\Poll;
use Domain\Polls\Models\PollOption;
use Domain\Polls\Models\PollVote;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Poll::class => PollPolicy::class,
        PollOption::class => PollOptionPolicy::class,
        PollVote::class => PollVotePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
