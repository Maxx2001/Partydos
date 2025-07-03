<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\ResetUserPasswordAction;
use Domain\Auth\Traits\PasswordValidationRules; // To access passwordRules for test setup
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ResetUserPasswordActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    // Use the trait to get password rules for defining validator expectations
    use PasswordValidationRules {
        passwordRules as protected testPasswordRules;
    }

    protected ResetUserPasswordAction $action;
    protected MockInterface | User $mockUser;
    protected $validatorMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ResetUserPasswordAction();
        $this->mockUser = Mockery::mock(User::class);

        Hash::shouldReceive('make')->andReturnUsing(fn($value) => 'hashed_' . $value)->byDefault();

        // Setup validator mock for general case, can be overridden in tests
        $this->validatorMock = Mockery::mock(['validate' => null]); // Default to valid
        Validator::shouldReceive('make')->andReturn($this->validatorMock)->byDefault();
    }

    public function test_it_resets_user_password_on_valid_input(): void
    {
        // Arrange
        $input = [
            'password' => 'newPassword123!',
            'password_confirmation' => 'newPassword123!',
        ];
        $hashedPassword = 'hashed_newPassword123!';

        Validator::shouldReceive('make')
            ->once()
            ->with($input, ['password' => $this->testPasswordRules()])
            ->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validate')->once()->andReturn($input); // Ensure it returns validated data or null

        $this->mockUser->shouldReceive('forceFill')
            ->once()
            ->with(['password' => $hashedPassword])
            ->andReturnSelf();

        $this->mockUser->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        // Act
        $this->action->reset($this->mockUser, $input);

        // Assertions handled by Mockery expectations
        Hash::shouldHaveReceived('make')->once()->with($input['password']);
        $this->assertTrue(true);
    }

    public function test_it_throws_validation_exception_on_invalid_input(): void
    {
        // Arrange
        $this->expectException(ValidationException::class);
        $input = ['password' => 'short', 'password_confirmation' => 'short']; // Invalid password

        Validator::shouldReceive('make')
            ->once()
            ->with($input, ['password' => $this->testPasswordRules()])
            ->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validate')->once()->andThrow(ValidationException::withMessages(['password' => 'Invalid password.']));

        // Act
        $this->action->reset($this->mockUser, $input);

        // Assert
        $this->mockUser->shouldNotHaveReceived('forceFill');
        $this->mockUser->shouldNotHaveReceived('save');
    }
}
