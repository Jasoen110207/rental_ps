# Backend Implementation Plan (Fase 3): Fitur Lanjutan Rental PS

Dokumen ini berisi panduan teknis **langkah demi langkah** untuk mengimplementasikan 5 fitur lanjutan dari backend sistem kasir Rental PlayStation "TambahBang". Panduan ini disusun sangat detail agar mudah dibaca dan dieksekusi secara mandiri oleh Junior Programmer atau AI Assistant.

**Catatan Penting**: Implementasikan fitur-fitur ini **secara berurutan** dari Tahap 1 hingga Tahap 5. Setiap tahap bergantung pada tahap sebelumnya.

---

## Referensi Kode yang Sudah Ada

Sebelum mulai, pastikan kamu memahami struktur project yang sudah ada:

| Komponen | Path | Deskripsi |
|---|---|---|
| `PlaySessionService` | `app/Services/PlaySessionService.php` | Logika start/end sesi bermain |
| `OrderService` | `app/Services/OrderService.php` | Logika pemesanan F&B ke sesi |
| `PlaySessionController` | `app/Http/Controllers/PlaySessionController.php` | Endpoint start/stop sesi |
| `OrderController` | `app/Http/Controllers/OrderController.php` | Endpoint pesan F&B |
| `DashboardController` | `app/Http/Controllers/DashboardController.php` | Endpoint tampilkan semua TV |
| `CheckRoleAdmin` | `app/Http/Middleware/CheckRoleAdmin.php` | Middleware cek role admin |
| Model `CustomerRequest` | `app/Models/CustomerRequest.php` | Model sudah ada, kolom: `tv_id`, `type` (enum: add_time, order_food), `payload` (JSON), `status` (enum: pending, approved, rejected) |
| Model `User` | `app/Models/User.php` | Kolom `role` (enum: admin, kasir) sudah ada |
| Tabel `tvs` | Migration sudah ada | Kolom `is_buzzer_on` (boolean) dan `iot_endpoint` (nullable string) sudah tersedia |

---

## Tahap 4: Time Warnings / Notifikasi Deadline Billing

**Tujuan**: Membuat sistem peringatan waktu untuk sesi bermain bertipe `prepaid` yang durasinya hampir atau sudah habis. Karena project ini tidak punya perangkat IoT fisik, peringatan direpresentasikan secara logis di database (kolom `is_buzzer_on` di tabel `tvs` sebagai simulasi/mock).

### Langkah 4.1: Tambahkan Kolom `duration_minutes` pada Migration `play_sessions`
1. Buat migration baru:
   ```bash
   php artisan make:migration add_duration_minutes_to_play_sessions_table --table=play_sessions
   ```
2. Isi migration:
   ```php
   public function up(): void
   {
       Schema::table('play_sessions', function (Blueprint $table) {
           $table->integer('duration_minutes')->nullable()->after('billing_type');
       });
   }
   public function down(): void
   {
       Schema::table('play_sessions', function (Blueprint $table) {
           $table->dropColumn('duration_minutes');
       });
   }
   ```
3. Jalankan `php artisan migrate`.

### Langkah 4.2: Update `PlaySessionService` untuk Prepaid
1. Update method `startSession` agar menerima parameter opsional `$durationMinutes = null`:
   ```php
   public function startSession(Tv $tv, User $kasir, string $billingType, ?int $durationMinutes = null): PlaySession
   ```
2. Jika `$billingType === 'prepaid'` dan `$durationMinutes` null atau kurang dari 1, throw Exception: "Durasi wajib diisi untuk billing prepaid."
3. Simpan `duration_minutes` saat membuat `PlaySession`.

### Langkah 4.3: Update `PlaySessionController`
1. Pada method `store`, tambahkan validasi opsional untuk `duration_minutes`:
   ```php
   $validated = $request->validate([
       'tv_id' => 'required|exists:tvs,id',
       'billing_type' => 'required|in:prepaid,postpaid',
       'duration_minutes' => 'required_if:billing_type,prepaid|nullable|integer|min:30',
   ]);
   ```
2. Teruskan `$validated['duration_minutes'] ?? null` ke `startSession(...)`.

