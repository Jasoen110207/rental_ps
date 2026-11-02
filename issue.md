# Dokumen Perencanaan Implementasi (Implementation Plan) — Bugfix Login Kasir Baru & Otomatisasi Absensi ke Penggajian (Payroll)

> **Target Pembaca:** Junior Programmer / AI Coding Assistant (Model Hemat Biaya)
> **Stack Proyek:** Laravel 12 (PHP 8.x), Blade + Tailwind CSS, MySQL/SQLite
> **Penting:** Ikuti setiap langkah secara berurutan. Jangan melewati langkah analisis penyebab (*root cause*), modifikasi controller/model/view, maupun pengujian di bagian akhir.

---

## Ringkasan Masalah & Tujuan Perbaikan

1. **Bug #1 — Akun Kasir Baru Tidak Bisa Login (Mismatch Hashing PIN & Password):**
   Saat Admin membuat akun kasir baru melalui menu **Kelola Akun (Users)**, kasir tersebut **selalu gagal login** meskipun sudah memasukkan Email, Password, dan PIN yang 100% sesuai dengan yang dibuat Admin.
2. **Bug #2 — Kasir Login / Absen Tidak Otomatis Masuk ke Penggajian Kasir (Payroll):**
   Saat kasir berhasil login (atau melakukan absen/buka shift), data kehadirannya tidak otomatis tercatat atau tersinkronisasi ke halaman **Penggajian (Payroll)** milik kasir tersebut.

---

## FITUR / PERBAIKAN 1: Perbaikan Login Akun Kasir Baru (Email, Password, & PIN)

### 1.1. Analisis Penyebab Utama (*Root Cause*)
Kenapa akun kasir dari `DatabaseSeeder` bisa login, tetapi akun kasir baru yang dibuat Admin lewat menu `Users` tidak bisa login?

1. **Ketidakcocokan Format PIN (`Hash::make` vs Plain Text):**
   - Pada `app/Http/Controllers/UserController.php` di method `store()` (baris 53) dan `update()` (baris 100), PIN yang diinput Admin di-*hash* menggunakan Bcrypt:
     ```php
     'pin' => $validated['role'] === 'kasir' ? Hash::make($validated['pin']) : null,
     ```
     Sehingga di database, kolom `pin` berisi string hash seperti `$2y$12$abcdef...`.
   - Sebaliknya, pada `app/Http/Controllers/AuthController.php` di method `login()` (baris 39), pengecekan PIN dilakukan menggunakan perbandingan teks biasa (*plain-text comparison*):
     ```php
     if ($request->pin != $user->pin) {
         return back()->withErrors(['pin' => 'PIN kasir salah.']);
     }
     ```
   - Akibatnya, saat kasir baru mengetik PIN `123456`, sistem membandingkan `'123456' != '$2y$12$abcdef...'` yang **selalu bernilai salah (mismatch)**! Itulah sebabnya akun kasir baru tidak pernah bisa login.

2. **Potensi Double-Hashing / Inkonsistensi Password & Input Spasi:**
   - Pada `app/Models/User.php`, atribut `'password' => 'hashed'` sudah terdaftar di `casts()`.
   - Selain itu, di `AuthController::login()`, jika ada spasi tidak sengaja pada input email atau PIN, validasi bisa gagal.

---

### 1.2. Tahapan Implementasi Perbaikan Bug #1

#### Langkah 1: Perbaiki Validasi PIN di `app/Http/Controllers/AuthController.php`
Buka file `app/Http/Controllers/AuthController.php`. Pada method `login(Request $request)`, ubah pengecekan PIN agar mendukung **PIN yang di-hash (`Hash::check`)** sekaligus **PIN teks biasa (*plain text* dari seeder lama)**:

```php
// Sebelumnya di app/Http/Controllers/AuthController.php:
if ($user->role === 'kasir') {
    if (! $request->filled('pin')) {
        return back()->withErrors([
            'pin' => 'Kasir wajib memasukkan PIN 6 digit.',
        ])->onlyInput('email');
    }

    if ($request->pin != $user->pin) {
        return back()->withErrors([
            'pin' => 'PIN kasir salah.',
        ])->onlyInput('email');
    }
}
```

