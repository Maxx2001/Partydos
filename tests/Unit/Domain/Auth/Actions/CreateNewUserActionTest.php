<?php

namespace Tests\Unit\Domain\Auth\Actions;

use Domain\Auth\Actions\CreateNewUserAction;
use Domain\Auth\Traits\PasswordValidationRules;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Actions\TransferEventsToUserAction;
use Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class CreateNewUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    // Use the same trait as the action to get password rules for tests
    use PasswordValidationRules {
        passwordRules as protected testPasswordRules;
    }

    protected CreateNewUserAction $action;
    protected $validatorMock;
    protected $userMock;
    protected $guestUserMock;
    protected $transferActionMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock Facades
        Validator::shouldReceive('make')->andReturn($this->validatorMock = Mockery::mock(['validate' => null]));
        Hash::shouldReceive('make')->andReturnUsing(fn($value) => 'hashed_' . $value);
        Auth::shouldReceive('login')->andReturnNull(); // Mock login facade

        // Mock Jetstream
        Jetstream::shouldReceive('hasTermsAndPrivacyPolicyFeature')->andReturn(false); // Default to false

        // Mock Models using overload (for static calls like User::create)
        $this->userMock = Mockery::mock('overload:' . User::class);
        $this->guestUserMock = Mockery::mock('overload:' . GuestUser::class);
        // Mock the action (static execute method)
        $this->transferActionMock = Mockery::mock('overload:' . TransferEventsToUserAction::class);

        $this->action = new CreateNewUserAction();
    }

    private function getValidInput(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123', // Trait includes confirmed rule
            'terms' => true, // Assuming terms are accepted
        ], $overrides);
    }

    public function test_it_creates_user_successfully_without_guest_user(): void
    {
        $input = $this->getValidInput();

        $this->validatorMock->shouldReceive('validate')->once()->andReturn($input);
        Validator::shouldReceive('make')
            ->once()
            ->with($input, [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->testPasswordRules(),
                'terms'    => '', // Jetstream feature is false by default in setUp
            ])
            ->andReturn($this->validatorMock);

        $createdUserInstance = Mockery::mock(User::class);
        $this->userMock->shouldReceive('create')
            ->once()
            ->with([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => 'hashed_' . $input['password'],
            ])
            ->andReturn($createdUserInstance);

        $this->guestUserMock->shouldReceive('where')->once()->with('email', $input['email'])->andReturnSelf();
        $this->guestUserMock->shouldReceive('first')->once()->andReturn(null); // No guest user found

        $this->transferActionMock->shouldNotReceive('execute');
        Auth::shouldNotReceive('login'); // Should not be called
        // No guestUser->delete() should be called either

        $result = $this->action->create($input);

        $this->assertSame($createdUserInstance, $result);
    }

    public function test_it_creates_user_and_transfers_data_if_guest_user_exists(): void
    {
        $input = $this->getValidInput(['email' => 'guest-exists@example.com']);

        $this->validatorMock->shouldReceive('validate')->once()->andReturn($input);
         Validator::shouldReceive('make')
            ->once()
            ->with($input, Mockery::any()) // Simpler check for validation rules for this test
            ->andReturn($this->validatorMock);


        $createdUserInstance = Mockery::mock(User::class);
        $this->userMock->shouldReceive('create')->once()->andReturn($createdUserInstance);

        $mockExistingGuestUser = Mockery::mock(GuestUser::class);
        $this->guestUserMock->shouldReceive('where')->once()->with('email', $input['email'])->andReturnSelf();
        $this->guestUserMock->shouldReceive('first')->once()->andReturn($mockExistingGuestUser);

        $this->transferActionMock->shouldReceive('execute')
            ->once()
            ->with($mockExistingGuestUser, $createdUserInstance);

        Auth::shouldReceive('login')->once()->with($createdUserInstance);
        $mockExistingGuestUser->shouldReceive('delete')->once();

        $result = $this->action->create($input);

        $this->assertSame($createdUserInstance, $result);
    }

    public function test_it_throws_validation_exception_for_invalid_input(): void
    {
        $this->expectException(ValidationException::class);
        $input = $this->getValidInput(['email' => 'not-an-email']);

        Validator::shouldReceive('make')
            ->once()
            ->with($input, Mockery::any())
            ->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validate')->once()->andThrow(ValidationException::withMessages(['email' => 'Invalid email.']));

        $this->userMock->shouldNotReceive('create');

        $this->action->create($input);
    }

    public function test_it_validates_terms_if_jetstream_feature_enabled(): void
    {
        Jetstream::shouldReceive('hasTermsAndPrivacyPolicyFeature')->once()->andReturn(true);
        $input = $this->getValidInput(['terms' => false]); // Terms not accepted

        Validator::shouldReceive('make')
            ->once()
            ->with($input, [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->testPasswordRules(),
                'terms'    => ['accepted', 'required'], // Now terms are required
            ])
            ->andReturn($this->validatorMock = Mockery::mock(['validate' => null])); // New mock for this specific validator call

        $this->validatorMock->shouldReceive('validate')->once()->andThrow(ValidationException::withMessages(['terms' => 'Terms must be accepted.']));
        $this->expectException(ValidationException::class);

        $this->action->create($input);
    }
}
