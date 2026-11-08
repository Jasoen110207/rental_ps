<?php

namespace Tests\Feature;

use App\Models\PlaySession;
use App\Models\Shift;
use App\Models\Tv;
use App\Models\User;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_calculate_session_totals_and_finalize_checkout_idempotently(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $tv = Tv::factory()->create([
            'status' => 'playing',
            'price_per_hour' => 15000,
            'is_buzzer_on' => true,
        ]);

        $shift = Shift::create([
            'user_id' => $user->id,
            'start_time' => Carbon::now()->subHours(2),
            'status' => 'active',
            'initial_cash' => 100000,
            'total_revenue' => 0,
            'transactions_count' => 0,
        ]);

        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'customer_name' => 'Budi',
            'billing_type' => 'prepaid',
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now(),
            'status' => 'active',
            'rental_amount' => 30000,
            'fnb_amount' => 10000,
            'total_amount' => 40000,
            'payment_method' => 'cash',
        ]);

        $service = app(MidtransService::class);
        $totals = $service->calculateSessionTotals($session);

        $this->assertEquals(30000, $totals['rental_amount']);
        $this->assertEquals(10000, $totals['fnb_amount']);
        $this->assertEquals(40000, $totals['grand_total']);

        // First finalization
        $finalTotal = $service->finalizeSessionCheckout($session, 'qris', 'Catatan testing');
        $this->assertEquals(40000, $finalTotal);

        $session->refresh();
        $tv->refresh();
        $shift->refresh();

        $this->assertEquals('completed', $session->status);
        $this->assertEquals('qris', $session->payment_method);
        $this->assertEquals('available', $tv->status);
        $this->assertFalse((bool) $tv->is_buzzer_on);
        $this->assertEquals(40000, $shift->total_revenue);
        $this->assertEquals(1, $shift->transactions_count);

        // Second finalization (Idempotency test - should not double increment revenue)
        $secondTotal = $service->finalizeSessionCheckout($session, 'qris');
        $this->assertEquals(40000, $secondTotal);

        $shift->refresh();
        $this->assertEquals(40000, $shift->total_revenue);
        $this->assertEquals(1, $shift->transactions_count);
    }

    public function test_admin_checkout_route_finalizes_session_with_qris(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tv = Tv::factory()->create([
            'status' => 'playing',
            'price_per_hour' => 12000,
        ]);

        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $admin->id,
            'customer_name' => 'Joko',
            'billing_type' => 'prepaid',
            'start_time' => Carbon::now()->subHour(),
            'end_time' => Carbon::now(),
            'status' => 'active',
            'rental_amount' => 12000,
            'fnb_amount' => 0,
            'total_amount' => 12000,
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($admin)->post("/admin/rental/{$session->id}/checkout", [
            'payment_method' => 'qris',
            'notes' => 'Lunas via QRIS',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $session->refresh();
        $this->assertEquals('completed', $session->status);
        $this->assertEquals('qris', $session->payment_method);
    }

    public function test_midtrans_webhook_processes_valid_signature(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $tv = Tv::factory()->create(['status' => 'playing']);

        $orderId = 'RENTAL-TEST-12345';
        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'customer_name' => 'Andi',
            'billing_type' => 'prepaid',
            'start_time' => Carbon::now()->subHour(),
            'status' => 'active',
            'rental_amount' => 20000,
            'fnb_amount' => 0,
            'total_amount' => 20000,
            'payment_method' => 'cash',
            'midtrans_order_id' => $orderId,
        ]);

        $statusCode = '200';
        $grossAmount = '20000';
        $serverKey = config('midtrans.server_key');
        $signature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        $response = $this->postJson('/midtrans/webhook', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ]);

        $response->assertOk()
            ->assertJson(['message' => 'Webhook processed']);

        $session->refresh();
        $this->assertEquals('completed', $session->status);
        $this->assertEquals('qris', $session->payment_method);
    }
}
