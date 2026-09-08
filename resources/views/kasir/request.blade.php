@extends('layouts.app')

@section('title', 'Requests - TambahBang')
@section('page_title', 'Customer Requests Hub')

@section('content')
<div class="flex flex-col gap-6">
  <div class="flex items-center gap-2 overflow-x-auto pb-1">
    <a href="{{ route('kasir.request', ['tab' => 'pending']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'pending' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Menunggu ({{ $counts['pending'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'approved']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'approved' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Disetujui ({{ $counts['approved'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'rejected']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'rejected' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Ditolak ({{ $counts['rejected'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'all']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'all' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Semua ({{ $counts['all'] }})</a>
  </div>

  @if ($requests->count() === 0)
    <div class="p-12 text-center bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <span class="material-symbols-outlined text-4xl text-on-surface-variant">inbox</span>
      <h3 class="font-bold uppercase mt-2">Tidak ada permintaan</h3>
      <p class="text-xs text-on-surface-variant mt-1">Request dari QR pelanggan akan muncul di sini.</p>
    </div>
  @else
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
      @foreach ($requests as $req)
        @php $isTime = $req->type === 'add_time'; $isService = $req->type === 'service_call'; $payload = $req->payload ?? []; $pending = $req->status === 'pending'; @endphp
        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow overflow-hidden">
          <div class="px-4 py-2.5 border-b-2 border-on-surface flex items-center justify-between {{ $isTime ? 'bg-amber-400' : ($isService ? 'bg-tertiary text-white' : 'bg-primary text-white') }}">
            <span class="font-black text-sm uppercase">{{ $req->tv ? $req->tv->name : 'Meja' }}</span>
            <span class="text-[11px] font-bold">{{ $req->created_at->format('H:i') }} ({{ $req->created_at->diffForHumans() }})</span>
          </div>
          <div class="p-4 flex flex-col gap-2 text-xs">
            <p class="font-black uppercase">{{ $isTime ? 'Tambah Waktu +'.($payload['duration_hours'] ?? 1).' Jam' : ($isService ? 'Panggil Kasir ke Meja' : 'Pesanan F&B Meja') }}</p>
            @if ($isTime)
              <div class="flex justify-between border p-2 bg-surface"><span class="text-on-surface-variant">Estimasi:</span><span class="font-bold">Rp {{ number_format($payload['price'] ?? 0, 0, ',', '.') }}</span></div>
            @elseif ($isService)
              <div class="border p-2 bg-surface text-on-surface">{{ $payload['note'] ?? 'Kasir dipanggil ke meja.' }}</div>
            @else
              <div class="border-2 border-on-surface p-2 bg-surface-container-low">
                @forelse ($payload['items'] ?? [] as $item)
                  <div class="flex justify-between"><span>{{ $item['quantity'] }}x {{ $item['name'] }}</span><span class="font-bold">Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</span></div>
                @empty
                  <span class="text-on-surface-variant">Detail item tidak tersedia.</span>
                @endforelse
              </div>
            @endif
            <div class="flex items-center justify-between pt-2 border-t-2 border-dashed border-on-surface">
              <span class="text-[11px] font-bold uppercase {{ $req->status === 'approved' ? 'text-emerald-700' : ($req->status === 'rejected' ? 'text-error' : 'text-secondary') }}">{{ $req->status }}</span>
              @if ($pending)
                <div class="flex gap-2">
                  <form method="POST" action="{{ route('kasir.request.reject', $req->id) }}">@csrf<button onclick="return confirm('Tolak request ini?')" class="px-4 py-2 bg-white text-error border-2 border-on-surface text-xs font-bold btn-press">TOLAK</button></form>
                  <form method="POST" action="{{ route('kasir.request.approve', $req->id) }}">@csrf<button class="px-5 py-2 bg-emerald-600 text-white border-2 border-on-surface text-xs font-bold btn-press">SETUJUI</button></form>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div>{{ $requests->links() }}</div>
  @endif
</div>
@endsection
