<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Events\Actions\GetEventListsForUserAction;
use Domain\Users\Models\User;
use Illuminate\Support\Collection as EloquentCollection; // Alias for Eloquent's Collection
use Illuminate\Support\Collection; // For the final return type
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class GetEventListsForUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected GetEventListsForUserAction $action;
    protected MockInterface | User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetEventListsForUserAction();
        $this->mockUser = Mockery::mock(User::class);
    }

    public function test_it_returns_correctly_structured_event_lists_for_user(): void
    {
        // Arrange
        $mockInvitedEvents = new EloquentCollection(['invited_event_1', 'invited_event_2']);
        $mockOwnedEvents = new EloquentCollection(['owned_event_1']);
        $mockHistoryEvents = new EloquentCollection(['history_event_1', 'history_event_2']);

        // Mocking the chain for invitedEvents: $user->events()->with()->futureEvents()->get()
        $invitedRelationMock = Mockery::mock();
        $invitedRelationMock->shouldReceive('with')->once()->with('address')->andReturnSelf();
        $invitedRelationMock->shouldReceive('futureEvents')->once()->andReturnSelf();
        $invitedRelationMock->shouldReceive('get')->once()->andReturn($mockInvitedEvents);
        $this->mockUser->shouldReceive('events')->once()->andReturn($invitedRelationMock);

        // Mocking the chain for ownedEvents: $user->ownedEvents()->with()->futureEvents()->orderBy()->get()
        $ownedRelationMock = Mockery::mock();
        $ownedRelationMock->shouldReceive('with')->once()->with('address')->andReturnSelf();
        $ownedRelationMock->shouldReceive('futureEvents')->once()->andReturnSelf();
        $ownedRelationMock->shouldReceive('orderBy')->once()->with('start_date_time')->andReturnSelf();
        $ownedRelationMock->shouldReceive('get')->once()->andReturn($mockOwnedEvents);
        $this->mockUser->shouldReceive('ownedEvents')->once()->andReturn($ownedRelationMock);

        // Mocking getHistoryEvents
        $this->mockUser->shouldReceive('getHistoryEvents')->once()->andReturn($mockHistoryEvents);

        // Act
        $result = $this->action->execute($this->mockUser);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->has('invitedEvents'));
        $this->assertTrue($result->has('ownedEvents'));
        $this->assertTrue($result->has('historyEvents'));

        $this->assertSame($mockInvitedEvents, $result->get('invitedEvents'));
        $this->assertSame($mockOwnedEvents, $result->get('ownedEvents'));
        $this->assertSame($mockHistoryEvents, $result->get('historyEvents'));
    }
}
