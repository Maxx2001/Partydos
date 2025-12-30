<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Events\Actions\ViewEventsAction;
use Domain\Events\DataTransferObjects\EventEntity;
use Domain\Events\Models\Event; // Though we'll mock models, namespace is good.
use Domain\Users\Models\User;
use Illuminate\Support\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Spatie\LaravelData\DataCollection;


class ViewEventsActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected ViewEventsAction $action;
    protected MockInterface | User $mockAuthUser;
    protected MockInterface $mockEventEntity; // To mock static EventEntity::collect

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ViewEventsAction();
        $this->mockAuthUser = Mockery::mock(User::class);
        AuthFacade::shouldReceive('user')->andReturn($this->mockAuthUser);

        // Mock the EventEntity static collect method
        $this->mockEventEntity = Mockery::mock('overload:' . EventEntity::class);
    }

    public function test_it_returns_collection_of_event_entities_for_authed_user(): void
    {
        // Arrange
        $mockRawEvents = new EloquentCollection([
            Mockery::mock(Event::class),
            Mockery::mock(Event::class),
        ]);

        // Mocking the chain: $user->events()->futureEvents()->get()
        $eventsRelationMock = Mockery::mock();
        $eventsRelationMock->shouldReceive('futureEvents')->once()->andReturnSelf();
        $eventsRelationMock->shouldReceive('get')->once()->andReturn($mockRawEvents);
        $this->mockAuthUser->shouldReceive('events')->once()->andReturn($eventsRelationMock);

        // Expected DTO collection (can be simple array or mocked DataCollection for type hint)
        $expectedDtoCollection = Mockery::mock(DataCollection::class);


        $this->mockEventEntity->shouldReceive('collect')
            ->once()
            ->with($mockRawEvents)
            ->andReturn($expectedDtoCollection);

        // Act
        $result = $this->action->execute();

        // Assert
        $this->assertSame($expectedDtoCollection, $result);
    }

    public function test_it_returns_empty_collection_if_user_has_no_future_events(): void
    {
        // Arrange
        $mockRawEvents = new EloquentCollection([]); // Empty collection

        $eventsRelationMock = Mockery::mock();
        $eventsRelationMock->shouldReceive('futureEvents')->once()->andReturnSelf();
        $eventsRelationMock->shouldReceive('get')->once()->andReturn($mockRawEvents);
        $this->mockAuthUser->shouldReceive('events')->once()->andReturn($eventsRelationMock);

        $expectedDtoCollection = Mockery::mock(DataCollection::class); // Mocked empty DataCollection
        // Or, if EventEntity::collect returns a new DataCollection:
        // $expectedDtoCollection = new DataCollection(EventEntity::class, []);


        $this->mockEventEntity->shouldReceive('collect')
            ->once()
            ->with($mockRawEvents)
            ->andReturn($expectedDtoCollection); // Ensure it returns a DataCollection instance

        // Act
        $result = $this->action->execute();

        // Assert
        $this->assertSame($expectedDtoCollection, $result);
        // If $expectedDtoCollection was a real empty DataCollection, you could also assert:
        // $this->assertInstanceOf(DataCollection::class, $result);
        // $this->assertTrue($result->isEmpty());
    }
}