### Langkah 4.4: Buat Artisan Command `CheckPrepaidSessions`
1. Jalankan:
   ```bash
   php artisan make:command CheckPrepaidSessions
   ```
2. Set signature: `app:check-prepaid-sessions`.
3. Set description: `Memeriksa sesi prepaid yang hampir/sudah habis dan mengaktifkan buzzer simulasi.`
4. Di dalam method `handle()`:
   ```php
   // Cari semua sesi prepaid yang masih aktif
   $sessions = PlaySession::where('billing_type', 'prepaid')
       ->where('status', 'active')
       ->get();

   foreach ($sessions as $session) {
       $endTime = Carbon::parse($session->start_time)->addMinutes($session->duration_minutes);
       $now = Carbon::now();
       $remainingMinutes = $now->diffInMinutes($endTime, false); // negatif jika sudah lewat

       if ($remainingMinutes <= 5) {
           // Aktifkan buzzer simulasi pada TV
           $session->tv->update(['is_buzzer_on' => true]);
           $this->info("⚠️ TV '{$session->tv->name}': Sisa waktu {$remainingMinutes} menit. Buzzer diaktifkan.");
       }

       if ($remainingMinutes <= 0) {
           // Waktu habis, akhiri sesi secara otomatis
           $playSessionService = app(PlaySessionService::class);
           $playSessionService->endSession($session);
           $this->info("⏹️ TV '{$session->tv->name}': Waktu habis. Sesi otomatis dihentikan.");
       }
   }
   ```

### Langkah 4.5: Daftarkan Schedule
1. Buka file `routes/console.php`.
2. Tambahkan:
   ```php
   use Illuminate\Support\Facades\Schedule;

   Schedule::command('app:check-prepaid-sessions')->everyMinute();
   ```
3. Untuk menjalankan scheduler di lokal (testing), gunakan:
   ```bash
   php artisan schedule:work
   ```

### Langkah 4.6: Buat Endpoint Opsional untuk Status Waktu Sisa
1. Tambahkan method `show($id)` di `PlaySessionController` yang mengembalikan detail sesi beserta sisa waktu:
   ```php
   public function show($id): JsonResponse
   {
       $session = PlaySession::with(['tv', 'sessionOrders.product'])->findOrFail($id);

       $data = new PlaySessionResource($session);
       $extra = [];

       if ($session->billing_type === 'prepaid' && $session->status === 'active') {
           $endTime = Carbon::parse($session->start_time)->addMinutes($session->duration_minutes);
           $extra['remaining_minutes'] = max(0, (int) Carbon::now()->diffInMinutes($endTime, false));
           $extra['is_expired'] = $extra['remaining_minutes'] <= 0;
       }

       return response()->json([
           'data' => $data,
           'time_info' => $extra,
       ]);
   }
   ```
2. Daftarkan route di dalam grup `auth:sanctum`:
   ```php
   Route::get('/play-sessions/{id}', [PlaySessionController::class, 'show']);
   ```

### Langkah 4.7: Verifikasi
- Jalankan `php artisan migrate`.
- Buat sesi prepaid via API (misalnya `duration_minutes: 1` untuk testing cepat).
- Jalankan `php artisan app:check-prepaid-sessions` secara manual dan pastikan output menunjukkan buzzer aktif dan sesi dihentikan otomatis.
- Cek di database bahwa `tvs.is_buzzer_on` berubah menjadi `true` dan `play_sessions.status` berubah menjadi `completed`.

---

## Tahap 5: Shift Management (Login / Logout Shift Kasir)

**Tujuan**: Mencatat kapan kasir mulai dan selesai shift. Data ini berguna untuk rekap transaksi per shift dan akuntabilitas.

### Langkah 5.1: Buat Migration Tabel `shifts`
1. Jalankan:
   ```bash
   php artisan make:migration create_shifts_table
   ```
2. Isi migration:
   ```php
   Schema::create('shifts', function (Blueprint $table) {
       $table->id();
       $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
       $table->dateTime('start_time');
       $table->dateTime('end_time')->nullable();
       $table->integer('total_revenue')->default(0); // total pemasukan selama shift
       $table->enum('status', ['active', 'completed'])->default('active');
       $table->timestamps();
   });
   ```
