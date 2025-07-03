<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\DestroyUserAction;
use Domain\Events\Models\Event; // Assuming Event model namespace
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Collection; // Eloquent Collection
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class DestroyUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected DestroyUserAction $action;
    protected Mockery\MockInterface | User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DestroyUserAction();
        $this->mockUser = Mockery::mock(User::class);
    }

    public function test_it_destroys_user_and_related_data(): void
    {
        // Arrange

        // Mock ownedEvents
        $mockOwnedEvent1 = Mockery::mock(Event::class);
        $mockOwnedEvent1->shouldReceive('delete')->once();
        $mockOwnedEvent2 = Mockery::mock(Event::class);
        $mockOwnedEvent2->shouldReceive('delete')->once();
        $ownedEventsCollection = new Collection([$mockOwnedEvent1, $mockOwnedEvent2]);
        $this->mockUser->shouldReceive('getAttribute')->with('ownedEvents')->andReturn($ownedEventsCollection);

        // Mock invitedEvents
        $mockInvitedEvent1 = Mockery::mock(Event::class);
        $pivotMock1 = Mockery::mock();
        $pivotMock1->shouldReceive('detach')->with($this->mockUser)->once();
        $mockInvitedEvent1->shouldReceive('users')->once()->andReturn($pivotMock1);

        $mockInvitedEvent2 = Mockery::mock(Event::class);
        $pivotMock2 = Mockery::mock();
        $pivotMock2->shouldReceive('detach')->with($this->mockUser)->once();
        $mockInvitedEvent2->shouldReceive('users')->once()->andReturn($pivotMock2);

        $invitedEventsCollection = new Collection([$mockInvitedEvent1, $mockInvitedEvent2]);
        $this->mockUser->shouldReceive('getAttribute')->with('events')->andReturn($invitedEventsCollection);

        // Mock forceDelete
        $this->mockUser->shouldReceive('forceDelete')->once()->andReturn(true);

        // Act
        $this->action->execute($this->mockUser);

        // Assertions are handled by Mockery expectations (->once())
        $this->assertTrue(true); // Placeholder if no other direct assertion is needed
    }

    public function test_it_handles_no_owned_events(): void
    {
        // Arrange
        $ownedEventsCollection = new Collection([]); // Empty collection
        $this->mockUser->shouldReceive('getAttribute')->with('ownedEvents')->andReturn($ownedEventsCollection);

        // Still need to mock invitedEvents and forceDelete
        $invitedEventsCollection = new Collection([]);
        $this->mockUser->shouldReceive('getAttribute')->with('events')->andReturn($invitedEventsCollection);
        $this->mockUser->shouldReceive('forceDelete')->once()->andReturn(true);

        // Act
        $this->action->execute($this->mockUser);

        // Assertions are handled by Mockery (no delete calls on owned events)
        $this->assertTrue(true);
    }

    public function test_it_handles_no_invited_events(): void
    {
        // Arrange
        $ownedEventsCollection = new Collection([]);
        $this->mockUser->shouldReceive('getAttribute')->with('ownedEvents')->andReturn($ownedEventsCollection);

        $invitedEventsCollection = new Collection([]); // Empty collection
        $this->mockUser->shouldReceive('getAttribute')->with('events')->andReturn($invitedEventsCollection);

        $this->mockUser->shouldReceive('forceDelete')->once()->andReturn(true);

        // Act
        $this->action->execute($this->mockUser);

        // Assertions are handled by Mockery (no detach calls on invited events)
        $this->assertTrue(true);
    }
}
