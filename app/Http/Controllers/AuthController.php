<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Authenticate user & issue Sanctum token.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'pin' => 'required|string',
        ]);

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = $request->user() ?? Auth::user();

        $inputPin = trim((string) $request->input('pin', ''));
        $storedPin = (string) ($user->pin ?? '');

        $isPinValid = false;
        if ($storedPin === '') {
            if ($inputPin === '123456' || $user->role === 'admin') {
                $isPinValid = true;
                $user->update(['pin' => Hash::make($inputPin !== '' ? $inputPin : '123456')]);
            }
        } elseif (str_starts_with($storedPin, '$2y$') || str_starts_with($storedPin, '$2a$') || str_starts_with($storedPin, '$2b$')) {
            $isPinValid = Hash::check($inputPin, $storedPin);
        } else {
            $isPinValid = hash_equals($storedPin, $inputPin);
        }

        if (! $isPinValid) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'PIN salah.',
                ], 401);
            }

            return back()->withErrors(['pin' => 'PIN kasir salah.'])->onlyInput('email');
        }

        if ($user->role === 'kasir') {
            $now = Carbon::now();
            $currentShiftType = Shift::detectShiftType($now);

            // 1. Cek shift aktif saat ini di sistem
            $activeShift = Shift::where('status', 'active')->first();

            // Jika ada shift aktif tapi milik kasir lain atau dari hari kemarin, tutup shift tersebut
            if ($activeShift && ($activeShift->user_id !== $user->id || ! Carbon::parse($activeShift->start_time)->isToday())) {
                $activeShift->update([
                    'status' => 'closed',
                    'end_time' => $now,
                    'notes' => trim(($activeShift->notes ?? '').' (Ditutup otomatis saat pergantian login kasir)'),
                ]);
                if ($activeShift->user) {
                    Payroll::syncForUser($activeShift->user);
                }
                $activeShift = null;
            }

            // 2. Jika belum ada shift aktif untuk kasir yang baru login ini
            if (! $activeShift) {
                $existingTodayShift = Shift::where('user_id', $user->id)
                    ->whereDate('start_time', $now->toDateString())
                    ->where('shift_type', $currentShiftType)
                    ->first();

                if ($existingTodayShift) {
                    $existingTodayShift->update([
                        'status' => 'active',
                        'end_time' => null,
                    ]);
                } else {
                    $lastClosedShift = Shift::where('status', 'closed')
                        ->latest('end_time')
                        ->first();

                    $defaultStartingCash = $lastClosedShift ? (int) $lastClosedShift->leftover_cash : 200000;

                    Shift::create([
                        'user_id' => $user->id,
                        'shift_type' => $currentShiftType,
                        'start_time' => $now,
                        'starting_cash' => $defaultStartingCash,
                        'total_revenue' => 0,
                        'reported_revenue' => 0,
                        'leftover_cash' => 0,
                        'transactions_count' => 0,
                        'status' => 'active',
                        'notes' => 'Absen otomatis saat login kasir.',
                    ]);
                }
            }

            // 3. Sinkronkan langsung ke Penggajian (Payroll)
            Payroll::syncForUser($user);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil.',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ]);
        }

        $request->session()->regenerate();
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('kasir.dashboard'));
    }

    /**
     * Revoke user's current token.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Berhasil logout.',
            ]);
        }

        return redirect()->route('login');
    }

    /**
     * Return current authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}