**Ubah menjadi:**
```php
if ($user->role === 'kasir') {
    $inputPin = trim((string) $request->input('pin', ''));

    if ($inputPin === '') {
        return back()->withErrors([
            'pin' => 'Kasir wajib memasukkan PIN 6 digit.',
        ])->onlyInput('email');
    }

    $storedPin = (string) ($user->pin ?? '');

    // Cek apakah storedPin adalah hash Bcrypt ($2y$ / $2a$ / $2b$) atau plain text
    $isPinValid = false;
    if (str_starts_with($storedPin, '$2y$') || str_starts_with($storedPin, '$2a$') || str_starts_with($storedPin, '$2b$')) {
        $isPinValid = \Illuminate\Support\Facades\Hash::check($inputPin, $storedPin);
    } else {
        $isPinValid = hash_equals($storedPin, $inputPin);
    }

    if (! $isPinValid) {
        return back()->withErrors([
            'pin' => 'PIN kasir salah.',
        ])->onlyInput('email');
    }
}
```

#### Langkah 2: Konsistensikan Penyimpanan PIN di `app/Http/Controllers/UserController.php`
Buka file `app/Http/Controllers/UserController.php`:
1. Pada method `store(Request $request)`:
   - Pastikan email disimpan dalam huruf kecil dan tanpa spasi (`strtolower(trim($validated['email']))`).
   - Simpan PIN dalam bentuk *plain text* 6 digit (`trim($validated['pin'])`) **ATAU** `Hash::make(trim($validated['pin']))` (karena Langkah 1 sudah mendukung keduanya). Namun, **disarankan menyimpan plain text 6 digit (`trim($validated['pin'])`)** jika Admin perlu melihat/mengingat kembali PIN kasir di halaman edit, atau tetap menggunakan `Hash::make` tetapi pastikan di-`trim()`.
   - Contoh kode yang direkomendasikan pada `UserController::store()`:
     ```php
     User::create([
         'name' => trim($validated['name']),
         'email' => strtolower(trim($validated['email'])),
         'role' => $validated['role'],
         'password' => $validated['password'], // Otomatis di-hash oleh cast 'password' => 'hashed' di model User
         'pin' => $validated['role'] === 'kasir' ? Hash::make(trim($validated['pin'])) : null,
     ]);
     ```
2. Pada method `update(Request $request, User $user)`:
   - Lakukan hal yang sama: gunakan `trim()` pada `pin` dan `strtolower(trim($validated['email']))`.
   - Jika `password` diisi, cukup assign `$validated['password']` (atau `Hash::make($validated['password'])`).

---

## FITUR / PERBAIKAN 2: Otomatisasi Absen Kasir Saat Login & Sinkronisasi ke Penggajian (Payroll)

### 2.1. Analisis Penyebab Utama (*Root Cause*)
Kenapa saat kasir login atau absen, datanya tidak otomatis masuk ke halaman **Penggajian Kasir (`/payroll`)**?

1. **Login Kasir Tidak Otomatis Mencatat Absensi Shift (`Shift`):**
   - Di `PayrollController::calculatePayrollForUser()`, jumlah kehadiran kasir dihitung dari tabel `shifts` berdasarkan `user_id` pada bulan berjalan:
     ```php
     $totalShifts = Shift::where('user_id', $user->id)
         ->whereBetween('start_time', [$monthStart, $monthEnd])
         ->count();
     ```
   - Namun, saat kasir login di `AuthController::login()`, sistem **hanya melakukan `Auth::login($user)` lalu redirect ke `/kasir/dashboard`** tanpa membuat record absensi `Shift` untuk kasir tersebut!
   - Lebih parah lagi: ketika di sistem sudah ada shift yang berstatus `'active'` (misalnya bawaan dari `DatabaseSeeder` milik Kasir 1, atau kasir shift sebelumnya lupa klik "Tutup Shift"), kasir yang baru login akan melihat tulisan **"SHIFT AKTIF"** di atas layar. Kasir mengira dirinya sudah terhitung absen, padahal `Shift` yang sedang aktif tersebut tercatat atas nama `user_id` kasir lain! Akibatnya, absen kasir yang baru login tetap `0` di halaman Payroll.

