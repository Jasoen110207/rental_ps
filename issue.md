# Dokumen Perencanaan Implementasi Fitur (Issue & Action Plan)

Dokumen ini disusun sebagai panduan teknis langkah demi langkah (*step-by-step implementation guide*) untuk **Junior Programmer** atau **AI Model**. Setiap tahapan sudah mencantumkan nama file, struktur database, logika bisnis, hingga potongan kode acuan agar tidak terjadi kesalahan atau kebingungan saat implementasi.

---

## Ringkasan Kebutuhan (Overview)

1. **Issue #1 — Keamanan URL Pelanggan (Customer Self-Service) dengan Access Token**
   Mengganti parameter ID numerik (`/customer/1`, `/customer/2`) menjadi token acak unik (`/customer/{access_token}`) agar pelanggan tidak bisa mengganti angka di URL secara iseng untuk memesan F&B atau menambah waktu di meja orang lain.
2. **Issue #2 — Perhitungan Gaji Kasir Berbasis Shift (Rp 50.000 / Shift)**
   Menghapus sistem gaji pokok bulanan statis. Gaji kasir dihitung murni berdasarkan jumlah kehadiran (absen shift) dikalikan **Rp 50.000 per shift**.
3. **Issue #3 — Input Uang Tinggalan & Total Uang Dihasilkan saat Tutup Shift (Sistem 2 Shift: Pagi & Sore)**
   Menambahkan input wajib **Total Uang Dihasilkan** dan **Uang Tinggalan (Modal Besok Pagi / Shift Berikutnya)** ketika kasir menutup shift, serta mengotomatisasi nilai **Modal Kas Awal** pada pembukaan shift berikutnya dari uang tinggalan tersebut.

---

## ISSUE #1: Keamanan URL Customer Menggunakan Access Token

### 1. Masalah Saat Ini
* Route pelanggan di `routes/web.php` masih menggunakan ID urutan database (`/{tv_id}`), contoh: `http://rental_ps.test/customer/1`.
* Pelanggan yang usil dapat mengubah angka `1` menjadi `2`, `3`, dst. di browser HP mereka dan memesan F&B banyak-banyak ke meja pelanggan lain.
* Di halaman `resources/views/customer/order.blade.php` (baris 27–34), terdapat dropdown **"Pindah meja"** yang memungkinkan pelanggan berpindah meja secara bebas.

### 2. File yang Terdampak
1. `database/migrations/xxxx_xx_xx_xxxxxx_add_access_token_to_tvs_table.php` *(Buat Baru)*
2. `app/Models/Tv.php`
3. `database/seeders/DatabaseSeeder.php`
4. `routes/web.php`
5. `app/Http/Controllers/CustomerController.php`
6. `app/Http/Controllers/UnitController.php`
7. `resources/views/customer/index.blade.php`
8. `resources/views/customer/order.blade.php`
9. `resources/views/admin/units/index.blade.php`
10. `resources/views/admin/units/qr.blade.php`
11. `resources/views/kasir/unit.blade.php`

### 3. Tahapan Implementasi

#### Tahap 1.1: Buat Migration untuk Kolom `access_token` pada Tabel `tvs`
Jalankan perintah Artisan:
```bash
php artisan make:migration add_access_token_to_tvs_table --table=tvs --no-interaction
```
Isi file migration:
* Tambahkan kolom `access_token` bertipe `string(64)->unique()->nullable()->after('id')`.
* Isi token secara otomatis untuk semua baris `tvs` yang sudah ada menggunakan `Str::random(32)`.
* Setelah terisi, pastikan setiap unit memiliki `access_token` unik.

Contoh kode di dalam method `up()`:
```php
Schema::table('tvs', function (Blueprint $table) {
    $table->string('access_token', 64)->nullable()->unique()->after('id');
});

// Generate token untuk data TV yang sudah ada di database
$tvs = DB::table('tvs')->get();
foreach ($tvs as $tv) {
    DB::table('tvs')
        ->where('id', $tv->id)
        ->update(['access_token' => \Illuminate\Support\Str::random(32)]);
}
```

