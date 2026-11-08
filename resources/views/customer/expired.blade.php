@extends('layouts.customer')

@section('title', 'Sesi Tidak Aktif — ' . ($storeName ?? 'TambahBang Rental PS'))

@section('content')
<div class="flex flex-col items-center justify-center py-10 px-4">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg text-center flex flex-col items-center gap-4">
    
    <div class="w-16 h-16 bg-red-100 text-error border-2 border-on-surface flex items-center justify-center neo-shadow-sm">
      <span class="material-symbols-outlined text-4xl">qr_code_scanner</span>
    </div>

    @if (isset($tv) && $tv)
      <span class="px-2.5 py-0.5 bg-secondary-fixed text-secondary border border-on-surface text-[10px] font-label-sm font-black uppercase">
        {{ $tv->name }} ({{ strtoupper($tv->type) }})
      </span>
    @endif

    <div>
      <h2 class="text-xl font-headline-lg font-black uppercase text-on-surface leading-tight">
        {{ $title ?? 'SESI RENTAL TELAH BERAKHIR' }}
      </h2>
      <p class="text-xs font-body-md text-on-surface-variant mt-2 leading-relaxed">
        {{ $message ?? 'Demi keamanan dan mencegah pesanan fiktif, halaman ini hanya berlaku untuk 1 sesi bermain aktif dan tidak dapat dibuka ulang dari riwayat browser (History).' }}
      </p>
    </div>

    <div class="w-full p-3.5 bg-surface-container-high border-2 border-on-surface text-left text-xs flex flex-col gap-1.5">
      <p class="font-headline-sm font-black uppercase text-on-surface flex items-center gap-1.5">
        <span class="material-symbols-outlined text-base text-primary">verified_user</span>
        <span>Cara Mengakses Menu Meja:</span>
      </p>
      <ol class="list-decimal pl-5 text-[11px] text-on-surface-variant space-y-1 font-medium">
        <li>Pastikan sesi bermain Anda sudah diaktifkan oleh Kasir.</li>
        <li>Arahkan kamera HP Anda dan <strong>Scan ulang QR Code</strong> yang tertempel di meja TV.</li>
      </ol>
    </div>

  </div>
</div>
@endsection
