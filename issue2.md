# 📋 Perencanaan Perbaikan Fitur & Bug (Issue 2)

Dokumen ini berisi daftar tugas lanjutan untuk memperbaiki fitur sinkronisasi, autentikasi, serta perbaikan UI dan logika di sisi pelanggan maupun admin. Silakan implementasikan solusi di bawah ini langkah demi langkah.

---

## 1. Real-time Sinkronisasi Buzzer (Admin <-> Kasir)
**Masalah:** Saat ini ketika Admin mematikan buzzer dari dashboardnya, kasir tidak otomatis melihat perubahan (bunyi berhenti, tapi tulisan "ALARM BUZZER AKTIF" masih berkedip/muncul di layar kasir) kecuali kasir melakukan *refresh* (F5).
**Penyebab:** Pada dashboard kasir (`resources/views/kasir/dashboard.blade.php`), sudah ada fungsi polling JavaScript (`pollKasirStatus()`) yang berjalan setiap 5 detik. Fungsi tersebut bisa mengecek `alarm` untuk membunyikan suara, namun *belum mengubah elemen HTML/DOM* card unit (meja/TV) untuk menghilangkan tulisan alarm secara dinamis jika status `tv.is_buzzer_on` di database menjadi `false`.

**Instruksi Implementasi (Frontend / Polling JS):**
1. Buka file `resources/views/kasir/dashboard.blade.php` (dan `admin/dashboard.blade.php` jika diperlukan sinkronisasi dua arah).
2. Temukan fungsi JS `pollKasirStatus()`.
3. Di dalam *loop* `data.tvs.forEach(tv => { ... })`, tambahkan logika untuk me-ngupdate elemen HTML. Misalnya dengan mencari elemen card/indikator buzzer berdasarkan ID TV tersebut:
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
4. Pastikan elemen HTML yang menampilkan "ALARM BUZZER AKTIF" diberi ID yang sesuai (contoh: `id="buzzer-indicator-{{ $tv->id }}"`).
5. (Opsional / Advanced) Jika ingin benar-benar *real-time* (0 detik *delay*), pertimbangkan untuk mengganti polling `setInterval` dengan **WebSockets** (contoh: *Laravel Reverb* atau *Pusher*). Namun untuk saat ini, mengupdate DOM via `pollKasirStatus()` adalah perbaikan tercepat yang valid.

---

## 2. Auto-Logout Saat End Shift (Tutup Shift)
**Masalah:** Saat kasir mengakhiri shift, akunnya tidak otomatis keluar (logout), sehingga shift baru yang masuk bisa secara tidak sengaja menggunakan akun orang sebelumnya.
**Ekspektasi:** Setelah shift ditutup/diakhiri, sistem langsung memaksa pengguna keluar (logout) dan kembali ke halaman login.

**Instruksi Implementasi:**
1. Buka `app/Http/Controllers/ShiftController.php` (method `update` atau `endShift`) atau `KasirController.php` (method `endShift`).
2. Temukan baris kode di mana proses akhir shift disimpan (`$this->shiftService->endShift(...)` atau proses `update` lainnya).
3. Setelah proses simpan selesai, tambahkan pemanggilan *facade* `Auth` untuk menghapus sesi dan logout:
   ```php
   use Illuminate\Support\Facades\Auth;
   // ...
   Auth::logout();
   $request->session()->invalidate();
   $request->session()->regenerateToken();
   ```
4. Ubah nilai *return*-nya agar melakukan redirect ke halaman login:
   ```php
   return redirect()->route('login')->with('success', 'Shift berhasil diakhiri, silakan login kembali.');
   ```
*(Catatan: Jika request dipanggil via API/AJAX, cukup kembalikan JSON, lalu arahkan browser redirect menggunakan `window.location.href = '/login';` di sisi Javascript-nya)*.

---

## 3. Request F&B Customer Tidak Muncul di Kasir
**Masalah:** Permintaan (request) tambah durasi dari halaman HP/Customer berhasil masuk dan muncul di notifikasi Kasir, tapi anehnya request pesanan Makanan/Minuman (F&B) tidak muncul sama sekali.
**Penyebab:** Pada file `app/Http/Controllers/CustomerController.php` method `requestFood()`, ada form *validation* atau *stock checking* yang gagal namun pesannya tidak terlihat di UI HP customer (karena session 'error' atau `$errors` dari Laravel tidak di-render di view), atau masalah *casting* payload JSON.
**Instruksi Implementasi:**
1. Cek view `resources/views/customer/order.blade.php`. Pastikan pesan error (flash session `error` atau variabel `$errors`) ditampilkan secara jelas di bagian atas form pesanan F&B, sehingga jika validasi (`items.*.product_id` dsb) gagal, customer mengetahuinya.
2. Di dalam `CustomerController@requestFood`, periksa kembali apakah loop pengumpulan `orderItems` berfungsi baik. Tambahkan logic untuk *men-dump/log* error jika form ter-submit kosong.
3. Di dashboard kasir JS (dalam fungsi `pollKasirStatus()` di HTML template string-nya), pastikan logic pe-renderan tipe pesanan F&B berjalan baik (saat ini sudah ada `req.type === 'order_food'`, pastikan ID dan namanya tidak bentrok).

---

## 4. Tambah Tombol Hapus pada Edit Unit Konsol
**Masalah:** Admin belum memiliki cara (tombol) untuk menghapus unit konsol atau QR melalui antarmuka web saat meng-edit unit.
**Ekspektasi:** Terdapat opsi *Hapus Unit* di modal/halaman edit.

**Instruksi Implementasi:**
1. Buka file routes `routes/web.php`, lalu tambahkan route DELETE untuk unit di dalam *group* admin:
   ```php
   Route::delete('/units/{id}', [App\Http\Controllers\UnitController::class, 'destroy'])->name('units.destroy');
   ```
2. Buat method `destroy($id)` pada `app/Http/Controllers/UnitController.php` yang menjalankan aksi penghapusan (jangan lupa hapus/cek relasi `Tv` atau `PlaySession` sebelum di-*delete*, atau gunakan *Soft Deletes* jika data transaksinya tidak ingin hilang).
3. Buka view `resources/views/admin/units/index.blade.php` (tempat modal Edit berada).
4. Tambahkan form tombol *Hapus* di dalam modal Edit tersebut, mengarah ke route hapus dengan konfirmasi keamanan:
   ```html
   <form method="POST" action="{{ route('admin.units.destroy', $unit->id) }}" onsubmit="return confirm('Yakin ingin menghapus unit ini?');">
       @csrf
       @method('DELETE')
       <button type="submit" class="btn-delete-style">Hapus Unit</button>
   </form>
   ```
*(Sesuaikan logic pada JS untuk mengambil URL form destroy dengan mengirimkan ID Unit pada fungsi `editUnit(...)`)*.