2. **Tabel `payrolls` (Riwayat Gaji Per Bulan) Tidak Otomatis Ter-update Saat Kasir Absen:**
   - Pada `PayrollController::index()`, tabel `Payroll` hanya dibuat ketika Admin menekan tombol **"Konfirmasi Pembayaran"** (`PayrollController::generate()`).
   - Selama Admin belum menekan tombol tersebut, atau jika Admin sudah pernah membuat data `pending` di awal bulan, penambahan jumlah shift/absen kasir yang baru login **tidak otomatis memperbarui (`sync`)** record di tabel `payrolls`.

---

### 2.2. Tahapan Implementasi Perbaikan Bug #2

#### Langkah 1: Buat Method Auto-Sync Payroll di Model `app/Models/Payroll.php`
Buka file `app/Models/Payroll.php` dan tambahkan konstanta gaji per shift serta method statis `syncForUser(User $user, ?string $periodMonth = null)` agar setiap kali kasir absen (login / buka shift / tutup shift), data di tabel `payrolls` langsung ter-update otomatis.

Tambahkan kode berikut di dalam class `Payroll` pada `app/Models/Payroll.php`:

```php
public const SHIFT_RATE = 50000;

/**
 * Sinkronisasi otomatis data penggajian kasir berdasarkan jumlah absen (shift) pada bulan terkait.
 */
public static function syncForUser(User $user, ?string $periodMonth = null): ?self
{
    if ($user->role !== 'kasir') {
        return null;
    }

    $now = \Carbon\Carbon::now();
    $periodMonth = $periodMonth ?? $now->format('Y-m');

    $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $periodMonth)->startOfMonth();
    $monthEnd = \Carbon\Carbon::createFromFormat('Y-m', $periodMonth)->endOfMonth();

    $shifts = Shift::where('user_id', $user->id)
        ->whereBetween('start_time', [$monthStart, $monthEnd])
        ->get();

    $totalShifts = $shifts->count();

    $workHours = $shifts->sum(function ($shift) use ($now) {
        $start = \Carbon\Carbon::parse($shift->start_time);
        $end = $shift->end_time ? \Carbon\Carbon::parse($shift->end_time) : $now;

        return max(0, $start->diffInMinutes($end) / 60);
    });
    $workHours = round($workHours, 1);

    $totalShiftSalary = $totalShifts * self::SHIFT_RATE;

    // Cari apakah sudah ada record payroll bulan ini
    $existingPayroll = self::where('user_id', $user->id)
        ->where('period_month', $periodMonth)
        ->first();

    // Jika sudah berstatus 'paid', kita tetap update jumlah hari/gaji atau biarkan sesuai kebijakan,
    // namun jika belum ada atau masih 'pending', wajib di-upsert otomatis.
    $bonus = $existingPayroll ? (float) $existingPayroll->bonus : 0;
    $deduction = $existingPayroll ? (float) $existingPayroll->deduction : 0;
    $netSalary = max(0, $totalShiftSalary + $bonus - $deduction);

    return self::updateOrCreate(
        [
            'user_id' => $user->id,
            'period_month' => $periodMonth,
        ],
        [
            'work_days' => $totalShifts,
            'work_hours' => $workHours,
            'base_salary' => $totalShiftSalary,
            'bonus' => $bonus,
            'deduction' => $deduction,
            'net_salary' => $netSalary,
            'status' => $existingPayroll ? $existingPayroll->status : 'pending',
        ]
    );
}
```

