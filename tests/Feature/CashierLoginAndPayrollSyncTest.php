<?php

namespace Tests\Feature;

use App\Models\Payroll;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierLoginAndPayrollSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creates_new_cashier_and_cashier_can_login_with_pin(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@rental.test',
            'role' => 'admin',
            'password' => 'secret123',
            'pin' => '123456',
        ]);

        $this->actingAs($admin);

        // Admin membuat kasir baru
        $response = $this->post(route('admin.users.store'), [
            'name' => 'Kasir Baru',
            'email' => 'kasirbaru@rental.test',
            'password' => 'password123',
            'pin' => '654321',
            'role' => 'kasir',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'kasirbaru@rental.test',
            'role' => 'kasir',
        ]);

        // Logout admin
        $this->post(route('logout'));
        $this->assertGuest();

        // Kasir baru mencoba login dengan email, password, dan PIN yang baru dibuat
        $loginResponse = $this->post(route('login'), [
            'email' => 'kasirbaru@rental.test',
            'password' => 'password123',
            'pin' => '654321',
        ]);

        $loginResponse->assertRedirect(route('kasir.dashboard'));
        $this->assertAuthenticated();
        $this->assertSame('kasirbaru@rental.test', auth()->user()->email);
    }

    public function test_cashier_login_with_wrong_pin_fails(): void
    {
        User::create([
            'name' => 'Kasir Siska',
            'email' => 'siska@rental.test',
            'role' => 'kasir',
            'password' => 'password123',
            'pin' => bcrypt('998877'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'siska@rental.test',
            'password' => 'password123',
            'pin' => '112233', // PIN salah
        ]);

        $response->assertSessionHasErrors('pin');
        $this->assertGuest();
    }

    public function test_cashier_login_automatically_creates_shift_and_syncs_payroll(): void
    {
        $cashier = User::create([
            'name' => 'Kasir Anton',
            'email' => 'anton@rental.test',
            'role' => 'kasir',
            'password' => 'password123',
            'pin' => '123456',
        ]);

        $this->assertSame(0, Shift::where('user_id', $cashier->id)->count());
        $this->assertSame(0, Payroll::where('user_id', $cashier->id)->count());

        // Login kasir
        $loginResponse = $this->post(route('login'), [
            'email' => 'anton@rental.test',
            'password' => 'password123',
            'pin' => '123456',
        ]);

        $loginResponse->assertRedirect(route('kasir.dashboard'));

        // Pastikan Shift otomatis dibuat dan berstatus active
        $this->assertDatabaseHas('shifts', [
            'user_id' => $cashier->id,
            'status' => 'active',
        ]);

        // Pastikan Payroll bulan berjalan otomatis terbuat dan tersinkronisasi
        $currentMonth = Carbon::now()->format('Y-m');
        $payroll = Payroll::where('user_id', $cashier->id)
            ->where('period_month', $currentMonth)
            ->first();

        $this->assertNotNull($payroll);
        $this->assertSame(1, $payroll->work_days);
        $this->assertSame(50000, $payroll->base_salary);
        $this->assertSame(50000, $payroll->net_salary);
        $this->assertSame('pending', $payroll->status);
    }

    public function test_cashier_login_closes_stale_shift_from_previous_cashier(): void
    {
        $cashierA = User::create([
            'name' => 'Kasir A',
            'email' => 'kasira@rental.test',
            'role' => 'kasir',
            'password' => 'password123',
            'pin' => '111111',
        ]);

        $cashierB = User::create([
            'name' => 'Kasir B',
            'email' => 'kasirb@rental.test',
            'role' => 'kasir',
            'password' => 'password123',
            'pin' => '222222',
        ]);

        // Shift aktif milik Kasir A
        $staleShift = Shift::create([
            'user_id' => $cashierA->id,
            'shift_type' => 'pagi',
            'start_time' => Carbon::now()->subHours(6),
            'starting_cash' => 200000,
            'total_revenue' => 150000,
            'reported_revenue' => 150000,
            'leftover_cash' => 50000,
            'status' => 'active',
        ]);

        // Kasir B login
        $this->post(route('login'), [
            'email' => 'kasirb@rental.test',
            'password' => 'password123',
            'pin' => '222222',
        ]);

        // Shift lama Kasir A harus otomatis ditutup
        $staleShift->refresh();
        $this->assertSame('closed', $staleShift->status);
        $this->assertNotNull($staleShift->end_time);

        // Kasir B mendapatkan shift aktif dengan modal awal dari leftover_cash Kasir A
        $newShift = Shift::where('user_id', $cashierB->id)->where('status', 'active')->first();
        $this->assertNotNull($newShift);
        $this->assertSame(50000, $newShift->starting_cash);

        // Kedua kasir memiliki payroll yang tersinkronisasi
        $currentMonth = Carbon::now()->format('Y-m');
        $payrollA = Payroll::where('user_id', $cashierA->id)->where('period_month', $currentMonth)->first();
        $payrollB = Payroll::where('user_id', $cashierB->id)->where('period_month', $currentMonth)->first();

        $this->assertNotNull($payrollA);
        $this->assertSame(1, $payrollA->work_days);
        $this->assertNotNull($payrollB);
        $this->assertSame(1, $payrollB->work_days);
    }

    public function test_payroll_index_page_displays_auto_synced_data_and_attendance_log(): void
    {
        $admin = User::create([
            'name' => 'Admin Boss',
            'email' => 'boss@rental.test',
            'role' => 'admin',
            'password' => 'secret123',
            'pin' => '123456',
        ]);

        $cashier = User::create([
            'name' => 'Kasir Citra',
            'email' => 'citra@rental.test',
            'role' => 'kasir',
            'password' => 'password123',
            'pin' => '123456',
        ]);

        // Login kasir untuk trigger shift dan sync payroll
        $this->post(route('login'), [
            'email' => 'citra@rental.test',
            'password' => 'password123',
            'pin' => '123456',
        ]);

        $this->post(route('logout'));

        // Admin membuka halaman payroll
        $this->actingAs($admin);
        $response = $this->get(route('admin.payroll.index', ['kasir_id' => $cashier->id]));

        $response->assertOk();
        $response->assertSee('Kasir Citra');
        $response->assertSee('Riwayat Absen Shift Bulan Ini');
        $response->assertSee('Rp 50.000');
    }
}