#### Tahap 1.2: Auto-Generate `access_token` di Model `Tv` & Seeder
Buka `app/Models/Tv.php`:
* Tambahkan event `booted()` agar setiap kali unit TV baru dibuat (`creating`), kolom `access_token` otomatis terisi dengan `Str::random(32)` jika belum ada:
```php
protected static function booted(): void
{
    static::creating(function (Tv $tv) {
        if (empty($tv->access_token)) {
            $tv->access_token = \Illuminate\Support\Str::random(32);
        }
    });
}
```
* Opsional tapi direkomendasikan: Gunakan `access_token` untuk pencarian helper atau Route Model Binding.
* Buka `database/seeders/DatabaseSeeder.php`: Tambahkan `'access_token' => \Illuminate\Support\Str::random(32)` pada setiap item array `$tvs` (Meja 1 s/d 8).

#### Tahap 1.3: Ubah Parameter Route di `routes/web.php`
Buka `routes/web.php` (baris 50–61) dan ubah parameter `{tv_id}` menjadi `{token}`:
```php
// Public Customer Routes (Accessed via QR Code - No Login Required)
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/order/{token}', [CustomerController::class, 'order'])->name('order');
    Route::get('/{token}', [CustomerController::class, 'index'])->name('index');
    Route::post('/{token}/add-time', [CustomerController::class, 'requestAddTime'])->name('add-time');
    Route::post('/{token}/order-food', [CustomerController::class, 'requestFood'])->name('order-food');
    Route::post('/{token}/call-cashier', [CustomerController::class, 'callCashier'])->name('call-cashier');
    Route::get('/{token}/status', [CustomerController::class, 'apiStatus'])->name('status');
});

// QR short url alias
Route::get('/scan/{token}', function (string $token) {
    return redirect()->route('customer.index', ['token' => $token]);
});
```

#### Tahap 1.4: Update `CustomerController.php` & `UnitController.php`
1. Buka `app/Http/Controllers/CustomerController.php`:
   * Buat helper method privat di dalam `CustomerController`:
     ```php
     protected function findTvByToken(string $token): Tv
     {
         return Tv::where('access_token', $token)->firstOrFail();
     }
     ```
   * Pada method `index($token)`, `requestAddTime(Request $request, $token)`, `requestFood(Request $request, $token)`, `callCashier(Request $request, $token)`, `apiStatus($token)`, dan `order(Request $request, $token)`:
     Ganti `Tv::findOrFail($tvId)` menjadi `$tv = $this->findTvByToken($token);`.
   * Pada method `order(Request $request, string $token)`:
     Hapus logika pencarian `tv_id` dari query string dan hapus pengiriman seluruh `$tvs` untuk pindah meja agar pelanggan terkunci hanya pada meja sesuai token QR yang di-scan.
2. Buka `app/Http/Controllers/UnitController.php`:
   * Pada method `qr($id)` (baris 60–66):
     Ubah pembuatan URL QR menjadi menggunakan `access_token`:
     ```php
     public function qr($id)
     {
         $tv = Tv::findOrFail($id);
         if (empty($tv->access_token)) {
             $tv->update(['access_token' => \Illuminate\Support\Str::random(32)]);
         }
         $customerUrl = route('customer.index', ['token' => $tv->access_token]);

         return view('admin.units.qr', compact('tv', 'customerUrl'));
     }
     ```

#### Tahap 1.5: Update Seluruh View yang Memanggil Route `customer.*`
1. `resources/views/customer/index.blade.php`:
   * Ganti semua pemanggilan `route('customer.call-cashier', $tv->id)` menjadi `route('customer.call-cashier', $tv->access_token)` (baris 148).
   * Ganti `route('customer.add-time', $tv->id)` menjadi `route('customer.add-time', $tv->access_token)` (baris 256).
   * Ganti `route('customer.order-food', $tv->id)` menjadi `route('customer.order-food', $tv->access_token)` (baris 308).
   * Ganti `route("customer.status", $tv->id)` menjadi `route("customer.status", $tv->access_token)` (baris 456).
