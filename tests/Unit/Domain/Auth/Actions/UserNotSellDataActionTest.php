<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\UserNotSellDataAction;
use Domain\Users\Models\User;
use Domain\Users\Models\UserNotSellData;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class UserNotSellDataActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected UserNotSellDataAction $action;
    protected MockInterface | User $mockUser;
    protected MockInterface $mockUserNotSellDataModelStatics; // For UserNotSellData::create()

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UserNotSellDataAction();
        $this->mockUser = Mockery::mock(User::class);
        $this->mockUserNotSellDataModelStatics = Mockery::mock('overload:' . UserNotSellData::class);
    }

    public function test_it_creates_user_not_sell_data_record(): void
    {
        // Arrange
        $userId = 456;
        $this->mockUser->shouldReceive('getKey')->once()->andReturn($userId);

        $createdInstanceMock = Mockery::mock(UserNotSellData::class);
        $createdInstanceMock->shouldReceive('save')->once()->andReturn(true); // Action calls ->save() after create

        $this->mockUserNotSellDataModelStatics->shouldReceive('create')
            ->once()
            ->with(['user_id' => $userId])
            ->andReturn($createdInstanceMock);

        // Act
        $this->action->execute($this->mockUser);

        // Assertions handled by Mockery expectations
        $this->assertTrue(true); // Placeholder if no other direct assertion
    }
}
