<?php

namespace Tests\Unit\Domain\Profile\Actions;

use Domain\Profile\Actions\UpdateProfileAction;
use Domain\Profile\Actions\UpdateProfilePictureAction;
use Domain\Profile\DataTransferObjects\ProfileUpdateData;
use Domain\Users\Models\User;
use Illuminate\Http\Testing\File; // For creating a dummy file for profile_photo
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Notification;

class UpdateProfileActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected UpdateProfileAction $action;
    protected MockInterface | User $mockUser;
    protected MockInterface $mockUpdateProfilePictureAction; // For the newed up action
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateProfileAction();
        $this->mockUser = Mockery::mock(User::class);

        // Mock the action that is instantiated within the tested action
        $this->mockUpdateProfilePictureAction = Mockery::mock('overload:' . UpdateProfilePictureAction::class);
        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    public function test_it_updates_profile_and_calls_picture_update_action(): void
    {
        // Arrange
        // Create a dummy file for the profile_photo_path; its content doesn't matter for this unit test.
        $dummyImage = File::image('avatar.jpg');
        $profileData = new ProfileUpdateData(
            name: 'Updated Name',
            email: 'updated@example.com',
            profile_photo: $dummyImage
        );

        $this->mockUser->shouldReceive('update')
            ->once()
            ->with([
                'name' => $profileData->name,
                'email' => $profileData->email,
            ]);

        // Expect the execute method on *any* instance of UpdateProfilePictureAction
        $this->mockUpdateProfilePictureAction->shouldReceive('execute')
            ->once()
            ->with($this->mockUser, $profileData);
            // If UpdateProfilePictureAction had a constructor that needed mocking:
            // $instanceMock = Mockery::mock(UpdateProfilePictureAction::class);
            // $instanceMock->shouldReceive('execute')->once()->with($this->mockUser, $profileData);
            // $this->mockUpdateProfilePictureAction->shouldReceive('newInstance')->andReturn($instanceMock);


        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with('Profile updated')
            ->andReturn($mockNotificationChained);

        // Act
        $this->action->execute($this->mockUser, $profileData);

        // Assertions are handled by Mockery expectations
        $this->assertTrue(true);
    }
}
