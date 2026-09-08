<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Pembayaran — #TB-{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Space+Grotesk:wght@500;700;800&display=swap" rel="stylesheet">
  
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Space Mono', monospace;
    }
    body {
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      padding: 20px;
      color: #000;
    }
    .receipt {
      width: 320px;
      background: #fff;
      padding: 20px;
      border: 2px solid #000;
      box-shadow: 4px 4px 0px #000;
    }
    .header {
      text-align: center;
      padding-bottom: 12px;
      border-bottom: 2px dashed #000;
      margin-bottom: 12px;
    }
    .header h1 {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 18px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .header p {
      font-size: 10px;
      margin-top: 2px;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      margin-bottom: 4px;
    }
    .divider {
      border-bottom: 1px dashed #000;
      margin: 8px 0;
    }
    .double-divider {
      border-bottom: 2px solid #000;
      margin: 10px 0;
    }
    .table-row {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      margin-bottom: 4px;
    }
    .total-row {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      font-weight: 700;
      margin-top: 6px;
    }
    .footer {
      text-align: center;
      margin-top: 15px;
      padding-top: 10px;
      border-top: 2px dashed #000;
      font-size: 10px;
    }
    .no-print {
      margin-top: 15px;
      display: flex;
      gap: 8px;
    }
    .btn {
      flex: 1;
      padding: 8px;
      border: 2px solid #000;
      background: #000;
      color: #fff;
      font-weight: bold;
      font-size: 11px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
    }
    .btn-outline {
      background: #fff;
      color: #000;
    }
    @media print {
      body {
        background: #fff;
        padding: 0;
      }
      .receipt {
        border: none;
        box-shadow: none;
        width: 100%;
        padding: 0;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
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
      <span style="font-weight: bold;">{{ $session->tv ? $session->tv->name : 'Meja' }}</span>
    </div>

    <div class="double-divider"></div>

    <!-- Rental Item -->
    @php
      $durationMin = $session->start_time && $session->end_time ? $session->start_time->diffInMinutes($session->end_time) : 60;
      $durationStr = floor($durationMin / 60) . ' Jam ' . ($durationMin % 60) . ' Menit';
    @endphp
    <div class="table-row" style="font-weight: bold;">
      <span>Rental PS ({{ strtoupper($session->billing_type) }})</span>
      <span>Rp {{ number_format($session->rental_amount, 0, ',', '.') }}</span>
    </div>
    <div class="info-row" style="color: #555; font-size: 10px; margin-bottom: 6px;">
      <span>Durasi: {{ $durationStr }}</span>
      <span>{{ $session->start_time->format('H:i') }} - {{ $session->end_time ? $session->end_time->format('H:i') : '-' }}</span>
    </div>

    <!-- F&B Items if any -->
    @if ($session->sessionOrders->count() > 0)
      <div class="divider"></div>
      <p style="font-size: 10px; font-weight: bold; margin-bottom: 4px; text-transform: uppercase;">Pesanan Makanan / Minuman:</p>
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

    <div class="info-row" style="margin-top: 6px;">
      <span>Metode Pembayaran</span>
      <span style="font-weight: bold; text-transform: uppercase;">{{ $session->payment_method ?? 'TUNAI' }}</span>
    </div>

    <div class="footer">
      <p>*** TERIMA KASIH ATAS KUNJUNGANNYA ***</p>
      <p style="margin-top: 4px;">Main Game Asik Hanya di TambahBang!</p>
    </div>

    <div class="no-print">
      <button onclick="window.print()" class="btn">🖨️ Cetak Struk</button>
      <button onclick="window.close()" class="btn btn-outline">Tutup</button>
    </div>
  </div>

</body>
</html>
