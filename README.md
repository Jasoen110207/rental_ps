# Smart POS & Real-Time Monitoring Rental PS

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![WebSocket](https://img.shields.io/badge/Laravel_Reverb-RealTime-000000?style=for-the-badge)

Sistem *Point of Sale* (POS) dan *monitoring* waktu cerdas yang dirancang khusus untuk menyelesaikan masalah operasional pada bisnis Rental PlayStation. Proyek ini dibangun untuk kompetisi *web development*, menawarkan fitur manajemen waktu *real-time*, pemesanan F&B terintegrasi, *self-service* via QR Code, dan simulasi integrasi perangkat keras (IoT) untuk kontrol *buzzer* peringatan.

## 🌟 Fitur Utama

1. **Grid Monitoring Real-Time (WebSocket):** Kasir dapat melihat status seluruh TV/PS (Kosong, Bermain, Waktu Mau Habis) dengan hitung mundur yang berjalan secara *live* di layar tanpa perlu me-*refresh* halaman, didukung oleh Laravel Reverb.
2. **Sistem Billing Fleksibel:** Mendukung skema penyewaan **Prabayar** (berdasarkan jam) dan **Pascabayar/Loss** (bayar di akhir).
3. **Kasir Terintegrasi F&B:** Penambahan pesanan makanan/minuman (seperti mie instan, es teh) langsung masuk ke tagihan TV/PS yang sedang berjalan.
4. **Self-Service QR Code (Request & Approve):** Pelanggan dapat memindai QR code di meja untuk melihat sisa waktu, memesan makanan, atau meminta tambahan waktu. *Request* ini akan memunculkan notifikasi *real-time* di layar kasir untuk disetujui.
5. **Integrasi Simulasi IoT (Buzzer):** Sistem ini dilengkapi *API Command Center*. Saat waktu rental pelanggan habis, sistem *backend* akan mengirimkan sinyal perintah untuk menyalakan *Buzzer* fisik di meja pelanggan sebagai peringatan, yang dapat dimatikan melalui *dashboard* kasir.

## 🛠️ Tech Stack

* **Backend:** Laravel 13 (PHP 8.3+)
* **Frontend:** Laravel Blade, Tailwind CSS, Alpine.js
* **Database:** MySQL / PostgreSQL
* **Real-time Engine:** Laravel Reverb & Event Broadcasting
* **Asset Bundler:** Vite

## 🚀 Panduan Instalasi (Development)

Pastikan sistem Anda sudah terinstal **PHP >= 8.2**, **Composer**, **Node.js & NPM**, serta **Database Server** (MySQL/PostgreSQL).

**1. Clone Repository & Install Dependencies**
```bash
git clone https://github.com/username-anda/repo-rental-ps.git
cd repo-rental-ps
composer install
npm install
```

**2. Konfigurasi Environment**
Gandakan file `.env.example` menjadi `.env`.
```bash
cp .env.example .env
```
Buka file `.env` dan atur koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_rental_ps
DB_USERNAME=root
DB_PASSWORD=
```
Pastikan pengaturan *broadcasting* menggunakan Reverb:
```env
BROADCAST_CONNECTION=reverb
```

**3. Generate App Key & Jalankan Migrasi**
```bash
php artisan key:generate
php artisan migrate --seed
```
*(Catatan: Flag `--seed` digunakan untuk mengisi data dummy seperti akun kasir, data TV, dan menu F&B).*

**4. Jalankan Aplikasi (Server, Vite, dan WebSocket)**
Karena aplikasi ini menggunakan Vite untuk kompilasi Alpine.js/Tailwind dan Reverb untuk *real-time WebSocket*, Anda perlu membuka **3 tab terminal berbeda** dan menjalankan perintah berikut di masing-masing terminal:

*Terminal 1 (Server PHP):*
```bash
php artisan serve
```
*Terminal 2 (Asset Bundler / Alpine.js):*
```bash
npm run dev
```
*Terminal 3 (WebSocket Server):*
```bash
php artisan reverb:start
```

Buka `http://localhost:8000` di *browser* Anda untuk mengakses aplikasi.

---
**Dikembangkan oleh:** Besok aja team