2. `resources/views/customer/order.blade.php`:
   * **PENTING**: Hapus form `<form method="GET" ...> Pindah meja <select name="tv_id">` pada baris 27–34! Pelanggan tidak boleh pindah meja dari halaman order.
   * Ubah `data-tv-id="{{ $tv->id }}"` pada baris 6 menjadi `data-tv-token="{{ $tv->access_token }}"`.
   * Ganti semua `route('customer.index', $tv->id)`, `route('customer.order-food', $tv->id)`, `route('customer.call-cashier', $tv->id)`, `route('customer.add-time', $tv->id)` agar menggunakan `$tv->access_token`.
   * Pada script JS baris 270 & 286, ganti `fetch('/customer/' + tvId + '/status')` menjadi menggunakan URL dari `{{ route('customer.status', $tv->access_token) }}`.
3. `resources/views/admin/units/index.blade.php` (baris 48–50):
   * Ganti `route('customer.index', $unit->id)` menjadi `route('customer.index', $unit->access_token)`.
4. `resources/views/kasir/unit.blade.php` (baris 37 & 58):
   * Ganti `route('customer.index', $u->id)` menjadi `route('customer.index', $u->access_token)`.
   * Ganti `route('customer.index', $selected->id)` pada generator QR image menjadi `route('customer.index', $selected->access_token)`.

---

## ISSUE #2: Perhitungan Gaji Kasir Rp 50.000 per Shift (Tanpa Gaji Pokok)

### 1. Masalah Saat Ini
* Di `app/Http/Controllers/PayrollController.php` (baris 39–55):
  * `$baseSalary` masih di-hardcode `2500000` (Rp 2.500.000).
  * Ada perhitungan `$bonus` 10% dari omset dan `$deductions` Rp 150.000.
* Padahal aturan bisnis yang diinginkan adalah: **Gaji kasir dihitung murni Rp 50.000 setiap shift (berdasarkan absen/kehadiran shift kasir tersebut), tanpa gaji pokok bulanan.**
* Selain itu, di `PayrollController::confirmPayment()` (baris 193) terdapat bug redirect ke `route('payroll.index')` yang seharusnya `route('admin.payroll.index')`.

### 2. File yang Terdampak
1. `app/Http/Controllers/PayrollController.php`
2. `resources/views/payroll/index.blade.php`
3. `resources/views/payroll/slip.blade.php`

### 3. Tahapan Implementasi

#### Tahap 2.1: Ubah Logika Kalkulasi di `PayrollController.php`
Buka `app/Http/Controllers/PayrollController.php`:
1. Tambahkan konstanta tarif per shift di dalam class `PayrollController`:
   ```php
   public const SHIFT_RATE = 50000;
   ```
2. Ubah method `calculatePayrollForUser(User $user, ?string $periodMonth = null): array`:
   * Hitung jumlah shift (`$totalShifts`) dari tabel `shifts` milik `$user->id` pada bulan `$periodMonth` (gunakan shift yang ada pada rentang `$monthStart` sampai `$monthEnd`).
   * Set tarif per shift = `self::SHIFT_RATE` (`50000`).
   * Hitung total gaji: `$netSalary = $totalShifts * self::SHIFT_RATE;`.
   * Tidak menggunakan gaji pokok statis, bonus, atau potongan tetap:
     * `'shift_rate' => self::SHIFT_RATE`
     * `'total_shifts' => $totalShifts`
     * `'work_days' => $totalShifts` *(kompatibilitas kolom database `payrolls.work_days`)*
     * `'base_salary' => $netSalary` *(kompatibilitas kolom database `payrolls.base_salary`)*
     * `'bonus' => 0`
     * `'deductions' => 0`
     * `'net_salary' => $netSalary`

   Contoh implementasi `calculatePayrollForUser()`:
   ```php
   protected function calculatePayrollForUser(User $user, ?string $periodMonth = null): array
   {
       $periodMonth ??= Carbon::now()->format('Y-m');

       $monthStart = Carbon::createFromFormat('Y-m', $periodMonth)->startOfMonth();
       $monthEnd = Carbon::createFromFormat('Y-m', $periodMonth)->endOfMonth();

       $shiftRate = self::SHIFT_RATE; // Rp 50.000 per shift

       $totalShifts = Shift::where('user_id', $user->id)
           ->whereBetween('start_time', [$monthStart, $monthEnd])
           ->count();

       $workHours = (int) Shift::where('user_id', $user->id)
           ->whereBetween('start_time', [$monthStart, $monthEnd])
           ->sum(DB::raw('TIMESTAMPDIFF(HOUR, start_time, COALESCE(end_time, NOW()))'));

       $totalRevenue = (int) PlaySession::where('user_id', $user->id)
           ->whereBetween('created_at', [$monthStart, $monthEnd])
           ->sum('total_amount');

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
   ```
