@extends('layouts.admin')

@section('title', 'Operational Monitoring Dashboard')
@section('page_title', 'Operational Monitoring Board')

@section('content')
<div class="flex flex-col gap-6">

  <!-- TOP KPI METRIC CARDS -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    <!-- Available -->
    <div class="p-3.5 bg-emerald-50 border-2 border-on-surface neo-shadow flex flex-col justify-between">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-emerald-900 tracking-wider">Tersedia</span>
        <span class="material-symbols-outlined text-xl text-emerald-700">check_circle</span>
      </div>
      <div class="mt-2">
        <span class="text-2xl font-headline-lg font-black text-emerald-950" id="kpi-available">{{ $availableUnits }}</span>
        <span class="text-xs font-label-sm text-emerald-800 font-bold">/ {{ $totalUnits }} Unit</span>
      </div>
    </div>

    <!-- Playing -->
    <div class="p-3.5 bg-blue-50 border-2 border-on-surface neo-shadow flex flex-col justify-between">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-blue-900 tracking-wider">Sedang Main</span>
        <span class="material-symbols-outlined text-xl text-primary">sports_esports</span>
      </div>
      <div class="mt-2">
        <span class="text-2xl font-headline-lg font-black text-blue-950" id="kpi-playing">{{ $playingUnits }}</span>
        <span class="text-xs font-label-sm text-blue-800 font-bold">Meja Aktif</span>
      </div>
    </div>

    <!-- Almost Finished (<10m) -->
    <div class="p-3.5 bg-amber-50 border-2 border-on-surface neo-shadow flex flex-col justify-between">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-amber-900 tracking-wider">Hampir Habis</span>
        <span class="material-symbols-outlined text-xl text-amber-600">timelapse</span>
      </div>
      <div class="mt-2">
        <span class="text-2xl font-headline-lg font-black text-amber-950" id="kpi-almost">{{ $almostFinishedUnits }}</span>
        <span class="text-xs font-label-sm text-amber-800 font-bold">&lt; 10 Menit</span>
      </div>
    </div>

    <!-- Time Up / Expired -->
    <div class="p-3.5 bg-red-50 border-2 border-on-surface neo-shadow flex flex-col justify-between">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-red-900 tracking-wider">Waktu Habis</span>
        <span class="material-symbols-outlined text-xl text-error">alarm</span>
      </div>
      <div class="mt-2">
        <span class="text-2xl font-headline-lg font-black text-red-950" id="kpi-timeup">{{ $timeUpUnits }}</span>
        <span class="text-xs font-label-sm text-red-800 font-bold">Perlu Checkout</span>
      </div>
    </div>

    <!-- Customer Requests -->
    <div class="p-3.5 bg-orange-50 border-2 border-on-surface neo-shadow flex flex-col justify-between">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-orange-900 tracking-wider">Request Masuk</span>
        <span class="material-symbols-outlined text-xl text-secondary-container">receipt_long</span>
      </div>
      <div class="mt-2">
        <span class="text-2xl font-headline-lg font-black text-orange-950" id="kpi-requests">{{ $pendingRequestsCount }}</span>
        <span class="text-xs font-label-sm text-orange-800 font-bold">Pending</span>
      </div>
    </div>

    <!-- Shift Revenue Quick -->
    <div class="p-3.5 bg-primary-fixed border-2 border-on-surface neo-shadow flex flex-col justify-between">
      <div class="flex items-center justify-between">
        <span class="text-[11px] font-headline-sm uppercase font-bold text-primary tracking-wider">Omset Shift</span>
        <span class="material-symbols-outlined text-xl text-primary">payments</span>
      </div>
      <div class="mt-2">
        <span class="text-base font-headline-md font-black text-on-surface">Rp {{ number_format($activeShift->total_revenue ?? 0, 0, ',', '.') }}</span>
        <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block">{{ $activeShift->transactions_count ?? 0 }} Transaksi</span>
      </div>
    </div>
  </div>

  <!-- MAIN MONITORING GRID OF PLAYSTATION UNITS -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5" id="unit-cards-container">
    @foreach ($tvs as $tv)
      @php
        $activeSession = $tv->playSessions->first();
        $isPrepaid = $activeSession && $activeSession->billing_type === 'prepaid';
        $isPostpaid = $activeSession && $activeSession->billing_type === 'postpaid';

        $isTimeUp = false;
        $isAlmostFinished = false;
        $remainingSeconds = 0;
        $elapsedSeconds = 0;

        if ($activeSession) {
          $elapsedSeconds = $activeSession->start_time->diffInSeconds(now());
          if ($isPrepaid && $activeSession->end_time) {
            if ($activeSession->end_time->isPast()) {
              $isTimeUp = true;
            } else {
              $remainingSeconds = now()->diffInSeconds($activeSession->end_time, false);
              if ($remainingSeconds <= 600) {
                $isAlmostFinished = true;
              }
            }
          }
        }

        $cardClass = 'bg-surface-container-lowest border-2 border-on-surface neo-shadow';
        if ($tv->status === 'available') {
          $cardClass = 'bg-surface-container-lowest border-2 border-on-surface neo-shadow hover:border-primary';
        } elseif ($isTimeUp || $tv->is_buzzer_on) {
          $cardClass = 'bg-red-50 border-2 border-error alarm-card';
        } elseif ($isAlmostFinished) {
          $cardClass = 'bg-amber-50 border-2 border-secondary-container warning-card';
        } elseif ($tv->status === 'playing') {
          $cardClass = 'bg-blue-50/40 border-2 border-on-surface neo-shadow';
        } elseif ($tv->status === 'maintenance') {
          $cardClass = 'bg-gray-100 border-2 border-dashed border-outline opacity-75';
        }
      @endphp

      <div class="p-5 flex flex-col justify-between relative transition-all {{ $cardClass }}" id="unit-card-{{ $tv->id }}" data-tv-id="{{ $tv->id }}" data-status="{{ $tv->status }}">

        <!-- Card Header -->
        <div class="flex items-start justify-between pb-3 border-b-2 border-on-surface">
          <div>
            <div class="flex items-center gap-2">
              <h3 class="font-headline-lg font-black text-lg uppercase tracking-tight text-on-surface leading-none">
                {{ $tv->name }}
              </h3>
              @if ($tv->customerRequests->count() > 0)
                <span class="px-1.5 py-0.5 bg-secondary-container text-on-secondary font-label-sm text-[10px] font-bold border border-on-surface animate-bounce" title="Permintaan customer baru">
                  REQ ({{ $tv->customerRequests->count() }})
                </span>
              @endif
            </div>
            <p class="text-xs font-label-sm text-on-surface-variant font-bold mt-1 uppercase">
              {{ strtoupper($tv->type) }} • Rp {{ number_format($tv->price_per_hour, 0, ',', '.') }}/jam
            </p>
          </div>

          <!-- Status Badge -->
          <div>
            @if ($tv->status === 'available')
              <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 border-2 border-on-surface font-headline-sm text-xs font-black uppercase tracking-wider neo-shadow-sm">
                TERSEDIA
              </span>
            @elseif ($isTimeUp)
              <span class="px-2.5 py-1 bg-error text-on-error border-2 border-on-surface font-headline-sm text-xs font-black uppercase tracking-wider neo-shadow-sm animate-pulse">
                WAKTU HABIS
              </span>
            @elseif ($isAlmostFinished)
              <span class="px-2.5 py-1 bg-secondary-container text-on-secondary border-2 border-on-surface font-headline-sm text-xs font-black uppercase tracking-wider neo-shadow-sm">
                &lt; 10 MENIT
              </span>
            @elseif ($tv->status === 'playing')
              <span class="px-2.5 py-1 bg-primary text-on-primary border-2 border-on-surface font-headline-sm text-xs font-black uppercase tracking-wider neo-shadow-sm">
                {{ $isPrepaid ? 'PREPAID' : 'POSTPAID' }}
              </span>
            @else
              <span class="px-2 py-1 bg-gray-200 text-gray-700 border border-outline font-headline-sm text-xs font-bold uppercase">
                MAINTENANCE
              </span>
            @endif
          </div>
        </div>

        <!-- Card Body / Live Timer Area -->
        <div class="py-4 my-auto">
          @if ($tv->status === 'available')
            <div class="py-6 text-center">
              <p class="font-headline-md text-sm text-on-surface-variant uppercase font-bold">Unit Kosong</p>
              <p class="font-label-sm text-xs text-on-surface-variant mt-1">Siap untuk sesi bermain baru</p>
            </div>

          @elseif ($tv->status === 'playing' && $activeSession)
            <div class="flex flex-col gap-2">
              <!-- Live Timer Display -->
              <div class="text-center py-2 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm">
                <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">
                  {{ $isPrepaid ? 'SISA WAKTU BERMAIN' : 'DURASI BERJALAN (POSTPAID)' }}
                </span>
                <div class="text-3xl font-timer-display font-black tracking-tight {{ $isTimeUp ? 'text-error animate-pulse' : ($isAlmostFinished ? 'text-secondary-container' : 'text-on-surface') }}" id="timer-tv-{{ $tv->id }}" data-type="{{ $activeSession->billing_type }}" data-end="{{ $activeSession->end_time ? $activeSession->end_time->timestamp : '' }}" data-start="{{ $activeSession->start_time->timestamp }}">
                  --:--:--
                </div>
              </div>

              <!-- Session Details -->
              <div class="grid grid-cols-2 gap-2 text-xs font-label-sm mt-1">
                <div class="p-1.5 bg-surface border border-on-surface">
                  <span class="text-[10px] text-on-surface-variant block font-bold">MULAI - SELESAI</span>
                  <span class="font-bold text-on-surface">
                    {{ $activeSession->start_time->format('H:i') }} - {{ $activeSession->end_time ? $activeSession->end_time->format('H:i') : 'Loss' }}
                  </span>
                </div>
                <div class="p-1.5 bg-surface border border-on-surface">
                  <span class="text-[10px] text-on-surface-variant block font-bold">TOTAL TAGIHAN</span>
                  <span class="font-bold text-primary" id="total-tv-{{ $tv->id }}">
                    Rp {{ number_format($activeSession->total_amount, 0, ',', '.') }}
                  </span>
                </div>
              </div>

              <!-- F&B Summary if any -->
              @if ($activeSession->sessionOrders->count() > 0)
                <div class="p-2 bg-surface-container border border-on-surface text-[11px] font-body-md flex items-center justify-between">
                  <span class="text-on-surface-variant font-bold">🍱 Pesanan F&B ({{ $activeSession->sessionOrders->sum('quantity') }} item)</span>
                  <span class="font-headline-sm font-bold">Rp {{ number_format($activeSession->fnb_amount, 0, ',', '.') }}</span>
                </div>
              @endif

              <!-- Buzzer State Indicator -->
              @if ($tv->is_buzzer_on)
                <div class="p-2 bg-error text-on-error font-headline-sm text-xs font-bold uppercase flex items-center justify-between border border-on-surface animate-pulse">
                  <span>🚨 ALARM BUZZER AKTIF!</span>
                  <form method="POST" action="{{ route('admin.rental.toggle-buzzer', $tv->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2 py-0.5 bg-white text-error font-bold text-[10px] border border-on-surface neo-shadow-sm">
                      MATIKAN
                    </button>
                  </form>
                </div>
              @endif
            </div>

          @else
            <div class="py-6 text-center text-on-surface-variant">
              <span class="material-symbols-outlined text-3xl">build</span>
              <p class="font-headline-sm text-xs font-bold uppercase mt-1">Sedang Maintenance</p>
            </div>
          @endif
        </div>

        <!-- Card Footer / Actions -->
        <div class="pt-3 border-t-2 border-on-surface">
          @if ($tv->status === 'available')
            <button onclick="openStartModal({{ $tv->id }}, '{{ $tv->name }}', {{ $tv->price_per_hour }})" class="w-full py-2.5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container flex items-center justify-center gap-1.5">
              <span class="material-symbols-outlined text-lg">play_arrow</span>
              <span>MULAI RENTAL</span>
            </button>
          @elseif ($tv->status === 'playing' && $activeSession)
            <div class="flex flex-col gap-2">
              <!-- Quick Action Row -->
              <div class="grid grid-cols-2 gap-2">
                <button onclick="openExtendModal({{ $activeSession->id }}, '{{ $tv->name }}', {{ $tv->price_per_hour }}, '{{ $activeSession->end_time ? $activeSession->end_time->format('H:i') : '' }}')" class="py-1.5 px-2 bg-secondary-container text-on-secondary font-headline-sm text-[11px] uppercase font-bold border-2 border-on-surface neo-shadow-sm btn-press hover:bg-secondary flex items-center justify-center gap-1">
                  <span class="material-symbols-outlined text-sm">more_time</span>
                  <span>+ WAKTU</span>
                </button>
                <button onclick="openAddFnbModal({{ $activeSession->id }}, '{{ $tv->name }}')" class="py-1.5 px-2 bg-surface border-2 border-on-surface font-headline-sm text-[11px] uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high flex items-center justify-center gap-1">
                  <span class="material-symbols-outlined text-sm">restaurant</span>
                  <span>+ F&B</span>
                </button>
              </div>

              <!-- Checkout CTA -->
              <button onclick="openCheckoutModal({{ $activeSession->id }}, '{{ $tv->name }}', '{{ $activeSession->billing_type }}', {{ $tv->price_per_hour }}, {{ $activeSession->rental_amount }}, {{ $activeSession->fnb_amount }}, {{ $activeSession->total_amount }}, '{{ $activeSession->start_time->format('H:i') }}', '{{ $activeSession->end_time ? $activeSession->end_time->format('H:i') : 'Loss' }}')" class="w-full py-2 bg-emerald-600 text-white font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface neo-shadow btn-press hover:bg-emerald-700 flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-base">point_of_sale</span>
                <span>SELESAI / CHECKOUT</span>
              </button>
            </div>
          @else
            <form method="POST" action="{{ route('admin.units.toggle-status', $tv->id) }}">
              @csrf
              <button type="submit" class="w-full py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
                AKTIFKAN UNIT
              </button>
            </form>
          @endif
        </div>

      </div>
    @endforeach
  </div>

