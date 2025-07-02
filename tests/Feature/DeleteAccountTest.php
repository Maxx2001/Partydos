<?php

namespace Tests\Feature;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_accounts_can_be_deleted(): void
    {
        if (! Features::hasAccountDeletionFeatures()) {
            $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = User::factory()->create());

        $this->delete('/destroy-user', [
            'password' => 'password',
        ]);

        $this->assertSoftDeleted($user);
    }

    public function test_correct_password_must_be_provided_before_account_can_be_deleted(): void
    {
        if (! Features::hasAccountDeletionFeatures()) {
            $this->markTestSkipped('Account deletion is not enabled.');
        }

        $this->actingAs($user = User::factory()->create());

        $this->delete('/destroy-user', [
            'password' => 'wrong-password',
        ]);

        $this->assertNotSoftDeleted($user);
    }
}
