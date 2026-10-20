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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('period_month');
            $table->integer('base_salary')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('deductions')->default(0);
            $table->integer('total_revenue')->default(0);
            $table->integer('work_hours')->default(0);
            $table->integer('work_days')->default(0);
            $table->integer('net_salary')->default(0);
            $table->enum('status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'period_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
