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

        $activePrepaidSessions = PlaySession::with('tv')
            ->where('status', 'active')
            ->where('billing_type', 'prepaid')
            ->get();

        $endedCount = 0;
        $warnedCount = 0;

        foreach ($activePrepaidSessions as $session) {
            $endTime = $session->end_time
                ?? ($session->duration_minutes ? Carbon::parse($session->start_time)->addMinutes($session->duration_minutes) : null);

            if (! $endTime) {
                continue;
            }

            $remainingMinutes = (int) $now->diffInMinutes($endTime, false);

            if ($remainingMinutes <= 5 && $remainingMinutes > 0) {
                if (! $session->tv->is_buzzer_on) {
                    $session->tv->update(['is_buzzer_on' => true]);
                    $warnedCount++;
                    $this->info("⚠️ TV '{$session->tv->name}': Sisa waktu {$remainingMinutes} menit. Buzzer diaktifkan.");
                }
            }

            if ($remainingMinutes <= 0) {
                try {
                    $playSessionService->endSession($session);
                    $session->tv->update(['is_buzzer_on' => true]);

                    if ($session->tv->iot_endpoint) {
                        try {
                            Http::timeout(3)->post($session->tv->iot_endpoint, [
                                'action' => 'turn_off_relay',
                                'tv_id' => $session->tv_id,
                                'session_id' => $session->id,
                            ]);
                        } catch (\Throwable $e) {
                            Log::warning("Gagal mengirim webhook IoT untuk TV ID {$session->tv_id}: ".$e->getMessage());
                        }
                    }

                    $endedCount++;
                    $this->info("⏹️ TV '{$session->tv->name}': Waktu habis. Sesi otomatis dihentikan.");
                } catch (\Throwable $e) {
                    Log::error("Error menghentikan sesi prepaid ID {$session->id}: ".$e->getMessage());
                }
            }
        }

        $this->info("Pengecekan selesai. Warned: {$warnedCount}, Ended: {$endedCount}");

        return Command::SUCCESS;
    }
}
