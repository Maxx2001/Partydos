<?php

namespace App\Console\Commands;

use Domain\Auth\Actions\DestroyUserAction;
use Domain\Users\Models\User;
use Illuminate\Console\Command;

use Carbon\Carbon;

class DeleteOldSoftDeletedUsers extends Command
{
    protected $signature = 'users:delete-old';

    protected $description = 'Delete all users that have been soft deleted for a month or longer';

    public function handle(): int
    {
        $thresholdDate = Carbon::now()->subMonth();

        $users = User::onlyTrashed()
            ->where('deleted_at', '<=', $thresholdDate)
            ->get();

        if ($users->isEmpty()) {
            $this->info('No old soft-deleted users found.');
            return 0;
        }

        foreach ($users as $user) {
            $this->info("Permanently deleting user: {$user->email}");
            (new DestroyUserAction())->execute($user);
        }

        $this->info('Old soft-deleted users have been permanently deleted.');

        return 0;
    }
}
