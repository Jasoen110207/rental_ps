<?php

namespace Tests\Feature;

use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Tv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3FeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_login_success_and_failure(): void
    {
        $user = User::create([
            'name' => 'Kasir Auth',
            'email' => 'kasirauth@rental.com',
            'password' => bcrypt('password123'),
            'role' => 'kasir',
        ]);

        // Wrong password
        $failResponse = $this->postJson('/api/login', [
            'email' => 'kasirauth@rental.com',
            'password' => 'wrong',
        ]);
        $failResponse->assertStatus(401);

        // Success
        $successResponse = $this->postJson('/api/login', [
            'email' => 'kasirauth@rental.com',
            'password' => 'password123',
        ]);
        $successResponse->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_customer_request_public_flow_and_approval(): void
    {
        $kasir = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasir@rental.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $tv = Tv::create([
            'name' => 'Meja QR',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'playing',
        ]);

        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'billing_type' => 'prepaid',
            'duration_minutes' => 60,
            'start_time' => now(),
            'status' => 'active',
        ]);

        $product = Product::create([
            'name' => 'Kopi Hitam',
            'price' => 5000,
            'stock' => 10,
        ]);

        // Public Customer Store Request
        $requestResponse = $this->postJson('/api/customer-requests', [
            'tv_id' => $tv->id,
            'type' => 'order_food',
            'payload' => [
                'product_id' => $product->id,
                'quantity' => 2,
            ],
        ]);
        $requestResponse->assertStatus(201);
        $requestId = $requestResponse->json('data.id');

        // Kasir List Customer Requests
        $listResponse = $this->actingAs($kasir)->getJson('/api/customer-requests');
        $listResponse->assertStatus(200)
            ->assertJsonCount(1, 'data');

        // Kasir Approve Request
        $approveResponse = $this->actingAs($kasir)->patchJson("/api/customer-requests/{$requestId}/approve");
        $approveResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'approved');

        $this->assertEquals(8, $product->fresh()->stock);
    }

    public function test_shift_management_flow(): void
    {
        $kasir = User::create([
            'name' => 'Kasir Shift',
            'email' => 'shift@rental.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        // Start Shift
        $startResponse = $this->actingAs($kasir)->postJson('/api/shifts');
        $startResponse->assertStatus(201)
            ->assertJsonPath('data.status', 'active');

        // Start Duplicate Shift Attempt
        $dupResponse = $this->actingAs($kasir)->postJson('/api/shifts');
        $dupResponse->assertStatus(422);

        // End Shift
        $endResponse = $this->actingAs($kasir)->putJson('/api/shifts');
        $endResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'closed');
    }
}
