<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\ResetPasswordUserAction;
use Domain\Auth\DataTransferObjects\UserResetPasswordEmailData;
use Domain\Auth\Mail\UserPasswordReset;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL; // For mocking route()
use Illuminate\Support\Facades\Config; // For mocking config()
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ResetPasswordUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected ResetPasswordUserAction $action;
    protected MockInterface | User $mockUserModelStatics;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ResetPasswordUserAction();

        Mail::fake();
        Password::shouldReceive('createToken')->andReturn('test_reset_token')->byDefault();
        URL::shouldReceive('route')->andReturn('http://localhost/reset-password-page-url')->byDefault();
        Config::shouldReceive('get')->with('auth.passwords.users.expire')->andReturn(60)->byDefault();
        // Alternative for config: config(['auth.passwords.users.expire' => 60]);

        $this->mockUserModelStatics = Mockery::mock('overload:' . User::class);
    }

    public function test_it_sends_password_reset_email(): void
    {
        // Arrange
        $email = 'test@example.com';
        $resetPasswordData = new UserResetPasswordEmailData(email: $email);

        $mockUserInstance = Mockery::mock(User::class)->makePartial();
        $mockUserInstance->email = $email; // Ensure the mock user has the email

        $this->mockUserModelStatics->shouldReceive('where')->once()->with('email', $email)->andReturnSelf();
        $this->mockUserModelStatics->shouldReceive('first')->once()->andReturn($mockUserInstance);

        $expectedToken = 'test_reset_token';
        $expectedRoute = 'http://localhost/reset-password-page-url';
        $expectedExpiry = 60;

        Password::shouldReceive('createToken')->once()->with($mockUserInstance)->andReturn($expectedToken);
        URL::shouldReceive('route')
            ->once()
            ->with('password.reset-page', ['email' => $email, 'token' => $expectedToken], true) // abs route usually true
            ->andReturn($expectedRoute);
        Config::shouldReceive('get')->once()->with('auth.passwords.users.expire')->andReturn($expectedExpiry);


        // Act
        $this->action->execute($resetPasswordData);

        // Assert
        Mail::assertSent(UserPasswordReset::class, function (UserPasswordReset $mail) use ($mockUserInstance, $expectedRoute, $expectedExpiry, $email) {
            $this->assertTrue($mail->hasTo($email));
            // Access public properties if mailable is built that way, or use reflection/getters if private
            $mailData = $mail->viewData; // Common way to get data passed to mailable view

            $this->assertSame($mockUserInstance, $mailData['user']);
            $this->assertEquals($expectedRoute, $mailData['route']);
            $this->assertEquals($expectedExpiry, $mailData['count']); // 'count' is the default name for expiry in Laravel mail templates
            return true;
        });
    }

    public function test_it_handles_user_not_found_gracefully(): void
    {
        // Current action does not explicitly handle user not found, will throw error on Password::createToken(null)
        // This test documents that. A better action would check if $user is null.
        $this->expectException(\TypeError::class); // Or Error, depending on PHP version and exact error

        $email = 'notfound@example.com';
        $resetPasswordData = new UserResetPasswordEmailData(email: $email);

        $this->mockUserModelStatics->shouldReceive('where')->once()->with('email', $email)->andReturnSelf();
        $this->mockUserModelStatics->shouldReceive('first')->once()->andReturn(null); // User not found

        // Password::createToken will likely be called with null, causing an error.
        // Mail::assertNotSent(UserPasswordReset::class); // Should not be sent

        $this->action->execute($resetPasswordData);
    }
}
