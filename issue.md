# Planning Implementasi: Popup Mulai Rental & Button Matikan Buzzer

Dokumen ini berisi detail instruksi dan langkah implementasi untuk dua fitur baru.
Instruksi ini ditujukan untuk _Junior Programmer_ atau AI Model yang akan melakukan _coding_.

> **Catatan**: Baca seluruh dokumen sebelum mulai coding. Pastikan paham alur kerja dan file-file yang terdampak.

---

## Objektif 1: Menambahkan "Nama Penyewa" & "Jumlah Stik Kontroller" ke Popup Mulai Rental

### Deskripsi

Saat ini popup "Mulai Rental" (`modal-start`) hanya berisi pilihan Tipe Billing (prepaid/postpaid), Durasi, dan Estimasi Biaya. Kita ingin menambahkan **2 field baru** agar popup ini lebih lengkap seperti form "New Rental":

1. **Nama Penyewa** — input text opsional untuk mencatat siapa yang menyewa.
2. **Jumlah Stik Kontroller** — input number untuk mencatat berapa stik yang dipinjamkan ke penyewa.

Data ini harus tersimpan di database dan ditampilkan di card unit pada dashboard saat sesi aktif.

### File yang Terdampak

| File | Aksi |
|------|------|
| `database/migrations/xxxx_add_customer_fields_to_play_sessions_table.php` | **[NEW]** — Migration baru |
| `app/Models/PlaySession.php` | **[MODIFY]** — Tambah fillable (jika pakai `$fillable`) |
| `resources/views/admin/dashboard.blade.php` | **[MODIFY]** — Modal Start + Card display |
| `resources/views/kasir/dashboard.blade.php` | **[MODIFY]** — Modal Start + Card display |
| `app/Http/Controllers/RentalController.php` | **[MODIFY]** — Method `start()` |
| `app/Http/Controllers/KasirController.php` | **[MODIFY]** — Method `startRental()` |
| `app/Http/Controllers/DashboardController.php` | **[MODIFY]** — Method `apiStatus()` (opsional, untuk polling) |

### Langkah Implementasi

#### Langkah 1: Buat Migration Baru

Buat migration untuk menambahkan 2 kolom baru ke tabel `play_sessions`:

```bash
php artisan make:migration add_customer_fields_to_play_sessions_table --table=play_sessions --no-interaction
```

Isi migration-nya:

```php
public function up(): void
{
    Schema::table('play_sessions', function (Blueprint $table) {
        $table->string('customer_name')->nullable()->after('user_id');
        $table->integer('controller_count')->default(1)->after('customer_name');
    });
}

public function down(): void
{
    Schema::table('play_sessions', function (Blueprint $table) {
        $table->dropColumn(['customer_name', 'controller_count']);
    });
}
```

Lalu jalankan:

```bash
php artisan migrate
```

#### Langkah 2: Update Model PlaySession

Buka `app/Models/PlaySession.php`. Model ini menggunakan `$guarded = ['id']`, jadi kolom baru otomatis bisa di-mass-assign. **Tidak perlu ubah apa-apa di model**, tapi pastikan kamu verifikasi bahwa memang menggunakan `$guarded` dan bukan `$fillable`.

Jika menggunakan `$fillable`, tambahkan `'customer_name'` dan `'controller_count'` ke array `$fillable`.

#### Langkah 3: Tambahkan Input Field di Modal Start — Admin Dashboard

Buka file `resources/views/admin/dashboard.blade.php`.

Cari bagian `<!-- 1. START RENTAL MODAL -->` (sekitar baris 282). Di dalam form, **SEBELUM** bagian "Billing Mode Tabs" (`<!-- Billing Mode Tabs -->`), tambahkan 2 field baru:

