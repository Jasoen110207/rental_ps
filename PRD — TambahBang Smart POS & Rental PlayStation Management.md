# Product Requirements Document
## TambahBang — Smart POS & Real-Time Monitoring Rental PlayStation

**Document Status:** Aligned with README  
**Product Type:** Web Application  
**Primary Platform:** Desktop/Web untuk kasir + Mobile Web untuk pelanggan melalui QR Code

---

# 1. Product Overview

TambahBang adalah aplikasi web untuk mengelola operasional bisnis rental PlayStation secara terintegrasi.

Sistem menggabungkan:

- monitoring unit PlayStation secara real-time,
- billing prabayar dan pascabayar,
- POS makanan dan minuman,
- self-service pelanggan melalui QR Code,
- request dan approval pelanggan,
- manajemen shift kasir,
- notifikasi waktu rental,
- serta simulasi IoT buzzer sebagai peringatan ketika waktu bermain habis.

Aplikasi dirancang terutama untuk kasir sebagai pusat operasional rental, sementara pelanggan dapat berinteraksi dengan sesi rental mereka melalui halaman mobile yang diakses menggunakan QR Code pada masing-masing meja/unit.

---

# 2. Problem Statement

Operasional rental PlayStation biasanya melibatkan beberapa aktivitas yang berjalan bersamaan:

- memantau unit yang sedang digunakan,
- mencatat waktu mulai dan waktu selesai,
- menghitung billing,
- menerima pembayaran,
- menangani tambahan waktu,
- menerima pesanan makanan/minuman,
- mengelola pergantian shift,
- serta mengingatkan pelanggan ketika waktu hampir habis.

Apabila dilakukan secara manual atau menggunakan sistem yang terpisah, kondisi tersebut dapat menyebabkan:

- kesalahan billing,
- keterlambatan menghentikan sesi,
- sulit mengetahui ketersediaan unit,
- pesanan F&B tidak tercatat dalam tagihan yang sama,
- request pelanggan terlewat,
- serta proses pergantian shift yang tidak konsisten.

TambahBang menyediakan satu dashboard terpusat agar seluruh aktivitas tersebut dapat dikelola secara real-time.

---

# 3. Product Goals

## 3.1 Operational Efficiency

Mengurangi pekerjaan manual kasir dalam monitoring rental, billing, F&B, dan request pelanggan.

## 3.2 Billing Accuracy

Memastikan durasi rental, tambahan waktu, pesanan F&B, dan total pembayaran tercatat secara konsisten.

## 3.3 Real-Time Monitoring

Memberikan status seluruh unit PlayStation secara langsung tanpa membutuhkan refresh halaman.

## 3.4 Customer Self-Service

Memungkinkan pelanggan melakukan beberapa aktivitas melalui QR Code tanpa harus mendatangi kasir.

## 3.5 Integrated POS

Menggabungkan biaya rental dan makanan/minuman dalam satu transaksi.

## 3.6 Flexible Rental Model

Mendukung:

- Prabayar / Prepaid
- Pascabayar / Loss

---

# 4. Success Metrics

Target keberhasilan produk:

1. Mengurangi kesalahan billing minimal **80%** dalam enam bulan pertama.
2. Mencapai tingkat kepuasan pelanggan minimal **90%**.
3. Menyediakan monitoring status rental secara real-time dengan tingkat konsistensi data mendekati **100%**.
4. Menargetkan uptime sistem minimal **99,9%**.
5. Interaksi utama dashboard ditargetkan memiliki response time kurang dari **1 detik** pada kondisi operasional normal.

---

# 5. Primary Users

## 5.1 Kasir

Kasir merupakan pengguna utama aplikasi.

Kasir dapat:

- login ke sistem,
- membuka dan menutup shift,
- melihat unit rental,
- memulai rental,
- memilih metode billing,
- memperpanjang rental,
- menghentikan rental,
- menambahkan F&B,
- menerima request pelanggan,
- approve/reject request,
- menerima warning waktu,
- mengelola buzzer,
- menyelesaikan pembayaran.

