# Database Schema Sync Fix Plan

Terdapat inkonsistensi antara struktur tabel di database saat ini dengan file migration yang ada. Masalah ini biasa terjadi selama proses development (misalnya, file migration diubah *setelah* dijalankan, sehingga database tidak mendapatkan kolom baru).

## Root Cause & Temuan
Dari hasil pengecekan, berikut adalah kolom yang hilang di database namun sudah ada di file migration:
1. **Tabel `products`**: Kehilangan kolom `category`.
2. **Tabel `play_sessions`**: Kehilangan kolom `rental_amount`, `fnb_amount`, `payment_method`, dan `notes`.

> [!WARNING]
> Karena kolom-kolom ini hilang, berbagai fitur di frontend yang melakukan query menggunakan kolom tersebut (seperti menampilkan kategori produk, memproses transaksi kasir, mencatat session, dll) akan selalu memunculkan error `SQLSTATE[42S22]: Column not found`.

## User Review Required

Ada dua pendekatan untuk menyelesaikan masalah ini. Harap konfirmasi pendekatan mana yang ingin diambil:

### Opsi A: Refresh Database & Seeder (Rekomendasi untuk Development)
Karena aplikasi ini sudah memiliki `DatabaseSeeder.php` yang sangat lengkap (berisi data dummy lengkap untuk PS, produk makanan, shift, dan riwayat session), cara termudah dan paling bersih adalah melakukan *fresh migration*:
- **Perintah**: `php artisan migrate:fresh --seed`
- **Efek**: Akan menghapus seluruh isi database saat ini, membuat ulang struktur tabel dari awal sesuai file migration terbaru, dan mengisi ulang dengan data dari Seeder.
- **Kelebihan**: Sangat cepat, menjamin database 100% tersinkronisasi tanpa error.

### Opsi B: Buat File Migration Tambahan (Jika Data Saat Ini Penting)
Jika Anda **tidak ingin** menghapus data yang ada di database saat ini, kita harus membuat file migration baru secara manual untuk menyisipkan kolom-kolom yang hilang:
1. Buat migration `add_category_to_products_table`.
2. Buat migration `add_details_to_play_sessions_table`.
3. Jalankan `php artisan migrate`.

> [!IMPORTANT]
> Harap beri tahu saya apakah Anda ingin menggunakan **Opsi A (Migrate Fresh + Seed)** atau **Opsi B (Migration Tambahan)**. Saya atau junior programmer dapat langsung mengeksekusinya setelah Anda memberikan persetujuan.

## Verification Plan

Setelah salah satu solusi di atas diterapkan, kita akan memverifikasi dengan cara:
1. Menggunakan `php artisan tinker` untuk memastikan kolom-kolom seperti `category`, `rental_amount`, `fnb_amount` benar-benar sudah ada di tabel `products` dan `play_sessions`.
2. Menjalankan ulang aplikasi dan menekan tombol di frontend yang sebelumnya menyebabkan error `Column not found` untuk memastikan tidak ada lagi error SQL tersebut.
