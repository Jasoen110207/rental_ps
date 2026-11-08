<?php

namespace Tests\Feature;

use App\Models\CustomerRequest;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Tv;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerQrSessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_scanning_qr_with_active_session_redirects_to_session_token_and_allows_ordering(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $tv = Tv::factory()->create([
            'name' => 'PS 03 - VIP',
            'status' => 'playing',
            'price_per_hour' => 15000,
        ]);

        $product = Product::create([
            'name' => 'Indomie Goreng',
            'category' => 'food',
            'price' => 12000,
            'stock' => 10,
            'is_available' => true,
        ]);

        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'customer_name' => 'Rizky',
            'billing_type' => 'prepaid',
            'start_time' => Carbon::now()->subMinutes(15),
            'end_time' => Carbon::now()->addMinutes(45),
            'status' => 'active',
            'rental_amount' => 15000,
            'fnb_amount' => 0,
            'total_amount' => 15000,
            'payment_method' => 'cash',
        ]);

        $this->assertNotEmpty($session->session_token);
        $this->assertNotEquals($tv->access_token, $session->session_token);

        // 1. Scan physical table QR (/scan/{tv_access_token})
        $scanResponse = $this->get(route('customer.scan', ['token' => $tv->access_token]));
        $scanResponse->assertRedirect(route('customer.index', ['token' => $session->session_token]));

        // 2. Follow redirect to customer session page
        $indexResponse = $this->get(route('customer.index', ['token' => $session->session_token]));
        $indexResponse->assertOk();
        $indexResponse->assertSee('PS 03 - VIP');

        // 3. Submit F&B order using session_token
        $orderResponse = $this->post(route('customer.order-food', ['token' => $session->session_token]), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
            'note' => 'Pedas',
        ]);

        $orderResponse->assertRedirect();
        $this->assertDatabaseHas('customer_requests', [
            'tv_id' => $tv->id,
            'type' => 'order_food',
            'status' => 'pending',
        ]);
    }

    public function test_direct_access_without_scanning_qr_gate_is_blocked(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $tv = Tv::factory()->create(['status' => 'playing']);

        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'billing_type' => 'prepaid',
            'start_time' => Carbon::now()->subMinutes(10),
            'end_time' => Carbon::now()->addMinutes(50),
            'status' => 'active',
            'rental_amount' => 15000,
            'fnb_amount' => 0,
            'total_amount' => 15000,
            'payment_method' => 'cash',
        ]);

        // Attempt to open /customer/{session_token} without scanning /scan/{tv_token} first
        $response = $this->get(route('customer.index', ['token' => $session->session_token]));
        $response->assertStatus(403);
        $response->assertSee('SESI RENTAL TELAH BERAKHIR');
    }

    public function test_reopening_from_browser_history_after_checkout_is_blocked_even_when_new_customer_plays(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $tv = Tv::factory()->create(['status' => 'playing', 'price_per_hour' => 15000]);

        $product = Product::create([
            'name' => 'Es Teh Manis',
            'category' => 'drink',
            'price' => 5000,
            'stock' => 20,
            'is_available' => true,
        ]);

        // Sesi Pelanggan A
        $sessionA = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'customer_name' => 'Pelanggan A',
            'billing_type' => 'prepaid',
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->subHour(),
            'status' => 'active',
            'rental_amount' => 15000,
            'fnb_amount' => 0,
            'total_amount' => 15000,
            'payment_method' => 'cash',
        ]);

        // Pelanggan A scan QR meja saat masih aktif
        $this->get(route('customer.scan', ['token' => $tv->access_token]))
            ->assertRedirect(route('customer.index', ['token' => $sessionA->session_token]));

        // Sesi Pelanggan A selesai (Checkout)
        $sessionA->update(['status' => 'completed', 'end_time' => Carbon::now()]);

        // Pelanggan B mulai main di meja TV yang sama
        $sessionB = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $kasir->id,
            'customer_name' => 'Pelanggan B',
            'billing_type' => 'prepaid',
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addHours(2),
            'status' => 'active',
            'rental_amount' => 30000,
            'fnb_amount' => 0,
            'total_amount' => 30000,
            'payment_method' => 'cash',
        ]);

        // Pelanggan A dari rumah membuka link dari History Browser miliknya ($sessionA->session_token)
        $historyPageResponse = $this->get(route('customer.index', ['token' => $sessionA->session_token]));
        $historyPageResponse->assertStatus(403);
        $historyPageResponse->assertSee('SESI RENTAL TELAH BERAKHIR');

        // Pelanggan A mencoba POST order fiktif menggunakan token history miliknya
        $this->post(route('customer.order-food', ['token' => $sessionA->session_token]), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ]);

        // Pastikan TIDAK ADA order fiktif yang masuk ke database
        $this->assertEquals(0, CustomerRequest::count());

        // Status polling API untuk sesi lama juga mengembalikan expired = true
        $this->getJson(route('customer.status', ['token' => $sessionA->session_token]))
            ->assertOk()
            ->assertJson(['expired' => true, 'session' => null]);
    }
}