## 5.2 Pelanggan

Pelanggan tidak memerlukan akun.

Pelanggan mengakses sistem melalui QR Code pada unit rental.

Pelanggan dapat:

- melihat status sesi,
- melihat sisa waktu,
- meminta tambahan waktu,
- melihat menu F&B,
- memesan makanan/minuman,
- melihat status request.

---

# 6. Core User Stories

## Kasir

**US-01**  
Sebagai kasir, saya ingin melihat status semua unit PS secara real-time sehingga saya mengetahui unit yang tersedia maupun sedang digunakan.

**US-02**  
Sebagai kasir, saya ingin memulai rental menggunakan billing prepaid sehingga durasi sesi dapat dihitung otomatis.

**US-03**  
Sebagai kasir, saya ingin memulai rental menggunakan billing postpaid/loss sehingga pelanggan dapat bermain tanpa batas waktu tertentu dan membayar berdasarkan durasi akhir.

**US-04**  
Sebagai kasir, saya ingin memperpanjang sesi pelanggan sehingga perubahan waktu langsung terlihat pada dashboard.

**US-05**  
Sebagai kasir, saya ingin menambahkan makanan atau minuman ke sesi sehingga biaya F&B menjadi bagian dari tagihan pelanggan.

**US-06**  
Sebagai kasir, saya ingin menerima request pelanggan secara real-time sehingga saya dapat segera memprosesnya.

**US-07**  
Sebagai kasir, saya ingin approve atau reject request sehingga perubahan sesi tidak dilakukan tanpa otorisasi.

**US-08**  
Sebagai kasir, saya ingin menerima peringatan saat waktu pelanggan hampir habis.

**US-09**  
Sebagai kasir, saya ingin mengetahui unit yang waktunya sudah habis sehingga unit dapat segera ditangani.

**US-10**  
Sebagai kasir, saya ingin mengontrol status buzzer unit dari dashboard.

**US-11**  
Sebagai kasir, saya ingin melakukan pergantian shift tanpa kehilangan transaksi aktif.

## Pelanggan

**US-12**  
Sebagai pelanggan, saya ingin melihat sisa waktu bermain melalui QR Code.

**US-13**  
Sebagai pelanggan, saya ingin meminta tambahan waktu tanpa harus mendatangi kasir.

**US-14**  
Sebagai pelanggan, saya ingin memesan makanan/minuman dari unit saya.

**US-15**  
Sebagai pelanggan, saya ingin mengetahui apakah request saya sudah disetujui atau ditolak.

---

# 7. Functional Requirements

## FR-01 Authentication

Sistem harus menyediakan login aman untuk kasir.

Fitur:

- login,
- logout,
- session authentication,
- validasi credential.

Pelanggan tidak diwajibkan login.

---

# 8. Dashboard Monitoring

Dashboard merupakan halaman utama kasir.

Sistem harus menampilkan seluruh unit menggunakan layout grid.

Setiap card unit minimal menampilkan:

- nama/nomor unit,
- status,
- tipe PlayStation apabila tersedia,
- waktu mulai,
- durasi,
- sisa waktu,
- jenis billing,
- total berjalan,
- indikator F&B,
- indikator request,
- status buzzer.

## Status Unit

Minimal:

### Available / Kosong

Unit tersedia untuk disewa.

### Playing / Bermain

Unit sedang digunakan.

### Almost Finished / Mau Habis

Waktu rental prepaid mendekati batas akhir.

### Time Up / Waktu Habis

Durasi prepaid telah selesai.

### Disabled

Unit dinonaktifkan oleh kasir dan tidak dapat digunakan untuk rental baru.

---

# 9. Real-Time Monitoring

Perubahan penting harus diteruskan secara real-time melalui WebSocket.

Contoh:

- rental baru dimulai,
- timer diperpanjang,
- rental dihentikan,
- request pelanggan masuk,
- request disetujui,
- order F&B masuk,
- status unit berubah,
- waktu hampir habis,
- waktu habis,
- status buzzer berubah.