```html
<!-- Nama Penyewa -->
<div>
  <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1.5">Nama Penyewa (Opsional)</label>
  <input type="text" name="customer_name" id="start-customer-name"
    placeholder="Misal: Budi, Andi, dll."
    class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface font-body-md text-sm neo-shadow-sm">
</div>

<!-- Jumlah Stik Kontroller -->
<div>
  <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1.5">Jumlah Stik Kontroller</label>
  <div class="grid grid-cols-4 gap-2">
    <button type="button" onclick="setControllerCount(1)" class="controller-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-primary-fixed" data-count="1">1 Stik</button>
    <button type="button" onclick="setControllerCount(2)" class="controller-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-primary-fixed" data-count="2">2 Stik</button>
    <button type="button" onclick="setControllerCount(3)" class="controller-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-primary-fixed" data-count="3">3 Stik</button>
    <button type="button" onclick="setControllerCount(4)" class="controller-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-primary-fixed" data-count="4">4 Stik</button>
  </div>
  <input type="hidden" name="controller_count" id="start-controller-count" value="1">
</div>
```

Lalu di bagian `<script>` di bawah, tambahkan function JavaScript untuk toggle button stik:

```javascript
function setControllerCount(count) {
  document.getElementById('start-controller-count').value = count;
  document.querySelectorAll('.controller-btn').forEach(btn => {
    if (parseInt(btn.getAttribute('data-count')) === count) {
      btn.classList.add('bg-primary-fixed');
      btn.classList.remove('bg-surface');
    } else {
      btn.classList.remove('bg-primary-fixed');
      btn.classList.add('bg-surface');
    }
  });
}
```

Jangan lupa reset field saat modal dibuka. Di function `openStartModal()`, tambahkan:

```javascript
document.getElementById('start-customer-name').value = '';
setControllerCount(1);
```

#### Langkah 4: Tambahkan Input Field di Modal Start — Kasir Dashboard

Lakukan hal yang **persis sama** seperti Langkah 3, tapi di file `resources/views/kasir/dashboard.blade.php`.

Cari bagian `<!-- MODAL START -->` (sekitar baris 197). Tambahkan field `customer_name` dan `controller_count` yang sama di dalam form, **SEBELUM** bagian Tipe Billing.

Tambahkan juga function `setControllerCount()` dan reset logic di `openStartModal()` yang sama.

#### Langkah 5: Update Controller — Admin (RentalController)

Buka `app/Http/Controllers/RentalController.php`, cari method `start()` (sekitar baris 21).

**5a. Tambahkan validasi baru:**

Di array `$request->validate()`, tambahkan:

```php
'customer_name' => 'nullable|string|max:100',
'controller_count' => 'nullable|integer|min:1|max:10',
```

**5b. Simpan ke PlaySession::create():**

Di dalam `DB::transaction`, cari `PlaySession::create([...])`. Tambahkan 2 field:

```php
'customer_name' => $validated['customer_name'] ?? null,
'controller_count' => $validated['controller_count'] ?? 1,
```

#### Langkah 6: Update Controller — Kasir (KasirController)

Buka `app/Http/Controllers/KasirController.php`, cari method `startRental()` (sekitar baris 170).

Lakukan hal yang **persis sama** seperti Langkah 5:
- Tambahkan validasi `customer_name` dan `controller_count`.
- Tambahkan field tersebut ke `PlaySession::create()`.

#### Langkah 7: Tampilkan Data di Card Unit Dashboard (Saat Sesi Aktif)

Di **kedua** file dashboard (`admin/dashboard.blade.php` dan `kasir/dashboard.blade.php`), pada bagian card unit yang sedang bermain (`playing`), tambahkan tampilan nama penyewa dan jumlah stik.

Cari bagian yang menampilkan informasi sesi aktif (timer, mulai-selesai, total tagihan). **Setelah** blok timer display dan **sebelum** grid `MULAI - SELESAI`, tambahkan:

```html
@if ($activeSession->customer_name)
  <div class="p-1.5 bg-surface border border-on-surface text-xs flex items-center gap-1.5">
    <span class="material-symbols-outlined text-sm text-primary">person</span>
    <span class="font-bold">{{ $activeSession->customer_name }}</span>
    <span class="text-on-surface-variant">•</span>
    <span class="font-bold">{{ $activeSession->controller_count ?? 1 }} Stik</span>
  </div>
@endif
```

Jika `customer_name` kosong/null, tampilan ini tidak muncul (behavior opsional).

