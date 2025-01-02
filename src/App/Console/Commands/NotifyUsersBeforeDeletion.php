<?php

namespace App\Console\Commands;

use Domain\Auth\Mail\AccountDeletionWarning;
use Domain\Users\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class NotifyUsersBeforeDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:notify-before-deletion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email warnings to users whose accounts will be permanently deleted soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $warningThreshold = Carbon::now()->subDays(26);

        $users = User::onlyTrashed()
            ->where('deleted_at', '<=', $warningThreshold)
            ->get();

        if ($users->isEmpty()) {
            $this->info('No users need a deletion warning email at this time.');
            return 0;
        }

        foreach ($users as $user) {
            Mail::to($user->email)->send(new AccountDeletionWarning($user));
            $this->info("Warning email sent to: {$user->email}");
        }

        $this->info('All warning emails have been sent.');

        return 0;
    }
}
