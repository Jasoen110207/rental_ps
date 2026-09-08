@extends('layouts.app')

@section('title', 'Dashboard - TambahBang')
@section('page_title', 'Operational Matrix & Monitoring')

@section('content')
<div class="flex flex-col gap-4">

  <!-- KPI RINGKAS (data real) -->
  <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
    <div class="p-3 bg-emerald-50 border-2 border-on-surface neo-shadow">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-emerald-900">Tersedia</span>
        <span class="material-symbols-outlined text-xl text-emerald-700">check_circle</span>
      </div>
      <div class="mt-1"><span class="text-2xl font-headline-lg font-black" id="kpi-available">{{ $availableUnits }}</span>
      <span class="text-xs font-bold text-emerald-800">/ {{ $totalUnits }} Unit</span></div>
    </div>
    <div class="p-3 bg-blue-50 border-2 border-on-surface neo-shadow">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-blue-900">Sedang Main</span>
        <span class="material-symbols-outlined text-xl text-primary">sports_esports</span>
      </div>
      <div class="mt-1"><span class="text-2xl font-headline-lg font-black" id="kpi-playing">{{ $playingUnits }}</span>
      <span class="text-xs font-bold text-blue-800">Meja Aktif</span></div>
    </div>
    <div class="p-3 bg-amber-50 border-2 border-on-surface neo-shadow">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-amber-900">Hampir Habis</span>
        <span class="material-symbols-outlined text-xl text-amber-600">timelapse</span>
      </div>
      <div class="mt-1"><span class="text-2xl font-headline-lg font-black" id="kpi-almost">{{ $almostFinishedUnits }}</span>
      <span class="text-xs font-bold text-amber-800">&lt; 10 mnt</span></div>
    </div>
    <div class="p-3 bg-red-50 border-2 border-on-surface neo-shadow">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-red-900">Waktu Habis</span>
        <span class="material-symbols-outlined text-xl text-error">alarm</span>
      </div>
      <div class="mt-1"><span class="text-2xl font-headline-lg font-black" id="kpi-timeup">{{ $timeUpUnits }}</span>
      <span class="text-xs font-bold text-red-800">Checkout</span></div>
    </div>
    <div class="p-3 bg-orange-50 border-2 border-on-surface neo-shadow">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-orange-900">Request</span>
        <span class="material-symbols-outlined text-xl text-secondary-container">receipt_long</span>
      </div>
      <div class="mt-1"><span class="text-2xl font-headline-lg font-black" id="kpi-requests">{{ $pendingRequestsCount }}</span>
      <span class="text-xs font-bold text-orange-800">Pending</span></div>
    </div>
    <div class="p-3 bg-primary-fixed border-2 border-on-surface neo-shadow">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-primary">Omset Shift</span>
        <span class="material-symbols-outlined text-xl text-primary">payments</span>
      </div>
      <div class="mt-1"><span class="text-base font-headline-md font-black">Rp {{ number_format($activeShift->total_revenue ?? 0, 0, ',', '.') }}</span>
      <span class="text-[10px] font-bold block text-on-surface-variant">{{ $activeShift->transactions_count ?? 0 }} Transaksi</span></div>
    </div>
  </div>

  <!-- FILTER BAR (fungsi client-side, data real) -->
  <div class="p-3 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-wrap items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-2" id="unit-filter-bar">
      <span class="text-xs font-bold flex items-center gap-1 mr-1">
        <span class="material-symbols-outlined text-sm">filter_list</span> FILTER:
      </span>
      <button data-filter="all" class="filter-btn px-3 py-1 bg-on-surface text-white font-label-md text-xs font-bold border-2 border-on-surface">Semua ({{ $totalUnits }})</button>
      <button data-filter="playing" class="filter-btn px-3 py-1 bg-surface-container-lowest font-label-md text-xs font-bold border-2 border-on-surface hover:bg-surface-container neo-shadow-sm btn-press">Aktif ({{ $playingUnits }})</button>
      <button data-filter="available" class="filter-btn px-3 py-1 bg-surface-container-lowest font-label-md text-xs font-bold border-2 border-on-surface hover:bg-surface-container neo-shadow-sm btn-press">Tersedia ({{ $availableUnits }})</button>
    </div>
    <div class="flex items-center gap-2">
      <div class="relative">
        <input id="unit-search" class="pl-8 pr-3 py-1 font-label-md text-xs border-2 border-on-surface neo-shadow-inset bg-surface-container-lowest focus:outline-none focus:border-primary" placeholder="Cari unit..." type="text">
        <span class="material-symbols-outlined absolute left-2 top-1.5 text-on-surface-variant text-base">search</span>
      </div>
      <button onclick="pollKasirStatus()" class="px-2.5 py-1 bg-surface-container-high border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">refresh</span><span>SYNC</span>
      </button>
    </div>
  </div>

  <!-- GRID UNIT (data real dari DB) -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4" id="unit-cards-container">
    @forelse ($tvs as $tv)
      @php
        $activeSession = $tv->playSessions->first();
        $isPrepaid = $activeSession && $activeSession->billing_type === 'prepaid';
        $isTimeUp = $activeSession && $isPrepaid && $activeSession->end_time && $activeSession->end_time->isPast();
        $remaining = 0;
        $almost = false;
        if ($activeSession && $isPrepaid && $activeSession->end_time && !$isTimeUp) {
          $remaining = now()->diffInSeconds($activeSession->end_time, false);
          $almost = $remaining <= 600;
        }
        $cardClass = 'bg-surface-container-lowest border-2 border-on-surface neo-shadow';
        if ($tv->status === 'available') $cardClass = 'bg-surface-container-lowest border-2 border-on-surface neo-shadow';
        elseif ($isTimeUp || $tv->is_buzzer_on) $cardClass = 'bg-red-50 border-2 border-error alarm-card';
        elseif ($almost) $cardClass = 'bg-amber-50 border-2 border-secondary-container warning-card';
        elseif ($tv->status === 'playing') $cardClass = 'bg-blue-50/40 border-2 border-on-surface neo-shadow';
        elseif ($tv->status === 'maintenance') $cardClass = 'bg-gray-100 border-2 border-dashed border-outline opacity-75';
      @endphp
      <div class="p-4 flex flex-col justify-between {{ $cardClass }} unit-card" data-tv-id="{{ $tv->id }}" data-status="{{ $tv->status }}" data-name="{{ strtolower($tv->name) }}">
        <div class="flex items-start justify-between pb-3 border-b-2 border-on-surface">
          <div>
            <div class="flex items-center gap-2">
              <h3 class="font-headline-lg font-black text-lg uppercase">{{ $tv->name }}</h3>
              @if ($tv->customerRequests->count() > 0)
                <span class="px-1.5 py-0.5 bg-secondary-container text-[10px] font-bold border border-on-surface animate-bounce">REQ ({{ $tv->customerRequests->count() }})</span>
              @endif
            </div>
            <p class="text-xs font-label-sm font-bold text-on-surface-variant uppercase mt-1">{{ strtoupper($tv->type) }} • Rp {{ number_format($tv->price_per_hour, 0, ',', '.') }}/jam</p>
          </div>
          <div>
            @if ($tv->status === 'available')
              <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 border-2 border-on-surface text-xs font-black uppercase neo-shadow-sm">TERSEDIA</span>
            @elseif ($isTimeUp)
              <span class="px-2.5 py-1 bg-error text-white border-2 border-on-surface text-xs font-black uppercase neo-shadow-sm animate-pulse">WAKTU HABIS</span>
            @elseif ($almost)
              <span class="px-2.5 py-1 bg-secondary-container border-2 border-on-surface text-xs font-black uppercase neo-shadow-sm">&lt; 10 MENIT</span>
            @elseif ($tv->status === 'playing')
              <span class="px-2.5 py-1 bg-primary text-white border-2 border-on-surface text-xs font-black uppercase neo-shadow-sm">{{ $isPrepaid ? 'PREPAID' : 'POSTPAID' }}</span>
            @else
              <span class="px-2 py-1 bg-gray-200 text-gray-700 border border-outline text-xs font-bold uppercase">MAINTENANCE</span>
            @endif
          </div>
        </div>

        <div class="py-3 my-auto">
          @if ($tv->status === 'available')
            <div class="py-4 text-center">
              <p class="font-bold text-sm uppercase text-on-surface-variant">Unit Kosong</p>
              <p class="text-xs text-on-surface-variant mt-1">Siap untuk sesi baru</p>
            </div>
          @elseif ($tv->status === 'playing' && $activeSession)
            <div class="text-center py-2 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm">
              <span class="text-[10px] font-bold uppercase text-on-surface-variant block">{{ $isPrepaid ? 'SISA WAKTU' : 'DURASI BERJALAN' }}</span>
              <div class="text-3xl font-timer-display font-black {{ $isTimeUp ? 'text-error animate-pulse' : ($almost ? 'text-secondary' : '') }}" id="timer-tv-{{ $tv->id }}" data-type="{{ $activeSession->billing_type }}" data-end="{{ $activeSession->end_time ? $activeSession->end_time->timestamp : '' }}" data-start="{{ $activeSession->start_time->timestamp }}">--:--:--</div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs mt-2">
              <div class="p-1.5 bg-surface border border-on-surface">
                <span class="text-[10px] block font-bold text-on-surface-variant">MULAI-SELESAI</span>
                <span class="font-bold">{{ $activeSession->start_time->format('H:i') }}-{{ $activeSession->end_time ? $activeSession->end_time->format('H:i') : 'Loss' }}</span>
              </div>
              <div class="p-1.5 bg-surface border border-on-surface">
                <span class="text-[10px] block font-bold text-on-surface-variant">TOTAL</span>
                <span class="font-bold text-primary">Rp {{ number_format($activeSession->total_amount, 0, ',', '.') }}</span>
              </div>
            </div>
            @if ($activeSession->sessionOrders->count() > 0)
              <div class="mt-2 p-2 bg-surface-container border border-on-surface text-[11px] flex items-center justify-between">
                <span class="font-bold">F&B ({{ $activeSession->sessionOrders->sum('quantity') }} item)</span>
                <span class="font-bold">Rp {{ number_format($activeSession->fnb_amount, 0, ',', '.') }}</span>
              </div>
            @endif
            @if ($tv->is_buzzer_on)
              <div class="mt-2 p-2 bg-error text-white text-xs font-bold uppercase flex items-center justify-between border border-on-surface animate-pulse">
                <span>ALARM BUZZER AKTIF!</span>
                <form method="POST" action="{{ route('kasir.rental.toggle-buzzer', $tv->id) }}" class="inline">@csrf
                  <button class="px-2 py-0.5 bg-white text-error text-[10px] font-bold border border-on-surface">MATIKAN</button>
                </form>
              </div>
            @endif
          @else
            <div class="py-4 text-center text-on-surface-variant">
              <span class="material-symbols-outlined text-3xl">build</span>
              <p class="text-xs font-bold uppercase mt-1">Maintenance</p>
            </div>
          @endif
        </div>

        <div class="pt-3 border-t-2 border-on-surface">
          @if ($tv->status === 'available')
            <button onclick="openStartModal({{ $tv->id }}, '{{ $tv->name }}', {{ $tv->price_per_hour }})" class="w-full py-2.5 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press flex items-center justify-center gap-1.5">
              <span class="material-symbols-outlined text-lg">play_arrow</span><span>MULAI RENTAL</span>
            </button>
          @elseif ($tv->status === 'playing' && $activeSession)
            <div class="flex flex-col gap-2">
              <div class="grid grid-cols-2 gap-2">
                <button onclick="openExtendModal({{ $activeSession->id }}, '{{ $tv->name }}', {{ $tv->price_per_hour }}, '{{ $activeSession->end_time ? $activeSession->end_time->format('H:i') : '' }}')" class="py-1.5 bg-secondary-container text-[11px] uppercase font-bold border-2 border-on-surface neo-shadow-sm btn-press">+ WAKTU</button>
                <button onclick="openAddFnbModal({{ $activeSession->id }}, '{{ $tv->name }}')" class="py-1.5 bg-surface text-[11px] uppercase font-bold border-2 border-on-surface neo-shadow-sm btn-press">+ F&B</button>
              </div>
              <button onclick="openCheckoutModal({{ $activeSession->id }}, '{{ $tv->name }}', {{ $activeSession->rental_amount }}, {{ $activeSession->fnb_amount }}, {{ $activeSession->total_amount }})" class="w-full py-2 bg-emerald-600 text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">SELESAI / CHECKOUT</button>
            </div>
          @else
            <form method="POST" action="{{ route('kasir.unit.toggle', $tv->id) }}">@csrf
              <button class="w-full py-2 bg-surface border-2 border-on-surface text-xs uppercase font-bold neo-shadow-sm btn-press">AKTIFKAN UNIT</button>
            </form>
          @endif
        </div>
      </div>
    @empty
      <div class="col-span-4 p-10 text-center bg-surface-container-lowest border-2 border-on-surface neo-shadow">
        <p class="font-bold uppercase">Belum ada unit. Tambah via menu Units.</p>
      </div>
    @endforelse
  </div>
