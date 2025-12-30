<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\UpdateUserPasswordAction;
use Domain\Auth\Traits\PasswordValidationRules; // To access passwordRules for test setup
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class UpdateUserPasswordActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use PasswordValidationRules {
        passwordRules as protected testPasswordRules;
    }

    protected UpdateUserPasswordAction $action;
    protected MockInterface | User $mockUser;
    protected $validatorMock; // Mock for the fluent validator object

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateUserPasswordAction();
        $this->mockUser = Mockery::mock(User::class);

        Hash::shouldReceive('make')->andReturnUsing(fn($value) => 'hashed_' . $value)->byDefault();

        // Setup validator mock for general case
        $this->validatorMock = Mockery::mock(['validateWithBag' => null]); // Default to valid
        Validator::shouldReceive('make')->andReturn($this->validatorMock)->byDefault();
    }

    public function test_it_updates_user_password_on_valid_input(): void
    {
        // Arrange
        $input = [
            'current_password' => 'oldPassword123',
            'password' => 'newPassword123!',
            'password_confirmation' => 'newPassword123!',
        ];
        $hashedNewPassword = 'hashed_newPassword123!';

        Validator::shouldReceive('make')
            ->once()
            ->with($input, [
                'current_password' => ['required', 'string', 'current_password:web'],
                'password' => $this->testPasswordRules(),
            ], [
                'current_password.current_password' => __('The provided password does not match your current password.'),
            ])
            ->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validateWithBag')->once()->with('updatePassword')->andReturn($input);


        $this->mockUser->shouldReceive('forceFill')
            ->once()
            ->with(['password' => $hashedNewPassword])
            ->andReturnSelf();

        $this->mockUser->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        // Act
        $this->action->update($this->mockUser, $input);

        // Assertions handled by Mockery
        Hash::shouldHaveReceived('make')->once()->with($input['password']);
        $this->assertTrue(true);
    }

    public function test_it_throws_validation_exception_on_invalid_current_password(): void
    {
        // Arrange
        $this->expectException(ValidationException::class);
        $input = [
            'current_password' => 'wrongOldPassword',
            'password' => 'newPassword123!',
            'password_confirmation' => 'newPassword123!',
        ];

        Validator::shouldReceive('make')->once()->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validateWithBag')
            ->once()
            ->with('updatePassword')
            ->andThrow(ValidationException::withMessages(['current_password' => 'Wrong current password.']));

        // Act
        $this->action->update($this->mockUser, $input);

        // Assert
        $this->mockUser->shouldNotHaveReceived('forceFill');
        $this->mockUser->shouldNotHaveReceived('save');
    }

    public function test_it_throws_validation_exception_on_invalid_new_password(): void
    {
        // Arrange
        $this->expectException(ValidationException::class);
        $input = [
            'current_password' => 'oldPassword123',
            'password' => 'short', // Invalid new password
            'password_confirmation' => 'short',
        ];

        Validator::shouldReceive('make')->once()->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validateWithBag')
            ->once()
            ->with('updatePassword')
            ->andThrow(ValidationException::withMessages(['password' => 'New password invalid.']));

        // Act
        $this->action->update($this->mockUser, $input);

        // Assert
        $this->mockUser->shouldNotHaveReceived('forceFill');
        $this->mockUser->shouldNotHaveReceived('save');
    }
}
