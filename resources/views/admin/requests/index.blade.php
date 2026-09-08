@extends('layouts.admin')

@section('title', 'Customer Requests Hub')
@section('page_title', 'Customer Requests Hub')

@section('content')
<div class="flex flex-col gap-6">

  <!-- Header & Tabs -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b-2 border-on-surface">
    <div>
      <h2 class="text-xl font-headline-lg font-black uppercase text-on-surface">Pusat Permintaan Pelanggan</h2>
      <p class="text-xs text-on-surface-variant font-medium mt-0.5">Kelola permintaan perpanjangan waktu dan pesanan F&B dari QR code meja.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-1 bg-surface-container-high p-1 border-2 border-on-surface neo-shadow-sm">
      <a href="{{ route('admin.requests.index', ['tab' => 'all']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'all' ? 'bg-primary text-on-primary neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Semua ({{ $counts['all'] }})
      </a>
      <a href="{{ route('admin.requests.index', ['tab' => 'pending']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'pending' ? 'bg-secondary-container text-on-secondary neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Pending ({{ $counts['pending'] }})
      </a>
      <a href="{{ route('admin.requests.index', ['tab' => 'approved']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'approved' ? 'bg-emerald-600 text-white neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Disetujui ({{ $counts['approved'] }})
      </a>
      <a href="{{ route('admin.requests.index', ['tab' => 'rejected']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'rejected' ? 'bg-error text-white neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Ditolak ({{ $counts['rejected'] }})
      </a>
    </div>
  </div>

  <!-- Requests Table / Grid -->
  @if ($requests->count() == 0)
    <div class="p-12 text-center bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">inbox</span>
      <h3 class="font-headline-md font-bold text-base uppercase">Belum Ada Permintaan</h3>
      <p class="text-xs text-on-surface-variant mt-1">Permintaan dari scan QR pelanggan akan muncul di sini secara real-time.</p>
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach ($requests as $req)
        @php
          $isPending = $req->status === 'pending';
          $isApproved = $req->status === 'approved';
          $isTime = $req->type === 'add_time';
          $payload = $req->payload;
        @endphp

        <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between relative {{ $isPending ? 'border-secondary-container' : '' }}">
          
          <div>
            <!-- Header -->
            <div class="flex items-start justify-between pb-3 border-b-2 border-on-surface">
              <div>
                <span class="font-headline-lg font-black text-lg uppercase text-primary block leading-tight">
                  {{ $req->tv ? $req->tv->name : 'Meja PS' }}
                </span>
                <span class="text-[10px] font-label-sm text-on-surface-variant font-bold">
                  {{ $req->created_at->diffForHumans() }} ({{ $req->created_at->format('H:i') }})
                </span>
              </div>

              <!-- Status Badge -->
              <div>
                @if ($isPending)
                  <span class="px-2 py-0.5 bg-secondary-container text-on-secondary font-headline-sm text-[10px] font-bold uppercase border border-on-surface neo-shadow-sm animate-pulse">
                    PENDING
                  </span>
                @elseif ($isApproved)
                  <span class="px-2 py-0.5 bg-emerald-100 text-emerald-900 font-headline-sm text-[10px] font-bold uppercase border border-on-surface">
                    DISETUJUI
                  </span>
                @else
                  <span class="px-2 py-0.5 bg-red-100 text-red-900 font-headline-sm text-[10px] font-bold uppercase border border-on-surface">
                    DITOLAK
                  </span>
                @endif
              </div>
            </div>

            <!-- Content Body -->
            <div class="py-4">
              <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-lg {{ $isTime ? 'text-primary' : 'text-secondary' }}">
                  {{ $isTime ? 'more_time' : 'restaurant' }}
                </span>
                <span class="font-headline-sm text-xs font-bold uppercase">
                  {{ $isTime ? 'Permintaan Tambah Waktu' : 'Pesanan Makanan & Minuman' }}
                </span>
              </div>

              @if ($isTime)
                <div class="p-3 bg-surface border border-on-surface text-xs font-body-md flex flex-col gap-1">
                  <div class="flex justify-between">
                    <span class="text-on-surface-variant">Tambahan:</span>
                    <span class="font-headline-sm font-bold text-primary">+{{ $payload['duration_hours'] ?? 1 }} Jam</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-on-surface-variant">Estimasi Biaya:</span>
                    <span class="font-headline-sm font-bold">Rp {{ number_format($payload['price'] ?? 0, 0, ',', '.') }}</span>
                  </div>
                  @if (!empty($payload['note']))
                    <p class="text-[11px] text-on-surface-variant italic mt-1">"{{ $payload['note'] }}"</p>
                  @endif
                </div>
              @else
                <div class="p-3 bg-surface border border-on-surface text-xs font-body-md flex flex-col gap-1.5">
                  <div class="flex flex-col gap-1 pb-2 border-b border-on-surface/20">
                    @if (!empty($payload['items']))
                      @foreach ($payload['items'] as $item)
                        <div class="flex justify-between text-xs">
                          <span>{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                          <span class="font-bold">Rp {{ number_format($item['subtotal'] ?? ($item['price'] * $item['quantity']), 0, ',', '.') }}</span>
                        </div>
                      @endforeach
                    @endif
                  </div>
                  <div class="flex justify-between font-headline-sm font-bold pt-1">
                    <span>Total F&B:</span>
                    <span class="text-primary">Rp {{ number_format($payload['total_price'] ?? 0, 0, ',', '.') }}</span>
                  </div>
                  @if (!empty($payload['note']))
                    <p class="text-[11px] text-on-surface-variant italic mt-1">Catatan: "{{ $payload['note'] }}"</p>
                  @endif
                </div>
              @endif
            </div>
          </div>

          <!-- Actions -->
          <div class="pt-3 border-t-2 border-on-surface">
            @if ($isPending)
              <div class="grid grid-cols-2 gap-2">
                <form method="POST" action="{{ route('admin.requests.reject', $req->id) }}">
                  @csrf
                  <button type="submit" onclick="return confirm('Tolak permintaan ini?')" class="w-full py-2 bg-surface text-error border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm btn-press hover:bg-red-50">
                    Tolak
                  </button>
                </form>
                <form method="POST" action="{{ route('admin.requests.approve', $req->id) }}">
                  @csrf
                  <button type="submit" class="w-full py-2 bg-primary text-on-primary border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm btn-press hover:bg-primary-container">
                    Setujui
                  </button>
                </form>
              </div>
            @else
              <p class="text-[10px] font-label-sm text-center uppercase font-bold text-on-surface-variant py-1">
                Diproses {{ $req->updated_at->format('d/m/Y H:i') }}
              </p>
            @endif
          </div>

        </div>
      @endforeach
    </div>

    <div class="mt-4">
      {{ $requests->links() }}
    </div>
  @endif

</div>
@endsection