#### Langkah 2: Otomatisasi Pencatatan Absen (`Shift`) Saat Kasir Login di `app/Http/Controllers/AuthController.php`
Ketika kasir berhasil login di `AuthController::login()`:
1. Cek apakah sudah ada shift berstatus `'active'` di sistem:
   - Jika belum ada shift `'active'` sama sekali, **ATAU** jika shift `'active'` saat ini adalah milik kasir lain (`$activeShift->user_id !== $user->id`), **ATAU** jika shift `'active'` tersebut adalah sisa dari hari sebelumnya (`!Carbon::parse($activeShift->start_time)->isToday()`):
     - Tutup otomatis shift lama yang masih menggantung (`status => 'closed'`, `end_time => Carbon::now()`).
     - Cek apakah kasir `$user->id` sudah memiliki catatan shift pada tipe shift saat ini (`Shift::detectShiftType()`) di hari ini (`Carbon::today()`).
     - Jika sudah ada shift milik kasir tersebut hari ini pada tipe shift yang sama, aktifkan kembali shift tersebut (`status => 'active'`, `end_time => null`).
     - Jika belum ada, **buatkan record `Shift` baru secara otomatis** untuk `$user->id` dengan modal awal diambil dari `leftover_cash` shift terakhir yang ditutup!
2. Panggil `\App\Models\Payroll::syncForUser($user)` agar absensi tersebut langsung masuk ke tabel `payrolls` detik itu juga!

**Contoh kode yang ditambahkan di `AuthController::login()` tepat sebelum `return redirect()->route('kasir.dashboard')`:**

```php
if ($user->role === 'kasir') {
    $now = \Carbon\Carbon::now();
    $currentShiftType = \App\Models\Shift::detectShiftType();

    // 1. Cek shift aktif saat ini
    $activeShift = \App\Models\Shift::where('status', 'active')->first();

    // Jika ada shift aktif tapi milik kasir lain atau dari hari kemarin, tutup shift tersebut
    if ($activeShift && ($activeShift->user_id !== $user->id || ! \Carbon\Carbon::parse($activeShift->start_time)->isToday())) {
        $activeShift->update([
            'status' => 'closed',
            'end_time' => $now,
            'notes' => trim(($activeShift->notes ?? '') . ' (Ditutup otomatis saat pergantian login kasir)'),
        ]);
        \App\Models\Payroll::syncForUser($activeShift->user);
        $activeShift = null;
    }

    // 2. Jika belum ada shift aktif untuk kasir yang baru login ini
    if (! $activeShift) {
        // Cek apakah kasir ini sudah pernah absen di hari & tipe shift yang sama
        $existingTodayShift = \App\Models\Shift::where('user_id', $user->id)
            ->whereDate('start_time', $now->toDateString())
            ->where('shift_type', $currentShiftType)
            ->first();

        if ($existingTodayShift) {
            $existingTodayShift->update([
                'status' => 'active',
                'end_time' => null,
            ]);
        } else {
            $lastClosedShift = \App\Models\Shift::where('status', 'closed')
                ->latest('end_time')
                ->first();

            $defaultStartingCash = $lastClosedShift ? (float) $lastClosedShift->leftover_cash : 200000;

            \App\Models\Shift::create([
                'user_id' => $user->id,
                'shift_type' => $currentShiftType,
                'start_time' => $now,
                'starting_cash' => $defaultStartingCash,
                'status' => 'active',
                'notes' => 'Absen otomatis saat login kasir.',
            ]);
        }
    }

    // 3. Sinkronkan langsung ke Penggajian (Payroll)
    \App\Models\Payroll::syncForUser($user);

    return redirect()->route('kasir.dashboard');
}
```

#### Langkah 3: Panggil `Payroll::syncForUser()` Saat Buka/Tutup Shift Manual
Agar data penggajian selalu sinkron baik saat kasir login maupun saat buka/tutup shift manual:
1. Buka `app/Http/Controllers/KasirController.php`:
   - Di akhir method `startShift(Request $request)` (sebelum `return redirect`), tambahkan:
     ```php
     \App\Models\Payroll::syncForUser(Auth::user());
     ```
   - Di akhir method `endShift(Request $request)` (sebelum `return redirect`), tambahkan:
     ```php
     if ($activeShift->user) {
         \App\Models\Payroll::syncForUser($activeShift->user);
     }
     ```
