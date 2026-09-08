@extends('layouts.customer')

@section('title', 'Self-Service ' . $tv->name)

@section('content')
<div class="flex flex-col gap-5">

  <!-- HERO UNIT CARD -->
  <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col gap-4">
    
    <!-- Meja Header -->
    <div class="flex items-start justify-between pb-3 border-b-2 border-on-surface">
      <div>
        <span class="text-[10px] font-label-sm font-bold uppercase px-1.5 py-0.5 bg-secondary-fixed text-secondary border border-on-surface">
          {{ strtoupper($tv->type) }}
        </span>
        <h2 class="text-2xl font-headline-lg font-black uppercase text-on-surface mt-1 leading-none">
          {{ $tv->name }}
        </h2>
        <p class="text-xs font-label-sm text-on-surface-variant font-bold mt-1">
          Tarif: Rp {{ number_format($tv->price_per_hour, 0, ',', '.') }} / jam
        </p>
      </div>

      <div>
        @if ($activeSession)
          <span class="px-2.5 py-1 bg-emerald-100 text-emerald-950 font-headline-sm text-xs font-black uppercase border-2 border-on-surface neo-shadow-sm flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
            <span>AKTIF</span>
          </span>
        @else
          <span class="px-2 py-1 bg-surface border border-outline font-headline-sm text-xs font-bold uppercase text-on-surface-variant">
            KOSONG
          </span>
        @endif
      </div>
    </div>

    <!-- Live Timer Section -->
    @if ($activeSession)
      @php
        $isPrepaid = $activeSession->billing_type === 'prepaid';
        $isTimeUp = $isPrepaid && $activeSession->end_time && $activeSession->end_time->isPast();
      @endphp
      <div class="text-center py-4 bg-surface border-2 border-on-surface neo-shadow-sm flex flex-col items-center justify-center">
        <span class="text-[10px] font-label-sm font-bold uppercase text-on-surface-variant tracking-wider">
          {{ $isPrepaid ? 'SISA WAKTU BERMAIN' : 'DURASI BERJALAN (POSTPAID)' }}
        </span>
        <div class="text-4xl font-timer-display font-black tracking-tight my-1 {{ $isTimeUp ? 'text-error animate-pulse' : 'text-on-surface' }}" id="cust-timer" data-type="{{ $activeSession->billing_type }}" data-start="{{ $activeSession->start_time->timestamp }}" data-end="{{ $activeSession->end_time ? $activeSession->end_time->timestamp : '' }}">
          --:--:--
        </div>
        <div class="flex items-center gap-3 text-xs font-label-sm font-bold text-on-surface-variant mt-1">
          <span>Mulai: {{ $activeSession->start_time->format('H:i') }}</span>
          <span>•</span>
          <span>Selesai: {{ $activeSession->end_time ? $activeSession->end_time->format('H:i') : 'Loss' }}</span>
        </div>
      </div>

      <!-- Tagihan Saat Ini -->
      <div class="p-3 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex items-center justify-between">
        <div>
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Total Tagihan Sementara</span>
          <span class="font-headline-lg font-black text-lg text-primary" id="cust-total-bill">
            Rp {{ number_format($activeSession->total_amount, 0, ',', '.') }}
          </span>
        </div>
        <div class="text-right">
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Metode Sesi</span>
          <span class="font-label-sm text-xs font-bold uppercase text-on-surface">{{ $activeSession->billing_type }}</span>
        </div>
      </div>

    @else
      <div class="py-8 text-center bg-surface border-2 border-dashed border-on-surface/40">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-1">sports_esports</span>
        <h3 class="font-headline-md font-bold text-sm uppercase">Meja Belum Aktif</h3>
        <p class="text-xs text-on-surface-variant mt-1 px-4">Silakan pesan rental melalui kasir untuk mengaktifkan timer meja ini.</p>
      </div>
    @endif

  </div>

  <!-- LARGE TOUCH ACTION BUTTONS -->
  <div class="grid grid-cols-1 gap-3">
    <!-- 1. Request Extra Time -->
    <button type="button" onclick="openTimeDrawer()" class="p-4 bg-secondary-container text-on-secondary border-2 border-on-surface neo-shadow btn-press hover:bg-secondary flex items-center justify-between text-left transition">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white text-secondary border border-on-surface flex items-center justify-center neo-shadow-sm flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">more_time</span>
        </div>
        <div>
          <h3 class="font-headline-lg font-black text-base uppercase leading-tight">TAMBAH WAKTU</h3>
          <p class="text-[11px] font-body-md text-white/90">Request perpanjangan jam main ke kasir</p>
        </div>
      </div>
      <span class="material-symbols-outlined text-xl">arrow_forward</span>
    </button>

    <!-- 2. Order F&B -->
    <button type="button" onclick="openFoodDrawer()" class="p-4 bg-primary text-on-primary border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container flex items-center justify-between text-left transition">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white text-primary border border-on-surface flex items-center justify-center neo-shadow-sm flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">restaurant</span>
        </div>
        <div>
          <h3 class="font-headline-lg font-black text-base uppercase leading-tight">PESAN MAKANAN & MINUMAN</h3>
          <p class="text-[11px] font-body-md text-white/90">Pesan Indomie, snack & minuman diantar ke meja</p>
        </div>
      </div>
      <span class="material-symbols-outlined text-xl">arrow_forward</span>
    </button>
  </div>

  <!-- CUSTOMER REQUEST STATUS HISTORY -->
  <div class="flex flex-col gap-3">
    <div class="flex items-center justify-between">
      <h3 class="font-headline-lg font-black text-sm uppercase text-on-surface">Riwayat Permintaan Meja Ini</h3>
      <span class="text-[10px] font-label-sm font-bold text-on-surface-variant uppercase">Real-Time Sync</span>
    </div>

    <div id="customer-requests-container" class="flex flex-col gap-2.5">
      @forelse ($requests as $req)
        @php
          $isPending = $req->status === 'pending';
          $isApproved = $req->status === 'approved';
          $isTime = $req->type === 'add_time';
          $payload = $req->payload;
        @endphp

        <div class="p-3.5 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm flex items-start justify-between gap-2">
          <div class="min-w-0">
            <div class="flex items-center gap-1.5 mb-1">
              <span class="material-symbols-outlined text-base {{ $isTime ? 'text-secondary' : 'text-primary' }}">
                {{ $isTime ? 'more_time' : 'restaurant' }}
              </span>
              <span class="font-headline-sm text-xs font-bold uppercase">
                {{ $isTime ? 'Tambah Waktu' : 'Pesanan F&B' }}
              </span>
              <span class="text-[10px] font-label-sm text-on-surface-variant font-bold">• {{ $req->created_at->format('H:i') }}</span>
            </div>

            @if ($isTime)
              <p class="text-xs font-body-md text-on-surface">
                +{{ $payload['duration_hours'] ?? 1 }} Jam (Rp {{ number_format($payload['price'] ?? 0, 0, ',', '.') }})
              </p>
            @else
              <p class="text-xs font-body-md text-on-surface line-clamp-1">
                @if (!empty($payload['items']))
                  @foreach ($payload['items'] as $item)
                    {{ $item['quantity'] }}x {{ $item['name'] }}{{ !$loop->last ? ', ' : '' }}
                  @endforeach
                @endif
              </p>
            @endif
          </div>

          <!-- Status Badge -->
          <div>
            @if ($isPending)
              <span class="px-2 py-1 bg-secondary-container text-on-secondary font-headline-sm text-[10px] font-black uppercase border border-on-surface animate-pulse">
                MENUNGGU KASIR
              </span>
            @elseif ($isApproved)
              <span class="px-2 py-1 bg-emerald-100 text-emerald-900 font-headline-sm text-[10px] font-black uppercase border border-on-surface">
                ✓ DISETUJUI
              </span>
            @else
              <span class="px-2 py-1 bg-red-100 text-red-900 font-headline-sm text-[10px] font-black uppercase border border-on-surface">
                ✗ DITOLAK
              </span>
            @endif
          </div>
        </div>
      @empty
        <div class="p-6 bg-surface border-2 border-on-surface text-center">
          <p class="text-xs text-on-surface-variant font-bold uppercase">Belum ada request dari meja ini.</p>
        </div>
      @endforelse
    </div>
  </div>

