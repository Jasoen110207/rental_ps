<?php

namespace Tests\Feature;

use App\Models\PlaySession;
use App\Models\Product;
use App\Models\Tv;
use App\Models\User;
use App\Services\OrderService;
use App\Services\PlaySessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_start_and_end_play_session(): void
    {
        $user = User::factory()->create();
        $tv = Tv::create([
            'name' => 'Meja Test',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'available',
        ]);

        $service = new PlaySessionService();

        // 1. Start Session
        $session = $service->startSession($tv, $user, 'postpaid');

        $this->assertEquals('playing', $tv->fresh()->status);
        $this->assertEquals('active', $session->status);
        $this->assertEquals($tv->id, $session->tv_id);

        // 2. End Session
        $completedSession = $service->endSession($session);

        $this->assertEquals('available', $tv->fresh()->status);
        $this->assertEquals('completed', $completedSession->status);
        $this->assertNotNull($completedSession->end_time);
    }

    public function test_can_add_food_to_active_session(): void
    {
        $user = User::factory()->create();
        $tv = Tv::create([
            'name' => 'Meja Test 2',
            'type' => 'ps5',
            'price_per_hour' => 15000,
            'status' => 'available',
        ]);

        $product = Product::create([
            'name' => 'Es Teh Test',
            'price' => 5000,
            'stock' => 20,
        ]);

        $playService = new PlaySessionService();
        $orderService = new OrderService();

        $session = $playService->startSession($tv, $user, 'prepaid');

        // Add food
        $order = $orderService->addFoodToSession($session, $product, 2);

        $this->assertEquals(18, $product->fresh()->stock);
        $this->assertEquals(10000, $order->subtotal);

        // End session and check total amount includes food
        $completedSession = $playService->endSession($session);
        $this->assertGreaterThanOrEqual(10000, $completedSession->total_amount);
    }
}
