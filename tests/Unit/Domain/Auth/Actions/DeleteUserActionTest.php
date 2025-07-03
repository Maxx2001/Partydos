<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\DeleteUserAction;
use Domain\Auth\Mail\UserDeleteRequest;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class DeleteUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected DeleteUserAction $action;
    protected Mockery\MockInterface | User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake(); // Use Laravel's Mail facade fake

        // Mock Str::random if a specific token is needed for assertion,
        // otherwise, just check if recovery_token is set.
        // For this test, we'll assume Str::random works and check for presence.
        // If specific token checks are needed: Str::shouldReceive('random')->andReturn('fixed_token');

        $this->action = new DeleteUserAction();
    }

    public function test_it_deletes_user_sets_recovery_token_deletes_tokens_and_sends_email(): void
    {
        // Arrange
        $this->mockUser = Mockery::mock(User::class)->makePartial(); // makePartial to allow property setting
        $this->mockUser->email = 'test@example.com'; // Needed for Mail::to()

        // Mock the tokens relationship and its delete method
        $mockTokensRelation = Mockery::mock();
        $mockTokensRelation->shouldReceive('delete')->once();
        $this->mockUser->shouldReceive('tokens')->once()->andReturn($mockTokensRelation);

        $this->mockUser->shouldReceive('save')->once()->andReturn(true);
        $this->mockUser->shouldReceive('delete')->once()->andReturn(true); // Soft delete

        // Act
        $this->action->execute($this->mockUser);

        // Assert
        $this->assertNotNull($this->mockUser->recovery_token);
        $this->assertEquals(64, strlen($this->mockUser->recovery_token ?? ''));

        Mail::assertQueued(UserDeleteRequest::class, function (UserDeleteRequest $mail) {
            return $mail->hasTo($this->mockUser->email) &&
                   $mail->user->is($this->mockUser);
        });

        // Ensure save was called before delete
        Mockery::getSequenceRecorder()->assertInOrder([
            [$this->mockUser, 'save', Mockery::any()],
            [$this->mockUser, 'delete', Mockery::any()],
        ]);
    }
}
