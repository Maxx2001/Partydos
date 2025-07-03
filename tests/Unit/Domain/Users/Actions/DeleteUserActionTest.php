<?php

namespace Tests\Unit\Domain\Users\Actions;

use Domain\Users\Actions\DeleteUserAction;
use Domain\Users\Models\User;
use Illuminate\Support\Collection; // For mocking the tokens collection
use Laravel\Sanctum\PersonalAccessToken; // Assuming tokens are Sanctum tokens
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class DeleteUserActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected DeleteUserAction $action;
    protected MockInterface | User $mockUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DeleteUserAction();
        $this->mockUser = Mockery::mock(User::class);
    }

    public function test_it_deletes_user_profile_photo_tokens_and_user_record(): void
    {
        // Arrange
        $this->mockUser->shouldReceive('deleteProfilePhoto')->once();

        // Mock tokens collection
        $mockToken1 = Mockery::mock(PersonalAccessToken::class);
        $mockToken1->shouldReceive('delete')->once();
        $mockToken2 = Mockery::mock(PersonalAccessToken::class);
        $mockToken2->shouldReceive('delete')->once();
        $mockTokensCollection = new Collection([$mockToken1, $mockToken2]);
        $this->mockUser->shouldReceive('getAttribute')->with('tokens')->andReturn($mockTokensCollection);
        // Or if 'tokens' is a relationship:
        // $this->mockUser->shouldReceive('tokens')->andReturn($mockTokensCollection);


        $this->mockUser->shouldReceive('delete')->once(); // The main user delete (soft delete)

        // Act
        $this->action->delete($this->mockUser);

        // Assertions are handled by Mockery expectations
        $this->assertTrue(true);
    }

    public function test_it_handles_user_with_no_tokens(): void
    {
        // Arrange
        $this->mockUser->shouldReceive('deleteProfilePhoto')->once();

        $mockTokensCollection = new Collection([]); // Empty collection
        $this->mockUser->shouldReceive('getAttribute')->with('tokens')->andReturn($mockTokensCollection);

        $this->mockUser->shouldReceive('delete')->once();

        // Act
        $this->action->delete($this->mockUser);

        // Assertions handled by Mockery
        $this->assertTrue(true); // No errors, delete was called on user.
    }
}
