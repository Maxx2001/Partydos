<?php

namespace Tests\Unit\Domain\Events\Actions;

use Carbon\Carbon;
use Domain\Events\Actions\CancelEventAction;
use Domain\Events\Models\Event;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification;

class CancelEventActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected CancelEventAction $action;
    protected MockInterface | Event $mockEvent;
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CancelEventAction();
        $this->mockEvent = Mockery::mock(Event::class)->makePartial(); // makePartial to allow property setting

        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    public function test_it_cancels_event_and_sends_notification(): void
    {
        // Arrange
        $now = Carbon::now();
        Carbon::setTestNow($now); // Freeze time for consistent now()

        $this->mockEvent->shouldReceive('save')->once()->andReturn(true);

        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with('Event has been canceled!')
            ->andReturn($mockNotificationChained);

        // Act
        $this->action->execute($this->mockEvent);

        // Assert
        // Check that canceled_at was set to the frozen 'now()'
        $this->assertNotNull($this->mockEvent->canceled_at);
        $this->assertInstanceOf(Carbon::class, $this->mockEvent->canceled_at); // Or check string format if not cast
        $this->assertEquals($now->toDateTimeString(), $this->mockEvent->canceled_at->toDateTimeString());

        // Verify save was called after canceled_at was set.
        // This is implicitly handled by property assertion + save mock, but can be explicit with sequence if needed.

        Carbon::setTestNow(); // Clear the frozen time
    }
}
