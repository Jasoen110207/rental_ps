# TambahBang Rental PS — Smart POS, Real-Time Billing & Self-Service QR Portal

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-Neo--Brutalism-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Midtrans](https://img.shields.io/badge/Midtrans-QRIS_Payment-002855?style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

**TambahBang Rental PS** adalah sistem *Point of Sale* (POS), manajemen *billing* waktu *real-time*, dan *Self-Service Portal* berbasis QR Code yang dirancang khusus untuk memodernisasi operasional bisnis **Rental PlayStation**.

Mengusung desain antarmuka **Neo-Brutalism** yang tegas, kontras tinggi, dan responsif, aplikasi ini memudahkan kasir maupun pemilik usaha dalam memantau durasi sewa TV/PS secara *live*, menerima pesanan makanan/minuman (F&B) langsung dari meja pelanggan tanpa harus beranjak dari kursi, memproses pembayaran **QRIS Otomatis (Midtrans)**, hingga mengelola shift dan gaji karyawan secara otomatis.

---

## Fitur-Fitur Unggulan

### 1. Real-Time Unit Matrix & Smart Billing (Prepaid & Loss)
- **Live Timer & Indikator Visual:** Kasir dapat memantau seluruh unit PS (PS3, PS4, PS5, Regular/VIP) dalam satu layar matriks dengan *countdown timer* (Prepaid) dan *stopwatch* (Postpaid/Loss) secara *real-time* tanpa perlu me-*refresh* halaman.
- **2 Mode Sewa Fleksibel:**
  - **Prepaid (Paket Jam):** Pelanggan menentukan durasi di awal, dilengkapi alarm visual & suara saat sisa waktu kurang dari 5 menit atau habis.
  - **Postpaid / Loss (Open Billing):** Pelanggan bermain sepuasnya dan bayar di akhir. Tagihan dihitung otomatis secara presisi **per blok 5 menit** berdasarkan tarif per jam unit PS.
- **Auto-Restore Stok Add-On:** Penyewaan perangkat tambahan (seperti Stik Ekstra / VR) otomatis mengembalikan stok saat sesi *checkout* selesai.

### 2. Smart Self-Service QR Code (Anti-Order Fiktif & Anti-History Browser)
Terinspirasi dari sistem pemesanan QR restoran modern, setiap meja TV memiliki **QR Code khusus** yang dapat di-*scan* pelanggan menggunakan kamera HP tanpa perlu mengunduh aplikasi atau *login*:
- **Fitur Pelanggan dari Meja:**
  - Pantau sisa waktu bermain & rincian tagihan berjalan secara *live*.
  - ➕ **Request Tambah Waktu (Billing)** langsung dari HP.
  - 🍜 **Pesan F&B (Makanan & Minuman)** atau sewa *Add-On* tambahan.
  - 🔔 **Panggil Petugas (Service Call)** jika mengalami kendala teknis di meja.
- **Keamanan Token Sesi Dinamis (*Anti-Fictitious Order*):**
  - Saat pelanggan memindai QR statis di meja (`/scan/{tv_token}`), sistem melakukan *session handshake* dan mengalihkan pelanggan ke URL sesi dinamis (`/customer/{session_token}`).
  - Begitu sesi *checkout*, token sesi tersebut **langsung hangus permanen**. Pelanggan nakal tidak dapat membuka ulang link dari *History Browser* di rumah untuk membuat pesanan fiktif pada pelanggan berikutnya.

### 3. Payment Gateway Midtrans (QRIS Dinamis) & Multi-Payment
- **Integrasi Midtrans Snap:** Saat pelanggan memilih pembayaran **QRIS**, layar Kasir/Admin langsung memunculkan *popup* kode QRIS resmi dari Midtrans yang siap dipindai menggunakan *e-wallet* atau *mobile banking* pelanggan.
- **Sinkronisasi Otomatis & Webhook SHA-512:** Status pembayaran terverifikasi otomatis secara aman (*idempotent* dengan `lockForUpdate`), membebaskan meja PS dan mencatat omzet ke shift kasir yang sedang bertugas.
- **Metode Pembayaran Lengkap:** Mendukung pembayaran **Tunai (Cash)**, **QRIS (Midtrans)**, **Transfer Bank**, maupun metode lainnya.

### 4. Request Center & POS F&B Terintegrasi (dengan Audio Notifikasi)
- Setiap permintaan tambah jam, pesanan mie/minuman, atau panggilan bantuan dari QR meja pelanggan akan langsung muncul di panel **Pesan Masuk (Customer Requests)** di layar Kasir & Admin lengkap dengan **efek suara notifikasi otomatis**.
- Kasir cukup menekan **Terima (Approve)** atau **Tolak (Reject)**—stok produk F&B dan total tagihan meja akan langsung diperbarui detik itu juga.
- Mendukung juga transaksi **F&B Mandiri (Walk-in POS)** untuk pembeli yang hanya membeli makanan/minuman tanpa menyewa PS.

### 5. Keamanan Berlapis (Email + Password + 6-Digit PIN Kasir)
- Sistem autentikasi dilengkapi verifikasi **PIN 6-Digit** khusus untuk mencegah akses tanpa izin pada meja kasir.
- Pemisahan hak akses (*Role-Based Access Control*) yang jelas antara **Admin (Owner)** dan **Kasir (Operator)**.

### 6. Manajemen Shift Otomatis & Auto-Payroll (Slip Gaji PDF)
- **Auto-Shift Tracking:** Saat kasir *login*, sistem otomatis mendeteksi atau membuka sesi Shift kerja (Pagi / Sore), mencatat uang modal awal (*starting cash*), mengakumulasi seluruh omzet transaksi selama shift, serta mencatat uang sisa serah-terima saat *Tutup Shift*.
- **Auto-Payroll & Slip Gaji:** Setiap shift yang diselesaikan kasir otomatis tercatat ke modul **Penggajian (Payroll)** milik Admin. Admin dapat mengatur gaji pokok per shift, menambahkan bonus/potongan, serta mencetak atau mengunduh **Slip Gaji berformat PDF**.

### 7. Sistem Booking Jadwal Anti-Bentrok
- Pelanggan dapat memesan jadwal main di jam tertentu. Sistem secara cerdas memvalidasi ketersediaan unit agar tidak bentrok dengan sesi yang sedang berjalan maupun jadwal *booking* lainnya, lengkap dengan kalkulasi estimasi biaya otomatis.

### 8. Laporan Keuangan Lengkap & Ekspor Dokumen (PDF / CSV)
- Rekapitulasi pendapatan harian, bulanan, riwayat per meja, penjualan produk F&B, hingga cetak **Struk / Invoice Transaksi** serta ekspor laporan keuangan ke format **PDF** dan **CSV (Excel)**.

---

## Spesifikasi Teknis (Tech Stack)

| Komponen | Teknologi yang Digunakan |
| :--- | :--- |
| **Backend Framework** | Laravel 12.x (PHP 8.2+) |
| **Database** | MySQL / MariaDB |
| **Frontend & UI** | Laravel Blade, Tailwind CSS (Desain *Neo-Brutalism*), Vanilla JS |
| **Payment Gateway** | Midtrans PHP SDK (`midtrans/midtrans-php` - Snap & Notification Webhook) |
| **PDF Generator** | Laravel DomPDF (`barryvdh/laravel-dompdf`) |
| **API & Auth** | Laravel Session Auth & Laravel Sanctum |
| **Testing** | PHPUnit (Feature & Unit Testing) |

---

## Gambaran Alur Kerja Sistem (Workflow)

1. **Buka Sesi Rental:** Kasir memilih unit PS yang berstatus `AVAILABLE`, memasukkan nama pelanggan, dan memilih mode **Prepaid** (paket jam) atau **Postpaid** (Loss).
2. **Self-Service di Meja:** Pelanggan memindai **QR Code** di meja TV menggunakan HP untuk memantau waktu, memesan F&B, atau menambah durasi main.
3. **Konfirmasi Kasir:** Kasir menerima notifikasi suara di *dashboard*, menyetujui pesanan pelanggan, dan menyiapkan pesanan.
4. **Checkout & Pembayaran:** Saat selesai bermain, kasir menekan tombol **Selesai / Checkout**, memilih metode bayar (**Tunai** atau **QRIS Midtrans**), dan sesi ditutup secara otomatis.

---

## Panduan Instalasi & Menjalankan Proyek

Pastikan perangkat Anda sudah terinstal **PHP >= 8.2** (disarankan menggunakan Laragon / XAMPP / Herd), **Composer**, **Node.js & NPM**, serta **MySQL**.

### 1. Clone Repository & Instal Dependensi
```bash
git clone https://github.com/Jasoen110207/rental_ps.git
cd rental_ps
composer install
npm install
```
> **Catatan Library Tambahan:** Perintah `composer install` di atas sudah otomatis menginstal **`midtrans/midtrans-php`** dan **`barryvdh/laravel-dompdf`** karena sudah terdaftar di `composer.json`. Namun, jika di perangkat Anda muncul error *Class Midtrans Not Found*, jalankan perintah ini secara manual:
> ```bash
> composer require midtrans/midtrans-php
> ```

### 2. Konfigurasi File `.env`
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan konfigurasi **Database** serta **Midtrans Sandbox** Anda:
```env
APP_NAME="TambahBang Rental PS"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_ps
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Payment Gateway Midtrans (Sandbox / Production)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```
> **Tips Midtrans:** Anda bisa mendapatkan `MIDTRANS_SERVER_KEY` dan `MIDTRANS_CLIENT_KEY` gratis melalui [Dashboard Midtrans Sandbox](https://dashboard.sandbox.midtrans.com/) pada menu **Settings -> Access Keys**.

### 3. Generate Key, Migrasi Database & Data Awal (Seeder)
```bash
php artisan key:generate
php artisan migrate --seed
```
*(Perintah `--seed` akan otomatis membuat akun Admin, akun Kasir, daftar Unit PS3/PS4/PS5, serta katalog menu F&B contoh).*

### 4. Jalankan Server Aplikasi
Buka terminal dan jalankan:
```bash
# Terminal 1: Jalankan server Laravel
php artisan serve

# Terminal 2 (Opsional saat development UI): Kompilasi aset Vite
npm run dev
```
Atau jika Anda menggunakan **Laragon**, Anda dapat langsung mengaksesnya melalui *virtual host* `http://rental_ps.test` dan membuka `http://localhost:8000`.

### Panduan Update untuk Anggota Tim (Setelah `git pull`)
Jika Anda atau rekan tim baru saja menarik perubahan terbaru dari GitHub (`git pull origin main`), **wajib** menjalankan 3 perintah berikut agar *library* baru dan kolom database terbaru ikut tersinkronisasi:
```bash
composer install
php artisan migrate
php artisan config:clear
```

---

## Akun Demo Bawaan (Default Login)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat langsung masuk menggunakan akun berikut:

| Peran (Role) | Email | Password | PIN Kasir (6 Digit) | Hak Akses Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Admin (Owner)** | `admin@rental.com` | `password` | `123456` | Full Akses: Monitoring Billing, Kelola Unit & QR, Produk F&B, Kasir/User, Payroll Slip Gaji, & Laporan Keuangan PDF/CSV |
| **Kasir (Operator)** | `kasir@rental.com` | `password` | `123456` | Operasional Harian: Buka/Tutup Sesi PS, Terima Request QR Pelanggan, POS F&B, Booking, & Serah Terima Shift |

---

## Menjalankan Pengujian Otomatis (Automated Tests)

Proyek ini telah dilengkapi dengan pengujian fitur (*Feature Tests*) untuk memastikan alur *login*, keamanan QR Code berbasis sesi, dan *checkout* Midtrans berjalan dengan baik:

```bash
php artisan test --compact
```

---

**Dikembangkan oleh:** Tim **Besok Aja** — *Smart Solution for Modern PlayStation Rental Business*.