#### Langkah 8: (Opsional) Update API Polling

Agar data customer_name dan controller_count juga muncul di polling real-time, update response JSON di:

- `DashboardController::apiStatus()` (untuk admin)
- `KasirController::apiStatus()` (untuk kasir)

Di dalam `$sessionInfo` array, tambahkan:

```php
'customer_name' => $activeSession->customer_name,
'controller_count' => $activeSession->controller_count ?? 1,
```

### Verifikasi

1. Buka dashboard Admin atau Kasir.
2. Klik tombol "MULAI RENTAL" di salah satu unit yang tersedia.
3. Pastikan popup memiliki field **Nama Penyewa** dan **Jumlah Stik Kontroller**.
4. Isi nama, pilih jumlah stik, pilih billing type dan durasi, lalu submit.
5. Pastikan card unit yang aktif menampilkan nama penyewa dan jumlah stik.
6. Cek database tabel `play_sessions` bahwa kolom `customer_name` dan `controller_count` terisi.

---

## Objektif 2: Tombol "Matikan Buzzer" di Dashboard untuk Unit yang Billing-nya Habis

### Deskripsi

Saat waktu billing (prepaid) selesai/habis, buzzer alarm menyala otomatis di unit PS. Saat ini, tombol "MATIKAN" buzzer **sudah ada** di dalam card unit, tapi hanya muncul jika `is_buzzer_on == true`.

Yang diminta: Pada saat waktu billing selesai (timer habis), **otomatis nyalakan buzzer** (set `is_buzzer_on = true`) DAN pastikan **tombol "MATIKAN BUZZER"** selalu muncul dengan jelas di dashboard untuk unit yang billingnya habis — sehingga kasir bisa langsung matikan dari dashboard tanpa perlu scroll atau mencari.

### File yang Terdampak

| File | Aksi |
|------|------|
| `app/Http/Controllers/DashboardController.php` | **[MODIFY]** — Auto-set buzzer di `apiStatus()` |
| `app/Http/Controllers/KasirController.php` | **[MODIFY]** — Auto-set buzzer di `apiStatus()` |
| `resources/views/admin/dashboard.blade.php` | **[MODIFY]** — Perjelas tombol matikan buzzer |
| `resources/views/kasir/dashboard.blade.php` | **[MODIFY]** — Perjelas tombol matikan buzzer |

### Langkah Implementasi

#### Langkah 1: Auto-Nyalakan Buzzer Saat Waktu Habis (Backend)

Saat polling `apiStatus()` mendeteksi bahwa suatu unit prepaid sudah habis waktunya (`end_time` sudah lewat / `isPast()`), kita otomatis set `is_buzzer_on = true` di database.

**Di `app/Http/Controllers/DashboardController.php`**, cari method `apiStatus()` (sekitar baris 68). Di dalam loop `foreach ($tvs as $tv)`, cari kondisi:

```php
if ($activeSession->end_time->isPast()) {
    $remainingSeconds = 0;
    $isTimeUp = true;
    $timeUpCount++;
}
```

**Tambahkan** di dalam blok `if` tersebut (setelah `$timeUpCount++`):

```php
// Auto-nyalakan buzzer saat waktu habis
if (!$tv->is_buzzer_on) {
    $tv->update(['is_buzzer_on' => true]);
}
```

**Lakukan hal yang sama** di `app/Http/Controllers/KasirController.php` method `apiStatus()` (sekitar baris 61). Cari kondisi serupa dan tambahkan auto-set buzzer.

> **Catatan Penting**: Ini berarti setiap kali polling berjalan (setiap 3-5 detik) dan unit sudah time-up tapi buzzer belum nyala, buzzer akan otomatis dinyalakan. Query `update` hanya dijalankan jika `is_buzzer_on` masih `false`, jadi tidak ada redundant update.

#### Langkah 2: Perjelas Tombol Matikan Buzzer di Dashboard Card

Saat ini, indikator buzzer sudah ada di card (teks "ALARM BUZZER AKTIF!" + tombol "MATIKAN"). Namun, kita perlu memastikan tombol ini **lebih mencolok dan mudah ditekan** saat billing habis.