Implementasi real-time menggunakan:

**Laravel Reverb + Event Broadcasting**

Timer pada interface harus dapat berjalan secara live tanpa page refresh.

---

# 10. Rental Session

Kasir dapat membuat rental session dari unit yang tersedia.

Data sesi minimal meliputi:

- Session ID
- Unit ID
- Shift ID
- Billing Type
- Start Time
- End Time untuk prepaid
- Duration
- Rental Rate
- Rental Subtotal
- F&B Subtotal
- Grand Total
- Session Status

Possible session status:

- Active
- Almost Finished
- Time Up
- Completed
- Cancelled

---

# 11. Billing System

Sistem mendukung dua metode billing.

## 11.1 Prepaid

Kasir memilih durasi pada awal rental.

Contoh:

- 1 jam
- 2 jam
- 3 jam

Sistem menghitung:

`Rental Cost = Duration × Rental Rate`

Timer melakukan countdown secara real-time.

Kasir dapat memperpanjang sesi.

Jika waktu telah habis:

- status sesi berubah,
- dashboard memberikan warning,
- command buzzer dapat dipicu.

## 11.2 Postpaid / Loss

Tidak memiliki batas waktu awal.

Timer menghitung waktu bermain sejak rental dimulai.

Biaya dihitung berdasarkan durasi akhir sesuai aturan tarif rental.

Sesi dihentikan oleh kasir ketika pelanggan selesai.

---

# 12. F&B Point of Sale

Sistem menyediakan menu makanan/minuman.

Informasi menu minimal:

- nama produk,
- kategori,
- harga,
- availability/status.

Pesanan dapat berasal dari:

### Kasir

Kasir dapat menambahkan item langsung ke rental session.

### Pelanggan

Pelanggan memesan melalui QR Code.

Order pelanggan terlebih dahulu menjadi request dan muncul pada dashboard kasir.

Setelah diproses sesuai alur aplikasi, biaya F&B ditambahkan ke tagihan sesi.

---

# 13. QR Code Self-Service

Setiap unit memiliki QR Code unik.

QR Code membuka halaman mobile customer yang terhubung dengan unit tersebut.

Halaman pelanggan minimal menyediakan:

## Session Information

- nomor unit,
- status rental,
- tipe billing,
- sisa waktu jika prepaid.

## Extend Time

Pelanggan dapat memilih jumlah tambahan waktu dan mengirim request.

Request tidak langsung mengubah billing sebelum mendapat persetujuan kasir.

## Food & Beverage

Pelanggan dapat:

- melihat daftar menu,
- memilih produk,
- menentukan quantity,
- melihat subtotal,
- mengirim order.

## Request Status

Pelanggan dapat melihat status:

- Pending
- Approved
- Rejected

---

# 14. Request & Approval System

Request pelanggan masuk ke dashboard kasir secara real-time.

Request minimal memiliki:

- Request ID
- Unit
- Request Type
- Request Details
- Created At
- Status

Jenis request:

- Extend Time
- Food & Beverage Order

Kasir memiliki aksi:

- Approve
- Reject

Untuk request tambahan waktu yang disetujui:

1. request berubah menjadi approved,
2. durasi sesi diperbarui,
3. timer diperbarui,
4. billing diperbarui,
5. UI pelanggan diperbarui secara real-time.

---

# 15. Notifications

Dashboard kasir harus menyediakan notification center.

Notifikasi dapat berasal dari:

- request tambahan waktu,
- order F&B,
- rental hampir habis,
- rental habis,
- perubahan penting pada rental session.

Notifikasi harus memiliki indikator jumlah unread/pending.

---

# 16. Time Warning System

Untuk prepaid rental, sistem menyediakan beberapa state berdasarkan waktu.

Contoh:

**Normal**
Waktu rental masih cukup.

**Warning**
Waktu rental mendekati habis.

**Expired**
Waktu rental telah habis.

