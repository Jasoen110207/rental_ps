
## 2. Sistem Request Pelanggan (QR Code di Meja)
**Tujuan**: Memungkinkan pelanggan di meja untuk memesan makanan atau menambah waktu secara mandiri, yang nantinya akan masuk ke antrean dashboard kasir.

**Tahapan Implementasi**:
1. **Buat Controller**: Jalankan perintah `php artisan make:controller CustomerRequestController`.
2. **Buat Endpoint Publik (Method `store`)**:
   - **Validasi**: `$request` harus divalidasi memiliki `tv_id` (exists di tabel `tvs`), `type` (harus berisi: `add_time` atau `order_food`), dan `payload` (bertipe array, berisi data detail pesanan/durasi waktu).
   - **Simpan Data**: Lakukan `CustomerRequest::create(...)` dengan menyimpan data yang divalidasi. Secara default, migrasi sudah mengatur statusnya menjadi `pending`.
   - **Beri Respons**: Kembalikan JSON berstatus 201 dengan pesan "Permintaan berhasil dikirim ke kasir".
3. **Buat Endpoint Kasir (Method `index`)**:
   - Lakukan query ke model `CustomerRequest` dengan memfilter `.where('status', 'pending')`, lalu urutkan `.orderBy('created_at', 'asc')`.
   - Pastikan juga untuk *eager load* tabel TV: `.with('tv')`.
   - Kembalikan data tersebut sebagai array JSON.
4. **Buat Endpoint Eksekusi (Method `update`)**:
   - Menerima parameter `CustomerRequest $customerRequest`. Kasir bisa mengirimkan request untuk mengubah status menjadi `approved` atau `rejected`.
5. **Daftarkan Route** di `routes/api.php`:
   - `Route::post('/customer-requests', [CustomerRequestController::class, 'store']);`
   - `Route::get('/customer-requests', [CustomerRequestController::class, 'index']);`
   - `Route::put('/customer-requests/{customerRequest}', [CustomerRequestController::class, 'update']);`

---

## 3. Logika "Prepaid" dan Kontrol Hardware (Simulasi IoT untuk Lomba)
**Tujuan**: Menghentikan sesi secara otomatis jika sistem pembayaran adalah 'prepaid' dan durasinya telah habis, kemudian menyalakan notifikasi hardware (Simulasi TV / Alarm). Karena ini untuk lomba tanpa hardware asli, kita menggunakan teknik *Mock API* dan *UI Indicator*.

**Tahapan Implementasi**:
1. **Buat Command Artisan**: Jalankan `php artisan make:command CheckPrepaidSessions`.
2. **Tulis Logika di Method `handle()`** (di file `app/Console/Commands/CheckPrepaidSessions.php`):
   - Ambil seluruh data `PlaySession` di mana `status = 'active'` dan `billing_type = 'prepaid'`.
   - *Catatan Penting*: Tambahkan kolom `expected_end_time` (waktu berakhir yang diharapkan) saat `startSession` agar Command tahu kapan waktu habis.
   - *Looping Data*: Periksa apakah `now()` sudah melewati `expected_end_time`.
   - *Tindakan Simulasi IoT jika waktu habis*:
     1. Panggil class `PlaySessionService->endSession($session)` untuk menutup billing secara resmi.
     2. Update TV terkait agar buzzernya menyala (Indikator Frontend): `$session->tv->update(['is_buzzer_on' => true]);`. (Nantinya Frontend akan mendeteksi ini dan membunyikan suara alarm di browser / layar berkedip merah).
     3. Kirim Webhook (Simulasi Hardware): Gunakan Laravel HTTP Client untuk memanggil URL *mock* (misal: webhook.site) seolah-olah mengirim sinyal mematikan relay.
        `if ($session->tv->iot_endpoint) { Http::post($session->tv->iot_endpoint, ['action' => 'turn_off_relay']); }`
3. **Jadwalkan Command (Cron Job)**:
   - Buka `routes/console.php`.
   - Daftarkan perintah: `Schedule::command('app:check-prepaid-sessions')->everyMinute();`

---

## 4. Laporan Pendapatan (Admin Only)
**Tujuan**: Membuat laporan agregasi total pendapatan untuk dilihat oleh pemilik (Admin) secara periodik.

**Tahapan Implementasi**:
1. **Buat Controller**: Jalankan `php artisan make:controller ReportController`.
2. **Buat Method `index(Request $request)`**:
   - Ambil nilai `period` dari *query string* (misal: `?period=today` atau `?period=month`).
   - Buat query dasar: `PlaySession::where('status', 'completed')`.
   - Modifikasi *query* tersebut menggunakan logika waktu Laravel Carbon berdasarkan parameter `period`:
     - Jika `today`: gunakan `->whereDate('created_at', Carbon::today())`.
     - Jika `month`: gunakan `->whereMonth('created_at', Carbon::now()->month)`.
   - Hitung penjumlahan dari seluruh biaya menggunakan `.sum('total_amount')`.
   - Kembalikan repons JSON berisikan total pendapatan kotor, total jumlah transaksi, dan rincian opsional.
3. **Daftarkan Route dengan Perlindungan Middleware Admin**:
   - Di file `routes/api.php`, buat rute berikut (pastikan sudah menggunakan middleware yang telah dibuat di Fase 1):
     `Route::get('/reports/revenue', [ReportController::class, 'index'])->middleware([\App\Http\Middleware\CheckRoleAdmin::class]);`

## Pesan untuk Implementator (Junior Dev / AI)
- Harap patuhi kaidah **Single Responsibility Principle**. Jangan menulis logika perhitungan uang atau manipulasi stok secara langsung di dalam Controller.
- Selalu gunakan `DB::transaction()` saat menjalankan fungsi di Service (seperti `endSession` atau `addFoodToSession`) agar jika terjadi error, perubahan tabel TV dan tabel Transaksi bisa di-*rollback* dan tidak menyebabkan data tidak sinkron.
- Jika code sudah diimplementasikan, silahkan optimalisasi saja supaya lebih efisien