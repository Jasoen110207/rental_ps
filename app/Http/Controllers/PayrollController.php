<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PlaySession;
use App\Models\Shift;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public const SHIFT_RATE = 50000;

    protected function resolveSelectedKasir(?User $fallbackUser = null): ?User
    {
        $kasirList = User::where('role', 'kasir')->get();

        if ($kasirList->isEmpty()) {
            return $fallbackUser;
        }

        $selectedId = request('kasir_id');
        $selectedKasir = $kasirList->firstWhere('id', $selectedId)
            ?? $kasirList->first();

        return $selectedKasir ?: $fallbackUser;
    }

    protected function calculatePayrollForUser(User $user, ?string $periodMonth = null): array
    {
        $periodMonth ??= Carbon::now()->format('Y-m');

        $monthStart = Carbon::createFromFormat('Y-m', $periodMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $periodMonth)->endOfMonth();

        $shiftRate = self::SHIFT_RATE;
        $totalShifts = Shift::where('user_id', $user->id)
            ->whereBetween('start_time', [$monthStart, $monthEnd])
            ->count();

        $totalRevenue = (int) PlaySession::where('user_id', $user->id)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total_amount');

        $workHours = (int) Shift::where('user_id', $user->id)
            ->whereBetween('start_time', [$monthStart, $monthEnd])
            ->sum(DB::raw('TIMESTAMPDIFF(HOUR, start_time, COALESCE(end_time, NOW()))'));

        $netSalary = $totalShifts * $shiftRate;

        return [
            'user' => $user,
            'period_month' => $periodMonth,
            'shift_rate' => $shiftRate,
            'total_shifts' => $totalShifts,
            'base_salary' => $netSalary,
            'bonus' => 0,
            'deductions' => 0,
            'total_revenue' => $totalRevenue,
            'work_hours' => $workHours,
            'work_days' => $totalShifts,
            'net_salary' => $netSalary,
        ];
    }

    public function index()
    {
        $kasirList = User::where('role', 'kasir')->get();
        $selectedKasir = $this->resolveSelectedKasir();

        if (! $selectedKasir) {
            return view('payroll.index', ['kasirList' => $kasirList, 'selectedKasir' => null, 'kasir' => null, 'history' => collect()]);
        }

        $periodMonth = request('month', Carbon::now()->format('Y-m'));
        $summary = $this->calculatePayrollForUser($selectedKasir, $periodMonth);

        $payroll = Payroll::where('user_id', $selectedKasir->id)
            ->orderBy('period_month', 'desc')
            ->get();

        $existingPayroll = $payroll->firstWhere('period_month', $periodMonth);
        $isPaid = $existingPayroll && $existingPayroll->status === 'paid';

        $history = $payroll->map(function ($item) {
            return [
                'period' => $item->period_month,
                'amount' => $item->net_salary,
                'status' => ucfirst($item->status),
                'paid_at' => $item->paid_at ? $item->paid_at->format('d M Y') : '-',
            ];
        });

        $kasir = [
            'name' => $selectedKasir->name,
            'position' => 'Kasir',
            'shift' => 'Per Shift (Rp '.number_format($summary['shift_rate'], 0, ',', '.').')',
            'work_hours' => $summary['work_hours'].' jam',
            'total_work_days' => $summary['work_days'],
            'total_shifts' => $summary['total_shifts'],
            'shift_rate' => $summary['shift_rate'],
            'base_salary' => $summary['base_salary'],
            'transaction_bonus' => $summary['bonus'],
            'deductions' => $summary['deductions'],
            'net_salary' => $summary['net_salary'],
            'status' => $isPaid ? 'Sudah Dibayar' : 'Menunggu Konfirmasi',
            'is_paid' => $isPaid,
            'period' => Carbon::createFromFormat('Y-m', $periodMonth)->translatedFormat('F Y'),
        ];

        return view('payroll.index', compact('kasirList', 'selectedKasir', 'kasir', 'history'));
    }

    public function printSlip()
    {
        $selectedKasir = $this->resolveSelectedKasir(Auth::user() ?? User::first());

        if (! $selectedKasir) {
            abort(404);
        }

        $periodMonth = request('month', Carbon::now()->format('Y-m'));
        $summary = $this->calculatePayrollForUser($selectedKasir, $periodMonth);
        $payroll = [
            'name' => $selectedKasir->name,
            'position' => 'Kasir',
            'shift' => 'Per Shift (Rp '.number_format($summary['shift_rate'], 0, ',', '.').')',
            'work_hours' => $summary['work_hours'].' jam',
            'total_shifts' => $summary['total_shifts'],
            'shift_rate' => $summary['shift_rate'],
            'base_salary' => $summary['base_salary'],
            'transaction_bonus' => $summary['bonus'],
            'deductions' => $summary['deductions'],
            'net_salary' => $summary['net_salary'],
            'period' => Carbon::createFromFormat('Y-m', $periodMonth)->translatedFormat('F Y'),
        ];

        return view('payroll.slip', compact('payroll'));
    }

    public function downloadPdf()
    {
        $selectedKasir = $this->resolveSelectedKasir(Auth::user() ?? User::first());

        if (! $selectedKasir) {
            abort(404);
        }

        $periodMonth = request('month', Carbon::now()->format('Y-m'));
        $summary = $this->calculatePayrollForUser($selectedKasir, $periodMonth);
        $payroll = [
            'name' => $selectedKasir->name,
            'position' => 'Kasir',
            'shift' => 'Per Shift (Rp '.number_format($summary['shift_rate'], 0, ',', '.').')',
            'work_hours' => $summary['work_hours'].' jam',
            'total_shifts' => $summary['total_shifts'],
            'shift_rate' => $summary['shift_rate'],
            'base_salary' => $summary['base_salary'],
            'transaction_bonus' => $summary['bonus'],
            'deductions' => $summary['deductions'],
            'net_salary' => $summary['net_salary'],
            'period' => Carbon::createFromFormat('Y-m', $periodMonth)->translatedFormat('F Y'),
        ];

        $pdf = Pdf::loadView('payroll.slip', compact('payroll'));

        return $pdf->download('slip-gaji-'.strtolower(str_replace(' ', '-', $selectedKasir->name)).'-'.Carbon::createFromFormat('Y-m', $periodMonth)->format('Ym').'.pdf');
    }

    public function confirmPayment()
    {
        $selectedKasir = $this->resolveSelectedKasir(Auth::user() ?? User::first());

        if (! $selectedKasir) {
            abort(404);
        }

        $periodMonth = request('month', Carbon::now()->format('Y-m'));
        $summary = $this->calculatePayrollForUser($selectedKasir, $periodMonth);

        Payroll::updateOrCreate(
            [
                'user_id' => $selectedKasir->id,
                'period_month' => $periodMonth,
            ],
            [
                'base_salary' => $summary['base_salary'],
                'bonus' => $summary['bonus'],
                'deductions' => $summary['deductions'],
                'total_revenue' => $summary['total_revenue'],
                'work_hours' => $summary['work_hours'],
                'work_days' => $summary['work_days'],
                'net_salary' => $summary['net_salary'],
                'status' => 'paid',
                'paid_at' => Carbon::now(),
                'notes' => 'Pembayaran gaji kasir berbasis shift ('.$summary['total_shifts'].' shift x Rp '.number_format($summary['shift_rate'], 0, ',', '.').').',
            ]
        );

        return redirect()->route('admin.payroll.index', ['kasir_id' => $selectedKasir->id, 'month' => $periodMonth])->with('success', 'Pembayaran gaji kasir '.$selectedKasir->name.' telah dikonfirmasi.');
    }
}
