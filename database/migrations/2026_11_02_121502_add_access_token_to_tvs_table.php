<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tvs', function (Blueprint $table) {
            $table->string('access_token', 64)->nullable()->unique()->after('id');
        });

        // Generate token untuk data TV yang sudah ada
        $tvs = DB::table('tvs')->get();
        foreach ($tvs as $tv) {
            DB::table('tvs')
                ->where('id', $tv->id)
                ->update(['access_token' => Str::random(32)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tvs', function (Blueprint $table) {
            $table->dropColumn('access_token');
        });
    }
};
