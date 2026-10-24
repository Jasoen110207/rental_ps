<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\PlaySession;
use App\Models\Tv;
use App\Models\User;
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
    public function test_booking_is_rejected_when_unit_status_is_playing(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'playing']);

        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('error', 'Peringatan: Unit '.$tv->name.' sedang digunakan (dimainkan) dan tidak dapat dibooking!')
            ->assertSessionHasInput('customer_name', 'Budi');
        $this->assertDatabaseCount('bookings', 0);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_is_rejected_when_unit_has_an_active_play_session(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        PlaySession::create([
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'billing_type' => 'postpaid',
            'start_time' => now(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('error', 'Peringatan: Unit '.$tv->name.' sedang digunakan (dimainkan) dan tidak dapat dibooking!');
        $this->assertDatabaseCount('bookings', 0);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_is_rejected_when_unit_already_has_an_open_booking(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        $this->createBooking($tv, 'Pending');

        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('error', 'Peringatan: Unit '.$tv->name.' sudah dibooking!');
        $this->assertDatabaseCount('bookings', 1);
    }

    #[DataProvider('bookingEndpoints')]
    public function test_booking_is_saved_when_unit_is_available_and_only_has_completed_bookings(string $endpoint): void
    {
        $user = User::factory()->create();
        $tv = $this->createTv(['status' => 'available']);
        $this->createBooking($tv, 'Completed');

        $response = $this->actingAs($user)
            ->from($endpoint)
            ->post($endpoint, $this->bookingPayload($tv));

        $response->assertRedirect($endpoint)
            ->assertSessionHas('success')
            ->assertSessionMissing('error');
        $this->assertDatabaseHas('bookings', [
            'tv_id' => $tv->id,
            'user_id' => $user->id,
            'customer_name' => 'Budi',
            'status' => 'Pending',
            'total_amount' => 30000,
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

    private function createBooking(Tv $tv, string $status): Booking
    {
        return Booking::create([
            'tv_id' => $tv->id,
            'customer_name' => 'Pelanggan Lama',
            'phone' => '081200000000',
            'start_datetime' => now()->addDay(),
            'duration_hours' => 1,
            'status' => $status,
            'total_amount' => 15000,
        ]);
    }

    /**
     * @return array{customer_name: string, phone: string, tv_id: int, start_datetime: string, duration_hours: int}
     */
    private function bookingPayload(Tv $tv): array
    {
        return [
            'customer_name' => 'Budi',
            'phone' => '081234567890',
            'tv_id' => $tv->id,
            'start_datetime' => now()->addDay()->format('Y-m-d H:i'),
            'duration_hours' => 2,
        ];
    }
}
