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

## Tahap 1: Authentication & Authorization (Laravel Sanctum)

**Tujuan**: Mengamankan semua endpoint API kasir/admin menggunakan token-based authentication (Laravel Sanctum). Endpoint publik (untuk pelanggan via QR Code) tetap tanpa login.

### Langkah 1.1: Install Laravel Sanctum
1. Jalankan perintah di terminal:
   ```bash
   composer require laravel/sanctum
   php artisan install:api
   ```
2. Perintah `install:api` akan otomatis:
   - Membuat migration untuk tabel `personal_access_tokens`.
   - Menambahkan file `routes/api.php` (sudah ada, jadi jangan timpa / pilih opsi "no" jika ditanya).
   - Menambahkan trait `HasApiTokens` ke model `User`.

### Langkah 1.2: Pastikan Model User Memiliki Trait `HasApiTokens`
1. Buka file `app/Models/User.php`.
2. Tambahkan `use Laravel\Sanctum\HasApiTokens;` di bagian import.
3. Tambahkan trait `HasApiTokens` di dalam class User:
   ```php
   use HasFactory, Notifiable, HasApiTokens;
   ```

### Langkah 1.3: Buat `AuthController`
1. Jalankan `php artisan make:controller AuthController`.
2. Buat method `login(Request $request)`:
   - **Validasi**: `email` (required, email) dan `password` (required).
   - **Cek Kredensial**: Gunakan `Auth::attempt(['email' => ..., 'password' => ...])`. Jika gagal, kembalikan JSON error 401 dengan pesan "Email atau password salah."
   - **Buat Token**: Jika berhasil, ambil user dengan `Auth::user()`, lalu buat token via `$user->createToken('auth_token')->plainTextToken`.
   - **Response**: Kembalikan JSON 200 berisi `token`, `user` (data user), dan `token_type: 'Bearer'`.
3. Buat method `logout(Request $request)`:
   - Hapus token saat ini: `$request->user()->currentAccessToken()->delete()`.
   - Kembalikan JSON 200 dengan pesan "Berhasil logout."
4. Buat method `me(Request $request)`:
   - Kembalikan `$request->user()` sebagai JSON 200.

### Langkah 1.4: Buat Middleware `CheckRole` (Generik)
1. Buat file baru `app/Http/Middleware/CheckRole.php`.
2. Method `handle` menerima parameter tambahan `...$roles`:
   ```php
   public function handle(Request $request, Closure $next, ...$roles): Response
   {
       $user = $request->user();
       if (!$user || !in_array($user->role, $roles)) {
           return response()->json([
               'message' => 'Akses ditolak. Anda tidak memiliki izin untuk fitur ini.'
           ], 403);
       }
       return $next($request);
   }
   ```
3. Daftarkan middleware alias di `bootstrap/app.php`:
   ```php
   ->withMiddleware(function (Middleware $middleware): void {
       $middleware->alias([
           'role' => \App\Http\Middleware\CheckRole::class,
       ]);
   })
   ```

### Langkah 1.5: Refaktor Routes `routes/api.php`
1. Pisahkan routes menjadi 3 grup:
   ```php
   use App\Http\Controllers\AuthController;

   // === Route Publik (Tanpa Login) ===
   Route::post('/login', [AuthController::class, 'login']);

   // === Route Kasir & Admin (Perlu Login) ===
   Route::middleware('auth:sanctum')->group(function () {
       Route::post('/logout', [AuthController::class, 'logout']);
       Route::get('/me', [AuthController::class, 'me']);

       Route::get('/dashboard', [DashboardController::class, 'index']);
       Route::post('/play-sessions', [PlaySessionController::class, 'store']);
       Route::put('/play-sessions/{playSession}', [PlaySessionController::class, 'update']);
       Route::post('/play-sessions/{id}/orders', [OrderController::class, 'store']);

       // (Route untuk CustomerRequestController kasir akan ditambah di Tahap 3)
   });

   // === Route Pelanggan / Publik (Tanpa Login, via QR Code) ===
   // (Route untuk endpoint publik pelanggan akan ditambah di Tahap 2)
   ```

### Langkah 1.6: Perbaiki `PlaySessionController`
1. Buka `app/Http/Controllers/PlaySessionController.php`.
2. Pada method `store`, ganti baris fallback kasir:
   ```php
   // SEBELUM (workaround tanpa auth):
   $kasir = $request->user() ?? \App\Models\User::where('role', 'kasir')->first() ?? \App\Models\User::first();

   // SESUDAH (gunakan user yang login):
   $kasir = $request->user();
   ```

### Langkah 1.7: Verifikasi
- Jalankan `php artisan test` — pastikan test yang sudah ada masih PASS (update test jika perlu, misalnya menambahkan `actingAs($user)` untuk simulasi login di test).
- Test manual via Postman/curl:
  - `POST /api/login` → dapat token.
  - `GET /api/dashboard` tanpa token → 401.
  - `GET /api/dashboard` dengan header `Authorization: Bearer {token}` → 200.

---

## Tahap 2: Customer Request via QR Code (Endpoint Publik)

**Tujuan**: Membuat endpoint API publik (tanpa perlu login) agar pelanggan yang scan QR Code di meja TV bisa mengirimkan request "tambah waktu" atau "pesan F&B".

### Langkah 2.1: Buat `CustomerRequestService`
1. Buat file baru `app/Services/CustomerRequestService.php`.
2. Buat method `createRequest(Tv $tv, string $type, array $payload)`:
   - **Validasi Bisnis**: Pastikan TV memiliki sesi aktif (`PlaySession` dengan status `active`). Jika tidak, throw `Exception("TV ini tidak sedang digunakan.")`.
   - **Simpan Request**: Buat record `CustomerRequest` baru:
     ```php
     return CustomerRequest::create([
         'tv_id' => $tv->id,
         'type' => $type,
         'payload' => $payload,
         'status' => 'pending',
     ]);
     ```

