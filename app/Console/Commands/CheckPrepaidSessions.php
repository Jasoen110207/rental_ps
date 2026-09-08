<?php

namespace App\Console\Commands;

use App\Models\PlaySession;
use App\Services\PlaySessionService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckPrepaidSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-prepaid-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa sesi bermain prepaid yang durasinya telah habis dan memicu sinyal alarm/IoT';

    /**
     * Execute the console command.
     */
    public function handle(PlaySessionService $playSessionService): int
    {
        $now = Carbon::now();

        // Ambil sesi prepaid yang aktif dan memiliki end_time yang sudah lewat dari sekarang
        $expiredSessions = PlaySession::with('tv')
            ->where('status', 'active')
            ->where('billing_type', 'prepaid')
            ->whereNotNull('end_time')
            ->where('end_time', '<=', $now)
            ->get();

        $count = 0;

        foreach ($expiredSessions as $session) {
            try {
                // 1. Akhiri sesi bermain secara otomatis via service
                $playSessionService->endSession($session);

                // 2. Aktifkan buzzer pada TV sebagai indikator UI/suara di frontend
                $session->tv->update(['is_buzzer_on' => true]);

                // 3. Simulasi webhook hardware/IoT jika endpoint diset
                if ($session->tv->iot_endpoint) {
                    try {
                        Http::timeout(3)->post($session->tv->iot_endpoint, [
                            'action' => 'turn_off_relay',
                            'tv_id' => $session->tv_id,
                            'session_id' => $session->id,
                        ]);
                    } catch (\Throwable $e) {
                        Log::warning("Gagal mengirim webhook IoT untuk TV ID {$session->tv_id}: " . $e->getMessage());
                    }
                }

                $count++;
            } catch (\Throwable $e) {
                Log::error("Error menghentikan sesi prepaid ID {$session->id}: " . $e->getMessage());
            }
        }

        $this->info("Berhasil memproses {$count} sesi prepaid yang habis.");

        return Command::SUCCESS;
    }
}
