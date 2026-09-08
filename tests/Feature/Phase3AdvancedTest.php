<?php

namespace Tests\Feature;

use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Tv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3AdvancedTest extends TestCase
{
    use RefreshDatabase;

    private function makeKasir(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Kasir Advanced',
            'email' => 'kasir.advanced@rental.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ], $overrides));
    }

    private function makeProduct(int $stock = 10): Product
    {
        return Product::create([
            'name' => 'Ayam Goreng',
            'price' => 15000,
            'stock' => $stock,
        ]);
    }

    public function test_customer_request_reject_flow(): void
    {
        $kasir = $this->makeKasir();
        $tv = Tv::create([
            'name' => 'Meja Reject',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'playing',
        ]);

        PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'billing_type' => 'postpaid',
            'start_time' => now(),
            'status' => 'active',
        ]);

        $product = $this->makeProduct();

        $store = $this->postJson('/api/customer-requests', [
            'tv_id' => $tv->id,
            'type' => 'order_food',
            'payload' => ['product_id' => $product->id, 'quantity' => 2],
        ]);
        $store->assertStatus(201);
        $requestId = $store->json('data.id');

        $reject = $this->actingAs($kasir)->patchJson("/api/customer-requests/{$requestId}/reject");
        $reject->assertStatus(200)->assertJsonPath('data.status', 'rejected');

        $this->assertEquals(10, $product->fresh()->stock);
    }

    public function test_customer_request_for_idle_tv_is_rejected(): void
    {
        $tv = Tv::create([
            'name' => 'Meja Kosong',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'available',
        ]);

        $response = $this->postJson('/api/customer-requests', [
            'tv_id' => $tv->id,
            'type' => 'add_time',
            'payload' => ['duration_minutes' => 60],
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_approve_non_pending_request(): void
    {
        $kasir = $this->makeKasir();
        $tv = Tv::create([
            'name' => 'Meja Dua Kali',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'playing',
        ]);

        PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'billing_type' => 'postpaid',
            'start_time' => now(),
            'status' => 'active',
        ]);

        $product = $this->makeProduct();

        $store = $this->postJson('/api/customer-requests', [
            'tv_id' => $tv->id,
            'type' => 'order_food',
            'payload' => ['product_id' => $product->id, 'quantity' => 1],
        ]);
        $requestId = $store->json('data.id');

        $this->actingAs($kasir)->patchJson("/api/customer-requests/{$requestId}/approve")->assertStatus(200);

        $retry = $this->actingAs($kasir)->patchJson("/api/customer-requests/{$requestId}/approve");
        $retry->assertStatus(422);
    }

    public function test_admin_only_report_endpoint_blocks_kasir(): void
    {
        $kasir = $this->makeKasir();
        $admin = $this->makeKasir(['email' => 'admin.advanced@rental.com', 'role' => 'admin']);

        $forbidden = $this->actingAs($kasir)->getJson('/api/reports/revenue');
        $forbidden->assertStatus(403);

        $allowed = $this->actingAs($admin)->getJson('/api/reports/revenue');
        $allowed->assertStatus(200)->assertJsonStructure(['period', 'summary']);
    }

    public function test_prepaid_session_requires_duration_minutes(): void
    {
        $kasir = $this->makeKasir();
        $tv = Tv::create([
            'name' => 'Meja Prepaid',
            'type' => 'ps5',
            'price_per_hour' => 15000,
            'status' => 'available',
        ]);

        $response = $this->actingAs($kasir)->postJson('/api/play-sessions', [
            'tv_id' => $tv->id,
            'billing_type' => 'prepaid',
        ]);

        $response->assertStatus(422);

        $this->assertEquals('available', $tv->fresh()->status);
    }

    public function test_show_prepaid_session_returns_time_info(): void
    {
        $kasir = $this->makeKasir();
        $tv = Tv::create([
            'name' => 'Meja Timer',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'available',
        ]);

        $store = $this->actingAs($kasir)->postJson('/api/play-sessions', [
            'tv_id' => $tv->id,
            'billing_type' => 'prepaid',
            'duration_minutes' => 60,
        ]);
        $store->assertStatus(201);
        $sessionId = $store->json('data.id');

        $show = $this->actingAs($kasir)->getJson("/api/play-sessions/{$sessionId}");
        $show->assertStatus(200)
            ->assertJsonStructure(['data', 'time_info' => ['remaining_minutes', 'is_expired']])
            ->assertJsonPath('time_info.is_expired', false);
    }

    public function test_check_prepaid_sessions_command_ends_expired_sessions(): void
    {
        $kasir = $this->makeKasir();
        $tv = Tv::create([
            'name' => 'Meja Expired',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'playing',
        ]);

        PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'billing_type' => 'prepaid',
            'duration_minutes' => 30,
            'start_time' => now()->subMinutes(35),
            'status' => 'active',
        ]);

        $this->artisan('app:check-prepaid-sessions')->assertSuccessful();

        $this->assertEquals('completed', PlaySession::first()->status);
        $this->assertEquals('available', $tv->fresh()->status);
        $this->assertTrue((bool) $tv->fresh()->is_buzzer_on);
    }
}
