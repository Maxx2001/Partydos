<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Events\Actions\CheckUserIsEventOwnerAction;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class CheckUserIsEventOwnerActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected CheckUserIsEventOwnerAction $action;
    protected MockInterface | Event $mockEvent;
    protected MockInterface | User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CheckUserIsEventOwnerAction();
        $this->mockEvent = Mockery::mock(Event::class);
        $this->mockUser = Mockery::mock(User::class);
    }

    public function test_it_does_nothing_if_user_is_event_owner(): void
    {
        // Arrange
        $userId = 123;
        $this->mockEvent->user_id = $userId;
        $this->mockUser->id = $userId;

        // Act
        $this->action->execute($this->mockEvent, $this->mockUser);

        // Assert
        $this->assertTrue(true); // No exception thrown, assertion passes
    }

    public function test_it_throws_authorization_exception_if_user_is_not_event_owner(): void
    {
        // Arrange
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage('This action is unauthorized.');

        $this->mockEvent->user_id = 123;
        $this->mockUser->id = 456; // Different user ID

        // Act
        $this->action->execute($this->mockEvent, $this->mockUser);
    }

    public function test_it_throws_authorization_exception_if_event_has_no_owner(): void
    {
        // Arrange
        // This also tests the case where event->user_id might be null
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage('This action is unauthorized.');

        $this->mockEvent->user_id = null; // Event has no owner
        $this->mockUser->id = 456;

        // Act
        $this->action->execute($this->mockEvent, $this->mockUser);
    }
}