</div>

<!-- ================= MODALS ================= -->

<!-- 1. START RENTAL MODAL -->
<div id="modal-start" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-lg bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-primary">play_circle</span>
        <h3 class="font-headline-lg font-black uppercase text-lg" id="start-modal-title">Mulai Rental — PS 01</h3>
      </div>
      <button onclick="closeModal('modal-start')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form method="POST" action="{{ route('admin.rental.start') }}" class="flex flex-col gap-4">
      @csrf
      <input type="hidden" name="tv_id" id="start-tv-id">

      <!-- Billing Mode Tabs -->
      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1.5">Pilih Tipe Billing</label>
        <div class="grid grid-cols-2 gap-2">
          <button type="button" onclick="setBillingType('prepaid')" id="btn-billing-prepaid" class="py-2.5 px-3 border-2 border-on-surface font-headline-sm text-xs font-bold uppercase tracking-wider bg-primary text-on-primary neo-shadow btn-press">
            ⏱️ PREPAID (WAKTU)
          </button>
          <button type="button" onclick="setBillingType('postpaid')" id="btn-billing-postpaid" class="py-2.5 px-3 border-2 border-on-surface font-headline-sm text-xs font-bold uppercase tracking-wider bg-surface hover:bg-surface-container-high neo-shadow btn-press">
            ⚡ POSTPAID (LOSS)
          </button>
        </div>
        <input type="hidden" name="billing_type" id="start-billing-type" value="prepaid">
      </div>

      <!-- Prepaid Duration Selector -->
      <div id="prepaid-options" class="flex flex-col gap-3">
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider">Pilih Durasi Jam</label>
        <div class="grid grid-cols-4 gap-2">
          <button type="button" onclick="setDuration(1)" class="duration-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-primary-fixed" data-hours="1">1 Jam</button>
          <button type="button" onclick="setDuration(2)" class="duration-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-primary-fixed" data-hours="2">2 Jam</button>
          <button type="button" onclick="setDuration(3)" class="duration-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-primary-fixed" data-hours="3">3 Jam</button>
          <button type="button" onclick="setDuration(5)" class="duration-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-primary-fixed" data-hours="5">5 Jam</button>
        </div>

        <div class="flex items-center gap-2">
          <label class="font-headline-sm text-xs font-bold uppercase whitespace-nowrap">Durasi Kustom (Jam):</label>
          <input type="number" step="0.5" min="0.5" max="24" name="duration_hours" id="start-duration-input" value="1" oninput="calculateStartCost()" class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface font-body-md text-sm neo-shadow-sm">
        </div>
      </div>

      <!-- Postpaid Info -->
      <div id="postpaid-options" class="p-3.5 bg-blue-50 border-2 border-on-surface text-xs font-body-md hidden">
        <p class="font-bold text-blue-950 uppercase font-headline-sm">Mode Postpaid / Open Time</p>
        <p class="text-blue-900 mt-1">Timer akan menghitung naik (elapsed time). Total biaya dihitung saat rental dihentikan berdasarkan tarif per jam.</p>
      </div>

      <!-- Cost Estimation Box -->
      <div class="p-3 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex items-center justify-between">
        <div>
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Estimasi Biaya Rental</span>
          <span class="font-headline-lg font-black text-xl text-primary" id="start-estimated-cost">Rp 0</span>
        </div>
        <div class="text-right">
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Tarif Meja</span>
          <span class="font-label-sm text-xs font-bold text-on-surface" id="start-rate-display">Rp 0 / jam</span>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface mt-2">
        <button type="button" onclick="closeModal('modal-start')" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2 px-5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container flex items-center gap-1.5">
          <span class="material-symbols-outlined text-lg">check</span>
          <span>Mulai Rental Sekarang</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 2. EXTEND TIME MODAL -->
