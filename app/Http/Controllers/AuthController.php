<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Support login with either email or username/name
        $user = User::where('email', $credentials['email'])
            ->orWhere('name', $credentials['email'])
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Auto-check or initialize shift for cashier
            $activeShift = Shift::where('user_id', $user->id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$activeShift) {
                // Check if any active shift exists in system
                $systemActiveShift = Shift::where('status', 'active')->latest()->first();
                if (!$systemActiveShift) {
                    Shift::create([
                        'user_id' => $user->id,
                        'start_time' => Carbon::now(),
                        'starting_cash' => 200000,
                        'total_revenue' => 0,
                        'transactions_count' => 0,
                        'notes' => 'Shift baru dimulai saat login.',
                        'status' => 'active',
                    ]);
                }
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email/Username atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