</div>

<!-- MODAL START -->
<div id="modal-start" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-on-surface/50 p-4">
  <div class="w-full max-w-lg bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-black uppercase text-lg" id="start-modal-title">Mulai Rental</h3>
      <button onclick="closeModal('modal-start')" class="p-1 border border-on-surface btn-press"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="POST" action="{{ route('kasir.rental.start') }}" class="flex flex-col gap-4">
      @csrf
      <input type="hidden" name="tv_id" id="start-tv-id">
      <div>
        <label class="block text-xs uppercase font-bold mb-1.5">Tipe Billing</label>
        <div class="grid grid-cols-2 gap-2">
          <button type="button" onclick="setBillingType('prepaid')" id="btn-billing-prepaid" class="py-2.5 border-2 border-on-surface text-xs font-bold uppercase bg-primary text-white neo-shadow btn-press">PREPAID</button>
          <button type="button" onclick="setBillingType('postpaid')" id="btn-billing-postpaid" class="py-2.5 border-2 border-on-surface text-xs font-bold uppercase bg-surface neo-shadow btn-press">POSTPAID</button>
        </div>
        <input type="hidden" name="billing_type" id="start-billing-type" value="prepaid">
      </div>
      <div id="prepaid-options" class="flex flex-col gap-3">
        <div class="grid grid-cols-4 gap-2">
          <button type="button" onclick="setDuration(1)" class="duration-btn py-2 border-2 border-on-surface text-xs font-bold neo-shadow-sm btn-press" data-hours="1">1 Jam</button>
          <button type="button" onclick="setDuration(2)" class="duration-btn py-2 border-2 border-on-surface text-xs font-bold neo-shadow-sm btn-press" data-hours="2">2 Jam</button>
          <button type="button" onclick="setDuration(3)" class="duration-btn py-2 border-2 border-on-surface text-xs font-bold neo-shadow-sm btn-press" data-hours="3">3 Jam</button>
          <button type="button" onclick="setDuration(5)" class="duration-btn py-2 border-2 border-on-surface text-xs font-bold neo-shadow-sm btn-press" data-hours="5">5 Jam</button>
        </div>
        <div class="flex items-center gap-2">
          <label class="text-xs font-bold uppercase whitespace-nowrap">Durasi (Jam):</label>
          <input type="number" step="0.5" min="0.5" max="24" name="duration_hours" id="start-duration-input" value="1" oninput="calculateStartCost()" class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface text-sm">
        </div>
      </div>
      <div id="postpaid-options" class="p-3 bg-blue-50 border-2 border-on-surface text-xs hidden">Mode Postpaid: timer naik, biaya dihitung saat checkout.</div>
      <div class="p-3 bg-surface-container-high border-2 border-on-surface flex items-center justify-between">
        <div><span class="text-[10px] uppercase font-bold block text-on-surface-variant">Estimasi</span>
        <span class="font-black text-xl text-primary" id="start-estimated-cost">Rp 0</span></div>
        <span class="text-xs font-bold" id="start-rate-display">Rp 0 / jam</span>
      </div>
      <div class="flex justify-end gap-3 pt-3 border-t-2 border-on-surface">
        <button type="button" onclick="closeModal('modal-start')" class="py-2 px-4 border-2 border-on-surface text-xs uppercase font-bold btn-press">Batal</button>
        <button type="submit" class="py-2 px-5 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">Mulai Rental</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EXTEND -->
