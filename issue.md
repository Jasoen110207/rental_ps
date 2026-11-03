# Panduan Implementasi: Integrasi Midtrans Payment Gateway (Sandbox) untuk Checkout Rental PS

Dokumen ini adalah panduan teknis langkah demi langkah (*step-by-step implementation blueprint*) untuk mengintegrasikan **Midtrans Snap (QRIS)** pada aplikasi **TambahBang Rental PS**.

Panduan ini ditulis secara detail dan eksplisit agar dapat langsung dieksekusi oleh programmer maupun AI agent tanpa ambiguitas.

---

## 1. Konteks & Aturan Bisnis (Wajib Dipahami Sebelum Coding)

1. **Pusat Pembayaran Payment Gateway ada di Checkout Rental PS:**
   - Saat sesi rental selesai (waktu habis atau kasir klik tombol **SELESAI / CHECKOUT**), seluruh tagihan dikumpulkan menjadi satu (`Grand Total` = `Biaya Rental Awal` + `Tambah Waktu` + `Pesanan F&B`).
   - Kasir memilih metode pembayaran di Modal Checkout: **Tunai (`cash`)**, **QRIS (`qris`)**, **Transfer (`transfer`)**, atau **Lainnya (`other`)**.
   - Jika kasir memilih **QRIS (`qris`)** lalu menekan tombol submit, sistem **tidak langsung men-submit form**, melainkan meminta `snap_token` ke backend dan memunculkan **Popup Midtrans Snap** di layar monitor kasir.
   - Pelanggan yang berdiri di depan meja kasir melakukan scan QRIS pada layar monitor kasir menggunakan HP mereka.
   - Begitu pembayaran berhasil (`onSuccess` pada Snap JS atau via Webhook Midtrans), sesi otomatis diselesaikan (`status = 'completed'`), unit TV kembali `available`, dan pendapatan shift (`total_revenue`) bertambah.

2. **Alur yang TIDAK Diubah (Jangan Dirombak):**
   - **Pemesanan F&B dari HP Pelanggan (`/customer/{token}/order-food`)**: Tetap masuk ke *Request Center* kasir dan ditambahkan ke tagihan sesi (`fnb_amount`). Dibayar nanti saat Checkout Rental.
   - **Request Tambah Waktu dari HP Pelanggan (`/customer/{token}/add-time`)**: Tetap masuk ke *Request Center* kasir dan ditambahkan ke tagihan sesi (`rental_amount`). Dibayar nanti saat Checkout Rental.
   - **Booking (`/booking` & `/kasir/booking`)**: Dilakukan oleh pelanggan via WhatsApp ke Kasir/CS, lalu Kasir menginput ke dalam halaman Booking dan pembayaran DP/Booking dilakukan secara manual via QRIS Statis / Transfer.

3. **Prinsip Idempotency (Sangat Penting):**
   - Pembayaran Midtrans dapat memicu penyelesaian sesi dari 2 jalur: **Submit Form setelah `onSuccess` di browser kasir** DAN/ATAU **Webhook Callback dari server Midtrans**.
   - Pastikan logika penyelesaian sesi memeriksa apakah `$session->status === 'completed'`. Jika sudah `completed`, **jangan** menambah `total_revenue` pada `Shift` dua kali dan **jangan** mengembalikan stok `addon` dua kali.

---

## 2. Tahapan Implementasi Step-by-Step

### Tahap 1: Konfigurasi Environment (`.env` & `config/midtrans.php`)

#### 1.1. Tambahkan Variabel di `.env` dan `.env.example`
Tambahkan baris berikut di bagian paling bawah file `.env` dan `.env.example`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-GANTI_DENGAN_SERVER_KEY_SANDBOX
MIDTRANS_CLIENT_KEY=SB-Mid-client-GANTI_DENGAN_CLIENT_KEY_SANDBOX
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

