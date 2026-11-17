<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPortalTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomerUser(): User
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
        ]);

        Customer::create([
            'user_id' => $user->id,
            'customer_code' => 'CUST-'.str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '9876543210',
            'type' => 'b2c',
        ]);

        return $user;
    }

    public function test_guest_is_redirected_to_login_from_customer_portal(): void
    {
        $response = $this->get('/customer/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_customer_can_access_dashboard(): void
    {
        $user = $this->createCustomerUser();

        $response = $this->actingAs($user)->get('/customer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Customer ID');
    }

    public function test_customer_can_view_bookings_page(): void
    {
        $user = $this->createCustomerUser();

        $response = $this->actingAs($user)->get('/customer/bookings');

        $response->assertStatus(200);
        $response->assertSee('My Bookings');
    }

    public function test_customer_can_view_saved_passengers(): void
    {
        $user = $this->createCustomerUser();

        $response = $this->actingAs($user)->get('/customer/passengers');

        $response->assertStatus(200);
        $response->assertSee('Saved Travellers');
    }

    public function test_customer_can_view_visa_and_passport_status(): void
    {
        $user = $this->createCustomerUser();

        $response = $this->actingAs($user)->get('/customer/visa-passport');

        $response->assertStatus(200);
        $response->assertSee('International Visa Files');
    }

    public function test_customer_can_view_support_tickets(): void
    {
        $user = $this->createCustomerUser();

        $response = $this->actingAs($user)->get('/customer/support');

        $response->assertStatus(200);
        $response->assertSee('Raise Support Request');
    }
}
