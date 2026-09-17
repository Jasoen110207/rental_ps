# 📋 Perencanaan Perbaikan Fitur & Bug (Issue 3)

Dokumen ini berisi tahapan implementasi (langkah demi langkah) untuk memperbaiki beberapa bug lanjutan dan penambahan fitur baru. Dokumen ini dirancang agar dapat dengan mudah diikuti oleh Junior Programmer atau AI Assistant (dengan modal model yang lebih efisien).

---

## 1. Error pada Menu Management Shift (Sisi Admin)
**Masalah:** Saat mengklik menu "Management Shift" di sidebar Admin, muncul error pada halaman.
**Diagnosa awal:** Kemungkinan file Blade View untuk halaman tersebut tidak lengkap, salah path, atau terdapat kegagalan query relasi di controller.

**Tahapan Implementasi:**
1. Buka file `app/Http/Controllers/ShiftController.php`.
2. Periksa metode `index()` untuk hak akses admin. Pastikan metode ini me-return view yang benar, contohnya: `return view('admin.shifts.index', compact('shifts'));`.
3. Buka (atau buat jika belum ada) file view di `resources/views/admin/shifts/index.blade.php`. 
4. Pastikan syntax didalam blade sudah sesuai (meng-extend layout `@extends('layouts.admin')`).
5. Jika error disebabkan oleh variabel yang _undefined_ (misal memanggil `$shift->user->name`), pastikan query di controller sudah melampirkan _eager loading_ `with('user')` atau gunakan _null safe operator_ (`$shift->user?->name`) di dalam Blade.

---

## 2. Penambahan Sistem PIN Kasir (Untuk Buka Shift)
**Masalah:** Kasir saat ini dapat membuka shift baru tanpa autentikasi spesifik.
**Ekspektasi:** Setiap kasir harus memasukkan PIN personal mereka saat akan membuka/memulai shift baru.

**Tahapan Implementasi:**
1. **Update Database & Model:**
   - Buat migration baru dengan perintah: `php artisan make:migration add_pin_to_users_table --table=users`.
   - Di file migration, tambahkan kolom: `$table->string('pin')->nullable()->after('password');`. (Gunakan string agar bisa menyimpan teks hash).
   - Jalankan `php artisan migrate`.
   - Buka `app/Models/User.php` dan tambahkan `'pin'` ke dalam atribut array `$fillable`.
2. **Manajemen Pengguna (Admin):**
   - Pada halaman form tambah/edit user kasir (sisi admin), tambahkan input form "PIN Kasir" (4-6 digit angka).
   - Di fungsi simpan/update controller, lakukan *hashing* pada PIN tersebut sebelum disimpan: `$user->pin = Hash::make($request->pin);`.
3. **Pembaruan UI (Sisi Kasir):**
   - Buka form/modal untuk memulai shift, misalnya di `resources/views/kasir/dashboard.blade.php` atau file shift terkait.
   - Tambahkan input tambahan: `<input type="password" name="pin" placeholder="Masukkan PIN Kasir" required maxlength="6" minlength="4">`.
4. **Logika Validasi Buka Shift:**
   - Buka metode `startShift` di `app/Http/Controllers/KasirController.php`.
   - Tambahkan aturan validasi: `$request->validate(['pin' => 'required']);`
   - Periksa kecocokan PIN dengan milik user yang sedang login menggunakan `Hash::check()`:
     ```php
     if (!\Illuminate\Support\Facades\Hash::check($request->pin, auth()->user()->pin)) {
         return back()->with('error', 'PIN yang Anda masukkan salah!');
     }
     ```
   - Jika PIN cocok, biarkan logika pembuatan shift berjalan seperti biasa.

---

## 3. Menampilkan Barcode QRIS Saat Checkout Lunas
**Ekspektasi:** Saat kasir mengklik tombol "Selesaikan Lunas" di pop-up Checkout, sistem harus menampilkan barcode QRIS (jika tipe pembayaran = QRIS) alih-alih langsung submit.

