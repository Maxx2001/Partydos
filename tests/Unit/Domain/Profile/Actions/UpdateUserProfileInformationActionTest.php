<?php

namespace Tests\Unit\Domain\Profile\Actions;

use Domain\Profile\Actions\UpdateUserProfileInformationAction;
use Domain\Users\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class UpdateUserProfileInformationActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected UpdateUserProfileInformationAction $action;
    protected MockInterface | User $mockUser;
    protected $validatorMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateUserProfileInformationAction();
        $this->mockUser = Mockery::mock(User::class)->makePartial(); // makePartial for property access and instanceof checks
        $this->mockUser->id = 1; // Default user ID for Rule::unique()->ignore()

        // Default validator mock, can be overridden
        $this->validatorMock = Mockery::mock(['validateWithBag' => null]);
        Validator::shouldReceive('make')->andReturn($this->validatorMock)->byDefault();
    }

    private function commonValidationExpectation(array $input, int $userIdToIgnore): void
    {
        Validator::shouldReceive('make')
            ->once()
            ->with($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userIdToIgnore)],
                'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            ])
            ->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validateWithBag')->once()->with('updateProfileInformation');
    }

    public function test_updates_name_and_email_when_email_not_changed_and_no_photo(): void
    {
        $input = ['name' => 'New Name', 'email' => 'current@example.com'];
        $this->mockUser->email = 'current@example.com'; // Email is not changing
        $this->mockUser->shouldNotBeInstanceOf(MustVerifyEmail::class); // Assume not MustVerifyEmail for this case

        $this->commonValidationExpectation($input, $this->mockUser->id);

        $this->mockUser->shouldReceive('forceFill')
            ->once()
            ->with(['name' => $input['name'], 'email' => $input['email']])
            ->andReturnSelf();
        $this->mockUser->shouldReceive('save')->once();
        $this->mockUser->shouldNotReceive('updateProfilePhoto');
        $this->mockUser->shouldNotReceive('sendEmailVerificationNotification');

        $this->action->update($this->mockUser, $input);
        $this->assertTrue(true); // Assertions by Mockery
    }

    public function test_updates_photo_when_provided(): void
    {
        $mockPhoto = Mockery::mock(UploadedFile::class);
        $input = ['name' => 'Name', 'email' => 'current@example.com', 'photo' => $mockPhoto];
        $this->mockUser->email = 'current@example.com';
        $this->mockUser->shouldNotBeInstanceOf(MustVerifyEmail::class);

        $this->commonValidationExpectation($input, $this->mockUser->id);

        $this->mockUser->shouldReceive('updateProfilePhoto')->once()->with($mockPhoto);
        $this->mockUser->shouldReceive('forceFill')->once()->andReturnSelf(); // For name/email
        $this->mockUser->shouldReceive('save')->once();

        $this->action->update($this->mockUser, $input);
        $this->assertTrue(true);
    }

    public function test_updates_email_and_sends_verification_if_user_must_verify_email(): void
    {
        $input = ['name' => 'Name', 'email' => 'new@example.com'];
        $this->mockUser->email = 'old@example.com'; // Email is changing

        // Mock user to be an instance of MustVerifyEmail
        $this->mockUser = Mockery::mock(User::class . ', ' . MustVerifyEmail::class)->makePartial();
        $this->mockUser->id = 1;
        $this->mockUser->email = 'old@example.com';


        $this->commonValidationExpectation($input, $this->mockUser->id);

        $this->mockUser->shouldReceive('forceFill')
            ->once()
            ->with(['name' => $input['name'], 'email' => $input['email'], 'email_verified_at' => null])
            ->andReturnSelf();
        $this->mockUser->shouldReceive('save')->once();
        $this->mockUser->shouldReceive('sendEmailVerificationNotification')->once();
        $this->mockUser->shouldNotReceive('updateProfilePhoto');

        $this->action->update($this->mockUser, $input);
        $this->assertTrue(true);
    }

    public function test_updates_email_without_verification_if_user_not_must_verify_email(): void
    {
        $input = ['name' => 'Name', 'email' => 'new@example.com'];
        $this->mockUser->email = 'old@example.com'; // Email is changing
        // Ensure this mockUser is NOT an instance of MustVerifyEmail for this test
        // This is tricky with Mockery's default behavior for `instanceof`.
        // The initial mockUser setup does not implement MustVerifyEmail.
        // We can explicitly assert this if needed, or rely on the `if` condition in the action.

        $this->commonValidationExpectation($input, $this->mockUser->id);

        $this->mockUser->shouldReceive('forceFill')
            ->once()
            ->with(['name' => $input['name'], 'email' => $input['email']]) // email_verified_at not nulled
            ->andReturnSelf();
        $this->mockUser->shouldReceive('save')->once();
        $this->mockUser->shouldNotReceive('sendEmailVerificationNotification');

        $this->action->update($this->mockUser, $input);
        $this->assertTrue(true);
    }

    public function test_throws_validation_exception_for_invalid_input(): void
    {
        $this->expectException(ValidationException::class);
        $input = ['name' => '', 'email' => 'not-an-email']; // Invalid data

        Validator::shouldReceive('make')->once()->andReturn($this->validatorMock);
        $this->validatorMock->shouldReceive('validateWithBag')
            ->once()
            ->with('updateProfileInformation')
            ->andThrow(ValidationException::withMessages(['name' => 'Name is required.']));

        $this->mockUser->shouldNotReceive('updateProfilePhoto');
        $this->mockUser->shouldNotReceive('forceFill');
        $this->mockUser->shouldNotReceive('save');
        $this->mockUser->shouldNotReceive('sendEmailVerificationNotification');

        $this->action->update($this->mockUser, $input);
    }
}