### Langkah 2.2: Buat `CustomerRequestController`
1. Jalankan `php artisan make:controller CustomerRequestController`.
2. Inject `CustomerRequestService` di constructor.
3. Buat method `store(Request $request)` — ini adalah **endpoint publik** (tanpa login):
   - **Validasi**:
     ```php
     $validated = $request->validate([
         'tv_id'   => ['required', 'integer', 'exists:tvs,id'],
         'type'    => ['required', 'in:add_time,order_food'],
         'payload' => ['required', 'array'],
     ]);
     ```
   - Untuk tipe `order_food`, validasi juga bahwa `payload.product_id` ada dan valid (`exists:products,id`) serta `payload.quantity` minimal 1.
   - Untuk tipe `add_time`, validasi bahwa `payload.duration_minutes` ada dan minimal 30.
   - Ambil `Tv` dengan `findOrFail`.
   - Panggil `$this->customerRequestService->createRequest($tv, ...)` dalam `try-catch`.
   - **Sukses**: Response JSON 201 dengan pesan "Request berhasil dikirim. Mohon tunggu konfirmasi kasir."
   - **Gagal**: Response JSON 422 dengan pesan error.

### Langkah 2.3: Buat `CustomerRequestResource`
1. Jalankan `php artisan make:resource CustomerRequestResource`.
2. Isi method `toArray()`:
   ```php
   return [
       'id' => $this->id,
       'tv_id' => $this->tv_id,
       'tv_name' => $this->whenLoaded('tv', fn() => $this->tv->name),
       'type' => $this->type,
       'payload' => $this->payload,
       'status' => $this->status,
       'created_at' => $this->created_at->format('Y-m-d H:i:s'),
   ];
   ```

### Langkah 2.4: Daftarkan Route Publik
1. Di `routes/api.php`, tambahkan di bawah grup route publik (di luar `auth:sanctum`):
   ```php
   Route::post('/customer-requests', [CustomerRequestController::class, 'store']);
   ```

### Langkah 2.5: Verifikasi
- Test manual via Postman (tanpa token):
  - `POST /api/customer-requests` dengan body `{ "tv_id": 1, "type": "order_food", "payload": { "product_id": 1, "quantity": 2 } }` → 201.
  - `POST /api/customer-requests` dengan `tv_id` yang tidak sedang bermain → 422.

---

## Tahap 3: Approve / Reject Request oleh Kasir

**Tujuan**: Membuat endpoint agar kasir bisa melihat daftar customer request yang masuk (status `pending`) dan melakukan Approve atau Reject.

### Langkah 3.1: Tambah Method di `CustomerRequestService`
1. Buat method `approveRequest(CustomerRequest $customerRequest)`:
   - Jika `$customerRequest->status !== 'pending'`, throw Exception.
   - **Jika tipe `order_food`**:
     - Ambil `product_id` dan `quantity` dari `$customerRequest->payload`.
     - Ambil `PlaySession` aktif dari TV: `$session = PlaySession::where('tv_id', $customerRequest->tv_id)->where('status', 'active')->firstOrFail()`.
     - Ambil `Product` dengan `findOrFail`.
     - Panggil `(new OrderService)->addFoodToSession($session, $product, $quantity)` untuk memproses pesanan. Bungkus dalam `DB::transaction()`.
   - **Jika tipe `add_time`**:
     - Untuk sekarang, cukup log atau simpan keterangan di payload bahwa request disetujui. Logika perpanjangan waktu aktual akan diimplementasikan pada Tahap 4 (Time Warnings). Untuk saat ini, update status saja.
   - Update status `CustomerRequest` menjadi `approved`.
2. Buat method `rejectRequest(CustomerRequest $customerRequest)`:
   - Jika `$customerRequest->status !== 'pending'`, throw Exception.
   - Update status `CustomerRequest` menjadi `rejected`.

### Langkah 3.2: Tambah Method di `CustomerRequestController`
1. Buat method `index(Request $request)` — endpoint kasir, perlu login:
   - Ambil semua `CustomerRequest` dengan status `pending`, eager-load relasi `tv`.
   - Kembalikan menggunakan `CustomerRequestResource::collection(...)`.
2. Buat method `approve($id)`:
   - Ambil `CustomerRequest` dengan `findOrFail($id)`.
   - Panggil `$this->customerRequestService->approveRequest(...)` dalam `try-catch`.
   - Sukses: Response JSON 200 "Request berhasil di-approve."
   - Gagal: Response JSON 422.
3. Buat method `reject($id)`:
   - Ambil `CustomerRequest` dengan `findOrFail($id)`.
   - Panggil `$this->customerRequestService->rejectRequest(...)` dalam `try-catch`.
   - Sukses: Response JSON 200 "Request berhasil di-reject."
   - Gagal: Response JSON 422.

### Langkah 3.3: Daftarkan Route (Kasir, butuh login)
1. Di `routes/api.php`, **di dalam** grup `auth:sanctum`, tambahkan:
   ```php
   Route::get('/customer-requests', [CustomerRequestController::class, 'index']);
   Route::patch('/customer-requests/{id}/approve', [CustomerRequestController::class, 'approve']);
   Route::patch('/customer-requests/{id}/reject', [CustomerRequestController::class, 'reject']);
   ```

### Langkah 3.4: Verifikasi
- Test manual (dengan token kasir):
  - `GET /api/customer-requests` → list pending requests.
  - `PATCH /api/customer-requests/1/approve` → 200, cek stok produk berkurang jika `order_food`.
  - `PATCH /api/customer-requests/1/reject` → 200.
  - Approve request yang sudah di-approve → 422.

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