<div id="modal-extend" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-secondary">more_time</span>
        <h3 class="font-headline-lg font-black uppercase text-lg" id="extend-modal-title">Tambah Waktu</h3>
      </div>
      <button onclick="closeModal('modal-extend')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form id="extend-form" method="POST" action="" class="flex flex-col gap-4">
      @csrf

      <div class="p-3 bg-surface border border-on-surface text-xs font-label-sm flex items-center justify-between">
        <span class="text-on-surface-variant font-bold">Waktu Selesai Saat Ini:</span>
        <span class="font-bold text-on-surface text-sm" id="extend-current-end">--:--</span>
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-2">Pilih Durasi Tambahan</label>
        <div class="grid grid-cols-3 gap-2">
          <button type="button" onclick="setExtendDuration(0.5, 30)" class="extend-quick-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-secondary-fixed">+30 Menit</button>
          <button type="button" onclick="setExtendDuration(1, 60)" class="extend-quick-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-secondary-container text-on-secondary" id="extend-btn-1h">+1 Jam</button>
          <button type="button" onclick="setExtendDuration(2, 120)" class="extend-quick-btn py-2 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-secondary-fixed">+2 Jam</button>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <label class="font-headline-sm text-xs font-bold uppercase whitespace-nowrap">Kustom Tambahan (Jam):</label>
        <input type="number" step="0.5" min="0.25" max="12" name="added_hours" id="extend-hours-input" value="1" oninput="calculateExtendCost()" class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface font-body-md text-sm neo-shadow-sm">
      </div>

      <div class="p-3 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex items-center justify-between">
        <span class="text-xs font-headline-sm uppercase font-bold text-on-surface-variant">Tambahan Biaya:</span>
        <span class="font-headline-lg font-black text-lg text-secondary" id="extend-estimated-cost">Rp 0</span>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface mt-2">
        <button type="button" onclick="closeModal('modal-extend')" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2 px-5 bg-secondary-container text-on-secondary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-secondary">
          Konfirmasi Tambah Waktu
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 3. ADD F&B MODAL -->
<div id="modal-add-fnb" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-2xl bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative max-h-[90vh] flex flex-col justify-between animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-primary">restaurant</span>
        <h3 class="font-headline-lg font-black uppercase text-lg" id="fnb-modal-title">Pesan Makanan & Minuman</h3>
      </div>
      <button onclick="closeModal('modal-add-fnb')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form id="fnb-form" method="POST" action="" class="flex-1 overflow-y-auto flex flex-col gap-4 pr-1">
      @csrf

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-72 overflow-y-auto p-1">
        @foreach ($products as $product)
          <div class="p-3 bg-surface border-2 border-on-surface neo-shadow-sm flex items-center justify-between gap-2">
            <div class="min-w-0">
              <span class="text-[10px] font-label-sm font-bold uppercase text-secondary px-1.5 py-0.5 bg-secondary-fixed border border-on-surface">
                {{ $product->category }}
              </span>
              <p class="font-headline-sm text-xs font-bold truncate mt-1 text-on-surface">{{ $product->name }}</p>
              <p class="font-label-sm text-xs text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>

            <!-- Qty Stepper -->
            <div class="flex items-center gap-1 border-2 border-on-surface bg-surface-container-lowest neo-shadow-sm">
              <button type="button" onclick="adjustFnbQty({{ $product->id }}, -1)" class="w-6 h-6 flex items-center justify-center font-bold text-xs hover:bg-surface-container-high">-</button>
              <input type="number" name="items[{{ $loop->index }}][quantity]" id="fnb-qty-{{ $product->id }}" value="0" min="0" max="{{ $product->stock }}" class="w-8 text-center text-xs font-bold border-none p-0 focus:ring-0" readonly>
              <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
              <button type="button" onclick="adjustFnbQty({{ $product->id }}, 1)" class="w-6 h-6 flex items-center justify-center font-bold text-xs hover:bg-surface-container-high">+</button>
            </div>
          </div>
        @endforeach
      </div>

      <div class="p-3.5 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex items-center justify-between mt-auto">
        <span class="font-headline-sm text-xs uppercase font-bold">Total Tambahan F&B:</span>
        <span class="font-headline-lg font-black text-xl text-primary" id="fnb-total-preview">Rp 0</span>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface">
        <button type="button" onclick="closeModal('modal-add-fnb')" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2 px-5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container">
          Tambahkan ke Tagihan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 4. CHECKOUT / FINAL PAYMENT MODAL -->