**Di `resources/views/admin/dashboard.blade.php`**, cari bagian buzzer indicator (sekitar baris 216-226). Ganti blok `@if ($tv->is_buzzer_on)` agar juga muncul saat `$isTimeUp` true:

**Ganti:**

```blade
@if ($tv->is_buzzer_on)
  <div class="p-2 bg-error text-on-error ...">
    ...
  </div>
@endif
```

**Menjadi:**

```blade
@if ($tv->is_buzzer_on || $isTimeUp)
  <div class="p-2.5 bg-error text-on-error font-headline-sm text-xs font-bold uppercase flex items-center justify-between border-2 border-on-surface animate-pulse neo-shadow-sm">
    <div class="flex items-center gap-1.5">
      <span class="material-symbols-outlined text-base">alarm</span>
      <span>ALARM BUZZER AKTIF!</span>
    </div>
    <form method="POST" action="{{ route('admin.rental.toggle-buzzer', $tv->id) }}" class="inline">
      @csrf
      <button type="submit" class="px-3 py-1 bg-white text-error font-black text-[11px] border-2 border-on-surface neo-shadow-sm btn-press hover:bg-red-50">
        MATIKAN BUZZER
      </button>
    </form>
  </div>
@endif
```

**Lakukan hal yang sama** di `resources/views/kasir/dashboard.blade.php`, cari bagian buzzer indicator (sekitar baris 155-160). Ganti kondisi dari `$tv->is_buzzer_on` menjadi `$tv->is_buzzer_on || $isTimeUp` dan perbesar tombolnya agar lebih mudah ditekan.

#### Langkah 3: Update JavaScript Polling untuk Muncul/Sembunyikan Tombol Buzzer

Di kedua file dashboard, JavaScript polling sudah mengecek `tv.is_buzzer_on` untuk show/hide buzzer indicator. Kita perlu juga mengecek apakah sesi sudah time-up.

**Di `resources/views/admin/dashboard.blade.php`**, cari di function `pollDashboardStatus()`, bagian yang mengecek alarm:

```javascript
data.tvs.forEach(tv => {
  if (tv.is_buzzer_on || (tv.active_session && tv.active_session.is_time_up)) {
    hasAlarm = true;
  }
});
```

Ini sudah benar — **sudah** mengecek `is_time_up`. Tapi pastikan juga logic show/hide elemen buzzer indicator di card diupdate via polling. Tambahkan logic berikut di dalam loop `data.tvs.forEach()`:

