# TambahBang Rental PS — Smart POS, Real-Time Billing & Self-Service QR Portal

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-C_8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-Neo--Brutalism-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Midtrans](https://img.shields.io/badge/Midtrans-QRIS_Snap_%26_Webhook-002855?style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![PHPUnit](https://img.shields.io/badge/PHPUnit-Automated_Tests-366680?style=for-the-badge&logo=php&logoColor=white)

**TambahBang Rental PS** adalah platform *Point of Sale* (POS) terintegrasi, sistem manajemen *billing* waktu *real-time*, dan *Self-Service Customer Portal* berbasis **Dynamic QR Code** yang dirancang khusus untuk mendigitalisasi dan memodernisasi operasional bisnis **Rental PlayStation & Game Lounge**.

Mengusung identitas visual **Neo-Brutalism** yang tegas, kontras tinggi, dan responsif, aplikasi ini menyatukan seluruh operasional mulai dari pemantauan waktu sewa konsol (PS3, PS4, PS5, Sim Racing, Nintendo Switch) secara *live*, pemesanan Makanan & Minuman (F&B) langsung dari meja pelanggan lewat *scan* QR, pembayaran **QRIS Otomatis (Midtrans)**, penjadwalan *booking* anti-bentrok, hingga manajemen *shift* dan kalkulasi slip gaji (*payroll*) kasir secara otomatis.

---

## Daftar Isi

1. [Latar Belakang Masalah & Solusi Inovatif](#latar-belakang-masalah-solusi-inovatif)
2. [Fitur-Fitur Unggulan](#fitur-fitur-unggulan)
3. [Spesifikasi Teknis (Tech Stack)](#spesifikasi-teknis-tech-stack)
4. [Panduan Instalasi & Konfigurasi Lengkap](#panduan-instalasi-konfigurasi-lengkap)
5. [Panduan Akses Modul (Admin, Kasir, QR Pelanggan & Publik)](#panduan-akses-modul-admin-kasir-qr-pelanggan-publik)
6. [Struktur Database & Direktori Proyek](#struktur-database-direktori-proyek)
7. [Pengujian Otomatis (Automated Testing)](#pengujian-otomatis-automated-testing)
8. [Panduan Sinkronisasi untuk Tim Pengembang](#panduan-sinkronisasi-untuk-tim-pengembang)

---

<a id="latar-belakang-masalah-solusi-inovatif"></a>
<a id="latar-belakang-masalah--solusi-inovatif"></a>
## Latar Belakang Masalah & Solusi Inovatif

Banyak usaha rental PlayStation masih mengandalkan pencatatan manual, *timer* terpisah, dan pemesanan makanan secara lisan yang rawan kebocoran omzet. **TambahBang Rental PS** hadir memberikan solusi menyeluruh:

| Permasalahan Konvensional | Solusi Inovatif **TambahBang Rental PS** |
| :--- | :--- |
| Pencatatan durasi main manual & sering terlewat saat jam ramai. | **Real-Time Unit Matrix** dengan *countdown timer* (Prepaid), *stopwatch* (Postpaid/Loss), indikator warna, & alarm/buzzer otomatis. |
| Pelanggan harus beranjak dari sofa/stik ke meja kasir untuk pesan mie/minuman atau tambah jam. | **Self-Service QR Portal di Meja**: Pelanggan cukup *scan* QR meja via HP untuk tambah durasi, pesan F&B, atau panggil petugas. |
| Kelemahan QR Code statis di mana pelanggan iseng memesan dari rumah melalui *History Browser*. | **Dynamic Session Handshake (`Anti-Fictitious Order`)**: QR fisik meja memverifikasi sesi aktif & mengalihkan ke token sesi unik yang langsung hangus begitu *checkout*. |
| Perhitungan tagihan *Open Billing (Loss)* sering membingungkan & tidak presisi. | **Kalkulasi Otomatis Per Blok 5 Menit** secara adil & transparan berdasarkan tarif per jam masing-masing konsol. |
| Jadwal *booking* sering bentrok dengan pelanggan *walk-in* yang sedang bermain. | **Anti-Collision Booking Engine** dengan validasi irisan waktu aktif & fitur **Smart Check-In (H-15 Menit)**. |
| Selisih uang kas saat pergantian penjaga & repotnya menghitung gaji/insentif kasir. | **Auto-Shift Tracking & Auto-Payroll**: Login kasir otomatis membuka/melanjutkan shift, mencatat modal & omzet, lalu menyinkronkan gaji bulanan + cetak **Slip Gaji PDF**. |

---

<a id="fitur-fitur-unggulan"></a>
## Fitur-Fitur Unggulan

### 1. Real-Time Unit Matrix & Smart Billing Engine (Prepaid & Postpaid/Loss)
- **Live Monitoring Tanpa Reload:** Kasir dan Admin dapat memantau seluruh status unit (`AVAILABLE`, `PLAYING`, `MAINTENANCE`) dalam satu layar matriks interaktif yang diperbarui secara *real-time* melalui AJAX *polling*.
- **2 Mode Sewa Fleksibel:**
  - **Prepaid (Paket Jam):** Pelanggan menentukan durasi di awal (termasuk opsi durasi menit/jam fleksibel). Dilengkapi peringatan visual (*warning threshold*) dan alarm suara/buzzer ketika waktu hampir habis atau selesai.
  - **Postpaid / Loss (Open Billing):** Pelanggan bermain sepuasnya dan membayar di akhir. Sistem menghitung tagihan secara otomatis dan presisi **per blok 5 menit**
- **Auto-Restore Stok Add-On:** Penyewaan perangkat tambahan (kategori `addon` seperti Stik Ekstra / VR) otomatis memotong stok saat dipesan dan **mengembalikan stok secara otomatis** begitu sesi *checkout*.

### 2. Smart Self-Service QR Code (`Anti-Order Fiktif` & `Anti-History Browser`)
Setiap meja TV memiliki **QR Code unik** yang dapat dicetak dari dashboard Admin dan ditempel di meja tanpa mewajibkan pelanggan mengunduh aplikasi atau membuat akun:
- **Layanan Mandiri Pelanggan dari Meja:**
  - Memantau sisa waktu bermain (*countdown* / *elapsed time*) dan rincian tagihan berjalan (Sewa PS + F&B) secara *real-time*.
  - **Request Tambah Waktu (Extend Billing)** langsung dari smartphone.
  - **Pesan Makanan, Minuman & Snack (F&B)** lengkap dengan catatan pesanan (contoh: *"Cabai 3 biji ya bang"*).
  - **Panggil Kasir (Service Call)** ke meja dengan proteksi *anti-spam cooldown* (maksimal 1 panggilan per 2 menit).
- **Arsitektur Keamanan Token 2 Lapis (*Dynamic Session Handshake*):**
  1. Saat pelanggan memindai QR statis di meja (`/scan/{tv_access_token}`), sistem memeriksa apakah meja tersebut sedang memiliki sesi `active`.
  2. Jika aktif, sistem menyematkan penanda verifikasi sesi di browser (`qr_verified_{session_token}`) dan mengalihkan pelanggan ke URL dinamis `/customer/{session_token}` dengan header `Cache-Control: no-store, no-cache, must-revalidate`.
  3. Saat sesi diselesaikan (*checkout*) oleh kasir atau waktu habis, token sesi tersebut **langsung hangus permanen**. Pelanggan tidak dapat membuka kembali tautan dari *History Browser* di luar lokasi untuk mengirim pesanan fiktif ke penyewa berikutnya.

### 3. Integrasi Payment Gateway Midtrans (QRIS Dinamis) & Multi-Payment
- **Midtrans Snap QRIS Popup:** Saat kasir memilih metode pembayaran **QRIS**, layar langsung memunculkan kode QRIS resmi dari Midtrans (mendukung seluruh aplikasi *mobile banking*, GoPay, ShopeePay, OVO, DANA).
- **Webhook Otomatis Berkeamanan SHA-512 & Idempotent Checkout:**
  - Endpoint `/midtrans/webhook` memvalidasi keaslian notifikasi menggunakan hash `SHA-512` (`order_id + status_code + gross_amount + server_key`).
  - Proses finalisasi transaksi menggunakan transaksi database dengan `lockForUpdate()` (*idempotent*), mencegah *race condition* antara klik manual kasir dan *callback* webhook, sekaligus otomatis mengubah status TV kembali menjadi `available` dan mencatat omzet ke shift aktif.
- **Multi-Metode Pembayaran:** Mendukung pembayaran **Tunai (Cash)** dengan kalkulator kembalian otomatis maupun **QRIS (Midtrans)**.

### 4. Real-Time Request Center & POS F&B Terintegrasi (dengan Audio Alert)
- **Notifikasi Suara Otomatis:** Setiap pesanan F&B, permintaan tambah jam, atau panggilan meja dari QR pelanggan akan memicu **bunyi notifikasi otomatis** dan lencana (*badge*) pesan masuk di layar Kasir & Admin.
- **Konfirmasi 1 Klik (Approve / Reject):**
  - Saat kasir menekan **Terima (Approve)** pada permintaan tambah jam, durasi sesi dan tagihan meja otomatis bertambah.
  - Saat kasir menekan **Terima (Approve)** pada pesanan makanan/minuman, stok produk otomatis berkurang dan nominalnya langsung masuk ke tagihan meja.
- **Walk-In POS (Beli F&B Tanpa Sewa PS):** Kasir juga dapat melayani pembeli yang hanya membeli makanan/minuman tanpa menyewa konsol melalui menu POS F&B.

### 5. Sistem Booking Jadwal Anti-Bentrok & Smart Check-In
- **Validasi Anti-Bentrok Ganda:** Mencegah pemesanan unit pada jam yang beririsan dengan sesi yang sedang berlangsung (`isInUse()`) maupun jadwal *booking* lain yang berstatus `Pending` atau `Active` (`hasOpenBooking()`).
- **Indikator Siap Check-In (H-15 Menit):** Sistem otomatis mendeteksi jadwal *booking* yang akan dimulai dalam ≤ 15 menit (`readyBooking()`) dan menampilkan tombol **Check-In Cepat** di kartu unit untuk langsung mengaktifkan sesi bermain.

### 6. Keamanan Berlapis (Email + Password + 6-Digit PIN Kasir)
- Sistem autentikasi mewajibkan 3 faktor kredensial saat masuk: **Email**, **Password**, dan **6-Digit PIN Kasir** (mendukung perbandingan aman `hash_equals` maupun Bcrypt hash), dilengkapi pemisahan hak akses (*Role-Based Access Control*) antara **Admin** dan **Kasir**.

### 7. Manajemen Shift Otomatis & Auto-Payroll (Slip Gaji PDF)
- **Auto-Shift Saat Login:** Ketika akun berperan `kasir` melakukan *login*, sistem secara cerdas mendeteksi jenis shift (`pagi` / `sore`), menutup otomatis shift menggantung dari kasir sebelumnya/hari kemarin, meneruskan uang kas sisa (*leftover cash*) sebagai modal awal (*starting cash*), dan merekam seluruh omzet selama kasir bertugas.
- **Sinkronisasi Penggajian (Auto-Payroll):** Setiap kehadiran shift dan penutupan shift otomatis tersinkronisasi ke modul **Payroll Admin** (`Payroll::syncForUser`). Admin dapat meninjau total hari/shift kerja, total jam kerja, akumulasi omzet yang dihasilkan kasir, menambahkan bonus/potongan, mengonfirmasi pembayaran gaji, serta mencetak atau mengunduh **Slip Gaji Resmi berformat PDF**.

### 8. Laporan Keuangan Eksekutif, Invoice & Sinkronisasi Tarif Toko
- **Cetak Struk / Invoice & Ekspor Laporan:** Cetak struk transaksi setiap sesi, serta ekspor rekapitulasi laporan keuangan lengkap ke format **PDF** (`barryvdh/laravel-dompdf`) maupun **CSV / Excel**.
- **Sinkronisasi Tarif Konsol Terpusat:** Mengubah tarif dasar per jam (PS3, PS4, PS5, Sim Racing, Nintendo Switch) di halaman **Pengaturan Toko** akan otomatis menyinkronkan harga sewa seluruh unit TV dengan tipe konsol terkait.

---

<a id="spesifikasi-teknis-tech-stack"></a>
## Spesifikasi Teknis (Tech Stack)

| Kategori | Teknologi & Versi | Kegunaan dalam Proyek |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel 13.x** (PHP `8.3+`) | Core MVC, Routing, Eloquent ORM, Session & Security |
| **Database** | **MySQL / MariaDB** (atau SQLite untuk Testing) | Penyimpanan relasional transaksi, sesi, shift, & payroll |
| **Frontend & Styling** | **Laravel Blade**, **Tailwind CSS v4**, **Vite 8** | Antarmuka *Neo-Brutalism* responsif & *interactive UI* |
| **Client-Side Scripting** | **Vanilla JavaScript (ES6+) & Fetch API** | Live countdown/stopwatch timer, AJAX polling, & audio alert |
| **Payment Gateway** | **Midtrans PHP SDK (`^2.6`)** | Integrasi pembayaran QRIS Snap & Webhook SHA-512 |
| **PDF Generator** | **Laravel DomPDF (`^3.1`)** | Pembuatan dokumen Laporan Keuangan PDF & Slip Gaji PDF |
| **API & Authentication** | **Laravel Session Auth + Laravel Sanctum (`^4.3`)** | Multi-layer Web Auth (Email + Password + PIN) & REST API |
| **Testing & Code Quality** | **PHPUnit (`^11.5`)** & **Laravel Pint (`^1.24`)** | Pengujian otomatis (Feature & Unit Test) & standar kode PSR-12 |

---

<a id="panduan-instalasi-konfigurasi-lengkap"></a>
<a id="panduan-instalasi--konfigurasi-lengkap"></a>
## Panduan Instalasi & Konfigurasi Lengkap

Pastikan lingkungan perangkat Anda telah terinstal:
- **PHP >= 8.3** (Direkomendasikan menggunakan **Laragon**, XAMPP, atau Laravel Herd) dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`, dan `gd`/`dom`.
- **Composer** (v2.x)
- **Node.js** (v18+ / v20+) & **NPM**
- **MySQL / MariaDB**

### Langkah 1: Clone Repository & Instal Dependensi
Buka terminal (Command Prompt / PowerShell / Git Bash) dan jalankan:

```bash
git clone https://github.com/Jasoen110207/rental_ps.git
cd rental_ps

# Instal dependensi PHP (termasuk Midtrans SDK & DomPDF)
composer install

# Instal dependensi Frontend (Tailwind CSS v4 & Vite)
npm install
```

### Langkah 2: Konfigurasi File Environment (`.env`)
Salin file `.env.example` menjadi `.env`:

```bash
# Untuk Windows (CMD / PowerShell)
copy .env.example .env

# Untuk Linux / macOS / Git Bash
cp .env.example .env
```

Buka file `.env` menggunakan teks editor dan sesuaikan konfigurasi **Database MySQL** serta **Midtrans Sandbox** seperti di bawah ini:

```env
APP_NAME="TambahBang Rental PS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Konfigurasi Database MySQL (Buat database bernama 'rental_ps' di phpMyAdmin / Laragon)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_ps
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Payment Gateway Midtrans (Sandbox Mode)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

> [!TIP]
> **Cara Mendapatkan Kunci API Midtrans Sandbox (Gratis):**
> 1. Daftar atau masuk ke [Dashboard Midtrans Sandbox](https://dashboard.sandbox.midtrans.com/).
> 2. Buka menu **Settings -> Access Keys**.
> 3. Salin **Server Key** dan **Client Key** ke dalam file `.env` Anda, lalu jalankan `php artisan config:clear`.
> *(Catatan: Pembayaran metode **Tunai / Cash** tetap dapat digunakan sepenuhnya meskipun kunci Midtrans belum diisi).*

### Langkah 3: Generate App Key, Migrasi Database & Jalankan Seeder
Pastikan Anda sudah membuat database kosong bernama `rental_ps` di MySQL, kemudian jalankan:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

> [!IMPORTANT]
> Perintah `--seed` **sangat disarankan** untuk dijalankan karena `DatabaseSeeder` sudah menyiapkan data skenario lengkap siap uji (2 akun Admin & Kasir, 8 unit konsol PS3/PS4/PS5/Sim Racing/Nintendo Switch, 12 katalog menu F&B, 3 sesi bermain aktif, 3 sesi selesai, shift kasir aktif, serta contoh pesanan masuk dari pelanggan).

### Langkah 4: Kompilasi Aset Frontend & Jalankan Server
```bash
# Build aset produksi (agar tampilan Tailwind CSS v4 langsung siap digunakan)
npm run build

# Jalankan server lokal Laravel
php artisan serve
```

Aplikasi kini dapat diakses melalui browser di alamat: **`http://localhost:8000`** (atau `http://rental_ps.test` jika menggunakan *auto virtual host* Laragon).

*(Opsional: Jika Anda sedang mengembangkan kode tampilan secara live, jalankan `npm run dev` di terminal terpisah atau gunakan `composer run dev`).*

---

<a id="panduan-akses-modul-admin-kasir-qr-pelanggan-publik"></a>
<a id="panduan-akses-modul-admin-kasir-qr-pelanggan--publik"></a>
## Panduan Akses Modul (Admin, Kasir, QR Pelanggan & Publik)

Bagian ini menjelaskan secara rinci cara mengakses dan menguji setiap peran (*role*) serta fitur di dalam aplikasi untuk keperluan demonstrasi maupun penilaian.

### 1. Daftar Akun Demo Bawaan (Default Login Credentials)

Halaman login dapat diakses melalui tombol **Login Staff** di halaman utama atau langsung menuju URL: **`http://localhost:8000/login`**

| Peran (Role) | Nama Pengguna | Email | Password | PIN Kasir (6 Digit) | Redirect Setelah Login |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Admin (Owner)** | Admin Rental | `admin@rental.com` | `password` | `123456` | `/admin/dashboard` |
| **Kasir (Operator)** | Kasir 1 (Budi) | `kasir@rental.com` | `password` | `123456` | `/kasir/dashboard` |

> [!NOTE]
> Saat masuk di halaman `/login`, pastikan Anda mengisi **ketiga kolom**: **Email**, **Password**, dan **PIN Kasir (`123456`)**. Sistem akan menolak akses apabila PIN 6-digit tidak sesuai.

---

### 2. Cara Mengakses Halaman Publik / Landing Page (`/`)
- **URL:** `http://localhost:8000/`
- **Fitur yang Dapat Diuji:**
  - Melihat informasi lengkap toko, alamat, dan jam operasional yang terhubung secara dinamis dengan pengaturan Admin.
  - Melihat **Katalog & Tarif Konsol** (PS3, PS4, PS5, Sim Racing, Nintendo Switch) beserta jumlah unit yang tersedia secara *real-time*.
  - Tombol **Hubungi / Booking via WhatsApp** yang otomatis membuat tautan pesan WhatsApp ke nomor toko.

---

### 3. Cara Mengakses & Menggunakan Dashboard Admin (Owner)
1. Buka `http://localhost:8000/login`.
2. Masukkan Email: `admin@rental.com`, Password: `password`, dan PIN: `123456`, lalu klik **Masuk**.
3. Anda akan diarahkan ke **Dashboard Operasional Admin** (`/admin/dashboard`).

Berikut adalah daftar lengkap menu dan URL pada **Panel Admin**:

| Menu / Modul Admin | Path URL | Fungsi & Cara Penggunaan |
| :--- | :--- | :--- |
| **Dashboard Matrix** | `/admin/dashboard` | Memantau seluruh unit TV secara *live*, membuka sesi baru (Prepaid/Postpaid), menambah jam (*extend*), menambah pesanan F&B ke meja, menyalakan/mematikan *buzzer*, serta memproses *Checkout* (Tunai / QRIS Midtrans). |
| **Pesan Masuk (Requests)** | `/admin/requests` | Melihat antrean permintaan *real-time* dari QR meja pelanggan (Tambah Waktu, Pesan Makanan/Minuman, Panggil Kasir) dan menekan tombol **Approve (Terima)** atau **Reject (Tolak)**. |
| **POS & Katalog F&B** | `/admin/pos` | Melayani pembelian makanan/minuman langsung (*walk-in* maupun dibebankan ke meja PS yang sedang aktif). |
| **Kelola Produk F&B** | `/admin/pos/manage` | Menambah menu makanan/minuman/snack/add-on baru, mengubah harga, memperbarui stok, atau menonaktifkan menu yang habis. |
| **Jadwal Booking** | `/booking` | Membuat jadwal *booking* unit PS untuk jam/tanggal tertentu, membatalkan *booking*, atau melakukan **Check-In** saat pelanggan datang. |
| **Manajemen Unit & QR** | `/admin/units` | Menambah/mengedit unit konsol PS, mengubah status unit ke *Maintenance*, serta **Mencetak / Membuka QR Code Meja** (`/admin/units/{id}/qr`). |
| **Riwayat Transaksi** | `/admin/transactions` | Melihat rekap pendapatan, rincian transaksi per sesi, mencetak **Struk / Invoice** (`/admin/transactions/{id}/invoice`), serta mengekspor laporan ke **PDF** (`/admin/reports/export-pdf`) dan **CSV/Excel** (`/admin/reports/export-csv`). |
| **Manajemen Shift** | `/admin/shifts` | Memantau shift kasir yang sedang aktif, riwayat buka/tutup shift, modal awal, omzet sistem vs uang aktual yang dilaporkan kasir. |
| **Payroll / Penggajian** | `/admin/payroll` | Memantau kalkulasi gaji kasir otomatis per periode bulan berdasarkan jumlah shift & jam kerja, menambah bonus/potongan, konfirmasi bayar gaji, serta mencetak **Slip Gaji PDF** (`/admin/payroll/print` & `/admin/payroll/pdf`). |
| **Kelola Pengguna/Kasir** | `/admin/users` | Menambah akun Kasir/Admin baru, mereset password, atau memperbarui PIN 6-digit karyawan. |
| **Pengaturan Toko** | `/admin/settings` | Mengatur nama toko, alamat, nomor WhatsApp, tarif dasar per konsol (otomatis memperbarui harga unit), batas menit peringatan, & efek suara notifikasi. |

---

### 4. Cara Mengakses & Menggunakan Dashboard Kasir (Operator)
1. Buka `http://localhost:8000/login` (Jika masih login sebagai Admin, klik tombol **Logout** terlebih dahulu).
2. Masukkan Email: `kasir@rental.com`, Password: `password`, dan PIN: `123456`, lalu klik **Masuk**.
3. **Otomatisasi Saat Login Kasir:** Begitu berhasil masuk, sistem otomatis membuka/melanjutkan **Shift Kerja Aktif** untuk kasir tersebut dan menyinkronkan absensi shift ke modul **Payroll**.
4. Anda akan diarahkan ke **Dashboard Operasional Kasir** (`/kasir/dashboard`).

Berikut adalah daftar lengkap menu dan URL pada **Panel Kasir**:

| Menu / Modul Kasir | Path URL | Fungsi & Cara Penggunaan |
| :--- | :--- | :--- |
| **Dashboard Kasir** | `/kasir/dashboard` | Layar utama kasir untuk memantau *live timer* seluruh meja PS, statistik omzet hari ini, dan kontrol cepat sesi rental. |
| **Kontrol POS & Sesi** | `/kasir/pos` | Mengelola sesi aktif (Buka Sesi, Tambah Waktu, Tambah F&B ke Meja, Buzzer, & Checkout Pembayaran Tunai/QRIS). |
| **Katalog Menu F&B** | `/kasir/menu` | Melayani pesanan F&B cepat untuk pelanggan *walk-in* maupun pelanggan yang sedang bermain di meja. |
| **Booking Unit** | `/kasir/booking` | Mencatat pesanan jadwal *booking* pelanggan, validasi anti-bentrok otomatis, & *Check-In* jadwal menjadi sesi bermain aktif. |
| **Request Pelanggan** | `/kasir/request` | Merespons permintaan *Self-Service QR* dari meja pelanggan (Approve / Reject pesanan mie, minuman, tambah jam, atau panggilan bantuan). |
| **Riwayat Transaksi** | `/kasir/transaksi` | Melihat daftar transaksi sewa PS dan penjualan F&B yang telah diselesaikan beserta metode pembayarannya. |
| **Serah Terima Shift** | `/kasir/sift` | Memantau uang kas awal (*starting cash*), akumulasi omzet shift berjalan, serta melakukan **Tutup Shift** dengan memasukkan laporan uang fisik aktual dan sisa kas untuk shift berikutnya. |
| **Status Unit TV** | `/kasir/unit` | Melihat daftar seluruh konsol dan mengubah status unit (misalnya menandai unit sedang `Maintenance` atau `Available`). |

---

### 5. Cara Mengakses & Menguji Self-Service QR Portal Pelanggan (Tanpa Login)

Untuk menguji fitur unggulan **Scan QR Code Meja Pelanggan** langsung di komputer/laptop maupun HP tanpa perlu mencetak kertas fisik:

1. Masuk sebagai **Admin** (`admin@rental.com`) dan buka menu **Manajemen Unit** di **`http://localhost:8000/admin/units`**.
2. Pilih salah satu unit yang berstatus **`PLAYING`** (Dari data *seeder*, unit **PS 03 - PS4 Pro**, **PS 04 - PS5 Next-Gen**, dan **PS 07 - PS5 VIP Arena** sudah berstatus aktif bermain).
3. Klik tombol **QR Code** pada unit tersebut (atau buka langsung **`http://localhost:8000/admin/units/3/qr`** untuk PS 03).
4. Di halaman cetak QR Code tersebut:
   - Anda dapat memindai gambar QR menggunakan HP (pastikan HP dan laptop berada di jaringan lokal yang sama), **ATAU**
   - Klik langsung tautan simulasi **`/scan/{access_token}`** yang tertera di halaman tersebut.
5. **Hasil Pengujian:**
   - Sistem akan memverifikasi sesi meja, menandai sesi browser Anda sebagai terverifikasi QR, lalu mengalihkan Anda ke halaman **Customer Self-Service Portal** (`/customer/{session_token}`).
   - Di halaman ini, Anda dapat menguji:
     - Melihat *countdown timer* / waktu berjalan secara *live*.
     - Mengirim permintaan **Tambah Waktu Bermain**.
     - Memilih menu **Indomie / Es Teh / Snack** dan mengirim pesanan ke kasir (atau melalui mode *Quick Order* `/customer/order/{session_token}`).
     - Menekan tombol **Panggil Kasir ke Meja**.
   - Buka kembali tab **Admin / Kasir** (`/admin/requests` atau `/kasir/request`) untuk melihat pesanan tersebut muncul secara *real-time* lengkap dengan suara notifikasi!
   - Coba lakukan **Checkout** pada meja PS 03 dari dashboard Kasir/Admin, lalu *refresh* halaman pelanggan tadi—halaman pelanggan akan otomatis terkunci dengan status **"SESI RENTAL TELAH BERAKHIR"** (*Anti-Order Fiktif*).

---

<a id="struktur-database-direktori-proyek"></a>
<a id="struktur-database--direktori-proyek"></a>
## Struktur Database & Direktori Proyek

### Skema Tabel Utama Database
| Nama Tabel | Deskripsi & Fungsi |
| :--- | :--- |
| `users` | Menyimpan data pengguna (`admin` & `kasir`), password terenkripsi, dan `pin` 6-digit kasir. |
| `tvs` | Menyimpan master unit konsol/TV (`ps3`, `ps4`, `ps5`, `sim_racing`, `nintendo_switch`), tarif per jam, status (`available`, `playing`, `maintenance`), status *buzzer*, dan `access_token` QR statis meja. |
| `play_sessions` | Menyimpan sesi bermain pelanggan (`prepaid` / `postpaid`), waktu mulai & selesai, `session_token` dinamis (untuk proteksi QR), nominal sewa, nominal F&B, total tagihan, metode bayar, serta `midtrans_order_id` & `snap_token`. |
| `products` | Menyimpan katalog produk F&B dan perangkat tambahan (`food`, `drink`, `snack`, `addon`), harga, stok, dan ketersediaan. |
| `session_orders` | Menyimpan item F&B atau *add-on* yang dipesan pada suatu `play_session` beserta kuantitas dan subtotalnya. |
| `customer_requests` | Menyimpan permintaan mandiri pelanggan dari QR meja (`add_time`, `order_food`, `service_call`), *payload* JSON, dan status konfirmasi (`pending`, `approved`, `rejected`). |
| `bookings` | Menyimpan jadwal reservasi unit oleh pelanggan, waktu mulai, durasi jam, estimasi biaya, dan status (`Pending`, `Active`, `Completed`). |
| `shifts` | Menyimpan catatan kehadiran dan kas per shift kasir (`pagi` / `sore`), modal awal, omzet sistem, uang aktual, sisa kas, dan catatan serah terima. |
| `payrolls` | Menyimpan rekapitulasi gaji kasir bulanan (`period_month`), gaji pokok berbasis jumlah shift, jam kerja, omzet, bonus, potongan, dan status pembayaran gaji. |
| `settings` | Menyimpan konfigurasi dinamis toko (nama toko, alamat, nomor telepon, tarif dasar konsol, dan pengaturan sistem). |

### Struktur Direktori Penting
```text
rental_ps/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controller Web & API (Admin, Kasir, Customer QR, Midtrans, Payroll, dll.)
│   │   └── Middleware/        # CheckRole Middleware (RBAC Admin & Kasir)
│   ├── Models/                # Eloquent Models (Tv, PlaySession, Product, Booking, Shift, Payroll, dll.)
│   ├── Notifications/         # Real-time Database Notifications untuk Request Pelanggan
│   └── Services/
│       └── MidtransService.php # Service kalkulasi billing, Snap Token, & Idempotent Checkout
├── config/
│   └── midtrans.php           # Konfigurasi kredensial Midtrans
├── database/
│   ├── factories/             # Model Factories untuk pengujian otomatis
│   ├── migrations/            # Skema tabel database
│   └── seeders/
│       └── DatabaseSeeder.php # Data awal lengkap siap demonstrasi
├── resources/
│   └── views/
│       ├── admin/             # Antarmuka Dashboard, POS, Payroll, Unit QR, & Laporan Admin
│       ├── kasir/             # Antarmuka Operasional Kasir (Dashboard, POS, Booking, Shift, dll.)
│       ├── customer/          # Portal Self-Service QR Pelanggan & Halaman Expired
│       ├── auth/              # Halaman Login 3-Faktor (Email + Password + PIN)
│       └── landing.blade.php  # Landing Page Publik Neo-Brutalism
├── routes/
│   ├── web.php                # Definisi rute Web (Publik, Customer QR, Admin, & Kasir)
│   └── api.php                # Definisi rute REST API (Sanctum)
└── tests/
    └── Feature/               # Suite pengujian otomatis (Booking, QR Security, Midtrans, Payroll, Auth)
```

---

<a id="pengujian-otomatis-automated-testing"></a>
## Pengujian Otomatis (Automated Testing)

Proyek ini telah dilengkapi dengan **Feature & Integration Tests** menggunakan **PHPUnit** untuk menjamin keandalan logika bisnis dan keamanan sistem, meliputi:
- **`CustomerQrSessionSecurityTest`**: Memastikan keamanan *Dynamic Session Handshake* QR Code meja, memblokir akses dari *history browser* setelah sesi berakhir, dan mencegah pesanan fiktif.
- **`MidtransCheckoutTest`**: Menguji kalkulasi tagihan *Prepaid* & *Postpaid* (blok 5 menit), pembuatan Snap Token, validasi tanda tangan `SHA-512` Webhook Midtrans, dan sifat *idempotent* saat *checkout*.
- **`BookingValidationTest`**: Menguji pencegahan bentrok jadwal *booking* dengan sesi aktif maupun *booking* lain, fitur *Check-In*, serta sinkronisasi tarif konsol di pengaturan.
- **`CashierLoginAndPayrollSyncTest` & `WebLoginTest`**: Menguji autentikasi Email + Password + PIN 6-Digit, pembukaan shift otomatis saat kasir login, serta sinkronisasi gaji (*Payroll*).

Untuk menjalankan seluruh pengujian otomatis, gunakan perintah:

```bash
php artisan test --compact
```

---

<a id="panduan-sinkronisasi-untuk-tim-pengembang"></a>
<a id="panduan-sinkronisasi-untuk-tim-pengembang-setelah-git-pull"></a>
## Panduan Sinkronisasi untuk Tim Pengembang (Setelah `git pull`)

Apabila Anda menarik pembaruan kode terbaru dari repositori (`git pull origin main`), jalankan perintah berikut agar seluruh dependensi, skema tabel baru, dan konfigurasi tersinkronisasi:

```bash
composer install
php artisan migrate
php artisan config:clear
npm run build
```

---

<div align="center">
  <b>Dikembangkan oleh Tim Besok Aja</b><br>
  <i>Smart POS, Real-Time Billing & Self-Service QR Solution for Modern PlayStation Rental Business</i>
</div>
