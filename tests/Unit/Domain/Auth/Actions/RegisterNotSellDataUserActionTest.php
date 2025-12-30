<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\RegisterNotSellDataUserAction;
use Domain\Users\Models\User;
use Domain\Users\Models\UserNotSellData;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification;

class RegisterNotSellDataUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected RegisterNotSellDataUserAction $action;
    protected MockInterface | User $mockUser;
    protected MockInterface $mockUserNotSellDataModelStatics; // For UserNotSellData::create()
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RegisterNotSellDataUserAction();
        $this->mockUser = Mockery::mock(User::class);

        $this->mockUserNotSellDataModelStatics = Mockery::mock('overload:' . UserNotSellData::class);
        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    public function test_it_notifies_if_user_already_registered_for_not_sell_data(): void
    {
        // Arrange
        $mockUserRelation = Mockery::mock();
        $mockUserRelation->shouldReceive('exists')->once()->andReturn(true);
        $this->mockUser->shouldReceive('userNotSellData')->once()->andReturn($mockUserRelation);
        $this->mockUser->shouldReceive('getKey')->never(); // Should not be called if already exists

        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with('You are already added to the do not sell my data list.')
            ->andReturn($mockNotificationChained);

        $this->mockUserNotSellDataModelStatics->shouldNotReceive('create');

        // Act
        $this->action->execute($this->mockUser);

        // Assertions handled by Mockery expectations
        $this->assertTrue(true);
    }

    public function test_it_registers_user_for_not_sell_data_if_not_already_registered(): void
    {
        // Arrange
        $userId = 123;
        $mockUserRelation = Mockery::mock();
        $mockUserRelation->shouldReceive('exists')->once()->andReturn(false);
        $this->mockUser->shouldReceive('userNotSellData')->once()->andReturn($mockUserRelation);
        $this->mockUser->shouldReceive('getKey')->once()->andReturn($userId);

        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with('You are successfully added to the do not sell my data list.')
            ->andReturn($mockNotificationChained);

        $createdInstanceMock = Mockery::mock(UserNotSellData::class);
        $createdInstanceMock->shouldReceive('save')->once()->andReturn(true); // Action calls ->save() after create

        $this->mockUserNotSellDataModelStatics->shouldReceive('create')
            ->once()
            ->with(['user_id' => $userId])
            ->andReturn($createdInstanceMock);

        // Act
        $this->action->execute($this->mockUser);

        // Assertions handled by Mockery expectations
        $this->assertTrue(true);
    }
}