3. Jalankan `php artisan migrate`.

### Langkah 5.2: Buat Model `Shift`
1. Jalankan `php artisan make:model Shift`.
2. Isi model:
   ```php
   protected $guarded = ['id'];

   public function user(): BelongsTo
   {
       return $this->belongsTo(User::class);
   }
   ```
3. Tambahkan relasi di `User.php`:
   ```php
   public function shifts(): HasMany
   {
       return $this->hasMany(Shift::class);
   }
   ```

### Langkah 5.3: Buat `ShiftService`
1. Buat file baru `app/Services/ShiftService.php`.
2. Buat method `startShift(User $kasir)`:
   - Cek apakah kasir sudah punya shift aktif: `Shift::where('user_id', $kasir->id)->where('status', 'active')->exists()`. Jika ada, throw Exception: "Kasir sudah memiliki shift aktif."
   - Buat record `Shift` baru dengan `start_time` = `Carbon::now()` dan `status` = `active`.
3. Buat method `endShift(User $kasir)`:
   - Cari shift aktif kasir: `Shift::where('user_id', $kasir->id)->where('status', 'active')->firstOrFail()`.
   - Hitung total revenue: jumlahkan `total_amount` dari semua `PlaySession` milik kasir yang `status = completed` dan `updated_at` antara `shift->start_time` dan sekarang.
   - Update shift: `end_time`, `total_revenue`, `status = completed`.
   - Kembalikan shift yang sudah diupdate.

### Langkah 5.4: Buat `ShiftController`
1. Jalankan `php artisan make:controller ShiftController`.
2. Inject `ShiftService` di constructor.
3. Method `store(Request $request)` — Mulai shift:
   - Panggil `$this->shiftService->startShift($request->user())` dalam `try-catch`.
   - Sukses: Response JSON 201 dengan data shift dan pesan "Shift berhasil dimulai."
   - Gagal: Response JSON 422.
4. Method `update(Request $request)` — Akhiri shift:
   - Panggil `$this->shiftService->endShift($request->user())` dalam `try-catch`.
   - Sukses: Response JSON 200 dengan data shift (termasuk `total_revenue`) dan pesan "Shift berhasil diakhiri."
   - Gagal: Response JSON 422.
5. Method `index(Request $request)` — Riwayat shift kasir yang login:
   - Ambil semua shift milik `$request->user()`, urutkan terbaru dulu.
   - Kembalikan sebagai JSON.

### Langkah 5.5: Daftarkan Route
1. Di `routes/api.php`, di dalam grup `auth:sanctum`, tambahkan:
   ```php
   Route::post('/shifts', [ShiftController::class, 'store']);      // Mulai shift
   Route::put('/shifts', [ShiftController::class, 'update']);       // Akhiri shift
   Route::get('/shifts', [ShiftController::class, 'index']);        // Riwayat shift
   ```

### Langkah 5.6: Verifikasi
- Login sebagai kasir → start shift → buat beberapa sesi bermain → end shift.
- Cek bahwa `total_revenue` terhitung dengan benar.
- Coba start shift saat sudah ada shift aktif → harus gagal 422.

---

## Pesan untuk Implementator (Junior Dev / AI)

1. **Kerjakan secara berurutan**: Tahap 1 (Auth) → Tahap 2 (Customer Request Publik) → Tahap 3 (Approve/Reject) → Tahap 4 (Time Warnings) → Tahap 5 (Shift).
2. **Jangan menulis logika bisnis di Controller**. Semua perhitungan, validasi bisnis, dan manipulasi data harus di **Service Class**. Controller hanya menerima request, memanggil service, dan mengembalikan response.
3. **Selalu gunakan `DB::transaction()`** di Service Class saat operasi melibatkan perubahan di lebih dari 1 tabel.
4. **Update test yang ada** jika test lama gagal setelah penambahan authentication (tambahkan `actingAs($user)` di test).
5. **Buat test baru** untuk setiap fitur yang ditambahkan. Minimal test happy path dan satu test error case.
6. Setelah selesai semua tahap, jalankan `php artisan test` dan pastikan **semua test PASS**.
