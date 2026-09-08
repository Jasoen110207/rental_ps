<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QR Code Meja — {{ $tv->name }}</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Chivo:wght@700;900&family=Space+Grotesk:wght@700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .neo-shadow {
      box-shadow: 5px 5px 0px #000;
    }
    @media print {
      body {
        background: #fff;
        padding: 0;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6 font-sans">

  <!-- Printable Table Tent Card -->
  <div class="w-[340px] bg-white border-4 border-black p-6 neo-shadow text-center flex flex-col items-center">
    
    <!-- Header -->
    <div class="flex items-center gap-2 pb-3 border-b-2 border-black w-full justify-center">
      <span class="material-symbols-outlined text-3xl text-blue-600">sports_esports</span>
      <h1 class="text-xl font-black uppercase tracking-tight text-black font-['Space_Grotesk']">TAMBAHBANG</h1>
    </div>

    <!-- Unit Title -->
    <div class="my-4 py-2 px-4 bg-orange-500 text-white border-2 border-black neo-shadow w-full">
      <h2 class="text-2xl font-black uppercase tracking-tight font-['Space_Grotesk']">{{ $tv->name }}</h2>
      <p class="text-xs font-mono font-bold uppercase">{{ strtoupper($tv->type) }}</p>
    </div>

    <!-- QR Code Container (SVG/Image API from qrserver) -->
    <div class="p-4 bg-white border-2 border-black neo-shadow my-2 flex items-center justify-center">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($customerUrl) }}&margin=4" alt="QR Code" class="w-44 h-44">
    </div>

    <p class="text-xs font-mono font-bold uppercase mt-2 text-gray-700">
      SCAN DENGAN KAMERA HP
    </p>

    <!-- Features Pill List -->
    <div class="w-full grid grid-cols-2 gap-2 my-3 text-[11px] font-bold">
      <div class="p-1.5 bg-blue-50 border border-black">
        ⏱️ Tambah Waktu
      </div>
      <div class="p-1.5 bg-amber-50 border border-black">
        🍜 Pesan Makanan
      </div>
    </div>

    <!-- Direct URL -->
    <p class="text-[10px] font-mono text-gray-500 break-all">
      {{ $customerUrl }}
    </p>

    <div class="mt-4 pt-3 border-t-2 border-dashed border-black w-full text-[10px] font-bold text-gray-600">
      Letakkan kartu ini di atas meja rental
    </div>
  </div>

  <div class="no-print mt-6 flex gap-3">
    <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 text-white font-bold text-xs uppercase border-2 border-black neo-shadow hover:bg-blue-700">
      🖨️ Cetak Kartu Meja
    </button>
    <button onclick="window.close()" class="px-5 py-2.5 bg-white text-black font-bold text-xs uppercase border-2 border-black neo-shadow hover:bg-gray-100">
      Tutup
    </button>
  </div>

</body>
</html>
