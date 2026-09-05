<?php

namespace Tests\Feature;

use App\Models\PlaySession;
use App\Models\Tv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_index_returns_tvs_list(): void
    {
        Tv::create([
            'name' => 'Meja Controller Test',
            'type' => 'ps4',
            'price_per_hour' => 10000,
            'status' => 'available',
        ]);

        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'type', 'price_per_hour', 'status', 'active_session']
                ]
            ]);
    }

    public function test_play_session_store_and_update(): void
    {
        $kasir = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasirtest@rental.com',
            'role' => 'kasir',
            'password' => bcrypt('password'),
        ]);

        $tv = Tv::create([
            'name' => 'Meja Session Test',
            'type' => 'ps5',
            'price_per_hour' => 15000,
            'status' => 'available',
        ]);

        // 1. Store (Start Play Session)
        $response = $this->postJson('/api/play-sessions', [
            'tv_id' => $tv->id,
            'billing_type' => 'postpaid',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.tv_id', $tv->id)
            ->assertJsonPath('data.status', 'active');

        $sessionId = $response->json('data.id');

        // 2. Update (End Play Session)
        $updateResponse = $this->putJson("/api/play-sessions/{$sessionId}");

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'completed');
    }
}