2. Buka `app/Http/Controllers/ShiftController.php`:
   - Di akhir method `startShift(Request $request)`, tambahkan:
     ```php
     $shiftUser = \App\Models\User::find($userId);
     if ($shiftUser) {
         \App\Models\Payroll::syncForUser($shiftUser);
     }
     ```
   - Di akhir method `endShift(Request $request, Shift $shift)`, tambahkan:
     ```php
     if ($shift->user) {
         \App\Models\Payroll::syncForUser($shift->user);
     }
     ```

#### Langkah 4: Sinkronkan Otomatis di `PayrollController::index()`
Buka `app/Http/Controllers/PayrollController.php`.
Pada method `index(Request $request)`:
1. Sebelum mengambil `$payrollHistory`, panggil `Payroll::syncForUser($selectedUser, $periodMonth)` untuk kasir yang sedang dipilih (atau untuk seluruh kasir jika `$users` di-loop).
2. Dengan demikian, begitu Admin atau Kasir membuka menu **Penggajian (`/payroll`)**, data gaji bulan berjalan **sudah otomatis muncul di Rincian Perhitungan Gaji dan di tabel Riwayat Gaji Per Bulan** tanpa harus menunggu Admin mengklik tombol manual!
3. Ambil juga daftar riwayat absensi shift (`$attendanceShifts`) milik kasir pada bulan tersebut untuk ditampilkan di halaman Payroll sebagai bukti kehadiran:
   ```php
   $attendanceShifts = Shift::where('user_id', $selectedUser->id)
       ->whereBetween('start_time', [$monthStart, $monthEnd])
       ->orderBy('start_time', 'desc')
       ->get();
   ```
   Lalu kirimkan `'attendanceShifts'` ke `view('payroll.index', compact(...))`.

#### Langkah 5: Tampilkan Tabel Bukti Absensi Shift di `resources/views/payroll/index.blade.php`
Buka `resources/views/payroll/index.blade.php`:
- Tambahkan bagian/tabel **"Riwayat Kehadiran / Absen Shift Bulan Ini"** di atas atau di bawah tabel *Riwayat Gaji Per Bulan* yang menampilkan:
  - Tanggal & Jam Masuk (`start_time`)
  - Jam Selesai (`end_time` atau badge `SEDANG AKTIF`)
  - Tipe Shift (`Pagi` / `Sore`)
  - Upah Shift (`Rp 50.000`)
- Dengan adanya tabel ini, Admin maupun Kasir bisa melihat secara transparan setiap kali kasir login/absen, lengkap dengan jam masuknya dan perhitungannya ke dalam penggajian.

---

## Tahap Pengujian & Verifikasi (Testing Checklist)

Setelah mengimplementasikan seluruh langkah di atas, jalankan pengujian berikut:

### 1. Automated Test (PHPUnit)
Buat atau perbarui feature test (misal di `tests/Feature/KasirLoginAndPayrollTest.php`) untuk menguji:
1. **Test Login Kasir Baru:**
   - Admin membuat akun kasir baru via `POST /users` dengan `email`, `password`, dan `pin` (`123456`).
   - Kasir baru melakukan `POST /login` menggunakan `email`, `password`, dan `pin` tersebut.
   - Pastikan response redirect ke `/kasir/dashboard` dan `Auth::check()` bernilai `true`.
2. **Test Otomatis Absen & Masuk Payroll Saat Kasir Login:**
   - Saat kasir baru login via `POST /login`, pastikan otomatis tercipta 1 record di tabel `shifts` untuk `user_id` kasir tersebut dengan `status = 'active'`.
   - Pastikan tabel `payrolls` untuk `user_id` kasir tersebut otomatis memiliki record bulan berjalan dengan `work_days = 1`, `base_salary = 50000`, dan `net_salary = 50000`.
   - Buka halaman `GET /payroll?user_id={id}` dan pastikan angka `1 Shift` serta gaji `Rp 50.000` langsung tampil.

Jalankan perintah test:
```bash
php artisan test --compact --filter=KasirLoginAndPayrollTest
```

### 2. Code Formatting
Jalankan Laravel Pint sebelum menyelesaikan pekerjaan:
```bash
vendor/bin/pint --dirty --format agent
```
