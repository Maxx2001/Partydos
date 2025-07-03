<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Events\Actions\CheckUserIsEventOwnerAction;
use Domain\Events\Actions\DestroyEventAction;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification;

class DestroyEventActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected DestroyEventAction $action;
    protected MockInterface | CheckUserIsEventOwnerAction $mockCheckOwnerAction;
    protected MockInterface | Event $mockEvent;
    protected MockInterface | User $mockAuthUser;
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockCheckOwnerAction = Mockery::mock(CheckUserIsEventOwnerAction::class);
        $this->action = new DestroyEventAction($this->mockCheckOwnerAction);

        $this->mockEvent = Mockery::mock(Event::class)->makePartial(); // makePartial for property access
        $this->mockAuthUser = Mockery::mock(User::class);
        AuthFacade::shouldReceive('user')->andReturn($this->mockAuthUser);

        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    private function expectNotificationSend(string $message): void
    {
        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with($message)
            ->andReturn($mockNotificationChained);
    }

    public function test_it_deletes_event_if_authorized_and_event_is_canceled(): void
    {
        // Arrange
        $this->mockEvent->canceled_at = now(); // Event is canceled

        $this->mockCheckOwnerAction->shouldReceive('execute')
            ->once()
            ->with($this->mockEvent, $this->mockAuthUser); // Should not throw

        $this->mockEvent->shouldReceive('delete')->once();
        $this->expectNotificationSend('Event has been deleted!');

        // Act
        $this->action->execute($this->mockEvent);

        // Assertions by Mockery
        $this->assertTrue(true);
    }

    public function test_it_throws_authorization_exception_if_user_not_owner(): void
    {
        // Arrange
        $this->expectException(AuthorizationException::class);
        $this->mockEvent->canceled_at = now(); // Event state doesn't matter if auth fails first

        $this->mockCheckOwnerAction->shouldReceive('execute')
            ->once()
            ->with($this->mockEvent, $this->mockAuthUser)
            ->andThrow(new AuthorizationException('Not owner.'));

        $this->mockNotification->shouldNotReceive('create'); // No notification should be attempted
        $this->mockEvent->shouldNotReceive('delete');

        // Act
        $this->action->execute($this->mockEvent);
    }

    public function test_it_notifies_if_event_is_not_canceled(): void
    {
        // Arrange
        $this->mockEvent->canceled_at = null; // Event is NOT canceled

        $this->mockCheckOwnerAction->shouldReceive('execute')
            ->once()
            ->with($this->mockEvent, $this->mockAuthUser); // Auth passes

        $this->expectNotificationSend('Event must first be canceled!');
        $this->mockEvent->shouldNotReceive('delete');

        // Act
        $this->action->execute($this->mockEvent);

        // Assertions by Mockery
        $this->assertTrue(true);
    }
}
