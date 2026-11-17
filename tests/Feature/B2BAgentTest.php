<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Role;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2BAgentTest extends TestCase
{
    use RefreshDatabase;

    private function createAgentUser(): array
    {
        $role = Role::firstOrCreate(['slug' => 'agent'], ['name' => 'B2B Partner Agent', 'is_system' => true]);

        $user = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);
        $user->roles()->attach($role->id);

        $agent = Agent::create([
            'user_id' => $user->id,
            'agency_name' => 'Shree Travels & Logistics',
            'agency_code' => 'AGT-'.str_pad((string) $user->id, 3, '0', STR_PAD_LEFT),
            'owner_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '9876543210',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'commission_rate' => 7.5,
            'wallet_balance' => 50000.00,
            'credit_limit' => 100000.00,
            'status' => 'approved',
        ]);

        return [$user, $agent];
    }

    public function test_guest_is_redirected_from_agent_portal(): void
    {
        $response = $this->get('/agent/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_non_agent_customer_is_forbidden_or_redirected(): void
    {
        $customerUser = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
        ]);

        $response = $this->actingAs($customerUser)->get('/agent/dashboard');
        $response->assertStatus(403);
    }

    public function test_approved_agent_can_view_dashboard(): void
    {
        [$user, $agent] = $this->createAgentUser();

        $response = $this->actingAs($user)->get('/agent/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Shree Travels & Logistics');
        $response->assertSee('50,000');
    }

    public function test_agent_can_view_packages_with_b2b_rates(): void
    {
        [$user, $agent] = $this->createAgentUser();

        $country = Country::firstOrCreate(['code' => 'IN'], ['name' => 'India', 'phone_code' => '+91', 'is_active' => true]);
        $destination = Destination::create([
            'country_id' => $country->id,
            'name' => 'Himachal Hills',
            'slug' => 'himachal-hills',
            'type' => 'domestic',
            'is_active' => true,
        ]);

        TourPackage::create([
            'destination_id' => $destination->id,
            'title' => 'Shimla Manali Volvo Package',
            'slug' => 'shimla-manali-volvo-package',
            'package_code' => 'PKG-SHM-01',
            'duration_days' => 6,
            'duration_nights' => 5,
            'starting_price' => 18999,
            'adult_price' => 18999,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/agent/packages');

        $response->assertStatus(200);
        $response->assertSee('Shimla Manali Volvo Package');
        $response->assertSee('B2B Wholesale Tour Tariffs');
    }

    public function test_agent_can_view_wallet_and_commission_ledger(): void
    {
        [$user, $agent] = $this->createAgentUser();

        $walletResponse = $this->actingAs($user)->get('/agent/wallet');
        $walletResponse->assertStatus(200);
        $walletResponse->assertSee('Current Wallet Balance');

        $commResponse = $this->actingAs($user)->get('/agent/commissions');
        $commResponse->assertStatus(200);
        $commResponse->assertSee('Commission Statements');
    }
}
