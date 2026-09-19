# Planning Implementasi: Validasi Order F&B dan Perilaku Logout Shift

Dokumen ini berisi detail instruksi dan langkah implementasi untuk dua fitur/perbaikan baru. Instruksi ini ditujukan untuk _Junior Programmer_ atau AI Model selanjutnya yang akan melakukan _coding_.

## Objektif 1: Pembatasan Pesanan F&B di Luar Jam Billing
**Deskripsi**: Pelanggan tidak boleh memesan makanan/minuman (F&B) apabila waktu bermainnya (timer billing) sudah habis.
**Lokasi File yang Terdampak**: 
- `app/Http/Controllers/CustomerController.php`
- `resources/views/customer/order.blade.php` (Opsional untuk UX)

### Langkah Implementasi:
1. Buka file `CustomerController.php` dan cari metode `requestFood()`.
2. Sebelum memproses `CustomerRequest::create`, pastikan untuk mengambil sesi bermain yang sedang aktif di TV tersebut:
   ```php
   $activeSession = PlaySession::where('tv_id', $tv->id)->where('status', 'active')->first();
   ```
3. Lakukan pengecekan waktu: Jika sesi adalah `prepaid` (berbayar di awal) dan waktu berjalannya telah habis (`end_time` berada di masa lalu), maka tolak pesanan F&B.
   ```php
   if ($activeSession && $activeSession->billing_type === 'prepaid' && $activeSession->end_time && $activeSession->end_time->isPast()) {
       return back()->with('error', 'Waktu bermain sudah habis! Anda tidak dapat memesan F&B.');
   }
   ```
4. *(Opsional)* Di `order.blade.php`, tambahkan logika kondisi untuk mematikan (`disabled`) atau menyembunyikan tombol "Pesan Makanan & Minuman" apabila timer sudah 00:00:00.

---

## Objektif 2: Pemisahan Logika Logout pada Penutupan Shift Kasir vs Admin
**Deskripsi**: Ketika penutupan shift dilakukan melalui tampilan Kasir, sistem harus melakukan *logout* (mengeluarkan akun kasir). Namun, jika penutupan shift dilakukan oleh Admin melalui *dashboard* Admin, sistem **TIDAK BOLEH** melakukan *logout*, karena admin mungkin masih perlu mengelola hal lain.
**Lokasi File yang Terdampak**: 
- `app/Http/Controllers/KasirController.php`
- `app/Http/Controllers/ShiftController.php`

### Langkah Implementasi:
1. Sistem telah memisahkan _controller_ untuk Kasir dan Admin. Pastikan logika *logout* **HANYA** berada di `KasirController.php`.
2. Buka `KasirController.php`, cari metode `endShift()`. Pastikan pada akhir fungsi terdapat logika *logout* sebelum di-*redirect*:
   ```php
   \Illuminate\Support\Facades\Auth::logout();
   $request->session()->invalidate();
   $request->session()->regenerateToken();
   return redirect()->route('login')->with('success', 'Shift berhasil ditutup, silakan login kembali.');
   ```
3. Buka `ShiftController.php`, cari metode `endShift()`. Metode ini dieksekusi saat Admin yang menekan tombol "Tutup Shift" dari view Admin. Pastikan di metode ini **TIDAK ADA** kode `Auth::logout()`. Cukup lakukan _update_ status shift menjadi `closed` dan kembalikan ke halaman sebelumnya:
   ```php
   // ... [logika update shift] ...
   return back()->with('success', 'Shift kasir berhasil ditutup dan diserahkan.');
   ```
4. Uji coba dengan menutup shift dari panel Kasir (harus ter-logout) dan menutup shift dari panel Admin (harus tetap login di halaman admin).
