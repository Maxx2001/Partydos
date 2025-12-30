<?php

namespace Tests\Unit\Domain\Profile\Actions;

use Domain\Profile\Actions\UpdateProfilePictureAction;
use Domain\Profile\DataTransferObjects\ProfileUpdateData;
use Domain\Users\Models\User;
use Illuminate\Http\Testing\File; // For creating a dummy UploadedFile
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class UpdateProfilePictureActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected UpdateProfilePictureAction $action;
    protected MockInterface | User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateProfilePictureAction();
        $this->mockUser = Mockery::mock(User::class);
    }

    public function test_it_updates_profile_photo_if_provided(): void
    {
        // Arrange
        $dummyImage = File::image('new_avatar.jpg'); // Creates an UploadedFile instance
        $profileData = new ProfileUpdateData(
            name: 'Any Name', // Required by DTO, but not used by this action directly
            email: 'any@example.com', // Required by DTO
            profile_photo: $dummyImage
        );

        $this->mockUser->shouldReceive('updateProfilePhoto')
            ->once()
            ->with($dummyImage);

        // Act
        $this->action->execute($this->mockUser, $profileData);

        // Assertions are handled by Mockery expectations
        $this->assertTrue(true);
    }

    public function test_it_does_nothing_if_profile_photo_is_not_provided(): void
    {
        // Arrange
        $profileData = new ProfileUpdateData(
            name: 'Any Name',
            email: 'any@example.com',
            profile_photo: null // No photo provided
        );

        $this->mockUser->shouldNotReceive('updateProfilePhoto');

        // Act
        $this->action->execute($this->mockUser, $profileData);

        // Assertions are handled by Mockery expectations
        $this->assertTrue(true);
    }
}
