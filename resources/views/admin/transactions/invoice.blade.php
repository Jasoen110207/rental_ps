<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Pembayaran — #TB-{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Space+Grotesk:wght@500;700;800&display=swap" rel="stylesheet">

  <!-- External CSS: struk cetak -->
  <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
</head>
<body>

  <div class="receipt">
    <div class="header">
      <h1>{{ $storeName ?? 'TAMBAHBANG RENTAL PS' }}</h1>
      <p>{{ $storeAddress ?? 'Jl. Game Arena No. 42' }}</p>
      <p>Telp: {{ $storePhone ?? '0812-3456-7890' }}</p>
    </div>

    <div class="info-row">
      <span>No. Faktur</span>
      <span>#TB-{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="info-row">
      <span>Tanggal</span>
      <span>{{ $session->end_time ? $session->end_time->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info-row">
      <span>Kasir</span>
      <span>{{ $session->user ? $session->user->name : 'Kasir' }}</span>
    </div>
    <div class="info-row">
      <span>Unit PS</span>
      <span class="text-strong">{{ $session->tv ? $session->tv->name : 'Meja' }}</span>
    </div>

    <div class="double-divider"></div>

    <!-- Rental Item -->
    @php
      $durationMin = $session->start_time && $session->end_time ? $session->start_time->diffInMinutes($session->end_time) : 60;
      $durationStr = floor($durationMin / 60) . ' Jam ' . ($durationMin % 60) . ' Menit';
    @endphp
    <div class="table-row table-row--strong">
      <span>Rental PS ({{ strtoupper($session->billing_type) }})</span>
      <span>Rp {{ number_format($session->rental_amount, 0, ',', '.') }}</span>
    </div>
    <div class="info-row info-row--muted">
      <span>Durasi: {{ $durationStr }}</span>
      <span>{{ $session->start_time->format('H:i') }} - {{ $session->end_time ? $session->end_time->format('H:i') : '-' }}</span>
    </div>

    <!-- F&B Items if any -->
    @if ($session->sessionOrders->count() > 0)
      <div class="divider"></div>
      <p class="section-label">Pesanan Makanan / Minuman:</p>
      @foreach ($session->sessionOrders as $order)
        <div class="table-row">
          <span>{{ $order->quantity }}x {{ $order->product ? $order->product->name : 'Item' }}</span>
          <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
      @endforeach
    @endif

    <div class="double-divider"></div>

    <div class="info-row">
      <span>Subtotal Rental</span>
      <span>Rp {{ number_format($session->rental_amount, 0, ',', '.') }}</span>
    </div>
    <div class="info-row">
      <span>Subtotal F&B</span>
      <span>Rp {{ number_format($session->fnb_amount, 0, ',', '.') }}</span>
    </div>
    
    <div class="total-row">
      <span>TOTAL LUNAS</span>
      <span>Rp {{ number_format($session->total_amount, 0, ',', '.') }}</span>
    </div>

    <div class="info-row info-row--spaced">
      <span>Metode Pembayaran</span>
      <span class="text-uppercase-strong">{{ $session->payment_method ?? 'TUNAI' }}</span>
    </div>

    <div class="footer">
      <p>*** TERIMA KASIH ATAS KUNJUNGANNYA ***</p>
      <p class="footer-note">Main Game Asik Hanya di TambahBang!</p>
    </div>

    <div class="no-print">
      <button onclick="window.print()" class="btn">🖨️ Cetak Struk</button>
      <button onclick="window.close()" class="btn btn-outline">Tutup</button>
    </div>
  </div>

</body>
</html>
