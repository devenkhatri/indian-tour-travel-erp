<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Create Your Account');
    }

    public function test_customer_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Aarav Patel',
            'email' => 'aarav@example.com',
            'phone' => '9876543210',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'aarav@example.com',
            'role' => 'customer',
        ]);
        $this->assertDatabaseHas('customers', [
            'email' => 'aarav@example.com',
        ]);
    }

    public function test_user_can_authenticate_and_redirect_based_on_role(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_agent_authenticates_and_redirects_to_agent_portal(): void
    {
        $agent = User::factory()->create([
            'email' => 'agent@test.com',
            'password' => Hash::make('password123'),
            'role' => 'agent',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'agent@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/agent/dashboard');
        $this->assertAuthenticatedAs($agent);
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => Hash::make('correct-password'),
        ]);

        $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