<div id="modal-extend" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-on-surface/50 p-4">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-black uppercase text-lg" id="extend-modal-title">Tambah Waktu</h3>
      <button onclick="closeModal('modal-extend')" class="p-1 border border-on-surface btn-press"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form id="extend-form" method="POST" action="" class="flex flex-col gap-4">
      @csrf
      <div class="p-3 bg-surface border border-on-surface text-xs flex justify-between"><span class="font-bold text-on-surface-variant">Selesai saat ini:</span><span class="font-bold" id="extend-current-end">--:--</span></div>
      <div class="grid grid-cols-3 gap-2">
        <button type="button" onclick="setExtendDuration(0.5)" class="py-2 border-2 border-on-surface text-xs font-bold btn-press">+30 Mnt</button>
        <button type="button" onclick="setExtendDuration(1)" class="py-2 border-2 border-on-surface text-xs font-bold btn-press">+1 Jam</button>
        <button type="button" onclick="setExtendDuration(2)" class="py-2 border-2 border-on-surface text-xs font-bold btn-press">+2 Jam</button>
      </div>
      <div class="flex items-center gap-2">
        <label class="text-xs font-bold uppercase whitespace-nowrap">Tambahan (Jam):</label>
        <input type="number" step="0.5" min="0.25" max="12" name="added_hours" id="extend-hours-input" value="1" oninput="calculateExtendCost()" class="w-full px-3 py-1.5 border-2 border-on-surface text-sm">
      </div>
      <div class="p-3 bg-surface-container-high border-2 border-on-surface flex justify-between"><span class="text-xs uppercase font-bold">Tambahan:</span><span class="font-black text-lg text-secondary" id="extend-estimated-cost">Rp 0</span></div>
      <div class="flex justify-end gap-3 pt-3 border-t-2 border-on-surface">
        <button type="button" onclick="closeModal('modal-extend')" class="py-2 px-4 border-2 border-on-surface text-xs uppercase font-bold btn-press">Batal</button>
        <button type="submit" class="py-2 px-5 bg-secondary-container text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">Konfirmasi</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL ADD FNB -->