Batas warning harus dapat dikonfigurasi pada implementasi, misalnya beberapa menit sebelum waktu berakhir.

---

# 17. IoT Buzzer Simulation

Sistem menyediakan simulasi command untuk perangkat buzzer pada masing-masing unit.

Tujuan fitur ini adalah mensimulasikan integrasi perangkat keras rental.

Ketika waktu rental habis, backend dapat mengirim command untuk mengaktifkan buzzer.

Dashboard kasir harus menampilkan status buzzer.

Kasir dapat melakukan aksi:

- Turn Buzzer On
- Turn Buzzer Off

Arsitektur fitur harus memungkinkan command tersebut nantinya diteruskan ke hardware/API eksternal tanpa mengubah flow utama aplikasi.

---

# 18. Shift Management

Kasir bekerja berdasarkan shift.

Sistem harus menyediakan:

- start shift,
- active shift,
- end shift.

Pergantian shift tidak boleh menghentikan rental session yang masih berjalan.

Data transaksi harus tetap tersimpan walaupun kasir berganti.

---

# 19. Payment & Checkout

Pada akhir rental, sistem harus menghasilkan billing summary.

Billing summary minimal menampilkan:

### Rental

- waktu mulai,
- waktu selesai,
- durasi,
- tarif,
- subtotal rental.

### F&B

- produk,
- quantity,
- harga,
- subtotal.

### Total

- rental subtotal,
- F&B subtotal,
- grand total.

Setelah pembayaran diselesaikan:

- session menjadi completed,
- unit kembali available,
- timer dihentikan,
- billing disimpan sebagai histori transaksi.

Metode pembayaran spesifik belum ditentukan oleh dokumen sumber sehingga dapat disiapkan sebagai field yang mudah dikembangkan kemudian.

---

# 20. Suggested Information Architecture

## Kasir

### Dashboard
Monitoring seluruh unit.

### POS / Rental
Membuat dan mengelola sesi rental.

### Requests
Melihat request pelanggan.

### F&B
Mengelola menu serta pesanan.

### Transactions
Riwayat transaksi.

### Shifts
Manajemen shift.

### Settings
Konfigurasi operasional.

---

# 21. Primary Screens

## Cashier

1. Login
2. Main Dashboard / Unit Monitoring
3. Start Rental Modal
4. Rental Session Detail
5. Extend Time Modal
6. Add F&B Modal
7. Requests Panel
8. Checkout Modal/Page
9. Transaction History
10. Shift Management
11. F&B Menu Management
12. Unit Management
13. Settings

## Customer

1. QR Landing / Session Status
2. Request Extend Time
3. F&B Menu
4. Cart
5. Request Confirmation
6. Request Status

---

# 22. Design Requirements

Visual direction:

**Neo Brutalism**

Dominant visual identity:

- Blue
- Orange

Karakter UI:

- bold,
- high contrast,
- playful tetapi tetap operational,
- border tebal,
- bentuk geometris,
- minimal rounded corners,
- strong hierarchy,
- readable typography,
- large interactive targets.

Dashboard kasir harus tetap memprioritaskan keterbacaan meskipun menggunakan gaya neo-brutalist.

Status unit harus dapat dikenali dengan cepat tanpa hanya mengandalkan warna.

Gunakan kombinasi:

- icon,
- label,
- visual state,
- warna.

---

# 23. Responsive Requirements

## Cashier Dashboard

Optimized primarily for:

- Desktop
- Laptop
- Tablet landscape

## Customer QR Page

Optimized primarily for:

- smartphone,
- mobile portrait.

Customer interface harus nyaman digunakan dengan satu tangan dan memiliki touch targets yang cukup besar.

---

# 24. Technical Requirements

## Backend

Laravel 13  
PHP 8.3+

## Frontend

Laravel Blade  
Tailwind CSS  
Alpine.js

## Database

MySQL

## Real-Time Communication

Laravel Reverb  
Laravel Event Broadcasting  
WebSocket

## Asset Bundler

Vite

---

# 25. Non-Functional Requirements

