<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\LoginAction;
use Domain\Auth\DataTransferObjects\LoginData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class LoginActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected LoginAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        Auth::shouldReceive('attempt')->byDefault(); // Allow overriding per test
        $this->action = new LoginAction();
    }

    public function test_it_logins_successfully_and_returns_true(): void
    {
        // Arrange
        $loginData = new LoginData(
            email: 'test@example.com',
            password: 'password123',
            remember: true
        );

        Auth::shouldReceive('attempt')
            ->once()
            ->with(
                ['email' => $loginData->email, 'password' => $loginData->password],
                $loginData->remember
            )
            ->andReturn(true);

        // Act
        $result = $this->action->execute($loginData);

        // Assert
        $this->assertTrue($result);
    }

    public function test_it_throws_validation_exception_on_failed_login(): void
    {
        // Arrange
        $this->expectException(ValidationException::class);
        // Optionally, check the exception message
        // $this->expectExceptionMessage(__('These credentials do not match our records.'));
        // However, testing exact translated messages can be brittle. Checking the field is often better.

        $loginData = new LoginData(
            email: 'wrong@example.com',
            password: 'wrongpassword',
            remember: false
        );

        Auth::shouldReceive('attempt')
            ->once()
            ->with(
                ['email' => $loginData->email, 'password' => $loginData->password],
                $loginData->remember
            )
            ->andReturn(false);

        // Act
        try {
            $this->action->execute($loginData);
        } catch (ValidationException $e) {
            // Assert that the exception has a message for the 'email' field
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertEquals(__('These credentials do not match our records.'), $e->errors()['email'][0]);
            throw $e; // Re-throw to satisfy expectException
        }
    }
}
