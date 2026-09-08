@extends('layouts.app')

@section('title', 'Rental / POS - TambahBang')
@section('page_title', 'Rental / POS')

@section('content')
<div class="grid grid-cols-12 gap-6 items-start">

  {{-- PANEL SESI AKTIF --}}
  <section class="col-span-12 xl:col-span-8">
    <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      @if (!$selected)
        <div class="p-10 text-center">
          <span class="material-symbols-outlined text-4xl text-on-surface-variant">sports_esports</span>
          <h2 class="font-black uppercase text-lg mt-2">Tidak ada sesi aktif</h2>
          <p class="text-xs text-on-surface-variant mt-1">Mulai rental baru dari panel kanan atau dashboard.</p>
        </div>
      @else
        <div class="bg-primary text-white p-4 border-b-2 border-on-surface flex flex-wrap items-center justify-between gap-2">
          <div>
            <h2 class="font-black uppercase text-lg">{{ $selected->tv ? $selected->tv->name : 'Meja' }} ({{ strtoupper($selected->tv->type ?? '') }})</h2>
            <p class="text-xs opacity-80">Mulai {{ $selected->start_time->format('H:i') }} • S/d {{ $selected->end_time ? $selected->end_time->format('H:i') : 'Loss (Postpaid)' }} • #{{ $selected->id }}</p>
          </div>
          <span class="px-3 py-1.5 bg-white text-on-surface text-xs font-black border-2 border-on-surface">{{ strtoupper($selected->billing_type) }}</span>
        </div>

        <div class="p-5 flex flex-col gap-5">
          {{-- Pilih sesi --}}
          <form method="GET" action="{{ route('kasir.pos') }}" class="flex items-center gap-2">
            <label class="text-xs font-bold uppercase">Sesi:</label>
            <select name="session_id" onchange="this.form.submit()" class="flex-1 px-3 py-2 bg-surface border-2 border-on-surface text-xs font-bold">
              @foreach ($activeSessions as $s)
                <option value="{{ $s->id }}" {{ $selected->id === $s->id ? 'selected' : '' }}>{{ $s->tv ? $s->tv->name : 'Meja' }} — {{ $s->start_time->format('H:i') }} ({{ strtoupper($s->billing_type) }})</option>
              @endforeach
            </select>
          </form>

          {{-- Timer --}}
          <div class="bg-surface-container-low border-2 border-on-surface p-4 text-center">
            <span class="text-[11px] uppercase font-bold text-on-surface-variant">{{ $selected->billing_type === 'prepaid' ? 'SISA WAKTU' : 'WAKTU BERJALAN' }}</span>
            <p class="font-timer-display font-black text-5xl" id="pos-timer-display">--:--:--</p>
          </div>

          {{-- F&B --}}
          <div class="border-2 border-on-surface">
            <div class="p-3 bg-surface-container border-b-2 border-on-surface flex items-center justify-between">
              <h3 class="font-bold uppercase text-sm">F&B Meja Ini ({{ $selected->sessionOrders->sum('quantity') }} item)</h3>
              <button onclick="document.getElementById('pos-fnb-box').classList.toggle('hidden')" class="px-3 py-1 bg-surface-container-lowest border-2 border-on-surface text-xs font-bold btn-press">+ Tambah</button>
            </div>
            <div id="pos-fnb-box" class="hidden p-4 border-b-2 border-on-surface bg-surface">
              <form method="POST" action="{{ route('kasir.rental.add-fnb', $selected->id) }}" class="flex flex-col gap-3">
                @csrf
                @foreach ($products as $p)
                  <div class="flex items-center justify-between gap-2 text-xs">
                    <span class="font-bold">{{ $p->name }} — Rp {{ number_format($p->price, 0, ',', '.') }} (stok {{ $p->stock }})</span>
                    <div class="flex items-center gap-1">
                      <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $p->id }}">
                      <input type="number" name="items[{{ $loop->index }}][quantity]" value="0" min="0" max="{{ $p->stock }}" class="w-16 px-2 py-1 border-2 border-on-surface text-xs font-bold">
                    </div>
                  </div>
                @endforeach
                <button class="py-2 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">Tambahkan ke Tagihan</button>
              </form>
            </div>
            <table class="w-full text-left text-xs">
              <thead><tr class="border-b-2 border-on-surface bg-surface-container-low text-on-surface-variant uppercase">
                <th class="py-2 px-4">Item</th><th class="py-2 px-4 text-center">Qty</th><th class="py-2 px-4 text-right">Subtotal</th>
              </tr></thead>
              <tbody>
                @forelse ($selected->sessionOrders as $o)
                  <tr class="border-b border-on-surface/20">
                    <td class="py-2 px-4 font-bold">{{ $o->product ? $o->product->name : 'Item' }}</td>
                    <td class="py-2 px-4 text-center">{{ $o->quantity }}x</td>
                    <td class="py-2 px-4 text-right font-bold">Rp {{ number_format($o->subtotal, 0, ',', '.') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="py-4 px-4 text-center text-on-surface-variant">Belum ada pesanan F&B.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- Total --}}
          <div class="p-4 bg-surface-container-high border-2 border-on-surface neo-shadow flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-6">
              <div><p class="text-[11px] uppercase font-bold text-on-surface-variant">Rental</p><p class="font-bold">Rp {{ number_format($selected->rental_amount, 0, ',', '.') }}</p></div>
              <div class="font-bold">+</div>
              <div><p class="text-[11px] uppercase font-bold text-on-surface-variant">F&B</p><p class="font-bold">Rp {{ number_format($selected->fnb_amount, 0, ',', '.') }}</p></div>
            </div>
            <div class="border-l-2 border-on-surface pl-6">
              <p class="text-[11px] uppercase font-bold text-secondary">LIVE GRAND TOTAL</p>
              <p class="font-black text-2xl text-primary">Rp {{ number_format($selected->total_amount, 0, ',', '.') }}</p>
            </div>
          </div>

          {{-- Aksi --}}
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <form method="POST" action="{{ route('kasir.rental.extend', $selected->id) }}">@csrf<input type="hidden" name="added_hours" value="1">
              <button class="w-full p-3 bg-secondary-container text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">+ 1 JAM</button>
            </form>
            <button onclick="document.getElementById('pos-fnb-box').classList.remove('hidden')" class="p-3 bg-surface-container-lowest text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">+ F&B</button>
            <form method="POST" action="{{ route('kasir.rental.toggle-buzzer', $selected->tv_id) }}">@csrf
              <button class="w-full p-3 bg-surface-container-lowest text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">BUZZER</button>
            </form>
            <form method="POST" action="{{ route('kasir.rental.checkout', $selected->id) }}">@csrf
              <input type="hidden" name="payment_method" value="cash">
              <button onclick="return confirm('Checkout {{ $selected->tv ? $selected->tv->name : '' }} sekarang?')" class="w-full p-3 bg-error text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">STOP & CHECKOUT</button>
            </form>
          </div>
        </div>
      @endif
    </div>
  </section>

  {{-- DOCK START RENTAL --}}
  <section class="col-span-12 xl:col-span-4">
    <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <div class="p-4 bg-on-surface text-white border-b-2 border-on-surface">
        <h3 class="font-black uppercase">Start New Rental</h3>
        <p class="text-[11px] opacity-70 uppercase">{{ $availableUnits->count() }} unit standby</p>
      </div>
      <form method="POST" action="{{ route('kasir.rental.start') }}" class="p-5 flex flex-col gap-4">
        @csrf
        <div>
          <label class="text-xs font-bold uppercase">Unit tersedia:</label>
          <select name="tv_id" required class="w-full mt-1 px-3 py-2 border-2 border-on-surface text-xs font-bold">
            @forelse ($availableUnits as $u)
              <option value="{{ $u->id }}">{{ $u->name }} — Rp {{ number_format($u->price_per_hour, 0, ',', '.') }}/jam</option>
            @empty
              <option value="">Tidak ada unit tersedia</option>
            @endforelse
          </select>
        </div>
        <div>
          <label class="text-xs font-bold uppercase">Billing:</label>
          <div class="grid grid-cols-2 gap-2 mt-1">
            <label class="p-2 border-2 border-on-surface text-xs font-bold uppercase text-center cursor-pointer has-[:checked]:bg-primary has-[:checked]:text-white"><input type="radio" name="billing_type" value="prepaid" checked class="hidden"><span>PREPAID</span></label>
            <label class="p-2 border-2 border-on-surface text-xs font-bold uppercase text-center cursor-pointer has-[:checked]:bg-primary has-[:checked]:text-white"><input type="radio" name="billing_type" value="postpaid" class="hidden"><span>POSTPAID</span></label>
          </div>
        </div>
        <div>
          <label class="text-xs font-bold uppercase">Durasi (jam, prepaid):</label>
          <input type="number" name="duration_hours" value="2" step="0.5" min="0.5" class="w-full mt-1 px-3 py-2 border-2 border-on-surface text-xs font-bold">
        </div>
        <button class="w-full py-4 bg-primary text-white font-black uppercase border-2 border-on-surface neo-shadow btn-press">MULAI SEWA UNIT</button>
      </form>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
(function(){
  @if($selected)
  let total = {{ $selected->billing_type === 'prepaid' && $selected->end_time ? max(0, now()->diffInSeconds($selected->end_time, false)) : 0 }};
  let start = {{ $selected->start_time->timestamp }};
  const isPrepaid = {{ $selected->billing_type === 'prepaid' ? 'true' : 'false' }};
  const el = document.getElementById('pos-timer-display');
  function tick(){
    if(isPrepaid){ if(total > 0) total--; el.textContent = [total/3600, total%3600/60, total%60].map((v,i)=>String(Math.floor(v)).padStart(2,'0')).join(':'); }
    else{ const s = Math.floor(Date.now()/1000) - start; el.textContent = [s/3600, s%3600/60, s%60].map(v=>String(Math.floor(v)).padStart(2,'0')).join(':'); }
  }
  tick(); setInterval(tick, 1000);
  @endif
})();
</script>
@endpush
