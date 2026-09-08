@extends('layouts.customer')

@section('title', 'Order ' . $tv->name . ' - TambahBang')

@section('content')
<div class="flex flex-col" id="order-page" data-tv-id="{{ $tv->id }}">

    {{-- Unit bar + pemilih meja --}}
    <header class="w-full flex flex-col gap-2.5 pb-3 mb-3 border-b-2 border-dashed border-on-surface">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $activeSession ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-surface-container text-on-surface-variant' }} neo-border-2 neo-shadow-sm self-start">
            <span class="w-2.5 h-2.5 rounded-full {{ $activeSession ? 'bg-tertiary' : 'bg-outline' }} inline-block pulse-dot"></span>
            <span class="font-label-sm text-[11px] font-black tracking-wider whitespace-nowrap">{{ $activeSession ? 'SESI AKTIF' : 'MEJA KOSONG' }}</span>
        </div>

        <div class="w-full bg-[#FFE500] neo-border-2 px-3 py-2 flex items-center justify-between neo-shadow-sm">
            <div class="flex items-center gap-2 min-w-0">
                <span class="material-symbols-outlined text-on-surface text-lg shrink-0">tv</span>
                <span class="font-label-md text-xs sm:text-sm text-on-surface font-black tracking-wide truncate">
                    UNIT: {{ $tv->name }} ({{ strtoupper($tv->type) }})
                </span>
            </div>
            <div class="bg-on-surface text-surface-container-lowest font-label-sm text-[10px] px-2 py-0.5 font-extrabold shrink-0 border border-on-surface">
                Rp {{ number_format($tv->price_per_hour, 0, ',', '.') }}/JAM
            </div>
        </div>

        <form method="GET" action="{{ route('customer.order') }}" class="flex items-center gap-2">
            <label class="font-label-sm text-[10px] font-bold uppercase text-on-surface-variant">Pindah meja:</label>
            <select name="tv_id" onchange="this.form.submit()" class="flex-1 px-2 py-1.5 bg-surface-container-lowest neo-border-2 font-label-sm text-xs font-bold">
                @foreach ($tvs as $t)
                    <option value="{{ $t->id }}" {{ $t->id === $tv->id ? 'selected' : '' }}>{{ $t->name }} — {{ $t->status === 'playing' ? 'Aktif' : ucfirst($t->status) }}</option>
                @endforeach
            </select>
        </form>
    </header>

    {{-- Timer + billing real --}}
    <section class="w-full bg-surface-container-lowest neo-border-3 neo-shadow-md p-3.5 mb-3.5 flex flex-col relative">
        <div class="flex items-center justify-between pb-2.5 border-b-2 border-on-surface mb-3">
            <div class="flex items-center gap-1.5 font-label-md text-xs font-bold tracking-wide">
                <span class="material-symbols-outlined text-base text-primary">timer</span>
                <span>{{ $activeSession ? ($activeSession->billing_type === 'prepaid' ? 'SISA WAKTU BERMAIN' : 'WAKTU BERJALAN') : 'STATUS MEJA' }}</span>
            </div>
            @if ($activeSession)
                <span class="font-label-sm text-[10px] bg-surface-container-high px-2 py-0.5 neo-border-2 font-bold tracking-wider uppercase">{{ $activeSession->billing_type }}</span>
            @endif
        </div>

        @if ($activeSession)
            @php $isTimeUp = $activeSession->billing_type === 'prepaid' && $activeSession->end_time && $activeSession->end_time->isPast(); @endphp
            <div class="w-full bg-surface-container-low neo-border-2 p-3 text-center mb-3 neo-shadow-sm">
                <div class="font-timer-display text-[40px] leading-tight sm:text-[46px] font-bold tracking-tight tabular-nums select-none {{ $isTimeUp ? 'text-error' : 'text-primary' }}" id="order-countdown"
                    data-type="{{ $activeSession->billing_type }}"
                    data-start="{{ $activeSession->start_time->timestamp }}"
                    data-end="{{ $activeSession->end_time ? $activeSession->end_time->timestamp : '' }}">
                    --:--:--
                </div>
                <div class="flex items-center justify-center gap-1.5 mt-1.5 pt-1 border-t border-outline-variant/60">
                    <span class="inline-block w-2 h-2 rounded-full bg-primary animate-ping"></span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-widest font-bold">Auto-Syncing with Matrix</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-1 p-2 bg-surface neo-border-2 mb-3 font-label-sm text-center">
                <div class="border-r-2 border-on-surface px-1">
                    <span class="text-on-surface-variant text-[10px] block font-semibold uppercase">Mulai</span>
                    <span class="text-on-surface font-extrabold text-xs sm:text-sm">{{ $activeSession->start_time->format('H:i') }}</span>
                </div>
                <div class="border-r-2 border-on-surface px-1">
                    <span class="text-on-surface-variant text-[10px] block font-semibold uppercase">Berakhir</span>
                    <span class="text-primary font-extrabold text-xs sm:text-sm">{{ $activeSession->end_time ? $activeSession->end_time->format('H:i') : 'Loss' }}</span>
                </div>
                <div class="px-1">
                    <span class="text-on-surface-variant text-[10px] block font-semibold uppercase">F&B</span>
                    <span class="text-on-surface font-extrabold text-xs sm:text-sm">{{ $activeSession->sessionOrders->sum('quantity') }} item</span>
                </div>
            </div>

            <div class="bg-surface-container-high neo-border-2 p-3 flex flex-col gap-2 font-label-sm">
                <div class="flex justify-between items-center text-xs text-on-surface-variant font-medium">
                    <span>Rental ({{ $tv->name }})</span>
                    <span class="font-bold text-on-surface" id="order-rental-amount">Rp {{ number_format($activeSession->rental_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-xs text-on-surface-variant font-medium">
                    <span>Pesanan F&B ({{ $activeSession->sessionOrders->count() }} menu)</span>
                    <span class="font-bold text-on-surface" id="order-fnb-amount">Rp {{ number_format($activeSession->fnb_amount, 0, ',', '.') }}</span>
                </div>
                <div class="border-t-2 border-dashed border-on-surface pt-2 mt-0.5 flex justify-between items-baseline gap-2">
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-xs font-black uppercase tracking-wider">TOTAL TAGIHAN</span>
                        <span class="text-[10px] text-on-surface-variant italic">Bayar di kasir saat checkout</span>
                    </div>
                    <span class="font-timer-display text-2xl font-black text-primary tracking-tight tabular-nums" id="order-grand-total">Rp {{ number_format($activeSession->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        @else
            <div class="py-8 text-center bg-surface neo-border-2">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant">sports_esports</span>
                <p class="font-bold text-sm uppercase mt-1">Meja Belum Aktif</p>
                <p class="text-xs text-on-surface-variant mt-1">Pesan rental via kasir dulu, lalu semua fitur di bawah aktif.</p>
            </div>
        @endif
    </section>

    {{-- Aksi cepat --}}
    <section class="w-full flex flex-col gap-2.5 mb-4">
        <button class="neo-btn w-full min-h-[50px] bg-secondary-container hover:bg-secondary text-surface-container-lowest px-4 py-3 neo-border-3 neo-shadow-md flex items-center justify-between font-headline-sm text-sm font-black tracking-tight"
            onclick="document.getElementById('order-modal-extend').classList.remove('hidden');document.getElementById('order-modal-extend').classList.add('flex');">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-2xl shrink-0" style="font-variation-settings: 'FILL' 1;">bolt</span>
                <span class="uppercase">TAMBAH WAKTU / EXTEND</span>
            </div>
            <span class="material-symbols-outlined text-xl font-black">arrow_forward</span>
        </button>

        <button class="neo-btn w-full min-h-[50px] bg-primary hover:bg-primary-container text-on-primary px-4 py-3 neo-border-3 neo-shadow-md flex items-center justify-between font-headline-sm text-sm font-black tracking-tight"
            onclick="document.getElementById('quick-menu').scrollIntoView({behavior:'smooth'});">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-2xl shrink-0" style="font-variation-settings: 'FILL' 1;">ramen_dining</span>
                <span class="uppercase">PESAN MAKANAN & MINUMAN</span>
            </div>
            <span class="material-symbols-outlined text-xl font-black">add_shopping_cart</span>
        </button>
    </section>

    {{-- Status permintaan real --}}
    <section class="w-full mb-4">
        <div class="flex items-center justify-between mb-2 px-0.5">
            <h2 class="font-headline-sm text-sm font-extrabold tracking-tight flex items-center gap-1.5 uppercase">
                <span class="material-symbols-outlined text-primary text-lg">sync_saved_locally</span>
                STATUS PERMINTAAN ANDA
            </h2>
            <span class="font-label-sm text-[10px] bg-surface-container-high px-2 py-0.5 neo-border-2 font-black" id="order-req-count">{{ $requests->where('status', 'pending')->count() }} AKTIF</span>
        </div>
        <div class="flex flex-col gap-2.5" id="order-requests">
            @forelse ($requests as $req)
                @php $isTime = $req->type === 'add_time'; $isService = $req->type === 'service_call'; $p = $req->payload ?? []; @endphp
                <div class="bg-surface-container-lowest neo-border-2 p-3 neo-shadow-sm flex flex-col gap-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-outlined text-xl shrink-0 {{ $isTime ? 'text-secondary' : ($isService ? 'text-tertiary' : 'text-primary') }}">{{ $isTime ? 'more_time' : ($isService ? 'support_agent' : 'restaurant') }}</span>
                            <span class="font-headline-sm text-xs font-bold truncate">
                                @if ($isTime)+{{ $p['duration_hours'] ?? 1 }} Jam (Rp {{ number_format($p['price'] ?? 0, 0, ',', '.') }})
                                @elseif ($isService){{ $p['note'] ?? 'Panggil kasir' }}
                                @else{{ collect($p['items'] ?? [])->map(fn($i) => $i['quantity'].'x '.$i['name'])->join(', ') ?: 'Pesanan F&B' }}
                                @endif
                            </span>
                        </div>
                        @if ($req->status === 'pending')
                            <div class="bg-[#FFF4D6] text-secondary neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold whitespace-nowrap">MENUNGGU KASIR</div>
                        @elseif ($req->status === 'approved')
                            <div class="bg-tertiary-fixed text-on-tertiary-fixed neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold whitespace-nowrap">DISETUJUI</div>
                        @else
                            <div class="bg-error-container text-error neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold whitespace-nowrap">DITOLAK</div>
                        @endif
                    </div>
                    <p class="font-body-sm text-xs text-on-surface-variant pl-7">{{ $req->created_at->format('H:i') }} • {{ $req->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="bg-surface-container-lowest neo-border-2 p-4 text-center text-xs text-on-surface-variant font-bold">Belum ada permintaan dari meja ini.</div>
            @endforelse
        </div>
    </section>

    {{-- Menu cepat real dari DB --}}
    <section class="w-full bg-surface-container-lowest neo-border-3 neo-shadow-md p-3 mb-3.5" id="quick-menu">
        <div class="flex items-center justify-between pb-2 border-b-2 border-on-surface mb-2.5">
            <div class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-primary font-bold text-lg">restaurant_menu</span>
                <h3 class="font-headline-sm text-xs sm:text-sm font-black tracking-tight uppercase">MENU CEPAT POPULER</h3>
            </div>
            <a href="{{ route('customer.index', $tv->id) }}" class="font-label-sm text-[10px] bg-secondary-fixed font-bold px-2 py-0.5 neo-border-2">KATALOG LENGKAP</a>
        </div>
        <div class="grid grid-cols-2 gap-2">
            @forelse ($products as $p)
                <div class="bg-surface-container-low neo-border-2 p-2.5 flex flex-col justify-between">
                    <div class="min-h-[42px]">
                        <div class="font-headline-sm text-xs font-bold leading-snug line-clamp-2">{{ $p->name }}</div>
                        <div class="font-label-sm text-xs text-primary font-bold mt-1">Rp {{ number_format($p->price, 0, ',', '.') }} • Stok {{ $p->stock }}</div>
                    </div>
                    <form method="POST" action="{{ route('customer.order-food', $tv->id) }}">
                        @csrf
                        <input type="hidden" name="items[0][product_id]" value="{{ $p->id }}">
                        <input type="hidden" name="items[0][quantity]" value="1">
                        <button class="neo-btn mt-2.5 w-full bg-on-surface text-surface-container-lowest font-label-sm text-[11px] font-bold py-1.5 px-2 neo-border-2 neo-shadow-sm flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-xs">add</span> PESAN
                        </button>
                    </form>
                </div>
            @empty
                <p class="col-span-2 text-center text-xs text-on-surface-variant py-6 font-bold">Menu sedang kosong.</p>
            @endforelse
        </div>
    </section>

    <section class="w-full bg-[#FFFBEB] neo-border-2 p-3 mb-3.5 flex items-start gap-2.5 neo-shadow-sm">
        <span class="material-symbols-outlined text-secondary font-bold text-xl shrink-0 mt-0.5">warning</span>
        <div class="font-body-sm text-xs leading-relaxed">
            <strong class="uppercase text-xs font-bold text-secondary">Peringatan:</strong>
            Buzzer dan layar TV mati saat waktu habis. Perpanjang sebelum <span class="font-bold bg-[#FEE2E2] text-error px-1 py-0.5 neo-border-2">00:00:00</span> agar main tanpa jeda.
        </div>
    </section>

    <footer class="w-full mt-auto flex flex-col gap-2.5 pt-1">
        <form method="POST" action="{{ route('customer.call-cashier', $tv->id) }}">
            @csrf
            <button class="neo-btn w-full min-h-[48px] bg-surface-container-lowest p-3 neo-border-2 neo-shadow-md flex items-center justify-center gap-2 font-headline-sm text-xs sm:text-sm font-extrabold uppercase">
                <span class="material-symbols-outlined text-primary text-xl">support_agent</span>
                <span>PANGGIL KASIR KE MEJA {{ $tv->name }}</span>
            </button>
        </form>
        <div class="text-center font-label-sm text-[10px] text-on-surface-variant flex items-center justify-center gap-1.5 py-1">
            <span class="material-symbols-outlined text-xs">lock</span>
            <span>KONEKSI ENKRIPSI SISTEM TAMBAHBANG MATRIX • TANPA LOGIN</span>
        </div>
    </footer>

    {{-- Modal extend → POST real ke DB --}}
    <div class="fixed inset-0 bg-on-surface/60 z-50 hidden flex-col justify-end backdrop-blur-[1px]" id="order-modal-extend">
        <form method="POST" action="{{ route('customer.add-time', $tv->id) }}" class="w-full max-w-md mx-auto bg-surface-container-lowest neo-border-3 border-b-0 p-4 neo-shadow-lg flex flex-col gap-3.5">
            @csrf
            <div class="flex items-center justify-between border-b-2 border-on-surface pb-2.5">
                <h3 class="font-headline-sm text-sm font-extrabold uppercase">TAMBAH WAKTU SEWA</h3>
                <button type="button" class="w-8 h-8 bg-surface-container neo-border-2 flex items-center justify-center font-bold" onclick="document.getElementById('order-modal-extend').classList.add('hidden');document.getElementById('order-modal-extend').classList.remove('flex');">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
            <p class="text-xs text-on-surface-variant">Request dikirim ke kasir. Timer bertambah otomatis setelah disetujui.</p>
            <div class="grid grid-cols-3 gap-2">
                @php $rate = $tv->price_per_hour; @endphp
                <button type="submit" name="duration_hours" value="0.5" class="p-2.5 bg-surface neo-border-2 neo-shadow-sm flex flex-col items-center gap-1"><span class="font-bold text-sm">+30m</span><span class="text-[10px] text-on-surface-variant">Rp {{ number_format($rate * 0.5, 0, ',', '.') }}</span></button>
                <button type="submit" name="duration_hours" value="1" class="p-2.5 bg-secondary-container text-white neo-border-2 neo-shadow-sm flex flex-col items-center gap-1"><span class="font-bold text-sm">+1 Jam</span><span class="text-[10px]">Rp {{ number_format($rate, 0, ',', '.') }}</span></button>
                <button type="submit" name="duration_hours" value="2" class="p-2.5 bg-surface neo-border-2 neo-shadow-sm flex flex-col items-center gap-1"><span class="font-bold text-sm">+2 Jam</span><span class="text-[10px] text-on-surface-variant">Rp {{ number_format($rate * 2, 0, ',', '.') }}</span></button>
            </div>
            <button type="button" class="w-full bg-surface-container neo-border-2 py-2.5 text-xs font-bold" onclick="document.getElementById('order-modal-extend').classList.add('hidden');document.getElementById('order-modal-extend').classList.remove('flex');">BATALKAN</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const tvId = document.getElementById('order-page').dataset.tvId;
  const timerEl = document.getElementById('order-countdown');
  function fmt(s){ s = Math.max(0, s); return [s/3600, (s%3600)/60, s%60].map(v => String(Math.floor(v)).padStart(2,'0')).join(':'); }
  function tick(){
    if(!timerEl) return;
    const now = Math.floor(Date.now()/1000);
    const type = timerEl.dataset.type;
    const start = parseInt(timerEl.dataset.start) || 0;
    const end = parseInt(timerEl.dataset.end) || 0;
    if(type === 'prepaid' && end > 0){ timerEl.textContent = fmt(end - now); if(end - now <= 0) timerEl.classList.add('text-error'); }
    else if(type === 'postpaid' && start > 0){ timerEl.textContent = fmt(now - start); }
  }
  tick(); setInterval(tick, 1000);

  async function pollOrder(){
    try{
      const res = await fetch(`/customer/${tvId}/status`);
      if(!res.ok) return;
      const data = await res.json();
      if(data.session){
        if(timerEl){
          timerEl.dataset.type = data.session.billing_type;
          if(data.session.billing_type === 'prepaid' && data.session.end_time){
            const [h, m] = data.session.end_time.split(':').map(Number);
            const d = new Date(); d.setHours(h, m, 0, 0);
            let ts = Math.floor(d.getTime()/1000);
            if(ts*1000 < Date.now() - 12*3600*1000) ts += 86400;
            timerEl.dataset.end = ts;
          }
        }
        const set = (id, v) => { const el = document.getElementById(id); if(el) el.innerText = v; };
        set('order-rental-amount', 'Rp ' + data.session.rental_amount.toLocaleString('id-ID'));
        set('order-fnb-amount', 'Rp ' + data.session.fnb_amount.toLocaleString('id-ID'));
        set('order-grand-total', 'Rp ' + data.session.total_amount.toLocaleString('id-ID'));
      }
      const box = document.getElementById('order-requests');
      const count = document.getElementById('order-req-count');
      if(box && data.requests){
        const pend = data.requests.filter(r => r.status === 'pending').length;
        if(count) count.innerText = pend + ' AKTIF';
        if(data.requests.length){
          box.innerHTML = data.requests.slice(0, 5).map(req => {
            const isTime = req.type === 'add_time', isService = req.type === 'service_call';
            const title = isTime ? `+${req.payload.duration_hours || 1} Jam (Rp ${(req.payload.price || 0).toLocaleString('id-ID')})`
              : (isService ? (req.payload.note || 'Panggil kasir') : (req.payload.items || []).map(i => `${i.quantity}x ${i.name}`).join(', ') || 'Pesanan F&B');
            const badge = req.status === 'pending' ? '<div class="bg-[#FFF4D6] text-secondary neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold whitespace-nowrap">MENUNGGU KASIR</div>'
              : (req.status === 'approved' ? '<div class="bg-tertiary-fixed text-on-tertiary-fixed neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold whitespace-nowrap">DISETUJUI</div>'
              : '<div class="bg-error-container text-error neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold whitespace-nowrap">DITOLAK</div>');
            return `<div class="bg-surface-container-lowest neo-border-2 p-3 neo-shadow-sm flex flex-col gap-1.5">
              <div class="flex items-start justify-between gap-2">
                <span class="font-headline-sm text-xs font-bold truncate">${title}</span>${badge}
              </div><p class="text-xs text-on-surface-variant">${req.time}</p></div>`;
          }).join('');
        }
      }
    }catch(e){ console.log('order sync error', e); }
  }
  setInterval(pollOrder, 3000);
})();
</script>
@endpush
