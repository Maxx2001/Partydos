<?php

namespace Tests\Unit\Domain\GuestUsers\Actions;

use Domain\GuestUsers\Actions\CreateOrFindGuestUserAction;
use Domain\GuestUsers\Models\GuestUser;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class CreateOrFindGuestUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected CreateOrFindGuestUserAction $action;
    protected MockInterface $mockGuestUserModelStatics; // For GuestUser::firstOrCreate

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateOrFindGuestUserAction();
        $this->mockGuestUserModelStatics = Mockery::mock('overload:' . GuestUser::class);
    }

    public function test_it_returns_existing_guest_user_if_found(): void
    {
        // Arrange
        $email = 'existing@example.com';
        $name = 'Existing User';

        $mockExistingUser = Mockery::mock(GuestUser::class);
        $mockExistingUser->email = $email; // Set properties for clarity if needed
        $mockExistingUser->name = $name;

        $this->mockGuestUserModelStatics->shouldReceive('firstOrCreate')
            ->once()
            ->with(
                ['email' => $email], // Attributes to find by
                ['name' => $name]    // Attributes to use if creating
            )
            ->andReturn($mockExistingUser);

        // Act
        $resultUser = $this->action->execute($email, $name);

        // Assert
        $this->assertSame($mockExistingUser, $resultUser);
    }

    public function test_it_creates_and_returns_new_guest_user_if_not_found(): void
    {
        // Arrange
        $email = 'new@example.com';
        $name = 'New User';

        $mockNewUser = Mockery::mock(GuestUser::class);
        $mockNewUser->email = $email;
        $mockNewUser->name = $name;

        $this->mockGuestUserModelStatics->shouldReceive('firstOrCreate')
            ->once()
            ->with(
                ['email' => $email],
                ['name' => $name]
            )
            ->andReturn($mockNewUser); // firstOrCreate would return the newly created model

        // Act
        $resultUser = $this->action->execute($email, $name);

        // Assert
        $this->assertSame($mockNewUser, $resultUser);
    }
}