3. Pada method `index()`, `printSlip()`, dan `downloadPdf()` di `PayrollController.php`:
   * Sertakan `'shift_rate' => $summary['shift_rate']` dan `'total_shifts' => $summary['total_shifts']` ke dalam array `$kasir` dan `$payroll`.
   * Cek apakah bulan tersebut sudah pernah dibayar (`Payroll::where('user_id', $selectedKasir->id)->where('period_month', $periodMonth)->where('status', 'paid')->exists()`) agar badge status menampilkan `'Sudah Dibayar'` jika sudah dikonfirmasi, atau `'Menunggu Konfirmasi'` jika belum.
4. Pada method `confirmPayment()` (baris 193):
   * Perbaiki nama route redirect dari `route('payroll.index', ...)` menjadi `route('admin.payroll.index', ['kasir_id' => $selectedKasir->id, 'month' => $periodMonth])`.

#### Tahap 2.2: Sesuaikan Tampilan `resources/views/payroll/index.blade.php` & `slip.blade.php`
1. Buka `resources/views/payroll/index.blade.php`:
   * Pada kartu metrik atas (baris 51–57): Ubah label **"Hari Kerja"** menjadi **"Total Absen Shift"** dengan nilai `{{ $kasir['total_work_days'] }} Shift`.
   * Pada bagian **"Rincian Komponen Gaji"** (baris 78–100):
     * Ganti baris **Gaji Pokok**, **Bonus**, dan **Potongan** menjadi rincian berbasis shift yang jelas:
       * **Tarif per Shift**: `Rp {{ number_format($kasir['shift_rate'] ?? 50000, 0, ',', '.') }} / shift`
       * **Jumlah Kehadiran (Absen Shift)**: `{{ $kasir['total_work_days'] }} Shift`
       * **Perhitungan Gaji**: `{{ $kasir['total_work_days'] }} Shift × Rp {{ number_format($kasir['shift_rate'] ?? 50000, 0, ',', '.') }}`
       * **Total Gaji Bersih / Nett**: `Rp {{ number_format($kasir['net_salary'], 0, ',', '.') }}`
2. Buka `resources/views/payroll/slip.blade.php`:
   * Sesuaikan cetakan slip gaji & PDF agar menampilkan:
     * **Jumlah Kehadiran Shift**: `{{ $payroll['total_shifts'] ?? 0 }} Shift`
     * **Upah per Shift**: `Rp {{ number_format($payroll['shift_rate'] ?? 50000, 0, ',', '.') }}`
     * **Total Gaji Diterima**: `Rp {{ number_format($payroll['net_salary'], 0, ',', '.') }}`

---

## ISSUE #3: Input Uang Tinggalan & Total Uang Dihasilkan saat Tutup Shift (2 Shift: Pagi & Sore)

### 1. Konteks Operasional & Masalah Saat Ini
* Rental memiliki **2 Shift Operasional**:
  * **Shift Pagi (Shift 1)**: `08:00 – 15:00`
  * **Shift Sore (Shift 2)**: `15:00 – 21:00`
