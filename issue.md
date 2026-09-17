# 📋 Perencanaan Perbaikan Fitur & Bug (Issue & Task Plan)

Dokumen ini berisi daftar tugas (task) untuk memperbaiki beberapa issue pada aplikasi Rental PS. Silakan ikuti instruksi pada masing-masing poin di bawah ini untuk mengimplementasikannya.

---

## 1. Perbaikan UI Halaman Manajemen Shift (Admin)
**Masalah:** Saat ini, ketika menu Manajemen Shift dibuka di dashboard admin, halaman tidak menampilkan antarmuka (UI) melainkan hanya menampilkan teks JSON mentah seperti `{"data": []}`.
**Penyebab:** Route untuk `/shifts` diarahkan ke `ShiftController@index`, yang saat ini hanya me-return response JSON (`response()->json(...)`). Karena ini diakses langsung via browser (bukan via API/AJAX call yang me-render UI di sisi client), maka browser hanya menampilkan teks JSON-nya.

**Instruksi Implementasi:**
1. Buka file `app/Http/Controllers/ShiftController.php`.
2. Perhatikan method `index()`.
3. Ubah logic pada method `index()` agar me-return sebuah Blade view apabila request berasal dari browser, atau buat controller/method terpisah khusus untuk tampilan web (misalnya me-return `view('admin.shifts.index', compact('shifts'))`).
4. Jika menggunakan method yang sama, kamu bisa menambahkan pengecekan:
   ```php
   if ($request->expectsJson() || $request->wantsJson()) {
       return response()->json(['data' => $shifts]);
   }
   return view('admin.shifts.index', compact('shifts'));
   ```
5. Pastikan view Blade untuk manajemen shift (`resources/views/admin/shifts/index.blade.php` atau sejenisnya) sudah dibuat dan menampilkan data `$shifts` dalam bentuk tabel yang rapi beserta layout admin.

---

## 2. Urutkan Produk F&B dan Unit Konsol Terbaru di Atas
**Masalah:** Saat admin menambahkan produk F&B (makanan/minuman) atau unit konsol baru, data yang baru ditambahkan muncul di bagian bawah daftar (karena default query biasanya berurut berdasarkan ID ASC).
**Ekspektasi:** Data yang paling baru ditambahkan harus selalu berada di urutan paling atas.

**Instruksi Implementasi:**
1. Cari Controller yang menangani tampilan daftar F&B (misalnya `FoodController` atau `ProductController`) dan daftar Unit Konsol (misalnya `UnitController` atau `ConsoleController`).
2. Temukan method `index()` atau query Eloquent yang mengambil daftar data tersebut untuk ditampilkan ke halaman admin.
3. Tambahkan method `->latest()` atau `->orderBy('created_at', 'desc')` pada query tersebut sebelum `->get()` atau `->paginate()`.
   *Contoh:*
   ```php
   // Sebelumnya:
   $units = Unit::all(); // atau Unit::paginate(10);
   // Ubah menjadi:
   $units = Unit::latest()->get(); // atau Unit::latest()->paginate(10);
   ```
4. Lakukan hal yang sama untuk query produk F&B.

---

## 3. Perbaikan Suara Buzzer (Audio Autoplay Policy)
**Masalah:** Suara buzzer (saat tombol buzzer di-klik atau saat waktu billing habis) tidak langsung berbunyi, dan anehnya harus membuka fitur *Inspect Element* di browser agar bunyinya keluar.
**Penyebab:** Ini adalah mekanisme keamanan bawaan dari browser modern yang disebut **Autoplay Policy**. Browser memblokir elemen audio/video agar tidak berbunyi secara otomatis kecuali jika *user/pengguna sudah pernah melakukan interaksi* (klik/tap) di halaman tersebut (User Gesture). Membuka *Inspect Element* terkadang tanpa disadari membuat window browser kembali mendapat fokus atau terhitung sebagai interaksi, sehingga audio ter-unlock.

**Instruksi Implementasi:**
Untuk mengatasi masalah ini, kita harus me-"pancing" (unlock) *Audio Context* segera setelah user melakukan interaksi pertama di halaman web, atau memastikan pemanggilan audio terkait langsung dengan event klik.
1. **Untuk Tombol Buzzer:** Pastikan *function/script JavaScript* yang memutar audio `.play()` dijalankan langsung di dalam callback event `onClick` tombol tersebut. Jangan letakkan `.play()` di dalam promise atau setTimeout yang terlalu panjang dari event klik awal.
2. **Untuk Timer / Waktu Habis:** Karena ini terjadi secara asinkron tanpa klik user di detik yang sama, halaman harus sudah "di-unlock" audionya sebelumnya.
   - **Solusi termudah:** Tambahkan satu interaksi kecil saat kasir/admin pertama kali masuk ke dashboard. Misalnya, ketika admin mengklik sembarang tempat pertama kali, buat sebuah script global yang memutar file audio yang sama namun dengan volume 0 (atau di-pause langsung). Ini akan memberi izin kepada browser untuk memutar audio tersebut di masa mendatang (saat timer billing habis) tanpa diblokir.
   - **Contoh Script (letakkan di file blade dashboard/layout utama sebelum tag `</body>`):**
     ```javascript
     document.addEventListener('click', function unlockAudio() {
         const buzzerAudio = document.getElementById('buzzer-audio-element'); // sesuaikan ID audionya
         if (buzzerAudio) {
             buzzerAudio.volume = 0;
             buzzerAudio.play().then(() => {
                 buzzerAudio.pause();
                 buzzerAudio.currentTime = 0;
                 buzzerAudio.volume = 1; // kembalikan ke volume normal
             }).catch(err => console.log('Audio unlock failed:', err));
         }
         // Hapus event listener setelah sukses unlock pertama kali
         document.removeEventListener('click', unlockAudio);
     }, { once: true });
     ```
3. Pastikan HTML `<audio>` tag untuk buzzer sudah dimuat (preload) di halaman dashboard kasir/admin.
