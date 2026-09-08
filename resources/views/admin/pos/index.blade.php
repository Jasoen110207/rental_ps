@extends('layouts.admin')

@section('title', 'F&B Catalog & POS')
@section('page_title', 'F&B Catalog & POS Ordering')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <!-- LEFT: PRODUCT CATALOG (2 Cols) -->
  <div class="lg:col-span-2 flex flex-col gap-5">
    
    <!-- Top Search & Category Filters -->
    <div class="p-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <!-- Search Form -->
      <form method="GET" action="{{ route('admin.pos.index') }}" class="flex-1 flex gap-2">
        <input type="hidden" name="category" value="{{ $category }}">
        <div class="relative flex-1">
          <input type="text" name="search" value="{{ $search }}" placeholder="Cari makanan / minuman..." class="w-full px-3.5 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm focus:outline-none">
          <button type="submit" class="absolute right-2.5 top-2 text-on-surface-variant">
            <span class="material-symbols-outlined text-lg">search</span>
          </button>
        </div>
      </form>

      <!-- Category Filter Tabs -->
      <div class="flex items-center gap-1 bg-surface-container-high p-1 border-2 border-on-surface neo-shadow-sm overflow-x-auto">
        <a href="{{ route('admin.pos.index', ['category' => 'all', 'search' => $search]) }}" class="px-2.5 py-1 font-headline-sm text-xs font-bold uppercase {{ $category === 'all' ? 'bg-primary text-on-primary neo-shadow-sm' : 'hover:bg-surface' }}">
          Semua
        </a>
        <a href="{{ route('admin.pos.index', ['category' => 'food', 'search' => $search]) }}" class="px-2.5 py-1 font-headline-sm text-xs font-bold uppercase {{ $category === 'food' ? 'bg-primary text-on-primary neo-shadow-sm' : 'hover:bg-surface' }}">
          Makanan
        </a>
        <a href="{{ route('admin.pos.index', ['category' => 'drink', 'search' => $search]) }}" class="px-2.5 py-1 font-headline-sm text-xs font-bold uppercase {{ $category === 'drink' ? 'bg-primary text-on-primary neo-shadow-sm' : 'hover:bg-surface' }}">
          Minuman
        </a>
        <a href="{{ route('admin.pos.index', ['category' => 'snack', 'search' => $search]) }}" class="px-2.5 py-1 font-headline-sm text-xs font-bold uppercase {{ $category === 'snack' ? 'bg-primary text-on-primary neo-shadow-sm' : 'hover:bg-surface' }}">
          Snack
        </a>
      </div>

      <a href="{{ route('admin.pos.manage') }}" class="px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">inventory</span>
        <span>Kelola Menu</span>
      </a>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
      @forelse ($products as $product)
        <div class="p-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between hover:border-primary transition">
          <div>
            <div class="flex justify-between items-start mb-1">
              <span class="text-[9px] font-label-sm font-bold uppercase px-1.5 py-0.5 border border-on-surface bg-secondary-fixed text-on-surface">
                {{ $product->category }}
              </span>
              <span class="text-[10px] font-label-sm {{ $product->stock > 0 ? 'text-emerald-700' : 'text-error' }} font-bold">
                Stok: {{ $product->stock }}
              </span>
            </div>
            <h4 class="font-headline-sm text-sm font-bold uppercase text-on-surface mt-1 line-clamp-2">{{ $product->name }}</h4>
            <p class="font-headline-lg font-black text-sm text-primary mt-1">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>
          </div>

          <button type="button" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})" {{ $product->stock <= 0 ? 'disabled' : '' }} class="w-full mt-3 py-1.5 bg-surface border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-primary hover:text-white transition flex items-center justify-center gap-1 disabled:opacity-50">
            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
            <span>+ Tambah</span>
          </button>
        </div>
      @empty
        <div class="col-span-3 p-12 text-center bg-surface border-2 border-on-surface">
          <p class="font-headline-sm text-xs text-on-surface-variant font-bold uppercase">Tidak ada produk yang cocok.</p>
        </div>
      @endforelse
    </div>

  </div>

  <!-- RIGHT: CART & BILLING DESTINATION (1 Col) -->
  <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between sticky top-20 max-h-[85vh]">
    <div>
      <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-3">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-xl text-primary">shopping_bag</span>
          <h3 class="font-headline-lg font-black text-base uppercase">Keranjang Pesanan</h3>
        </div>
        <button type="button" onclick="clearCart()" class="text-[10px] font-label-sm uppercase font-bold text-error hover:underline">
          Reset
        </button>
      </div>

      <form id="pos-order-form" method="POST" action="{{ route('admin.pos.order') }}" class="flex flex-col gap-3">
        @csrf

        <!-- Target Selector: Masukkan ke Billing Meja vs Bayar Langsung -->
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1.5">Tujuan Pesanan</label>
          <div class="grid grid-cols-2 gap-2 mb-2">
            <label class="p-2 border-2 border-on-surface text-center font-headline-sm text-xs font-bold uppercase cursor-pointer has-[:checked]:bg-primary has-[:checked]:text-white neo-shadow-sm">
              <input type="radio" name="target_type" value="session" checked onchange="toggleTargetType('session')" class="hidden">
              <span>Ke Meja PS</span>
            </label>
            <label class="p-2 border-2 border-on-surface text-center font-headline-sm text-xs font-bold uppercase cursor-pointer has-[:checked]:bg-primary has-[:checked]:text-white neo-shadow-sm">
              <input type="radio" name="target_type" value="direct" onchange="toggleTargetType('direct')" class="hidden">
              <span>Beli Langsung</span>
            </label>
          </div>

          <!-- Meja PS Selector -->
          <div id="session-select-box">
            <select name="play_session_id" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
              @if ($activeSessions->count() == 0)
                <option value="">Tidak ada meja yang sedang aktif</option>
              @else
                @foreach ($activeSessions as $s)
                  <option value="{{ $s->id }}">
                    {{ $s->tv ? $s->tv->name : 'Meja' }} ({{ strtoupper($s->billing_type) }})
                  </option>
                @endforeach
              @endif
            </select>
          </div>
        </div>

        <!-- Cart Items List -->
        <div class="border-2 border-on-surface p-2 bg-surface min-h-36 max-h-56 overflow-y-auto flex flex-col gap-2" id="cart-items-container">
          <p class="text-xs text-on-surface-variant font-bold text-center py-10" id="empty-cart-msg">Keranjang masih kosong</p>
        </div>

        <!-- Hidden input items for form submission -->
        <div id="form-hidden-inputs"></div>

        <!-- Order Summary -->
        <div class="p-3 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex items-center justify-between">
          <span class="font-headline-sm text-xs uppercase font-bold">Total Tagihan:</span>
          <span class="font-headline-lg font-black text-lg text-primary" id="cart-total-display">Rp 0</span>
        </div>

        <button type="submit" id="btn-submit-order" disabled class="w-full py-2.5 bg-primary text-on-primary font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container disabled:opacity-50 flex items-center justify-center gap-1.5 mt-2">
          <span class="material-symbols-outlined text-base">check_circle</span>
          <span>Proses Pesanan F&B</span>
        </button>
      </form>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  let cart = {};

  function addToCart(id, name, price, stock) {
    if (!cart[id]) {
      cart[id] = { id, name, price, stock, qty: 0 };
    }
    if (cart[id].qty < stock) {
      cart[id].qty++;
      renderCart();
    } else {
      alert('Stok tidak mencukupi!');
    }
  }

  function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) {
      delete cart[id];
    }
    renderCart();
  }

  function clearCart() {
    cart = {};
    renderCart();
  }

  function renderCart() {
    const container = document.getElementById('cart-items-container');
    const emptyMsg = document.getElementById('empty-cart-msg');
    const hiddenInputs = document.getElementById('form-hidden-inputs');
    const totalEl = document.getElementById('cart-total-display');
    const submitBtn = document.getElementById('btn-submit-order');

    container.innerHTML = '';
    hiddenInputs.innerHTML = '';

    const keys = Object.keys(cart);
    if (keys.length === 0) {
      container.innerHTML = '<p class="text-xs text-on-surface-variant font-bold text-center py-10">Keranjang masih kosong</p>';
      totalEl.innerText = 'Rp 0';
      submitBtn.disabled = true;
      return;
    }

    let grandTotal = 0;
    let idx = 0;

    keys.forEach(id => {
      const item = cart[id];
      const subtotal = item.qty * item.price;
      grandTotal += subtotal;

      const row = document.createElement('div');
      row.className = 'p-2 bg-surface-container-lowest border border-on-surface flex items-center justify-between gap-2';
      row.innerHTML = `
        <div class="min-w-0">
          <p class="font-headline-sm text-xs font-bold truncate">${item.name}</p>
          <p class="font-label-sm text-[10px] text-primary font-bold">Rp ${item.price.toLocaleString('id-ID')}</p>
        </div>
        <div class="flex items-center gap-1 border border-on-surface">
          <button type="button" onclick="changeQty(${item.id}, -1)" class="w-5 h-5 flex items-center justify-center font-bold text-xs hover:bg-surface-container-high">-</button>
          <span class="w-5 text-center text-xs font-bold">${item.qty}</span>
          <button type="button" onclick="changeQty(${item.id}, 1)" class="w-5 h-5 flex items-center justify-center font-bold text-xs hover:bg-surface-container-high">+</button>
        </div>
      `;
      container.appendChild(row);

      // Add hidden inputs for Laravel form
      hiddenInputs.innerHTML += `
        <input type="hidden" name="items[${idx}][product_id]" value="${item.id}">
        <input type="hidden" name="items[${idx}][quantity]" value="${item.qty}">
      `;
      idx++;
    });

    totalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    submitBtn.disabled = false;
  }

  function toggleTargetType(type) {
    const box = document.getElementById('session-select-box');
    if (type === 'session') {
      box.classList.remove('hidden');
    } else {
      box.classList.add('hidden');
    }
  }
</script>
@endpush