<div id="modal-add-fnb" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-on-surface/50 p-4">
  <div class="w-full max-w-2xl bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg max-h-[90vh] flex flex-col">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-black uppercase text-lg" id="fnb-modal-title">Tambah F&B</h3>
      <button onclick="closeModal('modal-add-fnb')" class="p-1 border border-on-surface btn-press"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form id="fnb-form" method="POST" action="" class="flex flex-col gap-4 overflow-y-auto">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach ($products as $product)
          <div class="p-3 bg-surface border-2 border-on-surface neo-shadow-sm flex items-center justify-between gap-2">
            <div class="min-w-0">
              <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 bg-secondary-fixed border border-on-surface">{{ $product->category }}</span>
              <p class="text-xs font-bold truncate mt-1">{{ $product->name }}</p>
              <p class="text-xs text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }} • Stok {{ $product->stock }}</p>
            </div>
            <div class="flex items-center gap-1 border-2 border-on-surface bg-white">
              <button type="button" onclick="adjustFnbQty({{ $product->id }}, -1)" class="w-6 h-6 font-bold hover:bg-surface-container-high">-</button>
              <input type="number" name="items[{{ $loop->index }}][quantity]" id="fnb-qty-{{ $product->id }}" value="0" min="0" max="{{ $product->stock }}" class="w-8 text-center text-xs font-bold border-none p-0" readonly>
              <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
              <button type="button" onclick="adjustFnbQty({{ $product->id }}, 1)" class="w-6 h-6 font-bold hover:bg-surface-container-high">+</button>
            </div>
          </div>
        @endforeach
      </div>
      <div class="p-3 bg-surface-container-high border-2 border-on-surface flex justify-between"><span class="text-xs uppercase font-bold">Total F&B:</span><span class="font-black text-xl text-primary" id="fnb-total-preview">Rp 0</span></div>
      <div class="flex justify-end gap-3 pt-3 border-t-2 border-on-surface">
        <button type="button" onclick="closeModal('modal-add-fnb')" class="py-2 px-4 border-2 border-on-surface text-xs uppercase font-bold btn-press">Batal</button>
        <button type="submit" class="py-2 px-5 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">Tambahkan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL CHECKOUT -->
