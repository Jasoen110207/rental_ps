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
        Schema::create('tvs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['ps3', 'ps4', 'ps5', 'sim_racing', 'nintendo_switch']);
            $table->integer('price_per_hour');
            $table->enum('status', ['available', 'playing', 'maintenance'])->default('available');
            $table->boolean('is_buzzer_on')->default(false);
            $table->string('iot_endpoint')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tvs');
    }
};
