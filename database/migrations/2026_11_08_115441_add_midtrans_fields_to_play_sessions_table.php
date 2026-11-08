<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('payment_method');
            $table->string('snap_token')->nullable()->after('midtrans_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id', 'snap_token']);
        });
    }
};