<div id="modal-checkout" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-on-surface/50 p-4">
  <div class="w-full max-w-lg bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-black uppercase text-lg" id="checkout-modal-title">Checkout</h3>
      <button onclick="closeModal('modal-checkout')" class="p-1 border border-on-surface btn-press"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form id="checkout-form" method="POST" action="" class="flex flex-col gap-4">
      @csrf
      <div class="border-2 border-on-surface p-4 bg-surface flex flex-col gap-2 text-xs">
        <div class="flex justify-between border-b pb-2"><span class="font-bold text-on-surface-variant uppercase">Rental</span><span class="font-bold" id="checkout-rental-amount">Rp 0</span></div>
        <div class="flex justify-between border-b pb-2"><span class="font-bold text-on-surface-variant uppercase">F&B</span><span class="font-bold" id="checkout-fnb-amount">Rp 0</span></div>
        <div class="flex justify-between pt-1 font-black text-emerald-700 text-base"><span class="uppercase">TOTAL</span><span id="checkout-grand-total">Rp 0</span></div>
      </div>
      <div>
        <label class="block text-xs uppercase font-bold mb-2">Metode Pembayaran</label>
        <div class="grid grid-cols-4 gap-2">
          <label class="p-2.5 bg-surface border-2 border-on-surface text-xs font-bold uppercase text-center cursor-pointer has-[:checked]:bg-emerald-600 has-[:checked]:text-white"><input type="radio" name="payment_method" value="cash" checked class="hidden"><span>Tunai</span></label>
          <label class="p-2.5 bg-surface border-2 border-on-surface text-xs font-bold uppercase text-center cursor-pointer has-[:checked]:bg-emerald-600 has-[:checked]:text-white"><input type="radio" name="payment_method" value="qris" class="hidden"><span>QRIS</span></label>
          <label class="p-2.5 bg-surface border-2 border-on-surface text-xs font-bold uppercase text-center cursor-pointer has-[:checked]:bg-emerald-600 has-[:checked]:text-white"><input type="radio" name="payment_method" value="transfer" class="hidden"><span>Transfer</span></label>
          <label class="p-2.5 bg-surface border-2 border-on-surface text-xs font-bold uppercase text-center cursor-pointer has-[:checked]:bg-emerald-600 has-[:checked]:text-white"><input type="radio" name="payment_method" value="other" class="hidden"><span>Lainnya</span></label>
        </div>
      </div>
      <div class="flex justify-end gap-3 pt-3 border-t-2 border-on-surface">
        <button type="button" onclick="closeModal('modal-checkout')" class="py-2 px-4 border-2 border-on-surface text-xs uppercase font-bold btn-press">Batal</button>
        <button type="submit" class="py-2.5 px-6 bg-emerald-600 text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">SELESAIKAN LUNAS</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const productPrices = { @foreach($products as $p) {{ $p->id }}: {{ $p->price }}, @endforeach };
