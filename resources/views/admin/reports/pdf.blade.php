<!DOCTYPE html>
<html>
<head>
    <title>Laporan Mingguan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Mingguan</h2>
    <p>Tanggal: {{ now()->startOfWeek()->format('d M Y') }} - {{ now()->endOfWeek()->format('d M Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>TV</th>
                <th>Kasir</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Rental</th>
                <th>F&B</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->tv->name ?? '-' }}</td>
                <td>{{ $row->user->name ?? '-' }}</td>
                <td>{{ $row->start_time }}</td>
                <td>{{ $row->end_time }}</td>
                <td>Rp {{ number_format($row->rental_amount, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row->fnb_amount, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
