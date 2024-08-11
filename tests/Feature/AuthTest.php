<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    
        $response->assertStatus(201)
            ->assertJsonStructure([
                'status_code',
                'message',
                'token',
            ]);
    
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }
    

    public function test_login_authenticates_user_and_returns_token()
{
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'john@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status_code',
            'message',
            'token',
        ]);
}


public function test_login_fails_with_invalid_credentials()
{
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'john@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'status_code' => 401,
            'message' => 'The provided credentials do not match.',
        ]);
}


public function test_logout_revokes_token()
{
    $user = User::factory()->create();
    $token = $user->createToken('API Token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully!',
        ]);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);
}

}
