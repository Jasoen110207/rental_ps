<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Kasir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 24px;
        }
        .container {
            border: 2px solid #111827;
            padding: 20px;
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
        }
        .meta {
            font-size: 12px;
            line-height: 1.7;
            text-align: right;
        }
        .section {
            margin-top: 18px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .total {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #111827;
            font-weight: bold;
            font-size: 20px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Slip Gaji</h1>
                <div>{{ $payroll['period'] }}</div>
            </div>
            <div class="meta">
                <div>TambahBang Rental PS</div>
                <div>Kasir: {{ $payroll['name'] }}</div>
                <div>{{ $payroll['shift'] }}</div>
            </div>
        </div>

        <div class="section">
            <div class="row"><span class="label">Nama</span><span>{{ $payroll['name'] }}</span></div>
            <div class="row"><span class="label">Jabatan</span><span>{{ $payroll['position'] }}</span></div>
            <div class="row"><span class="label">Jam Kerja</span><span>{{ $payroll['work_hours'] }}</span></div>
        </div>

        <div class="section">
            <div class="row"><span class="label">Gaji Pokok</span><span>Rp {{ number_format($payroll['base_salary'], 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">Bonus / Komisi</span><span>Rp {{ number_format($payroll['transaction_bonus'], 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">Potongan</span><span>- Rp {{ number_format($payroll['deductions'], 0, ',', '.') }}</span></div>
            <div class="total row"><span class="label">Total Gaji Bersih</span><span>Rp {{ number_format($payroll['net_salary'], 0, ',', '.') }}</span></div>
        </div>
    </div>
</body>
</html>