> **Catatan:** Ambil `Server Key` dan `Client Key` dari [Midtrans Sandbox Dashboard](https://dashboard.sandbox.midtrans.com) pada menu **Settings > Access Keys**.

#### 1.2. Buat File `config/midtrans.php`
Buat file baru di `config/midtrans.php` dengan isi berikut:

```php
<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => (bool) env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => (bool) env('MIDTRANS_IS_3DS', true),
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
];
```

---

### Tahap 2: Migrasi Database pada Tabel `play_sessions`

Kita perlu menyimpan `midtrans_order_id` dan `snap_token` di tabel `play_sessions` untuk melacak transaksi Midtrans pada setiap sesi rental.

#### 2.1. Buat File Migrasi
Jalankan perintah Artisan berikut:

```bash
php artisan make:migration add_midtrans_fields_to_play_sessions_table --table=play_sessions --no-interaction
```

#### 2.2. Isi File Migrasi
Edit file migrasi yang baru dibuat di `database/migrations/*_add_midtrans_fields_to_play_sessions_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('payment_method');
            $table->string('snap_token')->nullable()->after('midtrans_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('play_sessions', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id', 'snap_token']);
        });
    }
};
```

#### 2.3. Jalankan Migrasi
```bash
php artisan migrate --no-interaction
```

---

### Tahap 3: Buat Service Class Midtrans (`app/Services/MidtransService.php`)

Library `midtrans/midtrans-php` sudah terpasang di `composer.json`. Buat class service khusus untuk mengenkapsulasi pembuatan Snap Token dan perhitungan tagihan akhir sesi.

Buat file baru di `app/Services/MidtransService.php`:

```php
<?php

namespace App\Services;

use App\Models\PlaySession;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    /**
     * Hitung rincian tagihan terbaru untuk sesi (mendukung prepaid & postpaid).
     *
     * @return array{rental_amount: int, fnb_amount: int, grand_total: int}
     */
    public function calculateSessionTotals(PlaySession $session, ?int $customRentalAmount = null): array
    {
        $session->loadMissing(['tv', 'sessionOrders.product']);

        $now = Carbon::now();
        $rentalAmount = (int) $session->rental_amount;

        if ($session->billing_type === 'postpaid') {
            $durationMinutes = max(1, $session->start_time->diffInMinutes($now));
            $blokWaktu = ceil($durationMinutes / 5);
            $tarifPerLimaMenit = ($session->tv->price_per_hour / 60) * 5;

            if ($customRentalAmount !== null) {
                $rentalAmount = $customRentalAmount;
            } else {
                $rentalAmount = (int) ($blokWaktu * $tarifPerLimaMenit);
            }
        } elseif ($customRentalAmount !== null) {
            $rentalAmount = $customRentalAmount;
        }

        $fnbAmount = (int) $session->sessionOrders->sum('subtotal');
        $grandTotal = max(1, $rentalAmount + $fnbAmount);

        return [
            'rental_amount' => $rentalAmount,
            'fnb_amount' => $fnbAmount,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * Buat Snap Token Midtrans untuk PlaySession.
     *
     * @return array{snap_token: string, order_id: string, rental_amount: int, fnb_amount: int, grand_total: int}
     */
    public function createSnapTransaction(PlaySession $session, ?int $customRentalAmount = null): array
    {
        $totals = $this->calculateSessionTotals($session, $customRentalAmount);

        $orderId = 'RENTAL-' . $session->id . '-' . time();

        $itemDetails = [];

        if ($totals['rental_amount'] > 0) {
            $itemDetails[] = [
                'id' => 'TV-' . $session->tv_id,
                'price' => $totals['rental_amount'],
                'quantity' => 1,
                'name' => substr('Sewa ' . ($session->tv->name ?? 'Unit PS'), 0, 50),
            ];
        }

        foreach ($session->sessionOrders as $order) {
            if ($order->subtotal > 0 && $order->quantity > 0) {
                $unitPrice = (int) round($order->subtotal / $order->quantity);
                $itemDetails[] = [
                    'id' => 'FNB-' . $order->product_id,
                    'price' => $unitPrice,
                    'quantity' => (int) $order->quantity,
                    'name' => substr($order->product->name ?? 'Item F&B', 0, 50),
                ];
            }
        }

        // Fallback jika itemDetails kosong atau jumlahnya tidak sama dengan grand_total
        $itemsSum = collect($itemDetails)->sum(fn (array $item) => $item['price'] * $item['quantity']);
        if (empty($itemDetails) || $itemsSum !== $totals['grand_total']) {
            $itemDetails = [
                [
                    'id' => 'SESSION-' . $session->id,
                    'price' => $totals['grand_total'],
                    'quantity' => 1,
                    'name' => substr('Tagihan Rental ' . ($session->tv->name ?? 'PS'), 0, 50),
                ],
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totals['grand_total'],
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $session->customer_name ?: ('Pelanggan ' . ($session->tv->name ?? 'PS')),
            ],
            // Prioritaskan pembayaran berbasis QR (QRIS / GoPay / ShopeePay) di layar kasir
            'enabled_payments' => ['other_qris', 'gopay', 'shopeepay'],
        ];

        $snapToken = Snap::getSnapToken($params);

        $session->update([
            'midtrans_order_id' => $orderId,
            'snap_token' => $snapToken,
        ]);

        return [
            'snap_token' => $snapToken,
            'order_id' => $orderId,
            'rental_amount' => $totals['rental_amount'],
            'fnb_amount' => $totals['fnb_amount'],
            'grand_total' => $totals['grand_total'],
        ];
    }

    /**
     * Selesaikan sesi secara idempoten (aman dipanggil dari Controller maupun Webhook).
     */
    public function finalizeSessionCheckout(
        PlaySession $session,
        string $paymentMethod,
        ?string $notes = null,
        ?int $customRentalAmount = null
    ): int {
        $session->loadMissing(['tv', 'sessionOrders.product']);

        // Idempotency guard: Jika sesi sudah completed, langsung kembalikan total_amount yang tersimpan
        if ($session->status === 'completed') {
            return (int) $session->total_amount;
        }

        $totals = $this->calculateSessionTotals($session, $customRentalAmount);
        $now = Carbon::now();

        DB::transaction(function () use ($session, $now, $totals, $paymentMethod, $notes) {
            // Lock baris sesi untuk mencegah race condition antara Webhook dan Submit Form
            $lockedSession = PlaySession::where('id', $session->id)->lockForUpdate()->first();
            if (! $lockedSession || $lockedSession->status === 'completed') {
                return;
            }

            $lockedSession->update([
                'status' => 'completed',
                'end_time' => $now,
                'rental_amount' => $totals['rental_amount'],
                'fnb_amount' => $totals['fnb_amount'],
                'total_amount' => $totals['grand_total'],
                'payment_method' => $paymentMethod,
                'notes' => $notes ?? $lockedSession->notes,
            ]);

            // Kembalikan stok untuk produk addon
            foreach ($session->sessionOrders as $order) {
                if ($order->product && $order->product->category === 'addon') {
                    $order->product->increment('stock', $order->quantity);
                }
            }

            // Set TV menjadi available dan matikan buzzer
            $session->tv->update([
                'status' => 'available',
                'is_buzzer_on' => false,
            ]);

            // Update statistik shift aktif
            $activeShift = Shift::where('status', 'active')->latest()->first();
            if ($activeShift) {
                $activeShift->increment('total_revenue', $totals['grand_total']);
                $activeShift->increment('transactions_count', 1);
            }
        });

        return $totals['grand_total'];
    }
}
```

---

### Tahap 4: Buat Controller Midtrans & Update Checkout Controller

#### 4.1. Buat `app/Http/Controllers/MidtransController.php`
Buat controller untuk menangani request AJAX pembuatan `snap_token` dari kasir dan penerimaan `webhook` dari server Midtrans:

```php
<?php

namespace App\Http\Controllers;

use App\Models\PlaySession;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function __construct(public MidtransService $midtransService) {}

    /**
     * Endpoint AJAX untuk menghasilkan Snap Token saat kasir memilih metode QRIS.
     */
    public function createSnapToken(Request $request, int $sessionId): JsonResponse
    {
        $session = PlaySession::with(['tv', 'sessionOrders.product'])->findOrFail($sessionId);

        if ($session->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi rental ini sudah selesai dibayar.',
            ], 400);
        }

        $customRentalAmount = $request->filled('custom_rental_amount')
            ? (int) $request->input('custom_rental_amount')
            : null;

        try {
            $result = $this->midtransService->createSnapTransaction($session, $customRentalAmount);

            return response()->json([
                'success' => true,
                'snap_token' => $result['snap_token'],
                'order_id' => $result['order_id'],
                'rental_amount' => $result['rental_amount'],
                'fnb_amount' => $result['fnb_amount'],
                'grand_total' => $result['grand_total'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi Midtrans: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint Webhook Notification dari Midtrans.
     */
    public function webhook(Request $request): JsonResponse
    {
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');
        $transactionStatus = (string) $request->input('transaction_status');
        $fraudStatus = (string) $request->input('fraud_status');

        $serverKey = (string) config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if (! hash_equals($expectedSignature, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $session = PlaySession::where('midtrans_order_id', $orderId)->first();
        if (! $session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $isPaid = ($transactionStatus === 'capture' && $fraudStatus === 'accept')
            || $transactionStatus === 'settlement';

        if ($isPaid) {
            $this->midtransService->finalizeSessionCheckout(
                $session,
                'qris',
                'Dibayar otomatis via Midtrans QRIS (' . $orderId . ')'
            );
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
```

#### 4.2. Update Method `checkout` di `app/Http/Controllers/RentalController.php`
Pada `app/Http/Controllers/RentalController.php` (baris ~185), perbarui method `checkout` agar menggunakan `MidtransService::finalizeSessionCheckout` sehingga terhindar dari duplikasi perhitungan jika webhook sudah masuk lebih dulu:

```php
    /**
     * Checkout and finalize session
     */
    public function checkout(Request $request, $sessionId, \App\Services\MidtransService $midtransService)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer,other',
            'notes' => 'nullable|string',
            'custom_rental_amount' => 'nullable|numeric',
        ]);

        $session = PlaySession::with(['tv', 'sessionOrders.product'])->findOrFail($sessionId);

        $customRentalAmount = isset($validated['custom_rental_amount']) && $validated['custom_rental_amount'] !== ''
            ? (int) $validated['custom_rental_amount']
            : null;

        $grandTotal = $midtransService->finalizeSessionCheckout(
            $session,
            $validated['payment_method'],
            $validated['notes'] ?? null,
            $customRentalAmount
        );

        return redirect()->route('admin.dashboard')
            ->with('success', 'Checkout berhasil! Pembayaran '.$session->tv->name.' sebesar Rp '.number_format($grandTotal, 0, ',', '.').' telah lunas.');
    }
```

#### 4.3. Update Method `checkout` di `app/Http/Controllers/KasirController.php`
Pada `app/Http/Controllers/KasirController.php` (baris ~315), perbarui method `checkout` dengan cara yang sama:

```php
    public function checkout(Request $request, $sessionId, \App\Services\MidtransService $midtransService)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer,other',
            'notes' => 'nullable|string',
            'custom_rental_amount' => 'nullable|numeric',
        ]);

        $session = PlaySession::with(['tv', 'sessionOrders.product'])->findOrFail($sessionId);

        $customRentalAmount = isset($validated['custom_rental_amount']) && $validated['custom_rental_amount'] !== ''
            ? (int) $validated['custom_rental_amount']
            : null;

        $grandTotal = $midtransService->finalizeSessionCheckout(
            $session,
            $validated['payment_method'],
            $validated['notes'] ?? null,
            $customRentalAmount
        );

        return redirect()->route('kasir.dashboard')
            ->with('success', 'Checkout '.$session->tv->name.' lunas Rp '.number_format($grandTotal, 0, ',', '.'));
    }
```

---

### Tahap 5: Daftarkan Route (`routes/web.php`) & CSRF Exception (`bootstrap/app.php`)

#### 5.1. Tambahkan Route di `routes/web.php`
1. Tambahkan `use App\Http\Controllers\MidtransController;` di bagian atas `routes/web.php`.
2. Di luar middleware `auth`, daftarkan route webhook Midtrans:
   ```php
   Route::post('/midtrans/webhook', [MidtransController::class, 'webhook'])->name('midtrans.webhook');
   ```
3. Di dalam grup `Route::middleware('auth')->prefix('admin')->name('admin.')->group(...)` (di bawah `rental.checkout`), tambahkan:
   ```php
   Route::post('/rental/{sessionId}/snap-token', [MidtransController::class, 'createSnapToken'])->name('rental.snap-token');
   ```
4. Di dalam grup `Route::middleware('auth')->prefix('kasir')->name('kasir.')->group(...)` (di bawah `rental.checkout`), tambahkan:
   ```php
   Route::post('/rental/{sessionId}/snap-token', [MidtransController::class, 'createSnapToken'])->name('rental.snap-token');
   ```

#### 5.2. Kecualikan Route Webhook dari CSRF di `bootstrap/app.php`
Edit `bootstrap/app.php` pada bagian `withMiddleware` agar Midtrans dapat mengirim POST request ke `/midtrans/webhook` tanpa error `419 Page Expired`:

```php
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'midtrans/webhook',
        ]);
    })
```

---

### Tahap 6: Integrasi Snap.js pada Tampilan Checkout (Admin & Kasir)

#### 6.1. Update `resources/views/admin/dashboard.blade.php`
1. Pada form `#checkout-form` (sekitar baris 519), tambahkan atribut `onsubmit="handleAdminCheckout(event)"`:
   ```html
   <form id="checkout-form" method="POST" action="" class="flex flex-col gap-4" onsubmit="handleAdminCheckout(event)">
   ```
   Dan beri `id="checkout-submit-btn"` pada tombol submit di baris ~576:
   ```html
   <button type="submit" id="checkout-submit-btn" class="py-2.5 px-6 bg-emerald-600 text-white font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-emerald-700 flex items-center gap-1.5">
     <span class="material-symbols-outlined text-lg">check_circle</span>
     <span id="checkout-submit-text">SELESAIKAN PEMBAYARAN LUNAS</span>
   </button>
   ```

2. Di dalam `@push('scripts')` pada `resources/views/admin/dashboard.blade.php`, muat script `snap.js` Midtrans dan tambahkan fungsi `handleAdminCheckout`:

```html
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
  let currentCheckoutSessionId = null;
  let isMidtransPaid = false;

  function openCheckoutModal(sessionId, tvName, billingType, rate, rentalAmt, fnbAmt, totalAmt, startFormatted, endFormatted) {
    currentCheckoutSessionId = sessionId;
    isMidtransPaid = false;
    document.getElementById('checkout-modal-title').innerText = 'Checkout — ' + tvName;
    document.getElementById('checkout-form').action = '/admin/rental/' + sessionId + '/checkout';
    document.getElementById('checkout-duration-display').innerText = startFormatted + ' s/d ' + (endFormatted || 'Sekarang');
    document.getElementById('checkout-rental-amount').innerText = 'Rp ' + rentalAmt.toLocaleString('id-ID');
    document.getElementById('checkout-fnb-amount').innerText = 'Rp ' + fnbAmt.toLocaleString('id-ID');
    document.getElementById('checkout-grand-total').innerText = 'Rp ' + totalAmt.toLocaleString('id-ID');
    document.getElementById('checkout-submit-text').innerText = 'SELESAIKAN PEMBAYARAN LUNAS';
    document.getElementById('checkout-submit-btn').disabled = false;
    document.getElementById('modal-checkout').classList.remove('hidden');
  }

  async function handleAdminCheckout(e) {
    const methodEl = document.querySelector('#checkout-form input[name="payment_method"]:checked');
    const method = methodEl ? methodEl.value : 'cash';

    if (method === 'qris' && !isMidtransPaid) {
      e.preventDefault();

      const submitBtn = document.getElementById('checkout-submit-btn');
      const submitText = document.getElementById('checkout-submit-text');
      const originalText = submitText.innerText;

      submitBtn.disabled = true;
      submitText.innerText = 'MEMUAT QRIS MIDTRANS...';

      try {
        const res = await fetch(`/admin/rental/${currentCheckoutSessionId}/snap-token`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          body: JSON.stringify({})
        });

        const data = await res.json();
        if (!res.ok || !data.success) {
          alert(data.message || 'Gagal memuat pembayaran Midtrans.');
          submitBtn.disabled = false;
          submitText.innerText = originalText;
          return;
        }

        window.snap.pay(data.snap_token, {
          onSuccess: function () {
            isMidtransPaid = true;
            document.getElementById('checkout-form').submit();
          },
          onPending: function () {
            alert('Menunggu pembayaran diselesaikan.');
            submitBtn.disabled = false;
            submitText.innerText = originalText;
          },
          onError: function () {
            alert('Pembayaran gagal! Silakan coba lagi.');
            submitBtn.disabled = false;
            submitText.innerText = originalText;
          },
          onClose: function () {
            submitBtn.disabled = false;
            submitText.innerText = originalText;
          }
        });
      } catch (err) {
        alert('Terjadi kesalahan jaringan saat menghubungi server.');
        submitBtn.disabled = false;
        submitText.innerText = originalText;
      }
    }
  }
</script>
```

#### 6.2. Update `resources/views/kasir/dashboard.blade.php`
Di `resources/views/kasir/dashboard.blade.php`:
1. Hapus elemen `<div id="qris-container">...</div>` (baris 342–345) yang sebelumnya memakai gambar QR dummy `api.qrserver.com`.
2. Di dalam `@push('scripts')`, tambahkan script `snap.js`:
   ```html
   <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
   ```
3. Ganti fungsi `handleCheckout(e)` dan `openCheckoutModal(...)` (baris 447–474) menjadi seperti berikut:

```javascript
let currentCheckoutSessionId = null;
let qrisConfirmed = false;

async function handleCheckout(e) {
  const method = document.querySelector('#checkout-form input[name="payment_method"]:checked').value;
  if (method === 'qris' && !qrisConfirmed) {
    e.preventDefault();

    const submitBtn = document.getElementById('checkout-submit-btn');
    const originalText = submitBtn.innerText;
    submitBtn.disabled = true;
    submitBtn.innerText = 'MEMUAT QRIS...';

    try {
      const res = await fetch(`/kasir/rental/${currentCheckoutSessionId}/snap-token`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
        },
        body: JSON.stringify({})
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        alert(data.message || 'Gagal membuat QRIS Midtrans.');
        submitBtn.disabled = false;
        submitBtn.innerText = originalText;
        return false;
      }

      window.snap.pay(data.snap_token, {
        onSuccess: function () {
          qrisConfirmed = true;
          document.getElementById('checkout-form').submit();
        },
        onPending: function () {
          alert('Menunggu pembayaran diselesaikan.');
          submitBtn.disabled = false;
          submitBtn.innerText = originalText;
        },
        onError: function () {
          alert('Pembayaran QRIS gagal.');
          submitBtn.disabled = false;
          submitBtn.innerText = originalText;
        },
        onClose: function () {
          submitBtn.disabled = false;
          submitBtn.innerText = originalText;
        }
      });
    } catch (err) {
      alert('Terjadi kesalahan jaringan.');
      submitBtn.disabled = false;
      submitBtn.innerText = originalText;
    }
    return false;
  }
}

function openCheckoutModal(sessionId, tvName, rentalAmt, fnbAmt, totalAmt){
  currentCheckoutSessionId = sessionId;
  document.getElementById('checkout-modal-title').innerText = 'Checkout — ' + tvName;
  document.getElementById('checkout-form').action = '/kasir/rental/' + sessionId + '/checkout';
  document.getElementById('checkout-rental-amount').innerText = 'Rp ' + rentalAmt.toLocaleString('id-ID');
  document.getElementById('checkout-fnb-amount').innerText = 'Rp ' + fnbAmt.toLocaleString('id-ID');
  document.getElementById('checkout-grand-total').innerText = 'Rp ' + totalAmt.toLocaleString('id-ID');

  document.getElementById('checkout-details').classList.remove('hidden');
  document.getElementById('checkout-submit-btn').innerText = 'SELESAIKAN LUNAS';
  document.getElementById('checkout-submit-btn').disabled = false;
  qrisConfirmed = false;
  document.querySelector('#checkout-form input[name="payment_method"][value="cash"]').checked = true;

  document.getElementById('modal-checkout').classList.remove('hidden');
}
```

---

### Tahap 7: Panduan Cara Testing Menggunakan Midtrans Sandbox Simulator

Setelah semua kode di atas diterapkan:

1. Bersihkan cache konfigurasi Laravel:
   ```bash
   php artisan config:clear
   ```
2. Login sebagai **Admin** atau **Kasir**, lalu mulai 1 sesi rental baru (atau pilih unit TV yang sedang bermain).
3. Klik tombol **SELESAI / CHECKOUT** pada kartu unit TV tersebut.
4. Di dalam Modal Checkout, pilih metode pembayaran **📱 QRIS**, lalu klik tombol **SELESAIKAN PEMBAYARAN LUNAS**.
5. Akan muncul **Popup Midtrans Snap** di layar monitor kasir.
6. Pilih **QRIS** atau **GoPay** di dalam popup tersebut hingga muncul gambar **QR Code**.
7. Untuk menyimulasikan pelanggan men-scan QR tersebut di mode Sandbox:
   - Klik kanan pada gambar QR Code di popup Midtrans, lalu pilih **Copy Image Address** (Salin Alamat Gambar).
   - Buka halaman [Midtrans QRIS Simulator](https://simulator.sandbox.midtrans.com/qris/index) atau [GoPay QR Simulator](https://simulator.sandbox.midtrans.com/v2/qris/index) di tab baru.
   - Tempelkan (*paste*) URL gambar QR Code tadi ke kolom yang tersedia, klik **Scan QR**, lalu klik **Pay**.
8. Kembali ke tab aplikasi Rental PS — popup Midtrans akan otomatis mendeteksi pembayaran berhasil (`onSuccess`), menutup popup, men-submit form checkout, dan mengubah status TV kembali menjadi **AVAILABLE** serta mencatat transaksi lunas dengan metode **QRIS**!

---

### Tahap 8: Pemeriksaan Standar Kode & Pengujian Otomatis

Setelah selesai melakukan perubahan kode PHP:
1. Jalankan Laravel Pint untuk memastikan gaya penulisan kode rapi:
   ```bash
   vendor/bin/pint --dirty --format agent
   ```
2. Jalankan pengujian fitur untuk memastikan tidak ada regresi:
   ```bash
   php artisan test --compact
   ```
