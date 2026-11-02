<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->enum('shift_type', ['pagi', 'sore'])->default('pagi')->after('user_id');
            $table->integer('reported_revenue')->default(0)->after('total_revenue');
            $table->integer('leftover_cash')->default(0)->after('reported_revenue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn(['shift_type', 'reported_revenue', 'leftover_cash']);
        });
    }
};
