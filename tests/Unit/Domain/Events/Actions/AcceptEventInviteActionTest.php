<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Events\Actions\AcceptEventInviteAction;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification;

class AcceptEventInviteActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected AcceptEventInviteAction $action;
    protected MockInterface | Event $mockEvent;
    protected MockInterface | User $mockUser;
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new AcceptEventInviteAction();
        $this->mockEvent = Mockery::mock(Event::class);
        $this->mockUser = Mockery::mock(User::class);
        $this->mockUser->id = 1; // Example user ID
        $this->mockUser->email = 'test@example.com'; // Example user email

        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    private function mockNotificationSend(string $message): void
    {
        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with($message)
            ->andReturn($mockNotificationChained);
    }

    public function test_it_notifies_if_user_already_assigned_to_event(): void
    {
        // Arrange
        $usersRelationMock = Mockery::mock();
        $usersRelationMock->shouldReceive('where')->once()->with('user_id', $this->mockUser->id)->andReturnSelf();
        $usersRelationMock->shouldReceive('exists')->once()->andReturn(true);
        $this->mockEvent->shouldReceive('users')->once()->andReturn($usersRelationMock);

        $this->mockEvent->shouldNotReceive('guestUsers'); // guestUsers check should be skipped
        $usersRelationMock->shouldNotReceive('attach'); // attach should not be called

        $this->mockNotificationSend('You are already assigned to this event');

        // Act
        $this->action->execute($this->mockEvent, $this->mockUser);

        // Assertions handled by Mockery
        $this->assertTrue(true);
    }

    public function test_it_notifies_if_email_already_used_by_guest(): void
    {
        // Arrange
        $usersRelationMock = Mockery::mock();
        $usersRelationMock->shouldReceive('where')->once()->with('user_id', $this->mockUser->id)->andReturnSelf();
        $usersRelationMock->shouldReceive('exists')->once()->andReturn(false); // User not directly assigned
        $this->mockEvent->shouldReceive('users')->once()->andReturn($usersRelationMock);

        $guestUsersRelationMock = Mockery::mock();
        $guestUsersRelationMock->shouldReceive('where')->once()->with('email', $this->mockUser->email)->andReturnSelf();
        $guestUsersRelationMock->shouldReceive('exists')->once()->andReturn(true); // Email exists as guest
        $this->mockEvent->shouldReceive('guestUsers')->once()->andReturn($guestUsersRelationMock);

        $usersRelationMock->shouldNotReceive('attach'); // attach should not be called

        $this->mockNotificationSend('This email is already used');

        // Act
        $this->action->execute($this->mockEvent, $this->mockUser);

        // Assertions handled by Mockery
        $this->assertTrue(true);
    }

    public function test_it_attaches_user_and_notifies_on_successful_invite_acceptance(): void
    {
        // Arrange
        $usersRelationMock = Mockery::mock();
        $usersRelationMock->shouldReceive('where')->once()->with('user_id', $this->mockUser->id)->andReturnSelf();
        $usersRelationMock->shouldReceive('exists')->once()->andReturn(false); // User not directly assigned
        $this->mockEvent->shouldReceive('users')->once()->andReturn($usersRelationMock);

        $guestUsersRelationMock = Mockery::mock();
        $guestUsersRelationMock->shouldReceive('where')->once()->with('email', $this->mockUser->email)->andReturnSelf();
        $guestUsersRelationMock->shouldReceive('exists')->once()->andReturn(false); // Email does not exist as guest
        $this->mockEvent->shouldReceive('guestUsers')->once()->andReturn($guestUsersRelationMock);

        $usersRelationMock->shouldReceive('attach')->once()->with($this->mockUser);

        $this->mockNotificationSend('You have been registered to the event!');

        // Act
        $this->action->execute($this->mockEvent, $this->mockUser);

        // Assertions handled by Mockery
        $this->assertTrue(true);
    }
}
