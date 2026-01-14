<?php

namespace Tests\Feature;

use Domain\Profile\Models\Friendship;
use Domain\Profile\Models\GuestbookEntry;
use Domain\Profile\Models\Profile;
use Domain\Profile\Models\ProfilePhoto;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileExpansionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_is_visible(): void
    {
        $user = User::factory()->create();
        Profile::factory()->for($user)->create([
            'privacy_profile' => 'public',
        ]);

        $this->get("/u/{$user->id}")
            ->assertOk();
    }

    public function test_friends_only_profile_visibility(): void
    {
        $owner = User::factory()->create();
        Profile::factory()->for($owner)->create([
            'privacy_profile' => 'friends',
        ]);

        $friend = User::factory()->create();
        Friendship::factory()->create([
            'requester_user_id' => $friend->id,
            'addressee_user_id' => $owner->id,
            'status' => 'accepted',
        ]);

        $stranger = User::factory()->create();

        $this->actingAs($friend)
            ->get("/u/{$owner->id}")
            ->assertOk();

        $this->actingAs($stranger)
            ->get("/u/{$owner->id}")
            ->assertForbidden();
    }

    public function test_guestbook_privacy_respected(): void
    {
        $owner = User::factory()->create();
        Profile::factory()->for($owner)->create([
            'privacy_guestbook' => 'friends',
        ]);

        $stranger = User::factory()->create();

        $this->actingAs($stranger)
            ->post("/u/{$owner->id}/guestbook", ['body' => 'Hey'])
            ->assertForbidden();

        $friend = User::factory()->create();
        Friendship::factory()->create([
            'requester_user_id' => $friend->id,
            'addressee_user_id' => $owner->id,
            'status' => 'accepted',
        ]);

        $this->actingAs($friend)
            ->post("/u/{$owner->id}/guestbook", ['body' => 'Party time'])
            ->assertRedirect();

        $this->assertDatabaseHas(GuestbookEntry::class, [
            'profile_user_id' => $owner->id,
            'author_user_id' => $friend->id,
            'body' => 'Party time',
        ]);
    }

    public function test_friend_request_accept_flow(): void
    {
        $requester = User::factory()->create();
        $addressee = User::factory()->create();

        $this->actingAs($requester)
            ->post("/u/{$addressee->id}/friend-request")
            ->assertRedirect();

        $friendship = Friendship::query()->first();
        $this->assertNotNull($friendship);
        $this->assertSame('pending', $friendship->status);

        $this->actingAs($addressee)
            ->post("/friend-request/{$friendship->id}/accept")
            ->assertRedirect();

        $this->assertDatabaseHas(Friendship::class, [
            'id' => $friendship->id,
            'status' => 'accepted',
        ]);
    }

    public function test_photo_upload_works(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/profile/photos', [
                'photo' => UploadedFile::fake()->image('profile.jpg'),
                'caption' => 'Dance floor',
            ]);

        $response->assertRedirect();

        $photo = ProfilePhoto::query()->first();
        $this->assertNotNull($photo);

        Storage::disk('public')->assertExists($photo->path);
    }
}