</div>

<!-- ================= MOBILE DRAWERS ================= -->

<!-- 1. TIME REQUEST DRAWER -->
<div id="drawer-time" class="fixed inset-0 z-50 flex flex-col justify-end bg-on-surface/50 backdrop-blur-xs hidden">
  <div class="w-full max-w-md mx-auto bg-surface-container-lowest border-t-2 border-x-2 border-on-surface p-5 neo-shadow-lg relative animate-in slide-in-from-bottom duration-200">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-secondary">more_time</span>
        <h3 class="font-headline-lg font-black uppercase text-base">Request Tambah Waktu</h3>
      </div>
      <button onclick="closeDrawer('drawer-time')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form method="POST" action="{{ route('customer.add-time', $tv->id) }}" class="flex flex-col gap-4">
      @csrf

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-2">Pilih Durasi Tambahan</label>
        <div class="grid grid-cols-3 gap-2">
          <button type="button" onclick="setCustHours(0.5)" class="cust-hour-btn py-2.5 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-secondary-fixed" data-val="0.5">+30 Menit</button>
          <button type="button" onclick="setCustHours(1)" class="cust-hour-btn py-2.5 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-secondary-container text-on-secondary" data-val="1">+1 Jam</button>
          <button type="button" onclick="setCustHours(2)" class="cust-hour-btn py-2.5 border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm btn-press bg-surface hover:bg-secondary-fixed" data-val="2">+2 Jam</button>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <label class="font-headline-sm text-xs font-bold uppercase whitespace-nowrap">Durasi Lain (Jam):</label>
        <input type="number" step="0.5" min="0.5" max="12" name="duration_hours" id="cust-duration-input" value="1" oninput="calcCustCost()" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-sm neo-shadow-sm">
      </div>

      <div class="p-3 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex items-center justify-between">
        <span class="font-headline-sm text-xs uppercase font-bold">Biaya Tambahan:</span>
        <span class="font-headline-lg font-black text-lg text-secondary" id="cust-cost-preview">Rp {{ number_format($tv->price_per_hour, 0, ',', '.') }}</span>
      </div>

      <p class="text-[10px] text-on-surface-variant font-body-md">
        *Permintaan akan dikirimkan ke kasir untuk disetujui. Tagihan otomatis disesuaikan setelah kasir menyetujui.
      </p>

      <div class="flex gap-2 pt-2">
        <button type="button" onclick="closeDrawer('drawer-time')" class="w-1/3 py-2.5 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press">
          Batal
        </button>
        <button type="submit" class="w-2/3 py-2.5 bg-secondary-container text-on-secondary font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-secondary">
          Kirim Request ke Kasir
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 2. FOOD ORDER DRAWER -->
<div id="drawer-food" class="fixed inset-0 z-50 flex flex-col justify-end bg-on-surface/50 backdrop-blur-xs hidden">
  <div class="w-full max-w-md mx-auto bg-surface-container-lowest border-t-2 border-x-2 border-on-surface p-5 neo-shadow-lg relative max-h-[90vh] flex flex-col justify-between animate-in slide-in-from-bottom duration-200">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-3">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-primary">restaurant</span>
        <h3 class="font-headline-lg font-black uppercase text-base">Menu Makanan & Minuman</h3>
      </div>
      <button onclick="closeDrawer('drawer-food')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <!-- Product list -->
    <form id="cust-food-form" method="POST" action="{{ route('customer.order-food', $tv->id) }}" class="flex-1 overflow-y-auto flex flex-col gap-3 pr-1">
      @csrf

      <div class="flex flex-col gap-2.5 overflow-y-auto max-h-64 p-1">
        @foreach ($products as $p)
          <div class="p-3 bg-surface border-2 border-on-surface neo-shadow-sm flex items-center justify-between gap-2">
            <div class="min-w-0">
              <span class="text-[9px] font-label-sm font-bold uppercase px-1.5 py-0.5 border border-on-surface bg-secondary-fixed">
                {{ $p->category }}
              </span>
              <p class="font-headline-sm text-xs font-bold truncate mt-0.5">{{ $p->name }}</p>
              <p class="font-label-sm text-xs font-black text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
            </div>

            <!-- Stepper -->
            <div class="flex items-center gap-1 border-2 border-on-surface bg-surface-container-lowest neo-shadow-sm">
              <button type="button" onclick="adjustCustFoodQty({{ $p->id }}, -1)" class="w-7 h-7 flex items-center justify-center font-bold text-sm hover:bg-surface-container-high">-</button>
              <input type="number" name="items[{{ $loop->index }}][quantity]" id="cust-fnb-qty-{{ $p->id }}" value="0" min="0" max="{{ $p->stock }}" class="w-7 text-center text-xs font-bold border-none p-0 focus:ring-0" readonly>
              <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $p->id }}">
              <button type="button" onclick="adjustCustFoodQty({{ $p->id }}, 1)" class="w-7 h-7 flex items-center justify-center font-bold text-sm hover:bg-surface-container-high">+</button>
            </div>
          </div>
        @endforeach
      </div>

      <div>
        <label class="block font-headline-sm text-[11px] uppercase font-bold mb-1">Catatan Tambahan (Opsional)</label>
        <input type="text" name="note" placeholder="Misal: Es teh manis banget, mie matang" class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
      </div>

      <div class="p-3 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex items-center justify-between mt-auto">
        <span class="font-headline-sm text-xs uppercase font-bold">Total Pesanan:</span>
        <span class="font-headline-lg font-black text-lg text-primary" id="cust-food-total-preview">Rp 0</span>
      </div>

      <div class="flex gap-2 pt-2">
        <button type="button" onclick="closeDrawer('drawer-food')" class="w-1/3 py-2.5 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press">
          Batal
        </button>
        <button type="submit" id="btn-cust-food-submit" disabled class="w-2/3 py-2.5 bg-primary text-on-primary font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container disabled:opacity-50">
          Kirim Pesanan
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  const tvHourlyRate = {{ $tv->price_per_hour }};
  const custProductPrices = {
    @foreach ($products as $p)
      {{ $p->id }}: {{ $p->price }},
    @endforeach
  };

  function openTimeDrawer() {
    document.getElementById('drawer-time').classList.remove('hidden');
  }

  function openFoodDrawer() {
    document.getElementById('drawer-food').classList.remove('hidden');
  }

  function closeDrawer(id) {
    document.getElementById(id).classList.add('hidden');
  }

  function setCustHours(hours) {
    document.getElementById('cust-duration-input').value = hours;
    document.querySelectorAll('.cust-hour-btn').forEach(btn => {
      if (parseFloat(btn.getAttribute('data-val')) === hours) {
        btn.classList.add('bg-secondary-container', 'text-on-secondary');
        btn.classList.remove('bg-surface');
      } else {
        btn.classList.remove('bg-secondary-container', 'text-on-secondary');
        btn.classList.add('bg-surface');
      }
    });
    calcCustCost();
  }

  function calcCustCost() {
    const hours = parseFloat(document.getElementById('cust-duration-input').value) || 0;
    const cost = Math.round(hours * tvHourlyRate);
    document.getElementById('cust-cost-preview').innerText = 'Rp ' + cost.toLocaleString('id-ID');
  }

  function adjustCustFoodQty(prodId, delta) {
    const input = document.getElementById('cust-fnb-qty-' + prodId);
    if (!input) return;
    let val = parseInt(input.value) || 0;
    val = Math.max(0, val + delta);
    input.value = val;
    updateCustFoodPreview();
  }

  function updateCustFoodPreview() {
    let total = 0;
    let count = 0;
    document.querySelectorAll('[id^="cust-fnb-qty-"]').forEach(input => {
      const prodId = input.id.replace('cust-fnb-qty-', '');
      const qty = parseInt(input.value) || 0;
      const price = custProductPrices[prodId] || 0;
      total += (qty * price);
      count += qty;
    });
    document.getElementById('cust-food-total-preview').innerText = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('btn-cust-food-submit').disabled = (count === 0);
  }

  // ================= REAL-TIME CUSTOMER SYNC =================
  function formatSeconds(totalSeconds) {
    if (totalSeconds < 0) totalSeconds = 0;
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = Math.floor(totalSeconds % 60);
    return [hours, minutes, seconds].map(v => v < 10 ? '0' + v : v).join(':');
  }

  // 1-second local timer tick
  setInterval(() => {
    const timerEl = document.getElementById('cust-timer');
    if (!timerEl) return;
    const nowUnix = Math.floor(Date.now() / 1000);
    const type = timerEl.getAttribute('data-type');
    const startUnix = parseInt(timerEl.getAttribute('data-start')) || 0;
    const endUnix = parseInt(timerEl.getAttribute('data-end')) || 0;

    if (type === 'prepaid' && endUnix > 0) {
      const remaining = endUnix - nowUnix;
      if (remaining <= 0) {
        timerEl.innerText = '00:00:00';
        timerEl.classList.add('text-error', 'animate-pulse');
      } else {
        timerEl.innerText = formatSeconds(remaining);
      }
    } else if (type === 'postpaid' && startUnix > 0) {
      const elapsed = nowUnix - startUnix;
      timerEl.innerText = formatSeconds(elapsed);
    }
  }, 1000);

  // Background Database Poll for Customer every 3s
  async function pollCustomerStatus() {
    try {
      const res = await fetch('{{ route("customer.status", $tv->id) }}');
      if (!res.ok) return;
      const data = await res.json();

      if (data.session) {
        // Update bill display
        const billEl = document.getElementById('cust-total-bill');
        if (billEl) {
          billEl.innerText = 'Rp ' + data.session.total_amount.toLocaleString('id-ID');
        }
      }

      // Update requests list if changed
      const reqContainer = document.getElementById('customer-requests-container');
      if (reqContainer && data.requests && data.requests.length > 0) {
        let html = '';
        data.requests.forEach(req => {
          const isTime = req.type === 'add_time';
          const isPending = req.status === 'pending';
          const isApproved = req.status === 'approved';

          let statusBadge = isPending 
            ? '<span class="px-2 py-1 bg-secondary-container text-on-secondary font-headline-sm text-[10px] font-black uppercase border border-on-surface animate-pulse">MENUNGGU KASIR</span>'
            : (isApproved 
              ? '<span class="px-2 py-1 bg-emerald-100 text-emerald-900 font-headline-sm text-[10px] font-black uppercase border border-on-surface">✓ DISETUJUI</span>'
              : '<span class="px-2 py-1 bg-red-100 text-red-900 font-headline-sm text-[10px] font-black uppercase border border-on-surface">✗ DITOLAK</span>');

          let details = isTime 
            ? `+${req.payload.duration_hours || 1} Jam (Rp ${(req.payload.price || 0).toLocaleString('id-ID')})`
            : (req.payload.items ? req.payload.items.map(i => `${i.quantity}x ${i.name}`).join(', ') : 'Pesanan F&B');

          html += `
            <div class="p-3.5 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm flex items-start justify-between gap-2">
              <div class="min-w-0">
                <div class="flex items-center gap-1.5 mb-1">
                  <span class="material-symbols-outlined text-base ${isTime ? 'text-secondary' : 'text-primary'}">
                    ${isTime ? 'more_time' : 'restaurant'}
                  </span>
                  <span class="font-headline-sm text-xs font-bold uppercase">
                    ${isTime ? 'Tambah Waktu' : 'Pesanan F&B'}
                  </span>
                  <span class="text-[10px] font-label-sm text-on-surface-variant font-bold">• ${req.time}</span>
                </div>
                <p class="text-xs font-body-md text-on-surface line-clamp-1">${details}</p>
              </div>
              <div>${statusBadge}</div>
            </div>
          `;
        });
        reqContainer.innerHTML = html;
      }
    } catch (e) {
      console.log('Customer sync error:', e);
    }
  }

  setInterval(pollCustomerStatus, 3000);
</script>
@endpush
