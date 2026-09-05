<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Test Backend Rental PS</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f9; }
        .card { background: white; padding: 15px; margin-bottom: 10px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
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