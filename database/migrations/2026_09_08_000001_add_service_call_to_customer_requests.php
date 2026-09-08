<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah tipe 'service_call' (panggil kasir ke meja) pada enum customer_requests.type.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `customer_requests` MODIFY COLUMN `type` ENUM('add_time', 'order_food', 'service_call') NOT NULL");
        }
        // sqlite/pgsql: enum tidak di-enforce ketat, tidak perlu aksi.
    }

    public function down(): void
    {
        // Kembalikan hanya jika tidak ada baris service_call yang tersisa.
        $remaining = DB::table('customer_requests')->where('type', 'service_call')->count();
        if ($remaining === 0 && Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `customer_requests` MODIFY COLUMN `type` ENUM('add_time', 'order_food') NOT NULL");
        }
    }
};
