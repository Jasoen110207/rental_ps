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
        DB::table('users')->delete();
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
                'name' => 'Kasir 1 (Budi)',
                'email' => 'kasir@rental.com',
                'role' => 'kasir',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2. TVs Table (8 Meja PlayStation)
        DB::table('tvs')->delete();
        $tvs = [
            [
                'id' => 1,
                'name' => 'PS 01 - PS3 VIP',
                'type' => 'ps3',
                'price_per_hour' => 8000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'PS 02 - PS4 Regular',
                'type' => 'ps4',
                'price_per_hour' => 12000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'PS 03 - PS4 Pro',
                'type' => 'ps4',
                'price_per_hour' => 15000,
                'status' => 'playing',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'PS 04 - PS5 Next-Gen',
                'type' => 'ps5',
                'price_per_hour' => 25000,
                'status' => 'playing',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'PS 05 - Sim Racing Cockpit',
                'type' => 'sim_racing',
                'price_per_hour' => 35000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'PS 06 - Nintendo Switch',
                'type' => 'nintendo_switch',
                'price_per_hour' => 15000,
                'status' => 'available',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'name' => 'PS 07 - PS5 VIP Arena',
                'type' => 'ps5',
                'price_per_hour' => 25000,
                'status' => 'playing',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'name' => 'PS 08 - PS4 Pro Lounge',
                'type' => 'ps4',
                'price_per_hour' => 15000,
                'status' => 'maintenance',
                'is_buzzer_on' => false,
                'iot_endpoint' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        DB::table('tvs')->insert($tvs);

        // 3. Products Table (12 item makanan/minuman)
        DB::table('products')->delete();
        $products = [
            ['id' => 1, 'name' => 'Indomie Goreng + Telur', 'category' => 'food', 'price' => 12000, 'stock' => 35, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Indomie Kuah Soto + Telur', 'category' => 'food', 'price' => 12000, 'stock' => 40, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Nasi Goreng Spesial', 'category' => 'food', 'price' => 18000, 'stock' => 20, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Nugget + Nasi Hangat', 'category' => 'food', 'price' => 15000, 'stock' => 15, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'French Fries / Kentang Goreng', 'category' => 'snack', 'price' => 12000, 'stock' => 18, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Snack Chitato / Lay\'s', 'category' => 'snack', 'price' => 8000, 'stock' => 25, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'Sosis Bakar Jumbo', 'category' => 'snack', 'price' => 10000, 'stock' => 30, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'name' => 'Es Teh Manis Jumbo', 'category' => 'drink', 'price' => 5000, 'stock' => 50, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'name' => 'Es Jeruk Peras', 'category' => 'drink', 'price' => 6000, 'stock' => 30, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'name' => 'Kopi Susu Dingin', 'category' => 'drink', 'price' => 8000, 'stock' => 25, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'name' => 'Kopi Hitam Warmindo', 'category' => 'drink', 'price' => 5000, 'stock' => 25, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'name' => 'Air Mineral Dingin 600ml', 'category' => 'drink', 'price' => 4000, 'stock' => 45, 'is_available' => true, 'image' => null, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('products')->insert($products);

        // 4. Shifts Table (1 active shift)
        DB::table('shifts')->delete();
        DB::table('shifts')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'start_time' => $now->copy()->subHours(4),
                'end_time' => null,
                'starting_cash' => 200000,
                'total_revenue' => 145000,
                'transactions_count' => 3,
                'notes' => 'Shift siang lancar, stok mie dan es teh aman.',
                'status' => 'active',
                'created_at' => $now->copy()->subHours(4),
                'updated_at' => $now,
            ],
        ]);

        // 5. Play Sessions Table
        DB::table('play_sessions')->delete();
        $sessions = [
            // Sesi 1: Selesai
            [
                'id' => 1,
                'tv_id' => 1,
                'user_id' => 2,
                'billing_type' => 'prepaid',
                'start_time' => $now->copy()->subHours(4),
                'end_time' => $now->copy()->subHours(2),
                'status' => 'completed',
                'rental_amount' => 16000,
                'fnb_amount' => 28000,
                'total_amount' => 44000,
                'payment_method' => 'cash',
                'notes' => 'Lunas',
                'created_at' => $now->copy()->subHours(4),
                'updated_at' => $now->copy()->subHours(2),
            ],
            // Sesi 2: Selesai
            [
                'id' => 2,
                'tv_id' => 2,
                'user_id' => 2,
                'billing_type' => 'postpaid',
                'start_time' => $now->copy()->subHours(3),
                'end_time' => $now->copy()->subHours(1),
                'status' => 'completed',
                'rental_amount' => 24000,
                'fnb_amount' => 26000,
                'total_amount' => 50000,
                'payment_method' => 'qris',
                'notes' => 'Lunas QRIS',
                'created_at' => $now->copy()->subHours(3),
                'updated_at' => $now->copy()->subHours(1),
            ],
            // Sesi 3: Selesai
            [
                'id' => 3,
                'tv_id' => 5,
                'user_id' => 2,
                'billing_type' => 'prepaid',
                'start_time' => $now->copy()->subHours(2),
                'end_time' => $now->copy()->subHours(1),
                'status' => 'completed',
                'rental_amount' => 35000,
                'fnb_amount' => 16000,
                'total_amount' => 51000,
                'payment_method' => 'cash',
                'notes' => 'Lunas',
                'created_at' => $now->copy()->subHours(2),
                'updated_at' => $now->copy()->subHours(1),
            ],
            // Sesi 4: Aktif (PS 03 - Prepaid, remaining ~45 mins)
            [
                'id' => 4,
                'tv_id' => 3,
                'user_id' => 2,
                'billing_type' => 'prepaid',
                'start_time' => $now->copy()->subMinutes(75),
                'end_time' => $now->copy()->addMinutes(45),
                'status' => 'active',
                'rental_amount' => 30000,
                'fnb_amount' => 25000,
                'total_amount' => 55000,
                'payment_method' => 'cash',
                'notes' => null,
                'created_at' => $now->copy()->subMinutes(75),
                'updated_at' => $now->copy()->subMinutes(75),
            ],
            // Sesi 5: Aktif (PS 04 - Postpaid/Loss, running ~40 mins)
            [
                'id' => 5,
                'tv_id' => 4,
                'user_id' => 2,
                'billing_type' => 'postpaid',
                'start_time' => $now->copy()->subMinutes(40),
                'end_time' => null,
                'status' => 'active',
                'rental_amount' => 0,
                'fnb_amount' => 17000,
                'total_amount' => 17000,
                'payment_method' => 'cash',
                'notes' => null,
                'created_at' => $now->copy()->subMinutes(40),
                'updated_at' => $now->copy()->subMinutes(40),
            ],
            // Sesi 6: Aktif (PS 07 - Prepaid, almost finished ~8 mins remaining)
            [
                'id' => 6,
                'tv_id' => 7,
                'user_id' => 2,
                'billing_type' => 'prepaid',
                'start_time' => $now->copy()->subMinutes(52),
                'end_time' => $now->copy()->addMinutes(8),
                'status' => 'active',
                'rental_amount' => 25000,
                'fnb_amount' => 0,
                'total_amount' => 25000,
                'payment_method' => 'cash',
                'notes' => null,
                'created_at' => $now->copy()->subMinutes(52),
                'updated_at' => $now->copy()->subMinutes(52),
            ],
        ];
        DB::table('play_sessions')->insert($sessions);

        // 6. Session Orders
        DB::table('session_orders')->delete();
        $orders = [
            // Orders for session 1
            ['play_session_id' => 1, 'product_id' => 1, 'quantity' => 2, 'subtotal' => 24000, 'created_at' => $now->copy()->subHours(4), 'updated_at' => $now->copy()->subHours(4)],
            ['play_session_id' => 1, 'product_id' => 12, 'quantity' => 1, 'subtotal' => 4000, 'created_at' => $now->copy()->subHours(4), 'updated_at' => $now->copy()->subHours(4)],

            // Orders for session 4 (Active PS 03)
            ['play_session_id' => 4, 'product_id' => 4, 'quantity' => 1, 'subtotal' => 15000, 'created_at' => $now->copy()->subMinutes(60), 'updated_at' => $now->copy()->subMinutes(60)],
            ['play_session_id' => 4, 'product_id' => 8, 'quantity' => 2, 'subtotal' => 10000, 'created_at' => $now->copy()->subMinutes(60), 'updated_at' => $now->copy()->subMinutes(60)],

            // Orders for session 5 (Active PS 04)
            ['play_session_id' => 5, 'product_id' => 5, 'quantity' => 1, 'subtotal' => 12000, 'created_at' => $now->copy()->subMinutes(30), 'updated_at' => $now->copy()->subMinutes(30)],
            ['play_session_id' => 5, 'product_id' => 8, 'quantity' => 1, 'subtotal' => 5000, 'created_at' => $now->copy()->subMinutes(30), 'updated_at' => $now->copy()->subMinutes(30)],
        ];
        DB::table('session_orders')->insert($orders);

        // 7. Customer Requests Table
        DB::table('customer_requests')->delete();
        $requests = [
            [
                'id' => 1,
                'tv_id' => 3, // PS 03
                'type' => 'add_time',
                'payload' => json_encode([
                    'duration_hours' => 1,
                    'price' => 15000,
                    'note' => 'Tambah 1 Jam'
                ]),
                'status' => 'pending',
                'created_at' => $now->copy()->subMinutes(5),
                'updated_at' => $now->copy()->subMinutes(5),
            ],
            [
                'id' => 2,
                'tv_id' => 4, // PS 04
                'type' => 'order_food',
                'payload' => json_encode([
                    'items' => [
                        ['product_id' => 1, 'name' => 'Indomie Goreng + Telur', 'price' => 12000, 'quantity' => 1, 'subtotal' => 12000],
                        ['product_id' => 10, 'name' => 'Kopi Susu Dingin', 'price' => 8000, 'quantity' => 1, 'subtotal' => 8000],
                    ],
                    'total_price' => 20000,
                    'note' => 'Cabai 3 biji ya bang'
                ]),
                'status' => 'pending',
                'created_at' => $now->copy()->subMinutes(2),
                'updated_at' => $now->copy()->subMinutes(2),
            ],
            [
                'id' => 3,
                'tv_id' => 7, // PS 07
                'type' => 'add_time',
                'payload' => json_encode([
                    'duration_hours' => 2,
                    'price' => 50000,
                    'note' => 'Tambah 2 Jam'
                ]),
                'status' => 'pending',
                'created_at' => $now->copy()->subMinutes(1),
                'updated_at' => $now->copy()->subMinutes(1),
            ],
        ];
        DB::table('customer_requests')->insert($requests);

        // 8. Settings Table
        DB::table('settings')->delete();
        $settings = [
            ['key' => 'store_name', 'value' => 'TambahBang Rental PS', 'group' => 'store'],
            ['key' => 'store_tagline', 'value' => 'Smart POS & Real-Time Monitoring Hub', 'group' => 'store'],
            ['key' => 'store_address', 'value' => 'Jl. Game Arena No. 42, Kota Digital', 'group' => 'store'],
            ['key' => 'store_phone', 'value' => '0812-3456-7890', 'group' => 'store'],
            ['key' => 'rate_ps3', 'value' => '8000', 'group' => 'pricing'],
            ['key' => 'rate_ps4', 'value' => '15000', 'group' => 'pricing'],
            ['key' => 'rate_ps5', 'value' => '25000', 'group' => 'pricing'],
            ['key' => 'rate_sim_racing', 'value' => '35000', 'group' => 'pricing'],
            ['key' => 'rate_nintendo_switch', 'value' => '15000', 'group' => 'pricing'],
            ['key' => 'warning_minutes', 'value' => '10', 'group' => 'system'],
            ['key' => 'auto_buzzer', 'value' => '1', 'group' => 'system'],
            ['key' => 'sound_notifications', 'value' => '1', 'group' => 'system'],
        ];
        DB::table('settings')->insert($settings);
    }
}
