<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\ResetPasswordAction;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification;

class ResetPasswordActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected ResetPasswordAction $action;
    protected MockInterface | User $mockUser;
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ResetPasswordAction();
        $this->mockUser = Mockery::mock(User::class);

        Hash::shouldReceive('make')->andReturnUsing(fn($value) => 'hashed_' . $value)->byDefault();
        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    public function test_it_resets_user_password_and_sends_notification(): void
    {
        // Arrange
        $input = ['password' => 'newSecurePassword!'];
        $hashedPassword = 'hashed_newSecurePassword!';

        $this->mockUser->shouldReceive('forceFill')
            ->once()
            ->with(['password' => $hashedPassword])
            ->andReturnSelf(); // forceFill returns $this

        $this->mockUser->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with("Password reset!")
            ->andReturn($mockNotificationChained);

        // Act
        $this->action->reset($this->mockUser, $input);

        // Assertions are handled by Mockery expectations
        Hash::shouldHaveReceived('make')->once()->with($input['password']);
        $this->assertTrue(true); // Placeholder
    }
}