```javascript
const buzzerEl = document.querySelector(`#unit-card-${tv.id} [class*="bg-error"]`);
// Jika perlu, buat elemen buzzer indicator baru atau show/hide via class
```

> **Catatan**: Karena halaman di-refresh via full page load oleh polling yang sudah ada, dan buzzer `is_buzzer_on` sudah di-set otomatis di backend (Langkah 1), indikator buzzer akan otomatis muncul pada load berikutnya. Jika ingin real-time tanpa refresh, implementasi DOM update di JavaScript diperlukan — tapi ini **opsional** karena polling sudah cukup frequent (3-5 detik).

**Di `resources/views/kasir/dashboard.blade.php`**, cek di function `pollKasirStatus()` bahwa logic show/hide buzzer indicator juga sudah menangani kondisi `is_buzzer_on`:

```javascript
const buzzerIndicator = document.getElementById('buzzer-indicator-' + tv.id);
if (buzzerIndicator) {
  if (tv.is_buzzer_on) {
    buzzerIndicator.classList.remove('hidden');
  } else {
    buzzerIndicator.classList.add('hidden');
  }
}
```

Ini sudah ada dan berjalan. Karena di Langkah 1 kita sudah auto-set `is_buzzer_on = true` di backend saat time-up, maka pada polling berikutnya (3-5 detik) tombol matikan buzzer akan otomatis muncul.

#### Langkah 4: Pastikan Toggle Buzzer Berfungsi Dengan Benar

Method `toggleBuzzer()` sudah ada di:
- `RentalController.php` baris 240 (route: `admin.rental.toggle-buzzer`)
- `KasirController.php` baris 334 (route: `kasir.rental.toggle-buzzer`)

Pastikan method ini berfungsi: toggle `is_buzzer_on` dari `true` ke `false`. Saat ini logicnya adalah toggle (flip), yang artinya jika buzzer sedang nyala (true), akan jadi mati (false). **Ini sudah benar**.

Namun, karena polling auto-set buzzer saat time-up (Langkah 1), ada kemungkinan buzzer kembali menyala setelah dimatikan jika sesi masih time-up. **Solusi**: Setelah buzzer dimatikan, pastikan polling **TIDAK** menyalakan ulang buzzer jika sudah pernah dimatikan secara manual.

**Cara implementasi**: Tambahkan pengecekan di Langkah 1. Daripada langsung auto-set buzzer, cek juga apakah sesi sudah melewati threshold tertentu sejak time-up. **Atau**, lebih simpel: hanya auto-set buzzer **sekali** saja. Gunakan field bantuan atau logic sebagai berikut:

Di blok auto-set buzzer (Langkah 1), ubah menjadi:

```php
// Auto-nyalakan buzzer hanya jika waktu baru saja habis (< 5 detik yang lalu)
// ATAU cukup biarkan is_buzzer_on di-toggle manual oleh kasir
// Pendekatan simpel: hanya set jika end_time baru lewat dalam 30 detik terakhir
if (!$tv->is_buzzer_on && $activeSession->end_time->diffInSeconds(now()) <= 30) {
    $tv->update(['is_buzzer_on' => true]);
}
```

**Atau pendekatan alternatif yang lebih simpel:** Hapus auto-set buzzer di polling, dan **pindahkan** logic auto-set buzzer ke timer JavaScript client-side. Ketika timer client-side mendeteksi `remaining <= 0` untuk pertama kali, kirim AJAX request ke `toggle-buzzer` untuk menyalakan buzzer. Ini mencegah buzzer menyala ulang setelah dimatikan.

**Rekomendasi**: Gunakan pendekatan backend dengan pengecekan `diffInSeconds <= 30` agar buzzer hanya auto-nyala sekali saat waktu baru habis, dan tidak menyala lagi setelah kasir mematikannya.

### Verifikasi

1. Buat sesi rental prepaid dengan durasi pendek (misal 1 menit) untuk testing.
2. Tunggu sampai timer habis.
3. Verifikasi bahwa buzzer otomatis menyala (`is_buzzer_on = true` di database).
4. Verifikasi bahwa tombol "MATIKAN BUZZER" muncul di card unit pada dashboard.
5. Klik tombol "MATIKAN BUZZER" dan verifikasi buzzer mati (`is_buzzer_on = false`).
6. Verifikasi bahwa buzzer **TIDAK** menyala kembali otomatis setelah dimatikan.

---

## Checklist Implementasi

- [ ] Migration: Tambah kolom `customer_name` dan `controller_count` ke `play_sessions`
- [ ] Jalankan `php artisan migrate`
- [ ] Modal Start (Admin): Tambah input Nama Penyewa + Stik Kontroller
- [ ] Modal Start (Kasir): Tambah input Nama Penyewa + Stik Kontroller
- [ ] RentalController: Update validasi + `PlaySession::create()`
- [ ] KasirController: Update validasi + `PlaySession::create()`
- [ ] Card unit (Admin): Tampilkan nama penyewa & jumlah stik pada sesi aktif
- [ ] Card unit (Kasir): Tampilkan nama penyewa & jumlah stik pada sesi aktif
- [ ] Backend polling: Auto-nyalakan buzzer saat time-up (hanya sekali)
- [ ] Dashboard (Admin): Perjelals tombol MATIKAN BUZZER saat `isTimeUp`
- [ ] Dashboard (Kasir): Perjelas tombol MATIKAN BUZZER saat `isTimeUp`
- [ ] Verifikasi: Buzzer tidak menyala ulang setelah dimatikan manual
- [ ] Jalankan `vendor/bin/pint --dirty --format agent` untuk format kode PHP
- [ ] Test manual kedua fitur end-to-end
