<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Users Table (2 akun: Admin & Kasir)
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin Rental',
                'email' => 'admin@rental.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Kasir 1',
                'email' => 'kasir@rental.com',
                'role' => 'kasir',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2. TVs Table (6 meja TV: 1 playing, sisanya available)
        $tvs = [
            [
                'id' => 1,
                'name' => 'Meja 01 - PS3 VIP',
                'type' => 'ps3',
                'price_per_hour' => 5000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Meja 02 - PS4 Regular',
                'type' => 'ps4',
                'price_per_hour' => 10000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Meja 03 - PS4 Pro',
                'type' => 'ps4',
                'price_per_hour' => 12000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Meja 04 - PS5 Next-Gen',
                'type' => 'ps5',
                'price_per_hour' => 15000,
                'status' => 'playing',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Meja 05 - Sim Racing Cockpit',
                'type' => 'sim_racing',
                'price_per_hour' => 25000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'Meja 06 - Nintendo Switch Corner',
                'type' => 'nintendo_switch',
                'price_per_hour' => 8000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('tvs')->insert($tvs);

        // 3. Products Table (10 item makanan/minuman)
        $products = [
            ['id' => 1, 'name' => 'Indomie Goreng + Telur', 'price' => 10000, 'stock' => 35, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Indomie Kuah Soto', 'price' => 8000, 'stock' => 40, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Es Teh Manis', 'price' => 4000, 'stock' => 50, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Es Jeruk', 'price' => 5000, 'stock' => 30, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Kopi Hitam Warmindo', 'price' => 5000, 'stock' => 25, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Kopi Susu Dingin', 'price' => 7000, 'stock' => 20, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'Nugget + Nasi', 'price' => 15000, 'stock' => 15, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'name' => 'French Fries / Kentang Goreng', 'price' => 12000, 'stock' => 18, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'name' => 'Air Mineral 600ml', 'price' => 3000, 'stock' => 45, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'name' => 'Snack Chitato / Lay\'s', 'price' => 8000, 'stock' => 22, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('products')->insert($products);

        // 4. Play Sessions Table (3 completed, 1 active)
        $sessions = [
            [
                'id' => 1,
                'tv_id' => 1,
                'user_id' => 2,
                'billing_type' => 'prepaid',
                'start_time' => $now->copy()->subHours(5),
                'end_time' => $now->copy()->subHours(3),
                'status' => 'completed',
                'total_amount' => 10000,
                'created_at' => $now->copy()->subHours(5),
                'updated_at' => $now->copy()->subHours(3),
            ],
            [
                'id' => 2,
                'tv_id' => 2,
                'user_id' => 2,
                'billing_type' => 'postpaid',
                'start_time' => $now->copy()->subHours(4),
                'end_time' => $now->copy()->subHours(2),
                'status' => 'completed',
                'total_amount' => 20000,
                'created_at' => $now->copy()->subHours(4),
                'updated_at' => $now->copy()->subHours(2),
            ],
            [
                'id' => 3,
                'tv_id' => 5,
                'user_id' => 2,
                'billing_type' => 'prepaid',
                'start_time' => $now->copy()->subHours(3),
                'end_time' => $now->copy()->subHours(2),
                'status' => 'completed',
                'total_amount' => 25000,
                'created_at' => $now->copy()->subHours(3),
                'updated_at' => $now->copy()->subHours(2),
            ],
            [
                'id' => 4,
                'tv_id' => 4, // Status playing di tabel TV
                'user_id' => 2,
                'billing_type' => 'postpaid',
                'start_time' => $now->copy()->subHour(),
                'end_time' => null,
                'status' => 'active',
                'total_amount' => 0,
                'created_at' => $now->copy()->subHour(),
                'updated_at' => $now->copy()->subHour(),
            ],
        ];

        DB::table('play_sessions')->insert($sessions);

        // 5. Session Orders Table
        $orders = [
            [
                'play_session_id' => 1,
                'product_id' => 1, // Indomie Goreng
                'quantity' => 2,
                'subtotal' => 20000,
                'created_at' => $now->copy()->subHours(4),
                'updated_at' => $now->copy()->subHours(4),
            ],
            [
                'play_session_id' => 1,
                'product_id' => 3, // Es Teh
                'quantity' => 2,
                'subtotal' => 8000,
                'created_at' => $now->copy()->subHours(4),
                'updated_at' => $now->copy()->subHours(4),
            ],
            [
                'play_session_id' => 2,
                'product_id' => 8, // Kentang Goreng
                'quantity' => 1,
                'subtotal' => 12000,
                'created_at' => $now->copy()->subHours(3),
                'updated_at' => $now->copy()->subHours(3),
            ],
            [
                'play_session_id' => 2,
                'product_id' => 6, // Kopi Susu
                'quantity' => 2,
                'subtotal' => 14000,
                'created_at' => $now->copy()->subHours(3),
                'updated_at' => $now->copy()->subHours(3),
            ],
            [
                'play_session_id' => 4,
                'product_id' => 7, // Nugget + Nasi
                'quantity' => 1,
                'subtotal' => 15000,
                'created_at' => $now->copy()->subMinutes(30),
                'updated_at' => $now->copy()->subMinutes(30),
            ],
            [
                'play_session_id' => 4,
                'product_id' => 4, // Es Jeruk
                'quantity' => 2,
                'subtotal' => 10000,
                'created_at' => $now->copy()->subMinutes(30),
                'updated_at' => $now->copy()->subMinutes(30),
            ],
        ];

        DB::table('session_orders')->insert($orders);
    }
}
