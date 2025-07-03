<?php

namespace Tests\Unit\Domain\Users\Actions;

use Domain\Events\Models\Event;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Actions\TransferEventsToUserAction;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Collection; // Eloquent Collection
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class TransferEventsToUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    // This action only has a static method, so no $action property needed in setUp typically.
    // We will call TransferEventsToUserAction::execute directly.

    protected MockInterface | GuestUser $mockGuestUser;
    protected MockInterface | User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockGuestUser = Mockery::mock(GuestUser::class);
        $this->mockUser = Mockery::mock(User::class);
    }

    public function test_it_transfers_owned_and_attended_events_from_guest_to_user(): void
    {
        // ---- Arrange Owned Events ----
        $mockOwnedEvent1 = Mockery::mock(Event::class);
        $guestUserRelationForOwnedEvent1 = Mockery::mock();
        $guestUserRelationForOwnedEvent1->shouldReceive('dissociate')->once();
        $mockOwnedEvent1->shouldReceive('guestUser')->once()->andReturn($guestUserRelationForOwnedEvent1);

        $userRelationForOwnedEvent1 = Mockery::mock();
        $userRelationForOwnedEvent1->shouldReceive('associate')->once()->with($this->mockUser);
        $mockOwnedEvent1->shouldReceive('user')->once()->andReturn($userRelationForOwnedEvent1);
        $mockOwnedEvent1->shouldReceive('save')->once();

        $mockOwnedEvent2 = Mockery::mock(Event::class);
        $guestUserRelationForOwnedEvent2 = Mockery::mock();
        $guestUserRelationForOwnedEvent2->shouldReceive('dissociate')->once();
        $mockOwnedEvent2->shouldReceive('guestUser')->once()->andReturn($guestUserRelationForOwnedEvent2);

        $userRelationForOwnedEvent2 = Mockery::mock();
        $userRelationForOwnedEvent2->shouldReceive('associate')->once()->with($this->mockUser);
        $mockOwnedEvent2->shouldReceive('user')->once()->andReturn($userRelationForOwnedEvent2);
        $mockOwnedEvent2->shouldReceive('save')->once();

        $ownedEventsCollection = new Collection([$mockOwnedEvent1, $mockOwnedEvent2]);
        $ownedEventsRelationMock = Mockery::mock();
        $ownedEventsRelationMock->shouldReceive('get')->once()->andReturn($ownedEventsCollection);
        $this->mockGuestUser->shouldReceive('ownedEvents')->once()->andReturn($ownedEventsRelationMock);

        // ---- Arrange Attended Events ----
        $mockAttendedEvent1 = Mockery::mock(Event::class);
        $mockAttendedEvent2 = Mockery::mock(Event::class);
        $attendedEventsCollection = new Collection([$mockAttendedEvent1, $mockAttendedEvent2]);

        $guestAttendedEventsRelationMock = Mockery::mock();
        $guestAttendedEventsRelationMock->shouldReceive('get')->once()->andReturn($attendedEventsCollection);
        $guestAttendedEventsRelationMock->shouldReceive('detach')->once(); // Detach all
        $this->mockGuestUser->shouldReceive('events')->twice()->andReturn($guestAttendedEventsRelationMock); // Called for get and detach

        $userAttendedEventsRelationMock = Mockery::mock();
        $userAttendedEventsRelationMock->shouldReceive('saveMany')->once()->with($attendedEventsCollection);
        $this->mockUser->shouldReceive('events')->once()->andReturn($userAttendedEventsRelationMock);

        // ---- Act ----
        TransferEventsToUserAction::execute($this->mockGuestUser, $this->mockUser);

        // ---- Assert ----
        // All assertions are handled by Mockery's expectations.
        $this->assertTrue(true);
    }

    public function test_it_handles_no_owned_events(): void
    {
        // Arrange - No owned events
        $ownedEventsCollection = new Collection([]);
        $ownedEventsRelationMock = Mockery::mock();
        $ownedEventsRelationMock->shouldReceive('get')->once()->andReturn($ownedEventsCollection);
        $this->mockGuestUser->shouldReceive('ownedEvents')->once()->andReturn($ownedEventsRelationMock);

        // Arrange - Still need to mock attended events part even if empty for this test focus
        $attendedEventsCollection = new Collection([]);
        $guestAttendedEventsRelationMock = Mockery::mock();
        $guestAttendedEventsRelationMock->shouldReceive('get')->once()->andReturn($attendedEventsCollection);
        $guestAttendedEventsRelationMock->shouldReceive('detach')->once();
        $this->mockGuestUser->shouldReceive('events')->twice()->andReturn($guestAttendedEventsRelationMock);

        $userAttendedEventsRelationMock = Mockery::mock();
        $userAttendedEventsRelationMock->shouldReceive('saveMany')->once()->with($attendedEventsCollection);
        $this->mockUser->shouldReceive('events')->once()->andReturn($userAttendedEventsRelationMock);

        // Act
        TransferEventsToUserAction::execute($this->mockGuestUser, $this->mockUser);

        // Assert - No errors, and no event modification methods should have been called on mock owned events
        $this->assertTrue(true);
    }

    public function test_it_handles_no_attended_events(): void
    {
        // Arrange - Still need to mock owned events part
        $ownedEventsCollection = new Collection([]);
        $ownedEventsRelationMock = Mockery::mock();
        $ownedEventsRelationMock->shouldReceive('get')->once()->andReturn($ownedEventsCollection);
        $this->mockGuestUser->shouldReceive('ownedEvents')->once()->andReturn($ownedEventsRelationMock);

        // Arrange - No attended events
        $attendedEventsCollection = new Collection([]);
        $guestAttendedEventsRelationMock = Mockery::mock();
        $guestAttendedEventsRelationMock->shouldReceive('get')->once()->andReturn($attendedEventsCollection);
        $guestAttendedEventsRelationMock->shouldReceive('detach')->once(); // Detach on empty collection is fine
        $this->mockGuestUser->shouldReceive('events')->twice()->andReturn($guestAttendedEventsRelationMock);

        $userAttendedEventsRelationMock = Mockery::mock();
        $userAttendedEventsRelationMock->shouldReceive('saveMany')->once()->with($attendedEventsCollection); // saveMany on empty is fine
        $this->mockUser->shouldReceive('events')->once()->andReturn($userAttendedEventsRelationMock);

        // Act
        TransferEventsToUserAction::execute($this->mockGuestUser, $this->mockUser);

        // Assert - No errors
        $this->assertTrue(true);
    }
}
