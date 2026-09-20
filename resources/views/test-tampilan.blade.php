<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Test Backend Rental PS</title>
    <!-- External CSS: halaman debug -->
    <link rel="stylesheet" href="{{ asset('css/debug.css') }}">
</head>
<body>
    <h1>Dashboard Darurat Backend</h1>
    
    <h2>Daftar TV / Meja Rental</h2>
    @foreach($tvs as $tv)
        <div class="card">
            <strong>{{ $tv->name }}</strong> | Tipe: {{ $tv->type }} | Status: {{ $tv->status }} | Tarif: Rp {{ number_format($tv->price_per_hour) }}/jam
        </div>
    @endforeach

    <h2>Daftar Produk F&B</h2>
    @foreach($products as $product)
        <div class="card">
            {{ $product->name }} - Rp {{ number_format($product->price) }} (Stok: {{ $product->stock }})
        </div>
    @endforeach
</body>
</html>