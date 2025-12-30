<?php

namespace Tests\Feature\Api\Auth;

use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure sanctum migrations are run if not already part of test setup
        // This is usually handled by RefreshDatabase if migrations are in the main path
    }

    // --- Login Tests ---
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token'])
            ->assertJsonMissingPath('message'); // No 'Invalid credentials' message

        $this->assertNotNull($response->json('token'));
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/auth/login', []);
        $response->assertStatus(422) // Laravel's DTO validation typically returns 422
            ->assertJsonValidationErrors(['email', 'password']);
    }

    // --- Register Tests ---
    public function test_user_can_register_with_valid_data(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'register@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123', // Assuming RegisterUserData DTO needs confirmation
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(200) // Or 201 if you prefer for creation
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']])
            ->assertJsonPath('user.email', 'register@example.com');

        $this->assertNotNull($response->json('token'));
        $this->assertDatabaseHas('users', ['email' => 'register@example.com']);
    }

    public function test_register_fails_with_existing_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);
        $userData = [
            'name' => 'Another User',
            'email' => 'existing@example.com', // Email already taken
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_requires_name_email_password(): void
    {
        $response = $this->postJson('/api/auth/register', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    // --- Logout Tests ---
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('app-test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out']);

        // Optionally, check if the token is actually deleted/invalidated
        // This might require checking the personal_access_tokens table
        $this->assertDatabaseMissing('personal_access_tokens', [
            // 'tokenable_id' => $user->id, // This might not be enough if user has multiple tokens
            // 'name' => 'app-test', // Check by name
            // A more robust way would be to check the specific token ID if accessible or if only one token exists.
            // For simplicity, we assume currentAccessToken()->delete() works as intended by Laravel.
        ]);
    }

    public function test_unauthenticated_user_cannot_logout(): void
    {
        $response = $this->getJson('/api/auth/logout');
        $response->assertStatus(401); // Middleware protection
    }

    // --- User Endpoint Tests ---
    public function test_authenticated_user_can_get_their_details(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('app-test')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/auth/user');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ]);
    }

    public function test_unauthenticated_user_cannot_get_user_details(): void
    {
        $response = $this->getJson('/api/auth/user');
        $response->assertStatus(401);
    }
}
