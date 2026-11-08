<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            $table->string('session_token', 64)->nullable()->unique()->after('user_id');
        });

        // Isi session_token untuk data play_sessions yang sudah ada
        $sessions = DB::table('play_sessions')->whereNull('session_token')->get(['id']);
        foreach ($sessions as $session) {
            DB::table('play_sessions')
                ->where('id', $session->id)
                ->update(['session_token' => Str::random(40)]);
        }
    }

    public function down(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            $table->dropColumn('session_token');
        });
    }
};
