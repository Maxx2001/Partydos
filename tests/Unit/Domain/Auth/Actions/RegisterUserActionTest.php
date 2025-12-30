<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\RegisterUserAction;
use Domain\Users\DataTransferObjects\RegisterUserData;
use Domain\Users\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class RegisterUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected RegisterUserAction $action;
    protected MockInterface | User $mockUserModelStatics;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RegisterUserAction();

        // Mock Facades
        Hash::shouldReceive('make')->andReturnUsing(fn($value) => 'hashed_' . $value)->byDefault();
        Auth::shouldReceive('login')->andReturnNull()->byDefault();
        Event::fake(); // Use Laravel's Event fake

        // Mock User model static methods
        $this->mockUserModelStatics = Mockery::mock('overload:' . User::class);
    }

    public function test_it_registers_user_dispatches_event_and_logs_in(): void
    {
        // Arrange
        $registerData = new RegisterUserData(
            name: 'Test User',
            email: 'test@example.com',
            password: 'password123'
        );

        $createdUserInstance = Mockery::mock(User::class);
        // It's good practice to ensure the created instance has the ID or necessary properties
        // if they are used immediately after, though not strictly needed for this action's direct returns.
        $createdUserInstance->shouldReceive('getKey')->andReturn(1); // Example

        $this->mockUserModelStatics->shouldReceive('create')
            ->once()
            ->with([
                'name' => $registerData->name,
                'email' => $registerData->email,
                'password' => 'hashed_' . $registerData->password,
            ])
            ->andReturn($createdUserInstance);

        // Act
        $result = $this->action->execute($registerData);

        // Assert
        $this->assertSame($createdUserInstance, $result);

        Event::assertDispatched(Registered::class, function ($event) use ($createdUserInstance) {
            return $event->user === $createdUserInstance;
        });

        // Check Auth::login was called - direct mock expectation is better for unit tests
        // than relying on Auth::check() or Auth::user() if state is not fully managed.
        Auth::shouldHaveReceived('login')->once()->with($createdUserInstance);
        Hash::shouldHaveReceived('make')->once()->with($registerData->password);
    }
}
