@extends('layouts.admin')

@section('title', 'Manajemen Menu F&B')
@section('page_title', 'Kelola Produk & Menu F&B')

@section('content')
<div class="flex flex-col gap-6">

  <!-- Header -->
  <div class="flex items-center justify-between pb-4 border-b-2 border-on-surface">
    <div>
      <h2 class="text-xl font-headline-lg font-black uppercase text-on-surface">Daftar Makanan & Minuman</h2>
      <p class="text-xs text-on-surface-variant font-medium mt-0.5">Tambah produk baru, ubah harga, atau sesuaikan stok.</p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('admin.pos.index') }}" class="py-2 px-3 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm btn-press hover:bg-surface-container-high flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        <span>Kembali ke POS</span>
      </a>
      <button onclick="openProductModal()" class="py-2 px-4 bg-primary text-on-primary border-2 border-on-surface font-headline-sm text-xs font-black uppercase neo-shadow btn-press hover:bg-primary-container flex items-center gap-1.5">
        <span class="material-symbols-outlined text-base">add</span>
        <span>Tambah Produk Baru</span>
      </button>
    </div>
  </div>

  <!-- Products Table -->
  <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow overflow-x-auto">
    <table class="w-full text-left border-collapse text-xs">
      <thead>
        <tr class="bg-surface-container-high border-b-2 border-on-surface font-headline-sm uppercase text-on-surface">
          <th class="p-3">Nama Produk</th>
          <th class="p-3">Kategori</th>
          <th class="p-3">Harga</th>
          <th class="p-3">Stok</th>
          <th class="p-3">Status</th>
          <th class="p-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y-2 divide-on-surface/10 font-body-md">
        @foreach ($products as $product)
          <tr class="hover:bg-surface-container-low transition">
            <td class="p-3 font-headline-sm font-bold text-on-surface">
              {{ $product->name }}
            </td>
            <td class="p-3">
              <span class="px-2 py-0.5 font-label-sm font-bold uppercase border border-on-surface text-[10px] bg-secondary-fixed">
                {{ $product->category }}
              </span>
            </td>
            <td class="p-3 font-headline-sm font-bold text-primary text-sm">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </td>
            <td class="p-3 font-label-sm font-bold">
              <span class="{{ $product->stock <= 5 ? 'text-error font-black' : 'text-on-surface' }}">
                {{ $product->stock }} pcs
              </span>
            </td>
            <td class="p-3">
              <span class="px-2 py-0.5 text-[10px] font-headline-sm font-bold uppercase border border-on-surface {{ $product->is_available ? 'bg-emerald-100 text-emerald-900' : 'bg-red-100 text-red-900' }}">
                {{ $product->is_available ? 'Tersedia' : 'Nonaktif' }}
              </span>
            </td>
            <td class="p-3 text-right">
              <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="editProduct({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->category }}', {{ $product->price }}, {{ $product->stock }}, {{ $product->is_available ? 'true' : 'false' }})" class="p-1.5 bg-surface border border-on-surface neo-shadow-sm btn-press hover:bg-surface-container-high" title="Edit">
                  <span class="material-symbols-outlined text-sm">edit</span>
                </button>
                <form method="POST" action="{{ route('admin.pos.product.toggle', $product->id) }}" class="inline">
                  @csrf
                  <button type="submit" class="p-1.5 bg-surface border border-on-surface neo-shadow-sm btn-press hover:bg-surface-container-high text-xs font-bold" title="Toggle Status">
                    <span class="material-symbols-outlined text-sm">{{ $product->is_available ? 'block' : 'check' }}</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>

<!-- Product Add / Edit Modal -->
<div id="modal-product" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-headline-lg font-black uppercase text-base" id="product-modal-title">Tambah Produk Baru</h3>
      <button onclick="closeProductModal()" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form method="POST" action="{{ route('admin.pos.save-product') }}" class="flex flex-col gap-3">
      @csrf
      <input type="hidden" name="id" id="prod-id">

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Nama Produk</label>
        <input type="text" name="name" id="prod-name" required placeholder="Contoh: Indomie Goreng Spesial" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
      </div>

      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Kategori</label>
          <select name="category" id="prod-category" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
            <option value="food">Makanan</option>
            <option value="drink">Minuman</option>
            <option value="snack">Snack</option>
          </select>
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Stok Awal</label>
          <input type="number" name="stock" id="prod-stock" required min="0" value="20" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
        </div>
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Harga Jual (Rp)</label>
        <input type="number" name="price" id="prod-price" required min="500" step="500" value="10000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm font-bold text-primary">
      </div>

      <div class="flex items-center gap-2 mt-1">
        <input type="checkbox" name="is_available" id="prod-available" value="1" checked class="w-4 h-4 rounded-none border-2 border-on-surface text-primary focus:ring-0">
        <label for="prod-available" class="font-headline-sm text-xs font-bold">Produk Tersedia untuk Dijual</label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface mt-2">
        <button type="button" onclick="closeProductModal()" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2 px-5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container">
          Simpan Produk
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openProductModal() {
    document.getElementById('prod-id').value = '';
    document.getElementById('prod-name').value = '';
    document.getElementById('prod-category').value = 'food';
    document.getElementById('prod-price').value = 10000;
    document.getElementById('prod-stock').value = 20;
    document.getElementById('prod-available').checked = true;
    document.getElementById('product-modal-title').innerText = 'Tambah Produk Baru';
    document.getElementById('modal-product').classList.remove('hidden');
  }

  function editProduct(id, name, category, price, stock, isAvailable) {
    document.getElementById('prod-id').value = id;
    document.getElementById('prod-name').value = name;
    document.getElementById('prod-category').value = category;
    document.getElementById('prod-price').value = price;
    document.getElementById('prod-stock').value = stock;
    document.getElementById('prod-available').checked = isAvailable;
    document.getElementById('product-modal-title').innerText = 'Edit Produk — ' + name;
    document.getElementById('modal-product').classList.remove('hidden');
  }

  function closeProductModal() {
    document.getElementById('modal-product').classList.add('hidden');
  }
</script>
@endsection
