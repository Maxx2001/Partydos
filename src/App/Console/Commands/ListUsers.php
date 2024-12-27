<?php

namespace App\Console\Commands;

use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all Users and GuestUsers with their ID, Name, and Email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fetching all Users and GuestUsers...');

        // Fetch GuestUsers (if a separate model)
//        if (class_exists(GuestUser::class)) {
//            $guestUsers = GuestUser::all(['id', 'name', 'email']);
//            $this->info("\nGuestUsers:");
//            $this->table(['ID', 'Name', 'Email'], $guestUsers->toArray());
//        } else {
//            $this->warn("\nGuestUser model does not exist. Skipping GuestUsers.");
//        }

        // Fetch Users
        $users = User::all(['id', 'name', 'email']);
        $this->info("\nUsers:");
        $this->table(['ID', 'Name', 'Email'], $users->toArray());



        $this->info("\nDone.");
        return Command::SUCCESS;
    }
}