<div id="modal-checkout" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-lg bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-emerald-600">receipt</span>
        <h3 class="font-headline-lg font-black uppercase text-lg" id="checkout-modal-title">Checkout & Pembayaran</h3>
      </div>
      <button onclick="closeModal('modal-checkout')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form id="checkout-form" method="POST" action="" class="flex flex-col gap-4">
      @csrf

      <!-- Session Billing Summary Table -->
      <div class="border-2 border-on-surface p-4 bg-surface neo-shadow-sm flex flex-col gap-2.5 text-xs font-label-sm">
        <div class="flex justify-between pb-2 border-b border-on-surface-variant/30">
          <span class="text-on-surface-variant font-bold uppercase">Waktu Bermain</span>
          <span class="font-bold text-on-surface" id="checkout-duration-display">--:-- s/d --:--</span>
        </div>
        <div class="flex justify-between pb-2 border-b border-on-surface-variant/30">
          <span class="text-on-surface-variant font-bold uppercase">Biaya Rental PS</span>
          <span class="font-bold text-on-surface" id="checkout-rental-amount">Rp 0</span>
        </div>
        <div class="flex justify-between pb-2 border-b border-on-surface-variant/30">
          <span class="text-on-surface-variant font-bold uppercase">Biaya Makanan & Minuman</span>
          <span class="font-bold text-on-surface" id="checkout-fnb-amount">Rp 0</span>
        </div>

        <div class="flex justify-between pt-1 text-sm font-headline-lg font-black text-emerald-950">
          <span class="uppercase">TOTAL HARUS DIBAYAR</span>
          <span class="text-xl font-black text-emerald-700" id="checkout-grand-total">Rp 0</span>
        </div>
      </div>

      <!-- Payment Method -->
      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-2">Metode Pembayaran</label>
        <div class="grid grid-cols-4 gap-2">
          <label class="flex items-center justify-center p-2.5 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase cursor-pointer hover:bg-emerald-50 has-[:checked]:bg-emerald-600 has-[:checked]:text-white neo-shadow-sm">
            <input type="radio" name="payment_method" value="cash" checked class="hidden">
            <span>💵 Tunai</span>
          </label>
          <label class="flex items-center justify-center p-2.5 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase cursor-pointer hover:bg-emerald-50 has-[:checked]:bg-emerald-600 has-[:checked]:text-white neo-shadow-sm">
            <input type="radio" name="payment_method" value="qris" class="hidden">
            <span>📱 QRIS</span>
          </label>
          <label class="flex items-center justify-center p-2.5 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase cursor-pointer hover:bg-emerald-50 has-[:checked]:bg-emerald-600 has-[:checked]:text-white neo-shadow-sm">
            <input type="radio" name="payment_method" value="transfer" class="hidden">
            <span>💳 Transfer</span>
          </label>
          <label class="flex items-center justify-center p-2.5 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase cursor-pointer hover:bg-emerald-50 has-[:checked]:bg-emerald-600 has-[:checked]:text-white neo-shadow-sm">
            <input type="radio" name="payment_method" value="other" class="hidden">
            <span>Lainnya</span>
          </label>
        </div>
      </div>

      <!-- Notes -->
      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Catatan Transaksi (Opsional)</label>
        <input type="text" name="notes" placeholder="Misal: Uang pas, pelanggan setia" class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface mt-2">
        <button type="button" onclick="closeModal('modal-checkout')" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2.5 px-6 bg-emerald-600 text-white font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-emerald-700 flex items-center gap-1.5">
          <span class="material-symbols-outlined text-lg">check_circle</span>
          <span>SELESAIKAN PEMBAYARAN LUNAS</span>
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Product pricing reference for JS calculations
  const productPrices = {
    @foreach ($products as $p)
      {{ $p->id }}: {{ $p->price }},
    @endforeach
  };

  let currentTvRate = 10000;
  let currentExtendRate = 10000;

  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }

  // 1. Start Modal Handlers
  window.openStartModal = function(tvId = null, tvName = 'Pilih Unit', rate = 12000) {
    const modal = document.getElementById('modal-start');
    if (!modal) return;

    if (!tvId) {
      // Pick first available TV
      const firstAvail = document.querySelector('[data-status="available"]');
      if (firstAvail) {
        tvId = firstAvail.getAttribute('data-tv-id');
        tvName = firstAvail.querySelector('h3').innerText.trim();
      }
    }

    document.getElementById('start-tv-id').value = tvId || 1;
    document.getElementById('start-modal-title').innerText = 'Mulai Rental — ' + tvName;
    currentTvRate = rate;
    document.getElementById('start-rate-display').innerText = 'Rp ' + rate.toLocaleString('id-ID') + ' / jam';

    setBillingType('prepaid');
    setDuration(1);
    modal.classList.remove('hidden');
  };

  function setBillingType(type) {
    document.getElementById('start-billing-type').value = type;
    const btnPre = document.getElementById('btn-billing-prepaid');
    const btnPost = document.getElementById('btn-billing-postpaid');
    const prepOpts = document.getElementById('prepaid-options');
    const postOpts = document.getElementById('postpaid-options');

    if (type === 'prepaid') {
      btnPre.className = 'py-2.5 px-3 border-2 border-on-surface font-headline-sm text-xs font-bold uppercase tracking-wider bg-primary text-on-primary neo-shadow btn-press';
      btnPost.className = 'py-2.5 px-3 border-2 border-on-surface font-headline-sm text-xs font-bold uppercase tracking-wider bg-surface hover:bg-surface-container-high neo-shadow btn-press';
      prepOpts.classList.remove('hidden');
      postOpts.classList.add('hidden');
      calculateStartCost();
    } else {
      btnPost.className = 'py-2.5 px-3 border-2 border-on-surface font-headline-sm text-xs font-bold uppercase tracking-wider bg-primary text-on-primary neo-shadow btn-press';
      btnPre.className = 'py-2.5 px-3 border-2 border-on-surface font-headline-sm text-xs font-bold uppercase tracking-wider bg-surface hover:bg-surface-container-high neo-shadow btn-press';
      prepOpts.classList.add('hidden');
      postOpts.classList.remove('hidden');
      document.getElementById('start-estimated-cost').innerText = 'Dihitung saat selesai';
    }
  }

  function setDuration(hours) {
    document.getElementById('start-duration-input').value = hours;
    document.querySelectorAll('.duration-btn').forEach(btn => {
      if (parseFloat(btn.getAttribute('data-hours')) === hours) {
        btn.classList.add('bg-primary-fixed');
        btn.classList.remove('bg-surface');
      } else {
        btn.classList.remove('bg-primary-fixed');
        btn.classList.add('bg-surface');
      }
    });
    calculateStartCost();
  }

  function calculateStartCost() {
    if (document.getElementById('start-billing-type').value === 'postpaid') return;
    const hours = parseFloat(document.getElementById('start-duration-input').value) || 0;
    const total = Math.round(hours * currentTvRate);
    document.getElementById('start-estimated-cost').innerText = 'Rp ' + total.toLocaleString('id-ID');
  }

  // 2. Extend Modal Handlers
  function openExtendModal(sessionId, tvName, rate, currentEnd) {
    currentExtendRate = rate;
    document.getElementById('extend-modal-title').innerText = 'Tambah Waktu — ' + tvName;
    document.getElementById('extend-current-end').innerText = currentEnd || 'Sedang Main';
    document.getElementById('extend-form').action = '/admin/rental/' + sessionId + '/extend';
    setExtendDuration(1, 60);
    document.getElementById('modal-extend').classList.remove('hidden');
  }

  function setExtendDuration(hours, minutes) {
    document.getElementById('extend-hours-input').value = hours;
    calculateExtendCost();
  }

  function calculateExtendCost() {
    const hours = parseFloat(document.getElementById('extend-hours-input').value) || 0;
    const cost = Math.round(hours * currentExtendRate);
    document.getElementById('extend-estimated-cost').innerText = 'Rp ' + cost.toLocaleString('id-ID');
  }

  // 3. F&B Modal Handlers
  function openAddFnbModal(sessionId, tvName) {
    document.getElementById('fnb-modal-title').innerText = 'Tambah F&B — ' + tvName;
    document.getElementById('fnb-form').action = '/admin/rental/' + sessionId + '/add-fnb';
    // Reset inputs
    document.querySelectorAll('[id^="fnb-qty-"]').forEach(input => input.value = 0);
    updateFnbTotalPreview();
    document.getElementById('modal-add-fnb').classList.remove('hidden');
  }

  function adjustFnbQty(productId, change) {
    const input = document.getElementById('fnb-qty-' + productId);
    if (!input) return;
    let qty = parseInt(input.value) || 0;
    qty = Math.max(0, qty + change);
    input.value = qty;
    updateFnbTotalPreview();
  }

  function updateFnbTotalPreview() {
    let total = 0;
    document.querySelectorAll('[id^="fnb-qty-"]').forEach(input => {
      const productId = input.id.replace('fnb-qty-', '');
      const qty = parseInt(input.value) || 0;
      const price = productPrices[productId] || 0;
      total += (qty * price);
    });
    document.getElementById('fnb-total-preview').innerText = 'Rp ' + total.toLocaleString('id-ID');
  }

  // 4. Checkout Modal Handlers
  function openCheckoutModal(sessionId, tvName, billingType, rate, rentalAmt, fnbAmt, totalAmt, startFormatted, endFormatted) {
    document.getElementById('checkout-modal-title').innerText = 'Checkout — ' + tvName;
    document.getElementById('checkout-form').action = '/admin/rental/' + sessionId + '/checkout';
    document.getElementById('checkout-duration-display').innerText = startFormatted + ' s/d ' + (endFormatted || 'Sekarang');
    document.getElementById('checkout-rental-amount').innerText = 'Rp ' + rentalAmt.toLocaleString('id-ID');
    document.getElementById('checkout-fnb-amount').innerText = 'Rp ' + fnbAmt.toLocaleString('id-ID');
    document.getElementById('checkout-grand-total').innerText = 'Rp ' + totalAmt.toLocaleString('id-ID');
    document.getElementById('modal-checkout').classList.remove('hidden');
  }

  // ================= REAL-TIME TIMER TICK & LIVE SYNC =================
  function formatSeconds(totalSeconds) {
    if (totalSeconds < 0) totalSeconds = 0;
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = Math.floor(totalSeconds % 60);
    return [hours, minutes, seconds].map(v => v < 10 ? '0' + v : v).join(':');
  }

  // Local ticker every 1 second
  setInterval(() => {
    const nowUnix = Math.floor(Date.now() / 1000);
    document.querySelectorAll('[id^="timer-tv-"]').forEach(el => {
      const type = el.getAttribute('data-type');
      const startUnix = parseInt(el.getAttribute('data-start')) || 0;
      const endUnix = parseInt(el.getAttribute('data-end')) || 0;

      if (type === 'prepaid' && endUnix > 0) {
        const remaining = endUnix - nowUnix;
        if (remaining <= 0) {
          el.innerText = '00:00:00';
          el.classList.add('text-error', 'animate-pulse');
        } else {
          el.innerText = formatSeconds(remaining);
          if (remaining <= 600) {
            el.classList.add('text-secondary-container');
          }
        }
      } else if (type === 'postpaid' && startUnix > 0) {
        const elapsed = nowUnix - startUnix;
        el.innerText = formatSeconds(elapsed);
      }
    });
  }, 1000);

  // Background Database Poller every 3 seconds
  async function pollDashboardStatus() {
    try {
      const res = await fetch('{{ route("admin.dashboard.api-status") }}');
      if (!res.ok) return;
      const data = await res.json();

      if (data.success) {
        // Update KPIs
        document.getElementById('kpi-available').innerText = data.summary.available;
        document.getElementById('kpi-playing').innerText = data.summary.playing;
        document.getElementById('kpi-almost').innerText = data.summary.almost_finished;
        document.getElementById('kpi-timeup').innerText = data.summary.time_up;
        document.getElementById('kpi-requests').innerText = data.summary.pending_requests_count;

        // Update top badges
        const topBadge = document.getElementById('top-notif-badge');
        const navBadge = document.getElementById('nav-requests-badge');
        if (data.summary.pending_requests_count > 0) {
          if (topBadge) { topBadge.innerText = data.summary.pending_requests_count; topBadge.classList.remove('hidden'); }
          if (navBadge) { navBadge.innerText = data.summary.pending_requests_count; navBadge.classList.remove('hidden'); }
        } else {
          if (topBadge) topBadge.classList.add('hidden');
          if (navBadge) navBadge.classList.add('hidden');
        }

        // Check if any unit is in critical alarm buzzer state
        let hasAlarm = false;
        data.tvs.forEach(tv => {
          if (tv.is_buzzer_on || (tv.active_session && tv.active_session.is_time_up)) {
            hasAlarm = true;
          }
        });
        if (hasAlarm) {
          playAlarmBeep();
        }

        // Update Notification Slide-Over Drawer
        const notifList = document.getElementById('drawer-notifications-list');
        if (notifList && data.pending_requests) {
          if (data.pending_requests.length === 0) {
            notifList.innerHTML = '<p class="text-xs text-on-surface-variant font-bold text-center py-6">Tidak ada request pending saat ini.</p>';
          } else {
            let html = '';
            data.pending_requests.forEach(req => {
              const isTime = req.type === 'add_time';
              html += `
                <div class="p-3 bg-surface border-2 border-on-surface neo-shadow-sm flex flex-col gap-1.5">
                  <div class="flex items-center justify-between">
                    <span class="font-headline-sm text-xs font-bold uppercase text-primary">${req.tv_name}</span>
                    <span class="text-[10px] font-label-sm text-on-surface-variant">${req.time_ago}</span>
                  </div>
                  <p class="font-body-md text-xs font-bold text-on-surface">
                    ${isTime ? '⏱️ Request Tambah Waktu (+' + (req.payload.duration_hours || 1) + ' Jam)' : '🍜 Pesanan Makanan & Minuman'}
                  </p>
                  <div class="flex gap-2 mt-1">
                    <form method="POST" action="/admin/requests/${req.id}/approve" class="flex-1">
                      @csrf
                      <button type="submit" class="w-full py-1 bg-primary text-on-primary text-[10px] font-headline-sm uppercase font-bold border border-on-surface">Setujui</button>
                    </form>
                    <form method="POST" action="/admin/requests/${req.id}/reject" class="flex-1">
                      @csrf
                      <button type="submit" class="w-full py-1 bg-surface text-error text-[10px] font-headline-sm uppercase font-bold border border-on-surface">Tolak</button>
                    </form>
                  </div>
                </div>
              `;
            });
            notifList.innerHTML = html;
          }
        }
      }
    } catch (e) {
      console.log('Polling sync error:', e);
    }
  }

  // Poll immediately and every 3 seconds
  pollDashboardStatus();
  setInterval(pollDashboardStatus, 3000);
</script>
@endpush