* Saat ini, ketika kasir menutup shift (`ShiftController@endShift` dan `KasirController@endShift`), form hanya meminta `notes` (catatan).
* Pemilik rental menginginkan:
  1. Saat menutup shift, kasir **wajib** memasukkan:
     * **Total uang yang dihasilkan pada shift tersebut** (`reported_revenue` — uang hasil pendapatan shift yang dihitung fisik oleh kasir).
     * **Uang tinggalan** (`leftover_cash` — uang yang sengaja ditinggalkan di laci kasir sebagai modal kembalian untuk shift berikutnya / besok paginya).
  2. Ketika kasir shift berikutnya (terutama **Shift Pagi besok paginya**) membuka shift baru, kolom **Modal Kas Awal (`starting_cash`)** otomatis terisi (*auto-filled*) dengan nominal **Uang Tinggalan (`leftover_cash`)** dari shift terakhir yang ditutup, sehingga kasir pagi tidak bingung mencari uang kembalian.

### 2. File yang Terdampak
1. `database/migrations/xxxx_xx_xx_xxxxxx_add_handover_fields_to_shifts_table.php` *(Buat Baru)*
2. `app/Models/Shift.php`
3. `app/Http/Controllers/ShiftController.php`
4. `app/Http/Controllers/KasirController.php`
5. `resources/views/admin/shifts/index.blade.php`
6. `resources/views/kasir/sift.blade.php`
7. `database/seeders/DatabaseSeeder.php`

### 3. Tahapan Implementasi

#### Tahap 3.1: Buat Migration untuk Kolom Baru pada Tabel `shifts`
Jalankan perintah Artisan:
```bash
php artisan make:migration add_handover_fields_to_shifts_table --table=shifts --no-interaction
```
Tambahkan kolom berikut pada tabel `shifts`:
* `shift_type`: `enum('pagi', 'sore')` dengan default `'pagi'` (iletakkan setelah `user_id`).
  * Shift Pagi: `08:00 - 15:00`
  * Shift Sore: `15:00 - 21:00`
* `reported_revenue`: `integer` dengan default `0` (diletakkan setelah `total_revenue`) — menyimpan total uang yang dihasilkan pada shift tersebut berdasarkan input kasir saat tutup shift.
* `leftover_cash`: `integer` dengan default `0` (diletakkan setelah `reported_revenue`) — menyimpan uang tinggalan untuk modal kembalian shift berikutnya / besok pagi.

Contoh kode migration:
```php
public function up(): void
{
    Schema::table('shifts', function (Blueprint $table) {
        $table->enum('shift_type', ['pagi', 'sore'])->default('pagi')->after('user_id');
        $table->integer('reported_revenue')->default(0)->after('total_revenue');
        $table->integer('leftover_cash')->default(0)->after('reported_revenue');
    });
}

public function down(): void
{
    Schema::table('shifts', function (Blueprint $table) {
        $table->dropColumn(['shift_type', 'reported_revenue', 'leftover_cash']);
    });
}
```

#### Tahap 3.2: Tambahkan Helper Penentu Shift Pagi / Sore di Model `Shift`
Buka `app/Models/Shift.php`:
* Tambahkan helper statis atau method untuk mendeteksi shift secara otomatis berdasarkan jam sekarang (08:00–14:59 = `pagi`, 15:00–21:00 = `sore`) serta label jamnya:
```php
public static function detectShiftType(?\Carbon\Carbon $time = null): string
{
    $time ??= \Carbon\Carbon::now();

    return ($time->hour >= 8 && $time->hour < 15) ? 'pagi' : 'sore';
}

public function getShiftLabelAttribute(): string
{
    return $this->shift_type === 'sore'
        ? 'Shift Sore (15:00 - 21:00)'
        : 'Shift Pagi (08:00 - 15:00)';
}
```

#### Tahap 3.3: Update Logika Buka & Tutup Shift di `ShiftController.php` dan `KasirController.php`

> **CATATAN PENTING (Bug Fix PIN Kasir):**
> Di `AuthController.php` dan `DatabaseSeeder.php`, kolom `users.pin` disimpan sebagai teks biasa (`'123456'`), tetapi di `ShiftController@startShift` (baris 74) dan `KasirController@startShift` (baris 658) menggunakan `Hash::check()`. Pastikan pengecekan PIN mendukung keduanya agar tidak gagal saat buka shift:
> ```php
> $userPin = (string) auth()->user()->pin;
> $pinValid = ($validated['pin'] === $userPin) || \Illuminate\Support\Facades\Hash::check($validated['pin'], $userPin);
> if (! $pinValid) {
>     return back()->with('error', 'PIN yang Anda masukkan salah!');
> }
> ```

