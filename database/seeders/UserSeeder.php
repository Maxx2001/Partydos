<?php

namespace Database\Seeders;

use Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'name' => 'Max',
            ],
            [
                'email'             => 'max.felis11@gmail.com',
                'password'          => bcrypt(config('auth.user_password')),
                'email_verified_at' => now(),
            ]
        );
    }
}
