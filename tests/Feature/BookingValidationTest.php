<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Tv;
use App\Models\User;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BookingValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function bookingEndpoints(): array
    {
        return [
            'kasir booking' => ['/kasir/booking'],
            'general booking' => ['/booking'],
        ];
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_is_rejected_when_unit_status_is_playing_at_requested_time(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing']);

        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv, now()->format('Y-m-d H:i'), 2));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('error', 'Peringatan: Unit '.$tv->name.' sedang digunakan (dimainkan) pada waktu tersebut dan tidak dapat dibooking!')
            ->assertSessionHasInput('customer_name', 'Budi');
        $this->assertDatabaseCount('bookings', 0);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_is_allowed_for_future_date_even_when_unit_is_currently_playing(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing']);
        PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'billing_type' => 'prepaid',
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv, now()->addDay()->format('Y-m-d H:i'), 2));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('success')
            ->assertSessionMissing('error');
        $this->assertDatabaseCount('bookings', 1);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_is_rejected_when_requested_time_overlaps_existing_open_booking(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        $existingTime = now()->addDay()->setTime(12, 0);
        $this->createBooking($tv, 'Pending', $existingTime, 2);

        $conflictTime = now()->addDay()->setTime(12, 30);
        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv, $conflictTime->format('Y-m-d H:i'), 2));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('error', 'Peringatan: Unit '.$tv->name.' sudah dibooking pada waktu tersebut!');
        $this->assertDatabaseCount('bookings', 1);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_is_allowed_when_requested_time_does_not_overlap_existing_open_booking(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        $existingTime = now()->addDay()->setTime(12, 0);
        $this->createBooking($tv, 'Pending', $existingTime, 2);

        $futureTime = now()->addDays(2)->setTime(12, 30);
        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv, $futureTime->format('Y-m-d H:i'), 2));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('success')
            ->assertSessionMissing('error');
        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_kasir_pos_extend_rental_rejected_for_postpaid(): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing']);
        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'billing_type' => 'postpaid',
            'start_time' => now(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->from('/kasir/pos')
            ->post(route('kasir.rental.extend', $session->id), [
                'added_hours' => 1,
            ]);

        $response->assertRedirect('/kasir/pos')
            ->assertSessionHas('error', 'Penambahan waktu tidak tersedia untuk sesi Postpaid (Open Billing)!');
    }

    public function test_kasir_pos_add_fnb_rejected_when_all_quantities_zero(): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing']);
        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'billing_type' => 'prepaid',
            'start_time' => now(),
            'status' => 'active',
        ]);

        $product = Product::create([
            'name' => 'Kopi Hitam',
            'category' => 'drink',
            'price' => 5000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($user)
            ->from('/kasir/pos')
            ->post(route('kasir.rental.add-fnb', $session->id), [
                'items' => [
                    [
                        'product_id' => $product->id,
                        'quantity' => 0,
                    ],
                ],
            ]);

        $response->assertRedirect('/kasir/pos')
            ->assertSessionHas('error', 'Pilih minimal 1 item F&B dengan jumlah lebih dari 0!');
    }

    public function test_kasir_pos_toggle_buzzer_message(): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing', 'is_buzzer_on' => false]);

        // Toggle on
        $response = $this->actingAs($user)
            ->from('/kasir/pos')
            ->post(route('kasir.rental.toggle-buzzer', $tv->id));

        $response->assertRedirect('/kasir/pos')
            ->assertSessionHas('success', 'Buzzer '.$tv->name.' diaktifkan');
        $this->assertTrue($tv->fresh()->is_buzzer_on);

        // Toggle off
        $response = $this->actingAs($user)
            ->from('/kasir/pos')
            ->post(route('kasir.rental.toggle-buzzer', $tv->id));

        $response->assertRedirect('/kasir/pos')
            ->assertSessionHas('success', 'Buzzer '.$tv->name.' dimatikan');
        $this->assertFalse($tv->fresh()->is_buzzer_on);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_checkin_creates_play_session_and_updates_status(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        $booking = $this->createBooking($tv, 'Pending', now(), 2);

        $checkInUrl = $endpoint === '/kasir/booking'
            ? route('kasir.booking.checkin', $booking->id)
            : route('booking.checkin', $booking->id);

        $response = $this->actingAs($user)->post($checkInUrl);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Active', $booking->fresh()->status);
        $this->assertEquals('playing', $tv->fresh()->status);

        $this->assertDatabaseHas('play_sessions', [
            'tv_id' => $tv->id,
            'customer_name' => $booking->customer_name,
            'status' => 'active',
            'billing_type' => 'prepaid',
        ]);
    }

    public function test_checkin_is_rejected_when_tv_is_not_available(): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing']);
        $booking = $this->createBooking($tv, 'Pending', now(), 1);

        $response = $this->actingAs($user)->post(route('booking.checkin', $booking->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals('Pending', $booking->fresh()->status);
        $this->assertDatabaseCount('play_sessions', 0);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_can_be_cancelled(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        $booking = $this->createBooking($tv, 'Pending', now()->addDay(), 2);

        $cancelUrl = $endpoint === '/kasir/booking'
            ? route('kasir.booking.cancel', $booking->id)
            : route('booking.cancel', $booking->id);

        $response = $this->actingAs($user)->delete($cancelUrl);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_checkout_completes_active_booking(): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing']);
        $booking = $this->createBooking($tv, 'Active', now()->subHours(2), 2);

        $session = PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'customer_name' => $booking->customer_name,
            'billing_type' => 'prepaid',
            'start_time' => now()->subHours(2),
            'end_time' => now(),
            'status' => 'active',
            'rental_amount' => 30000,
            'total_amount' => 30000,
        ]);

        $midtransService = app(MidtransService::class);
        $midtransService->finalizeSessionCheckout($session, 'cash');

        $this->assertEquals('Completed', $booking->fresh()->status);
        $this->assertEquals('available', $tv->fresh()->status);
    }

    public function test_dashboard_api_status_includes_ready_booking(): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        $booking = $this->createBooking($tv, 'Pending', now()->addMinutes(5), 2);

        $response = $this->actingAs($user)->get(route('admin.dashboard.api-status'));

        $response->assertOk()
            ->assertJsonPath('tvs.0.ready_booking.id', $booking->id)
            ->assertJsonPath('tvs.0.ready_booking.customer_name', $booking->customer_name);
    }

    public function test_updating_default_rates_in_settings_syncs_tv_price_per_hour(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $simRacingTv = $this->createTv([
            'name' => 'PS 05 - Sim Racing Cockpit',
            'type' => 'sim_racing',
            'price_per_hour' => 35000,
        ]);
        $ps5Tv = $this->createTv([
            'name' => 'PS 04 - PS5 Next-Gen',
            'type' => 'ps5',
            'price_per_hour' => 25000,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.settings.index'))
            ->post(route('admin.settings.update'), [
                'store_name' => 'TambahBang Rental PS',
                'rate_sim_racing' => '25000',
                'rate_ps5' => '30000',
            ]);

        $response->assertRedirect(route('admin.settings.index'))
            ->assertSessionHas('success');

        $this->assertEquals(25000, $simRacingTv->fresh()->price_per_hour);
        $this->assertEquals(30000, $ps5Tv->fresh()->price_per_hour);
        $this->assertDatabaseHas('settings', [
            'key' => 'rate_sim_racing',
            'value' => '25000',
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createTv(array $attributes): Tv
    {
        return Tv::create(array_merge([
            'name' => 'TV Booking Test',
            'type' => 'ps5',
            'price_per_hour' => 15000,
            'status' => 'available',
        ], $attributes));
    }

    private function createBooking(Tv $tv, string $status, ?Carbon $startTime = null, int $duration = 1): Booking
    {
        return Booking::create([
            'tv_id' => $tv->id,
            'customer_name' => 'Pelanggan Lama',
            'phone' => '081200000000',
            'start_datetime' => $startTime ?? now()->addDay(),
            'duration_hours' => $duration,
            'status' => $status,
            'total_amount' => 15000 * $duration,
        ]);
    }

    /**
     * @return array{customer_name: string, phone: string, tv_id: int, start_datetime: string, duration_hours: int}
     */
    private function bookingPayload(Tv $tv, ?string $startDatetime = null, int $durationHours = 2): array
    {
        return [
            'customer_name' => 'Budi',
            'phone' => '081234567890',
            'tv_id' => $tv->id,
            'start_datetime' => $startDatetime ?? now()->addDay()->format('Y-m-d H:i'),
            'duration_hours' => $durationHours,
        ];
    }
}