1. **Pada Method Tampilan Shift (`ShiftController@index` dan `KasirController@sift`)**:
   * Ambil data shift terakhir yang sudah ditutup (`status = 'closed'`):
     ```php
     $lastClosedShift = Shift::where('status', 'closed')->latest('end_time')->first();
     $suggestedStartingCash = $lastClosedShift ? $lastClosedShift->leftover_cash : 200000;
     $defaultShiftType = Shift::detectShiftType();
     ```
   * Kirimkan `$lastClosedShift`, `$suggestedStartingCash`, dan `$defaultShiftType` ke view (`admin.shifts.index` dan `kasir.sift`).

2. **Pada Method Buka Shift (`ShiftController@startShift` dan `KasirController@startShift`)**:
   * Validasi input:
     ```php
     $validated = $request->validate([
         'shift_type' => 'required|in:pagi,sore',
         'starting_cash' => 'required|numeric|min:0',
         'pin' => 'required|string',
         'notes' => 'nullable|string',
     ]);
     ```
   * Simpan `'shift_type' => $validated['shift_type']` dan `'starting_cash' => (int) $validated['starting_cash']` saat `Shift::create(...)`.

3. **Pada Method Tutup Shift (`ShiftController@endShift` dan `KasirController@endShift`)**:
   * Ubah validasi agar **wajib** mengisi `reported_revenue` (total uang yang dihasilkan pada shift tersebut) dan `leftover_cash` (uang tinggalan untuk modal besok pagi / shift berikutnya):
     ```php
     $validated = $request->validate([
         'reported_revenue' => 'required|numeric|min:0',
         'leftover_cash' => 'required|numeric|min:0',
         'notes' => 'nullable|string',
     ]);
     ```
   * Hitung omset sistem (`$systemRevenue`) dari `PlaySession` yang selesai selama shift berlangsung (atau gunakan nilai yang sudah terakumulasi).
   * Update record `$shift`:
     ```php
     $shift->update([
         'end_time' => Carbon::now(),
         'total_revenue' => $systemRevenue,
         'reported_revenue' => (int) $validated['reported_revenue'],
         'leftover_cash' => (int) $validated['leftover_cash'],
         'transactions_count' => $txCount,
         'notes' => $validated['notes'] ?? $shift->notes,
         'status' => 'closed',
     ]);
     ```

#### Tahap 3.4: Update Modal & Tabel di `resources/views/admin/shifts/index.blade.php` dan `resources/views/kasir/sift.blade.php`
Lakukan perubahan UI pada kedua file Blade tersebut:

1. **Pada Modal Buka Shift Baru (`modal-start-shift` & `kasir-start-shift`)**:
   * Tambahkan pilihan **Pilih Shift**:
     * `Shift Pagi (08:00 - 15:00)` (`value="pagi"`)
     * `Shift Sore (15:00 - 21:00)` (`value="sore"`)
     * Otomatis terpilih (`selected`) sesuai `$defaultShiftType`.
   * Pada input **Modal Kas Awal (`starting_cash`)**:
     * Set `value="{{ $suggestedStartingCash }}"`.
     * Tambahkan keterangan kecil di bawah input:
       *"Otomatis terisi dari Uang Tinggalan shift sebelumnya (Rp {{ number_format($suggestedStartingCash, 0, ',', '.') }}) untuk modal kembalian."*