## Performance

- Interaksi utama ditargetkan memberikan respons kurang dari 1 detik.
- Real-time update tidak memerlukan manual page refresh.
- Sistem harus tetap usable selama peak operation.

## Security

- Authentication aman untuk kasir.
- Password disimpan menggunakan secure hashing Laravel.
- Authorization diterapkan pada endpoint internal.
- Validasi input dilakukan server-side.
- Data transaksi dan billing tidak boleh dapat dimanipulasi dari customer interface.

## Reliability

Perubahan transaksi penting harus disimpan pada server/database sebelum dianggap berhasil pada UI.

## Scalability

Arsitektur harus memungkinkan penambahan:

- unit rental,
- produk F&B,
- user/kasir,
- transaksi,
- event real-time.

---

# 26. Recommended Core Data Entities

Struktur detail database merupakan keputusan implementasi, tetapi kebutuhan produk mengindikasikan entity berikut:

- Users
- Shifts
- Rental Units
- Rental Sessions
- Rental Rates
- Products
- Orders
- Order Items
- Customer Requests
- Transactions
- Buzzer Commands / Device Commands

Relasi dan schema final harus mengikuti kebutuhan implementasi backend.

---

# 27. Core Application Flow

## Prepaid Rental

Available Unit  
→ Start Rental  
→ Select Prepaid  
→ Select Duration  
→ Session Active  
→ Countdown Running  
→ Warning  
→ Time Up  
→ Checkout  
→ Payment  
→ Unit Available

## Postpaid Rental

Available Unit  
→ Start Rental  
→ Select Postpaid  
→ Session Active  
→ Timer Running  
→ Stop Rental  
→ Calculate Duration  
→ Checkout  
→ Payment  
→ Unit Available

## Customer Extend Request

Scan QR  
→ View Session  
→ Request Extend Time  
→ Request Pending  
→ Cashier Notification  
→ Approve/Reject  
→ Session Updated  
→ Customer UI Updated

## Customer F&B Order

Scan QR  
→ Open Menu  
→ Add Product  
→ Submit Order  
→ Request Pending  
→ Cashier Notification  
→ Process Request  
→ Add F&B to Session Bill

---

# 28. Out of Scope / Not Yet Defined

Dokumen sumber belum menentukan secara spesifik:

- payment gateway,
- jenis pembayaran digital,
- customer account/login,
- inventory bahan F&B,
- multi-branch rental,
- accounting integration,
- printer integration,
- WhatsApp integration,
- hardware buzzer yang digunakan pada production.

Fitur tersebut tidak dianggap sebagai requirement MVP sampai ditetapkan secara eksplisit.

---

# 29. MVP Acceptance Criteria

MVP dianggap memenuhi requirement utama apabila:

1. Kasir dapat login.
2. Kasir dapat membuka shift.
3. Dashboard menampilkan seluruh unit.
4. Status unit dapat berubah secara real-time.
5. Kasir dapat memulai prepaid rental.
6. Kasir dapat memulai postpaid rental.
7. Timer berjalan tanpa page refresh.
8. Kasir dapat memperpanjang sesi.
9. Customer dapat membuka halaman sesi melalui QR.
10. Customer dapat mengirim request tambahan waktu.
11. Customer dapat memesan F&B.
12. Request muncul secara real-time pada dashboard.
13. Kasir dapat approve/reject request.
14. Kasir dapat menambahkan F&B ke transaksi.
15. Sistem memberikan warning waktu rental.
16. Sistem dapat mensimulasikan command buzzer.
17. Kasir dapat melakukan checkout.
18. Total rental + F&B dihitung dalam satu bill.
19. Sesi yang selesai mengembalikan unit menjadi available.
20. Pergantian shift tidak menghilangkan sesi aktif.

---

# 30. Product Principle

**“Kasir harus dapat memahami kondisi seluruh rental dalam beberapa detik tanpa perlu membuka banyak halaman.”**

Semua keputusan UX pada dashboard utama harus mendukung prinsip tersebut.