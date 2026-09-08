@extends('layouts.app')

@section('title', 'Units - TambahBang')
@section('page_title', 'Manajemen Unit & QR Meja')

@section('content')
<div class="flex flex-col gap-6">
  <div class="flex items-center justify-between pb-4 border-b-2 border-on-surface">
    <div>
      <h2 class="text-xl font-black uppercase">Daftar Unit ({{ $units->count() }})</h2>
      <p class="text-xs text-on-surface-variant mt-0.5">{{ $units->where('status', 'available')->count() }} tersedia • {{ $units->where('status', 'playing')->count() }} dimainkan • {{ $units->where('status', 'maintenance')->count() }} maintenance</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('kasir.unit') }}" class="px-3 py-1.5 text-xs font-bold border-2 border-on-surface {{ !request('filter') ? 'bg-on-surface text-white' : 'bg-surface-container-lowest' }}">Semua</a>
      <a href="{{ route('kasir.unit', ['filter' => 'available']) }}" class="px-3 py-1.5 text-xs font-bold border-2 border-on-surface {{ request('filter') === 'available' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest' }}">Tersedia</a>
      <a href="{{ route('kasir.unit', ['filter' => 'maintenance']) }}" class="px-3 py-1.5 text-xs font-bold border-2 border-on-surface {{ request('filter') === 'maintenance' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest' }}">Maintenance</a>
    </div>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
    <div class="xl:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-4">
      @foreach ($units as $u)
        @if (request('filter') && $u->status !== request('filter')) @continue @endif
        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow p-4 flex flex-col justify-between {{ $selected && $selected->id === $u->id ? 'ring-2 ring-primary' : '' }}">
          <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
            <div>
              <div class="flex items-center gap-2">
                <span class="font-bold">{{ $u->name }}</span>
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase border border-on-surface {{ $u->status === 'available' ? 'bg-emerald-100 text-emerald-900' : ($u->status === 'playing' ? 'bg-blue-100 text-blue-900' : 'bg-red-100 text-red-900') }}">{{ $u->status }}</span>
              </div>
              <span class="text-xs text-on-surface-variant">{{ strtoupper($u->type) }}</span>
            </div>
            <span class="font-bold text-primary text-sm">Rp {{ number_format($u->price_per_hour, 0, ',', '.') }}<span class="text-xs font-normal text-on-surface">/jam</span></span>
          </div>
          <div class="my-3 px-3 py-2 bg-surface-container border border-on-surface text-xs flex justify-between">
            <span class="font-bold {{ $u->is_buzzer_on ? 'text-error' : 'text-tertiary' }}">{{ $u->is_buzzer_on ? 'Buzzer: MENYALA' : 'Buzzer: Normal' }}</span>
            <a href="{{ route('customer.index', $u->id) }}" target="_blank" class="text-primary font-bold hover:underline">/customer/{{ $u->id }}</a>
          </div>
          <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
            <a href="{{ route('kasir.unit', ['unit_id' => $u->id]) }}" class="py-1.5 px-2 text-[11px] font-bold border-2 border-on-surface text-center btn-press bg-surface-container-lowest">QR</a>
            <form method="POST" action="{{ route('kasir.rental.toggle-buzzer', $u->id) }}">@csrf
              <button class="w-full py-1.5 px-2 text-[11px] font-bold border-2 border-on-surface bg-secondary-container btn-press">BUZZER</button>
            </form>
            <form method="POST" action="{{ route('kasir.unit.toggle', $u->id) }}">@csrf
              <button class="w-full py-1.5 px-2 text-[11px] font-bold border-2 border-on-surface {{ $u->status === 'maintenance' ? 'bg-tertiary text-white' : 'bg-surface' }} btn-press">{{ $u->status === 'maintenance' ? 'AKTIFKAN' : 'MAINT.' }}</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>

    <div class="xl:col-span-4 bg-surface-container-lowest border-[3px] border-on-surface neo-shadow p-6 text-center">
      @if ($selected)
        <p class="text-[11px] font-black uppercase bg-on-surface text-white inline-block px-3 py-1">SCAN — {{ $selected->name }}</p>
        <h3 class="font-black uppercase mt-2">Kontrol Sesi {{ $selected->name }}</h3>
        <div class="my-5 p-4 bg-surface-container-low border-2 border-on-surface flex flex-col items-center">
          <div class="bg-white border-2 border-on-surface p-2">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode(route('customer.index', $selected->id)) }}&margin=4" alt="QR {{ $selected->name }}" class="w-40 h-40">
          </div>
          <span class="mt-3 font-mono text-xs font-bold bg-on-surface text-white px-2 py-0.5">BAY ID: {{ strtoupper($selected->name) }}</span>
        </div>
        <a href="{{ route('admin.units.qr', $selected->id) }}" target="_blank" class="w-full py-3 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press block">CETAK STANDING BANNER</a>
        <p class="text-[11px] text-on-surface-variant mt-2">Scan tanpa aplikasi • pantau timer • tambah waktu & pesan F&B.</p>
      @else
        <p class="text-xs text-on-surface-variant py-8">Belum ada unit.</p>
      @endif
    </div>
  </div>
</div>
@endsection