2. **Pada Modal Tutup Shift (`modal-end-shift` & `kasir-end-shift`)**:
   * Tampilkan informasi ringkas:
     * **Modal Awal Shift Ini**: `Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}`
     * **Omset Tercatat di Sistem**: `Rp {{ number_format($liveShiftRevenue, 0, ',', '.') }}`
   * Tambahkan 2 input wajib (`required`):
     1. **Total Uang yang Dihasilkan pada Shift Ini (Rp)** (`name="reported_revenue"`):
        ```html
        <div>
          <label class="block text-xs uppercase font-bold mb-1">Total Uang Dihasilkan Shift Ini (Rp) *</label>
          <input type="number" name="reported_revenue" required min="0" step="1000" value="{{ $liveShiftRevenue }}" class="w-full px-3 py-2 border-2 border-on-surface text-sm font-bold">
          <p class="text-[10px] text-on-surface-variant mt-0.5">Masukkan total pendapatan uang dari hasil transaksi pada shift ini.</p>
        </div>
        ```
     2. **Uang Tinggalan / Modal untuk Shift Besok Pagi (Rp)** (`name="leftover_cash"`):
        ```html
        <div>
          <label class="block text-xs uppercase font-bold mb-1">Uang Tinggalan / Modal Kembalian Besok Pagi (Rp) *</label>
          <input type="number" name="leftover_cash" required min="0" step="1000" value="{{ $activeShift->starting_cash }}" class="w-full px-3 py-2 border-2 border-on-surface text-sm font-bold">
          <p class="text-[10px] text-on-surface-variant mt-0.5">Uang yang ditinggalkan di laci kasir sebagai modal kembalian untuk shift berikutnya / besok pagi.</p>
        </div>
        ```

3. **Pada Tabel Riwayat Shift**:
   * Tampilkan badge jenis shift (`Pagi 08:00-15:00` / `Sore 15:00-21:00`).
   * Tampilkan kolom **Modal Awal (`starting_cash`)**, **Uang Dihasilkan (`reported_revenue` / `total_revenue`)**, dan **Uang Tinggalan (`leftover_cash`)** agar admin maupun kasir shift pagi berikutnya bisa melihat jelas berapa uang tinggalan yang ditinggalkan.

---

## Urutan Eksekusi & Checklist Verifikasi (Untuk Junior Programmer / AI)

Ikuti urutan langkah ini saat mulai mengerjakan:

- [ ] **Langkah 1**: Buat dan jalankan kedua migration (`add_access_token_to_tvs_table` dan `add_handover_fields_to_shifts_table`) menggunakan `php artisan migrate`.
- [ ] **Langkah 2**: Kerjakan **Issue #1** (Model `Tv`, `routes/web.php`, `CustomerController`, `UnitController`, dan view customer/unit).
  - *Cara Test*: Buka `/admin/units`, klik **Cetak QR**, pastikan URL QR bukan lagi `/customer/1` melainkan `/customer/{32_karakter_token}`. Coba akses `/customer/1` dan pastikan menghasilkan `404 Not Found`.
- [ ] **Langkah 3**: Kerjakan **Issue #2** (`PayrollController`, `resources/views/payroll/index.blade.php`, dan `resources/views/payroll/slip.blade.php`).
  - *Cara Test*: Buka `/admin/payroll`. Jika Kasir memiliki 3 shift pada bulan ini, pastikan total gajinya otomatis tertulis `3 Shift × Rp 50.000 = Rp 150.000` (tanpa gaji pokok Rp 2.500.000).
- [ ] **Langkah 4**: Kerjakan **Issue #3** (Model `Shift`, `ShiftController`, `KasirController`, `resources/views/admin/shifts/index.blade.php`, dan `resources/views/kasir/sift.blade.php`).
  - *Cara Test*:
    1. Tutup shift aktif saat ini dan isi **Total Uang Dihasilkan** = `150000` serta **Uang Tinggalan** = `250000`.
    2. Klik **Buka Shift Baru**, pastikan input **Modal Kas Awal** otomatis terisi `250000` (dari uang tinggalan tadi) dan pilihan **Shift Pagi (08:00 - 15:00)** / **Shift Sore (15:00 - 21:00)** muncul.
- [ ] **Langkah 5**: Buat atau perbarui Feature Test di folder `tests/Feature/` untuk menguji ketiga fitur di atas, lalu jalankan `php artisan test --compact`.
- [ ] **Langkah 6**: Jalankan `vendor/bin/pint --dirty` untuk merapikan format kode PHP sesuai standar Laravel.

