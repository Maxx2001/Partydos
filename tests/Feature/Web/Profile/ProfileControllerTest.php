<?php

namespace Tests\Feature\Web\Profile;

use Domain\Profile\Actions\UpdateProfileAction;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // --- Edit Profile Page Tests ---
    public function test_can_view_profile_edit_page_when_authenticated(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('profile.edit'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('Profile/Edit')
                 ->where('user.id', $this->user->id)
                 ->where('user.name', $this->user->name)
                 ->where('user.email', $this->user->email)
                 ->has('user.profile_photo_url') // Jetstream appends this
        );
    }

    public function test_cannot_view_profile_edit_page_when_not_authenticated(): void
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));
    }

    // --- Update Profile Tests ---
    public function test_can_update_profile_with_valid_data_when_authenticated(): void
    {
        $this->actingAs($this->user);

        $mockUpdateAction = Mockery::mock(UpdateProfileAction::class);
        $mockUpdateAction->shouldReceive('execute')
            ->once()
            // ->withArgs(function (User $userArg, ProfileUpdateData $dataArg) use ($updateData) {
            //     return $userArg->is($this->user) &&
            //            $dataArg->name === $updateData['name'] &&
            //            $dataArg->email === $updateData['email'];
            // }) // Detailed argument checking
            ;
        $this->app->instance(UpdateProfileAction::class, $mockUpdateAction);

        $updateData = [
            'name' => 'New User Name',
            'email' => 'newemail@example.com',
            'profile_photo' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->post(route('profile.update'), $updateData);

        $response->assertRedirect(); // Redirects back
        // Further assertions could check if the user model in DB was actually updated if not mocking action fully
    }

    public function test_profile_update_validates_required_fields_when_authenticated(): void
    {
        $this->actingAs($this->user);
        // ProfileUpdateData DTO has validation rules

        $response = $this->post(route('profile.update'), ['name' => 'Test']);
        $response->assertSessionHasErrors('email');

        $response = $this->post(route('profile.update'), ['email' => 'test@example.com']);
        $response->assertSessionHasErrors('name');

        // Test invalid image
        $response = $this->post(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'profile_photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);
        $response->assertSessionHasErrors('profile_photo');
    }

    public function test_cannot_update_profile_when_not_authenticated(): void
    {
        $updateData = [
            'name' => 'New User Name',
            'email' => 'newemail@example.com',
        ];
        $response = $this->post(route('profile.update'), $updateData);
        $response->assertRedirect(route('login'));
    }
}
