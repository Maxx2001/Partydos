<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Events\Actions\RestoreEventAction;
use Domain\Events\Models\Event;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification;

class RestoreEventActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected RestoreEventAction $action;
    protected MockInterface | Event $mockEvent;
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RestoreEventAction();
        $this->mockEvent = Mockery::mock(Event::class)->makePartial(); // makePartial to allow property setting/checking

        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    public function test_it_restores_event_and_sends_notification(): void
    {
        // Arrange
        // Optionally, set an initial canceled_at to ensure it changes
        $this->mockEvent->canceled_at = now();

        $this->mockEvent->shouldReceive('save')->once()->andReturn(true);

        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with('Event has been restored!')
            ->andReturn($mockNotificationChained);

        // Act
        $this->action->execute($this->mockEvent);

        // Assert
        // Check that canceled_at was set to null
        $this->assertNull($this->mockEvent->canceled_at);

        // Verify save was called after canceled_at was set to null.
        // Mockery order assertion can be added if strict sequence is critical beyond property check.
    }
}
