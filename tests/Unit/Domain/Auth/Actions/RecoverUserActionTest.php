<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\RecoverUserAction;
use Domain\Users\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification; // Assuming this is the correct namespace

class RecoverUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected RecoverUserAction $action;
    protected MockInterface | User $mockUserModelStatics; // For User::where etc.
    protected MockInterface $mockNotification; // For Notification::create()->send()

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RecoverUserAction();

        // Mock static calls on User model
        $this->mockUserModelStatics = Mockery::mock('overload:' . User::class);

        // Mock Notification class (assuming it's used like Notification::create(...)->send())
        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    public function test_it_recovers_user_if_token_is_valid(): void
    {
        // Arrange
        $token = 'valid_recovery_token';
        $mockUserInstance = Mockery::mock(User::class)->makePartial(); // makePartial for property setting

        $this->mockUserModelStatics->shouldReceive('where')->once()->with('recovery_token', $token)->andReturnSelf();
        $this->mockUserModelStatics->shouldReceive('withTrashed')->once()->andReturnSelf();
        $this->mockUserModelStatics->shouldReceive('first')->once()->andReturn($mockUserInstance);

        $mockUserInstance->shouldReceive('restore')->once();
        $mockUserInstance->shouldReceive('save')->once()->andReturn(true);

        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with('Your account is successfully restored')
            ->andReturn($mockNotificationChained);

        // Act
        $this->action->execute($token);

        // Assert
        $this->assertNull($mockUserInstance->recovery_token); // Check property was set to null
    }

    public function test_it_sends_not_found_notification_if_token_is_invalid(): void
    {
        // Arrange
        $token = 'invalid_recovery_token';

        $this->mockUserModelStatics->shouldReceive('where')->once()->with('recovery_token', $token)->andReturnSelf();
        $this->mockUserModelStatics->shouldReceive('withTrashed')->once()->andReturnSelf();
        $this->mockUserModelStatics->shouldReceive('first')->once()->andReturn(null); // No user found

        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with('Account is not found')
            ->andReturn($mockNotificationChained);

        $mockUserInstance = Mockery::spy(User::class); // Spy to ensure methods NOT called

        // Act
        $this->action->execute($token);

        // Assert
        $mockUserInstance->shouldNotHaveReceived('restore');
        $mockUserInstance->shouldNotHaveReceived('save');
    }
}