let currentTvRate = 0, currentExtendRate = 0;
function closeModal(id){ document.getElementById(id).classList.add('hidden'); }
window.openStartModal = function(tvId, tvName, rate){
  document.getElementById('start-tv-id').value = tvId;
  document.getElementById('start-modal-title').innerText = 'Mulai Rental — ' + tvName;
  currentTvRate = rate;
  document.getElementById('start-rate-display').innerText = 'Rp ' + rate.toLocaleString('id-ID') + ' / jam';
  setBillingType('prepaid'); setDuration(1);
  document.getElementById('modal-start').classList.remove('hidden');
};
function setBillingType(type){
  document.getElementById('start-billing-type').value = type;
  const pre = document.getElementById('btn-billing-prepaid'), post = document.getElementById('btn-billing-postpaid');
  const pOpts = document.getElementById('prepaid-options'), qOpts = document.getElementById('postpaid-options');
  if(type === 'prepaid'){
    pre.className = 'py-2.5 border-2 border-on-surface text-xs font-bold uppercase bg-primary text-white neo-shadow btn-press';
    post.className = 'py-2.5 border-2 border-on-surface text-xs font-bold uppercase bg-surface neo-shadow btn-press';
    pOpts.classList.remove('hidden'); qOpts.classList.add('hidden'); calculateStartCost();
  } else {
    post.className = 'py-2.5 border-2 border-on-surface text-xs font-bold uppercase bg-primary text-white neo-shadow btn-press';
    pre.className = 'py-2.5 border-2 border-on-surface text-xs font-bold uppercase bg-surface neo-shadow btn-press';
    pOpts.classList.add('hidden'); qOpts.classList.remove('hidden');
    document.getElementById('start-estimated-cost').innerText = 'Dihitung saat selesai';
  }
}
function setDuration(h){
  document.getElementById('start-duration-input').value = h;
  document.querySelectorAll('.duration-btn').forEach(b => {
    if(parseFloat(b.dataset.hours) === h){ b.classList.add('bg-primary-fixed'); } else { b.classList.remove('bg-primary-fixed'); }
  });
  calculateStartCost();
}
function calculateStartCost(){
  if(document.getElementById('start-billing-type').value === 'postpaid') return;
  const h = parseFloat(document.getElementById('start-duration-input').value) || 0;
  document.getElementById('start-estimated-cost').innerText = 'Rp ' + Math.round(h * currentTvRate).toLocaleString('id-ID');
}
function openExtendModal(sessionId, tvName, rate, currentEnd){
  currentExtendRate = rate;
  document.getElementById('extend-modal-title').innerText = 'Tambah Waktu — ' + tvName;
  document.getElementById('extend-current-end').innerText = currentEnd || 'Berjalan';
  document.getElementById('extend-form').action = '/kasir/rental/' + sessionId + '/extend';
  setExtendDuration(1);
  document.getElementById('modal-extend').classList.remove('hidden');
}
function setExtendDuration(h){ document.getElementById('extend-hours-input').value = h; calculateExtendCost(); }
function calculateExtendCost(){
  const h = parseFloat(document.getElementById('extend-hours-input').value) || 0;
  document.getElementById('extend-estimated-cost').innerText = 'Rp ' + Math.round(h * currentExtendRate).toLocaleString('id-ID');
}
function openAddFnbModal(sessionId, tvName){
  document.getElementById('fnb-modal-title').innerText = 'Tambah F&B — ' + tvName;
  document.getElementById('fnb-form').action = '/kasir/rental/' + sessionId + '/add-fnb';
  document.querySelectorAll('[id^="fnb-qty-"]').forEach(i => i.value = 0);
  updateFnbTotalPreview();
  document.getElementById('modal-add-fnb').classList.remove('hidden');
}
function adjustFnbQty(pid, ch){
  const input = document.getElementById('fnb-qty-' + pid);
  if(!input) return;
  input.value = Math.max(0, (parseInt(input.value) || 0) + ch);
  updateFnbTotalPreview();
}
function updateFnbTotalPreview(){
  let total = 0;
  document.querySelectorAll('[id^="fnb-qty-"]').forEach(input => {
    total += (parseInt(input.value) || 0) * (productPrices[input.id.replace('fnb-qty-', '')] || 0);
  });
  document.getElementById('fnb-total-preview').innerText = 'Rp ' + total.toLocaleString('id-ID');
}
function openCheckoutModal(sessionId, tvName, rentalAmt, fnbAmt, totalAmt){
  document.getElementById('checkout-modal-title').innerText = 'Checkout — ' + tvName;
  document.getElementById('checkout-form').action = '/kasir/rental/' + sessionId + '/checkout';
  document.getElementById('checkout-rental-amount').innerText = 'Rp ' + rentalAmt.toLocaleString('id-ID');
  document.getElementById('checkout-fnb-amount').innerText = 'Rp ' + fnbAmt.toLocaleString('id-ID');
  document.getElementById('checkout-grand-total').innerText = 'Rp ' + totalAmt.toLocaleString('id-ID');
  document.getElementById('modal-checkout').classList.remove('hidden');
}
// Filter lokal
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('bg-on-surface','text-white'));
    btn.classList.add('bg-on-surface','text-white');
    const f = btn.dataset.filter, q = (document.getElementById('unit-search').value || '').toLowerCase();
    document.querySelectorAll('.unit-card').forEach(card => {
      const matchF = f === 'all' || card.dataset.status === f;
      const matchQ = !q || card.dataset.name.includes(q);
      card.style.display = (matchF && matchQ) ? '' : 'none';
    });
  });
});
document.getElementById('unit-search').addEventListener('input', e => {
  const q = e.target.value.toLowerCase();
  document.querySelectorAll('.unit-card').forEach(card => {
    card.style.display = card.dataset.name.includes(q) ? '' : 'none';
  });
});
// Timer lokal tiap detik
function fmt(s){ s = Math.max(0, s); const h = Math.floor(s/3600), m = Math.floor((s%3600)/60), ss = s%60; return [h,m,ss].map(v => String(v).padStart(2,'0')).join(':'); }
setInterval(() => {
  const now = Math.floor(Date.now()/1000);
  document.querySelectorAll('[id^="timer-tv-"]').forEach(el => {
    const type = el.dataset.type, start = parseInt(el.dataset.start) || 0, end = parseInt(el.dataset.end) || 0;
    if(type === 'prepaid' && end > 0){ el.innerText = end - now <= 0 ? '00:00:00' : fmt(end - now); }
    else if(type === 'postpaid' && start > 0){ el.innerText = fmt(now - start); }
  });
}, 1000);
// Polling DB tiap 5 detik → sinkron KPI + badge + drawer
async function pollKasirStatus(){
  try{
    const res = await fetch('{{ route("kasir.dashboard.api-status") }}');
    if(!res.ok) return;
    const data = await res.json();
    if(!data.success) return;
    const set = (id, v) => { const el = document.getElementById(id); if(el) el.innerText = v; };
    set('kpi-available', data.summary.available);
    set('kpi-playing', data.summary.playing);
    set('kpi-almost', data.summary.almost_finished);
    set('kpi-timeup', data.summary.time_up);
    set('kpi-requests', data.summary.pending_requests_count);
    const top = document.getElementById('top-notif-badge'), nav = document.getElementById('nav-requests-badge');
    if(top){ top.innerText = data.summary.pending_requests_count; top.classList.toggle('hidden', data.summary.pending_requests_count == 0); }
    if(nav){ nav.innerText = data.summary.pending_requests_count; nav.classList.toggle('hidden', data.summary.pending_requests_count == 0); }
    const list = document.getElementById('drawer-notifications-list');
    if(list){
      if(!data.pending_requests.length){ list.innerHTML = '<p class="text-xs font-bold text-center py-6 text-on-surface-variant">Tidak ada request pending.</p>'; }
      else{
        list.innerHTML = data.pending_requests.map(req => `
          <div class="p-3 bg-surface border-2 border-on-surface neo-shadow-sm flex flex-col gap-1.5">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold uppercase text-primary">${req.tv_name}</span>
              <span class="text-[10px] text-on-surface-variant">${req.time_ago}</span>
            </div>
            <p class="text-xs font-bold">${req.type === 'add_time' ? 'Tambah Waktu (+' + (req.payload.duration_hours || 1) + ' Jam)' : (req.type === 'service_call' ? 'Panggil Kasir ke Meja' : 'Pesanan F&B')}</p>
            <div class="flex gap-2 mt-1">
              <form method="POST" action="/kasir/request/${req.id}/approve" class="flex-1">@csrf<button class="w-full py-1 bg-primary text-white text-[10px] font-bold uppercase border border-on-surface">Setujui</button></form>
              <form method="POST" action="/kasir/request/${req.id}/reject" class="flex-1">@csrf<button class="w-full py-1 bg-surface text-error text-[10px] font-bold uppercase border border-on-surface">Tolak</button></form>
            </div>
          </div>`).join('');
      }
    }
    let alarm = false;
    data.tvs.forEach(tv => { if(tv.is_buzzer_on) alarm = true; });
    if(alarm) playAlarmBeep();
  }catch(e){ console.log('poll error', e); }
}
pollKasirStatus();
setInterval(pollKasirStatus, 5000);
</script>
@endpush
