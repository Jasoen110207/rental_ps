<?php

namespace Tests\Feature;

use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Shift;
use App\Models\Tv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Issue17FeatureTest extends TestCase
{
    use RefreshDatabase;

    private function createTv(array $attributes = []): Tv
    {
        return Tv::create(array_merge([
            'name' => 'PS 01 - Test Arena',
            'type' => 'ps5',
            'price_per_hour' => 20000,
            'status' => 'available',
        ], $attributes));
    }

    public function test_tv_model_generates_access_token_automatically(): void
    {
        $tv = $this->createTv();

        $this->assertNotEmpty($tv->access_token);
        $this->assertSame(32, strlen($tv->access_token));
    }

    public function test_customer_pages_require_valid_access_token(): void
    {
        $tv = $this->createTv();

        // Mengakses dengan token valid -> OK
        $response = $this->get(route('customer.index', ['token' => $tv->access_token]));
        $response->assertOk();

        // Mengakses order dengan token valid -> OK
        $orderResponse = $this->get(route('customer.order', ['token' => $tv->access_token]));
        $orderResponse->assertOk();

        // Mengakses dengan ID numerik biasa atau token ngawur -> 404
        $invalidResponse = $this->get('/customer/'.$tv->id);
        $invalidResponse->assertNotFound();

        $invalidOrderResponse = $this->get('/customer/order/token-palsu-123');
        $invalidOrderResponse->assertNotFound();
    }

    public function test_customer_can_request_actions_with_valid_access_token(): void
    {
        $tv = $this->createTv(['status' => 'playing']);
        $user = User::factory()->create(['role' => 'kasir']);
        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'billing_type' => 'prepaid',
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => 'active',
            'rental_amount' => 40000,
            'fnb_amount' => 0,
            'total_amount' => 40000,
        ]);

        $product = Product::create([
            'name' => 'Es Teh Manis',
            'category' => 'drink',
            'price' => 5000,
            'stock' => 10,
            'is_available' => true,
        ]);

        // Request Tambah Waktu
        $timeResponse = $this->post(route('customer.add-time', ['token' => $tv->access_token]), [
            'duration_hours' => 1,
            'note' => 'Tambah 1 jam',
        ]);
        $timeResponse->assertSessionHas('success');
        $this->assertDatabaseHas('customer_requests', [
            'tv_id' => $tv->id,
            'type' => 'add_time',
            'status' => 'pending',
        ]);

        // Request F&B
        $foodResponse = $this->post(route('customer.order-food', ['token' => $tv->access_token]), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
            'note' => 'Gula sedikit',
        ]);
        $foodResponse->assertSessionHas('success');
        $this->assertDatabaseHas('customer_requests', [
            'tv_id' => $tv->id,
            'type' => 'order_food',
            'status' => 'pending',
        ]);

        // API Status Polling
        $statusResponse = $this->get(route('customer.status', ['token' => $tv->access_token]));
        $statusResponse->assertOk()
            ->assertJsonPath('tv.name', $tv->name)
            ->assertJsonPath('session.id', $session->id);
    }

    public function test_qr_generator_in_unit_controller_uses_access_token(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tv = $this->createTv();

        $response = $this->actingAs($admin)->get(route('admin.units.qr', $tv->id));
        $response->assertOk();
        $response->assertViewHas('customerUrl', route('customer.index', ['token' => $tv->access_token]));
    }

    public function test_payroll_strictly_calculates_50k_per_shift(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir', 'name' => 'Kasir Joko']);

        // Buat 3 shift untuk kasir pada bulan ini
        Shift::create([
            'user_id' => $kasir->id,
            'shift_type' => 'pagi',
            'start_time' => now()->startOfMonth()->addDays(1)->setTime(8, 0),
            'end_time' => now()->startOfMonth()->addDays(1)->setTime(15, 0),
            'starting_cash' => 200000,
            'total_revenue' => 100000,
            'status' => 'closed',
        ]);
        Shift::create([
            'user_id' => $kasir->id,
            'shift_type' => 'sore',
            'start_time' => now()->startOfMonth()->addDays(2)->setTime(15, 0),
            'end_time' => now()->startOfMonth()->addDays(2)->setTime(21, 0),
            'starting_cash' => 200000,
            'total_revenue' => 120000,
            'status' => 'closed',
        ]);
        Shift::create([
            'user_id' => $kasir->id,
            'shift_type' => 'pagi',
            'start_time' => now()->startOfMonth()->addDays(3)->setTime(8, 0),
            'end_time' => now()->startOfMonth()->addDays(3)->setTime(15, 0),
            'starting_cash' => 200000,
            'total_revenue' => 80000,
            'status' => 'closed',
        ]);

        $currentMonth = now()->format('Y-m');

        $response = $this->actingAs($admin)->get(route('admin.payroll.index', [
            'kasir_id' => $kasir->id,
            'month' => $currentMonth,
        ]));

        $response->assertOk();
        $kasirView = $response->viewData('kasir');

        $this->assertSame(3, $kasirView['total_shifts']);
        $this->assertSame(50000, $kasirView['shift_rate']);
        // 3 shift x 50.000 = 150.000 (tidak ada gaji pokok 2.500.000)
        $this->assertSame(150000, $kasirView['net_salary']);
    }

    public function test_payroll_confirm_payment_creates_record_and_redirects_properly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir', 'name' => 'Kasir Siti']);

        Shift::create([
            'user_id' => $kasir->id,
            'shift_type' => 'pagi',
            'start_time' => now()->startOfMonth()->addDays(1)->setTime(8, 0),
            'end_time' => now()->startOfMonth()->addDays(1)->setTime(15, 0),
            'starting_cash' => 200000,
            'total_revenue' => 100000,
            'status' => 'closed',
        ]);

        $currentMonth = now()->format('Y-m');

        $response = $this->actingAs($admin)->post(route('admin.payroll.confirm', [
            'kasir_id' => $kasir->id,
            'month' => $currentMonth,
        ]));

        $response->assertRedirect(route('admin.payroll.index', [
            'kasir_id' => $kasir->id,
            'month' => $currentMonth,
        ]));

        $this->assertDatabaseHas('payrolls', [
            'user_id' => $kasir->id,
            'period_month' => $currentMonth,
            'work_days' => 1,
            'net_salary' => 50000,
            'status' => 'paid',
        ]);
    }

    public function test_cashier_can_start_shift_with_shift_type_and_pin(): void
    {
        $kasir = User::factory()->create([
            'role' => 'kasir',
            'pin' => '123456',
        ]);

        $response = $this->actingAs($kasir)->post(route('kasir.shifts.start'), [
            'shift_type' => 'pagi',
            'starting_cash' => 250000,
            'pin' => '123456',
            'notes' => 'Mulai shift pagi',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('shifts', [
            'user_id' => $kasir->id,
            'shift_type' => 'pagi',
            'starting_cash' => 250000,
            'status' => 'active',
        ]);
    }

    public function test_cashier_must_provide_reported_revenue_and_leftover_cash_on_end_shift(): void
    {
        $kasir = User::factory()->create([
            'role' => 'kasir',
            'pin' => '123456',
        ]);

        $shift = Shift::create([
            'user_id' => $kasir->id,
            'shift_type' => 'pagi',
            'start_time' => now()->subHours(6),
            'starting_cash' => 200000,
            'total_revenue' => 0,
            'reported_revenue' => 0,
            'leftover_cash' => 0,
            'transactions_count' => 0,
            'status' => 'active',
        ]);

        $response = $this->actingAs($kasir)->post(route('kasir.shifts.end', $shift->id), [
            'reported_revenue' => 450000,
            'leftover_cash' => 200000,
            'notes' => 'Omset shift pagi aman, sisa modal 200k',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('shifts', [
            'id' => $shift->id,
            'reported_revenue' => 450000,
            'leftover_cash' => 200000,
            'status' => 'closed',
        ]);
    }

    public function test_subsequent_shift_view_receives_suggested_starting_cash_from_leftover_cash(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        // Shift sebelumnya ditutup dengan uang tinggalan 350.000
        Shift::create([
            'user_id' => $kasir->id,
            'shift_type' => 'sore',
            'start_time' => now()->subDay()->setTime(15, 0),
            'end_time' => now()->subDay()->setTime(21, 0),
            'starting_cash' => 200000,
            'total_revenue' => 300000,
            'reported_revenue' => 300000,
            'leftover_cash' => 350000,
            'transactions_count' => 5,
            'status' => 'closed',
        ]);

        $response = $this->actingAs($kasir)->get(route('kasir.sift'));
        $response->assertOk();
        $response->assertViewHas('suggestedStartingCash', 350000);
    }
}
