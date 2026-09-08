@extends('layouts.app')

@section('title', 'F&B Menu - TambahBang')
@section('page_title', 'F&B Menu')

@section('content')
<div class="flex flex-col xl:flex-row gap-6 items-start">

  <section class="flex-1 min-w-0 bg-surface-container-lowest border-2 border-on-surface neo-shadow overflow-hidden">
    <div class="p-4 border-b-2 border-on-surface">
      <form method="GET" action="{{ route('kasir.menu') }}" class="flex gap-2">
        <input type="hidden" name="category" value="{{ $category }}">
        <input name="search" value="{{ $search }}" placeholder="Cari mie, kopi, snack..." class="flex-1 px-4 py-2.5 border-2 border-on-surface text-sm font-bold">
        <button class="px-4 py-2 bg-primary text-white text-xs font-black uppercase border-2 border-on-surface neo-shadow btn-press">CARI</button>
      </form>
      <div class="flex gap-2 mt-3 overflow-x-auto">
        <a href="{{ route('kasir.menu', ['category' => 'all']) }}" class="px-4 py-1.5 text-xs font-bold border-2 border-on-surface {{ $category === 'all' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Semua</a>
        <a href="{{ route('kasir.menu', ['category' => 'food']) }}" class="px-4 py-1.5 text-xs font-bold border-2 border-on-surface {{ $category === 'food' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Makanan</a>
        <a href="{{ route('kasir.menu', ['category' => 'drink']) }}" class="px-4 py-1.5 text-xs font-bold border-2 border-on-surface {{ $category === 'drink' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Minuman</a>
        <a href="{{ route('kasir.menu', ['category' => 'snack']) }}" class="px-4 py-1.5 text-xs font-bold border-2 border-on-surface {{ $category === 'snack' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Snack</a>
      </div>
    </div>

    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse ($products as $p)
        <div class="border-2 border-on-surface neo-shadow p-3.5 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="px-2 py-0.5 text-[10px] font-bold border border-on-surface {{ $p->stock <= 5 ? 'bg-secondary-fixed' : 'bg-tertiary-fixed' }}">{{ $p->stock <= 5 ? 'Sisa '.$p->stock : 'Stok '.$p->stock }}</span>
              <span class="text-[10px] font-bold uppercase text-on-surface-variant">{{ $p->category }}</span>
            </div>
            <h3 class="font-bold text-sm">{{ $p->name }}</h3>
          </div>
          <div class="mt-4 pt-3 border-t-2 border-dashed border-on-surface/40 flex items-center justify-between">
            <span class="font-bold text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
            <button type="button" onclick="kasirAddToCart({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->price }}, {{ $p->stock }})" {{ $p->stock <= 0 ? 'disabled' : '' }} class="px-3 py-1.5 bg-primary text-white text-xs uppercase font-bold border-2 border-on-surface neo-shadow-sm btn-press disabled:opacity-50">+ TAMBAH</button>
          </div>
        </div>
      @empty
        <p class="col-span-3 text-center text-sm text-on-surface-variant py-10">Tidak ada produk yang cocok.</p>
      @endforelse
    </div>
  </section>

  <section class="w-full xl:w-[380px] shrink-0 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
    <form method="POST" action="{{ route('kasir.pos.order') }}" id="kasir-cart-form" class="flex flex-col">
      @csrf
      <div class="p-4 border-b-2 border-on-surface bg-surface-container-low">
        <h3 class="font-black uppercase text-sm">ORDER BILLING DOCK</h3>
        <label class="text-[11px] font-bold uppercase block mt-3">Target tagihan</label>
        <select name="play_session_id" id="kasir-cart-session" class="w-full mt-1 px-3 py-2.5 border-2 border-on-surface text-xs font-bold">
          @forelse ($activeSessions as $s)
            <option value="{{ $s->id }}">{{ $s->tv ? $s->tv->name : 'Meja' }} — sesi aktif</option>
          @empty
            <option value="">Tidak ada sesi aktif</option>
          @endforelse
          <option value="direct">Takeaway langsung (non-rental)</option>
        </select>
        <input type="hidden" name="target_type" id="kasir-cart-target" value="session">
      </div>
      <div class="p-4 flex-1">
        <div id="kasir-cart-items" class="flex flex-col gap-2"></div>
        <p id="kasir-cart-empty" class="text-xs text-on-surface-variant text-center py-6">Keranjang kosong. Klik + TAMBAH pada menu.</p>
      </div>
      <div class="p-4 border-t-2 border-on-surface bg-surface-container-low">
        <div class="flex justify-between font-black"><span>Subtotal F&B:</span><span class="text-primary" id="kasir-cart-total">Rp 0</span></div>
        <button class="w-full mt-3 py-3.5 bg-primary text-white text-sm uppercase font-black border-2 border-on-surface neo-shadow btn-press">MASUKKAN KE TAGIHAN</button>
      </div>
    </form>
  </section>
</div>
@endsection

@push('scripts')
<script>
let kasirCart = {};
function kasirAddToCart(id, name, price, stock){
  if(!kasirCart[id]) kasirCart[id] = { id, name, price, qty: 0, stock };
  if(kasirCart[id].qty >= stock){ alert('Stok habis!'); return; }
  kasirCart[id].qty++;
  kasirRenderCart();
}
function kasirRenderCart(){
  const box = document.getElementById('kasir-cart-items');
  const empty = document.getElementById('kasir-cart-empty');
  const ids = Object.keys(kasirCart).filter(k => kasirCart[k].qty > 0);
  box.innerHTML = '';
  let total = 0, idx = 0;
  ids.forEach(k => {
    const it = kasirCart[k];
    total += it.price * it.qty;
    box.innerHTML += `<div class="p-3 bg-surface border-2 border-on-surface flex justify-between items-center text-xs">
      <div><p class="font-bold">${it.name}</p><p class="text-on-surface-variant">@ Rp ${it.price.toLocaleString('id-ID')} × ${it.qty}</p></div>
      <div class="flex items-center gap-1">
        <input type="hidden" name="items[${idx}][product_id]" value="${it.id}">
        <input type="hidden" name="items[${idx}][quantity]" value="${it.qty}">
        <button type="button" onclick="kasirCart[${it.id}].qty--; kasirRenderCart()" class="w-6 h-6 border-2 border-on-surface font-bold">-</button>
        <span class="font-bold w-6 text-center">${it.qty}</span>
        <button type="button" onclick="kasirCart[${it.id}].qty++; kasirRenderCart()" class="w-6 h-6 border-2 border-on-surface font-bold">+</button>
      </div></div>`;
    idx++;
  });
  empty.style.display = ids.length ? 'none' : '';
  document.getElementById('kasir-cart-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
}
document.getElementById('kasir-cart-session').addEventListener('change', e => {
  document.getElementById('kasir-cart-target').value = e.target.value === 'direct' ? 'direct' : 'session';
});
document.getElementById('kasir-cart-form').addEventListener('submit', e => {
  const has = Object.values(kasirCart).some(i => i.qty > 0);
  if(!has){ e.preventDefault(); alert('Keranjang masih kosong!'); }
});
</script>
@endpush