**Tahapan Implementasi:**
1. Buka file `resources/views/kasir/dashboard.blade.php`.
2. Cari bagian modal checkout (`#modal-checkout`) dan _form_ di dalamnya.
3. Siapkan satu blok HTML berisi gambar Barcode QRIS toko di dalam form tersebut, lalu sembunyikan secara default menggunakan kelas CSS `hidden`. Beri ID, contohnya `id="qris-container"`.
4. **Modifikasi JavaScript (Logika Sisi Klien):**
   - Pada event saat formulir di-_submit_, tahan prosesnya menggunakan `event.preventDefault()`.
   - Cek metode pembayaran apa yang dipilih (`document.querySelector('input[name="payment_method"]:checked').value`).
   - Jika metode pembayaran adalah `qris` dan kontainer QRIS saat ini masih `hidden`:
     - Tampilkan kontainer QRIS (hapus kelas `hidden`).
     - Ubah teks tombol submit dari "SELESAIKAN LUNAS" menjadi "KONFIRMASI SUDAH DIBAYAR".
   - Jika tombol ditekan lagi (kontainer QRIS sudah tidak di-hidden, alias kasir sudah mengkonfirmasi pembayaran diterima), maka abaikan penahanan event dan lakukan submisi asli: `document.getElementById('checkout-form').submit();`.

---

## 4. Request F&B Customer Tidak Muncul di Kasir & Riwayat
**Masalah:** Saat fitur pesan makan/minum diklik dari antarmuka Customer, permintaan gagal masuk ke daftar riwayat, tidak muncul di kasir, padahal proses *add time* (tambah durasi) berhasil.
**Diagnosa awal:** Terdapat ketidakcocokan antara _payload array_ yang dikirim melalui HTML form dengan aturan validasi di fungsi Controller. Akibatnya, request ditolak dan tidak tersimpan.

**Tahapan Implementasi:**
1. **Periksa Struktur HTML Form (Customer):**
   - Buka `resources/views/customer/order.blade.php`.
   - Periksa bagian tombol atau _form_ pemesanan "Menu Cepat Populer".
   - Pastikan variabel input untuk keranjang (item) benar-benar berbentuk _nested array_ yang bisa dibaca PHP. Contoh:
     ```html
     <input type="hidden" name="items[0][product_id]" value="{{ $p->id }}">
     <input type="hidden" name="items[0][quantity]" value="1">
     ```
2. **Lacak Payload di Controller:**
   - Buka `app/Http/Controllers/CustomerController.php`, cari metode `requestFood`.
   - Tambahkan fungsi debugging sementara di baris paling atas fungsi tersebut: `\Log::info($request->all());`.
   - Coba lakukan pemesanan ulang lewat web, lalu periksa `storage/logs/laravel.log`. Apakah array `items` terbaca?
   - Jika pesan error dari validator sebelumnya diam (`silently failed`), sesuaikan nama field (atribut `name`) HTML agar cocok persis dengan ekspektasi validasi: `$request->validate(['items' => 'required|array', 'items.*.product_id' => 'required']);`.
3. **Casting di Model Database:**
   - Setelah validasi lolos, pastikan bahwa tabel `customer_requests` kolom `payload` otomatis dikonversi ke JSON. 
   - Buka `app/Models/CustomerRequest.php`. Pastikan terdapat array casting:
     ```php
     protected $casts = [
         'payload' => 'array',
     ];
     ```
   - Jika hal ini tidak ada, maka array `items` yang dikirim dari `requestFood` akan gagal disimpan ke dalam database.
4. **Verifikasi Tampilan UI:**
   - Jika data berhasil masuk tabel database dengan status `pending`, maka mekanisme JavaScript polling bawaan (yang sudah ada) akan secara otomatis menampilkannya di dashboard Kasir dan di riwayat HP Customer.
