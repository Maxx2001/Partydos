<?php

namespace Tests\Feature\Web\Auth;

use Domain\Auth\Actions\LoginAction;
use Domain\Auth\Actions\RecoverUserAction;
use Domain\Auth\Actions\RegisterNotSellDataUserAction;
use Domain\Auth\Actions\ResetPasswordAction;
use Domain\Auth\Actions\ResetPasswordUserAction;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password; // For password reset token generation
use Inertia\Testing\AssertableInertia as Assert; // If testing Inertia responses
use Laravel\Jetstream\Contracts\DeletesUsers;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    // --- Authenticate Tests (/login/authenticate) ---
    public function test_user_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => Hash::make('sEcrEt123')]);

        // Mock LoginAction
        $mockLoginAction = Mockery::mock(LoginAction::class);
        $mockLoginAction->shouldReceive('execute')->once()->andReturn(true); // Simulate successful login
        $this->app->instance(LoginAction::class, $mockLoginAction);

        $response = $this->post(route('login.authenticate'), [
            'email' => $user->email,
            'password' => 'sEcrEt123',
        ]);

        $response->assertRedirect(route('users-events.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_authenticate_with_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'test@example.com', 'password' => Hash::make('sEcrEt123')]);

        // Mock LoginAction to simulate failure
        $mockLoginAction = Mockery::mock(LoginAction::class);
        // Assuming LoginAction throws ValidationException or returns false on failure
        // The controller checks for a truthy return value.
        $mockLoginAction->shouldReceive('execute')->once()->andReturn(false);
        $this->app->instance(LoginAction::class, $mockLoginAction);

        $response = $this->post(route('login.authenticate'), [
            'email' => 'test@example.com',
            'password' => 'wrongPass',
        ]);

        $response->assertRedirect(); // Redirects back
        $response->assertSessionHas('status', __('auth.failed'));
        $this->assertGuest();
    }

    public function test_authenticate_validates_missing_email_or_password(): void
    {
        // This tests the DTO validation before the action is even hit
        $response = $this->post(route('login.authenticate'), ['email' => 'test@example.com']);
        $response->assertSessionHasErrors('password');

        $response = $this->post(route('login.authenticate'), ['password' => 'secret']);
        $response->assertSessionHasErrors('email');
    }

    // --- Logout Tests (/logout) ---
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    // --- Forgot Password Tests (/forgot-password) ---
    public function test_can_request_password_reset_link_with_valid_email(): void
    {
        User::factory()->create(['email' => 'user@example.com']);

        $mockResetAction = Mockery::mock(ResetPasswordUserAction::class);
        $mockResetAction->shouldReceive('execute')->once(); // Just check it's called
        $this->app->instance(ResetPasswordUserAction::class, $mockResetAction);

        $response = $this->post(route('forgotPassword'), ['email' => 'user@example.com']);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Password reset link sent to your email address.');
    }

    public function test_forgot_password_validates_email(): void
    {
        // Test non-existent email (UserResetPasswordEmailData DTO has Rule::exists('users', 'email'))
        $response = $this->post(route('forgotPassword'), ['email' => 'nonexistent@example.com']);
        $response->assertSessionHasErrors('email');

        // Test invalid email format
        $response = $this->post(route('forgotPassword'), ['email' => 'not-an-email']);
        $response->assertSessionHasErrors('email');
    }

    // --- Reset Password Page (/reset-password/{token}) ---
    public function test_reset_password_page_renders_correctly_with_valid_token_and_email(): void
    {
        // This test requires a bit more setup as it queries the User model directly
        $user = User::factory()->create();
        // The token isn't validated by this controller method, just passed through.
        // ResetPasswordUserData DTO expects email and token.
        $token = 'test-token-123';

        $response = $this->get(route('password.reset-page', ['token' => $token, 'email' => $user->email]));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('Auth/ResetPassword')
                 ->where('token', $token)
                 ->where('email', $user->email)
                 ->where('userId', $user->id)
        );
    }

    // --- Update Password (/update-password/{user}) ---
    public function test_can_reset_password_with_valid_data(): void
    {
        $user = User::factory()->create();
        // This route takes user ID, which is unusual for password reset (usually token-based)
        // The ResetPasswordAction itself doesn't validate the token, just new password.

        $mockResetPasswordAction = Mockery::mock(ResetPasswordAction::class);
        $mockResetPasswordAction->shouldReceive('reset')->once()
            ->with($user, Mockery::on(function($arg) { // Check the data passed
                return isset($arg['password']) && $arg['password'] === 'newStrongPassword123!';
            }));
        $this->app->instance(ResetPasswordAction::class, $mockResetPasswordAction);

        $response = $this->post(route('password.reset', ['user' => $user->id]), [
            // UpdatePasswordUserData expects password and password_confirmation
            'password' => 'newStrongPassword123!',
            'password_confirmation' => 'newStrongPassword123!',
        ]);

        $response->assertRedirect(route('login'));
    }

    // --- Delete User (/destroy-user) ---
    public function test_authenticated_user_can_delete_their_account_with_correct_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('sEcrEt123')]);
        $this->actingAs($user);

        $mockDeleter = Mockery::mock(DeletesUsers::class);
        $mockDeleter->shouldReceive('delete')->once()->with($user);
        $this->app->instance(DeletesUsers::class, $mockDeleter);

        $response = $this->delete(route('user-destroy'), ['password' => 'sEcrEt123']);

        $response->assertRedirect(route('home'));
        $this->assertGuest(); // User should be logged out
    }

    public function test_delete_user_fails_with_incorrect_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('sEcrEt123')]);
        $this->actingAs($user);

        $mockDeleter = Mockery::mock(DeletesUsers::class);
        $mockDeleter->shouldNotReceive('delete'); // Should not be called
        $this->app->instance(DeletesUsers::class, $mockDeleter);

        $response = $this->delete(route('user-destroy'), ['password' => 'wrongPassword']);

        $response->assertSessionHasErrors('password');
        $this->assertAuthenticatedAs($user); // User should still be logged in
    }

    public function test_delete_user_is_auth_protected(): void
    {
        $response = $this->delete(route('user-destroy'), ['password' => 'any']);
        $response->assertRedirect(route('login')); // Or 401/403 depending on middleware behavior
    }

    // --- User Recovery (/user/recovery/{recovery_token}) ---
    public function test_user_can_recover_account_with_valid_token(): void
    {
        $token = 'valid-recovery-token-xyz';
        $mockRecoverAction = Mockery::mock(RecoverUserAction::class);
        $mockRecoverAction->shouldReceive('execute')->once()->with($token);
        $this->app->instance(RecoverUserAction::class, $mockRecoverAction);

        $response = $this->get(route('user-recovery', ['recovery_token' => $token]));
        $response->assertRedirect(route('home'));
    }

    // --- Register Not Sell Data (/user/do-not-sell-my-data) ---
    public function test_authenticated_user_can_register_for_do_not_sell_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $mockRegisterAction = Mockery::mock(RegisterNotSellDataUserAction::class);
        $mockRegisterAction->shouldReceive('execute')->once()->with($user);
        $this->app->instance(RegisterNotSellDataUserAction::class, $mockRegisterAction);

        $response = $this->post(route('user-do-not-sell-my-data'));
        $response->assertRedirect(route('home'));
    }

    public function test_register_not_sell_data_is_auth_protected(): void
    {
        $response = $this->post(route('user-do-not-sell-my-data'));
        $response->assertRedirect(route('login'));
    }

    // Note: The resetPasswordEmail method in AuthController has no return type and only sends a Notification.
    // It's hard to test its direct effect via HTTP without a proper response.
    // It might be an AJAX endpoint or an internal call. If it's meant for standard form submission,
    // it should probably return a RedirectResponse.
}
