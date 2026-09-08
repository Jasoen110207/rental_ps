@extends('layouts.admin')

@section('title', 'Unit Konsol & QR Generator')
@section('page_title', 'Kelola Unit PlayStation & QR Code')

@section('content')
<div class="flex flex-col gap-6">

  <!-- Header -->
  <div class="flex items-center justify-between pb-4 border-b-2 border-on-surface">
    <div>
      <h2 class="text-xl font-headline-lg font-black uppercase text-on-surface">Daftar Meja & Unit Konsol</h2>
      <p class="text-xs text-on-surface-variant font-medium mt-0.5">Kelola data PlayStation, sesuaikan tarif per jam, dan cetak QR code meja.</p>
    </div>

    <button onclick="openUnitModal()" class="py-2.5 px-5 bg-primary text-on-primary font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container flex items-center gap-1.5">
      <span class="material-symbols-outlined text-base">add</span>
      <span>Tambah Unit Konsol Baru</span>
    </button>
  </div>

  <!-- Units Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach ($units as $unit)
      <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
        <div>
          <!-- Header -->
          <div class="flex items-start justify-between pb-3 border-b-2 border-on-surface">
            <div>
              <span class="text-[9px] font-label-sm font-bold uppercase px-1.5 py-0.5 border border-on-surface bg-primary-fixed text-primary">
                {{ strtoupper($unit->type) }}
              </span>
              <h3 class="font-headline-lg font-black text-lg uppercase mt-1 text-on-surface">{{ $unit->name }}</h3>
              <p class="font-label-sm text-xs font-black text-primary mt-0.5">
                Rp {{ number_format($unit->price_per_hour, 0, ',', '.') }} / jam
              </p>
            </div>

            <span class="px-2 py-0.5 text-[10px] font-headline-sm font-bold uppercase border border-on-surface {{ $unit->status === 'available' ? 'bg-emerald-100 text-emerald-900' : ($unit->status === 'playing' ? 'bg-blue-100 text-blue-900' : 'bg-red-100 text-red-900') }}">
              {{ $unit->status }}
            </span>
          </div>

          <!-- Unit Info -->
          <div class="py-4 flex flex-col gap-2">
            <div class="p-2.5 bg-surface border border-on-surface text-xs font-label-sm flex items-center justify-between">
              <span class="text-on-surface-variant font-bold">QR Customer URL:</span>
              <a href="{{ route('customer.index', $unit->id) }}" target="_blank" class="text-primary font-bold hover:underline truncate max-w-[150px]">
                /customer/{{ $unit->id }}
              </a>
            </div>

            @if ($unit->is_buzzer_on)
              <div class="p-2 bg-error text-on-error font-headline-sm text-xs font-bold uppercase flex items-center justify-between">
                <span>🚨 Buzzer Aktif</span>
                <form method="POST" action="{{ route('admin.rental.toggle-buzzer', $unit->id) }}">
                  @csrf
                  <button type="submit" class="px-2 py-0.5 bg-white text-error text-[10px] font-bold border border-on-surface">Matikan</button>
                </form>
              </div>
            @endif
          </div>
        </div>

        <!-- Action Row -->
        <div class="pt-3 border-t-2 border-on-surface flex items-center justify-between gap-2">
          <div class="flex items-center gap-2">
            <button type="button" onclick="editUnit({{ $unit->id }}, '{{ addslashes($unit->name) }}', '{{ $unit->type }}', {{ $unit->price_per_hour }}, '{{ $unit->status }}')" class="py-1.5 px-3 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm btn-press hover:bg-surface-container-high flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">edit</span>
              <span>Edit</span>
            </button>

            <form method="POST" action="{{ route('admin.rental.toggle-buzzer', $unit->id) }}" class="inline">
              @csrf
              <button type="submit" class="p-1.5 bg-surface border-2 border-on-surface neo-shadow-sm btn-press hover:bg-surface-container-high" title="Test Buzzer Audio">
                <span class="material-symbols-outlined text-sm">volume_up</span>
              </button>
            </form>
          </div>

          <a href="{{ route('admin.units.qr', $unit->id) }}" target="_blank" class="py-1.5 px-3 bg-secondary-container text-on-secondary font-headline-sm text-xs font-bold uppercase border-2 border-on-surface neo-shadow-sm btn-press hover:bg-secondary flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">qr_code</span>
            <span>Cetak QR</span>
          </a>
        </div>
      </div>
    @endforeach
  </div>

</div>

<!-- Unit Modal -->
<div id="modal-unit" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-headline-lg font-black uppercase text-base" id="unit-modal-title">Tambah Unit Konsol</h3>
      <button onclick="closeUnitModal()" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form id="unit-form" method="POST" action="{{ route('admin.units.store') }}" class="flex flex-col gap-3">
      @csrf
      <input type="hidden" name="_method" id="unit-form-method" value="POST">

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Nama / Nomor Meja</label>
        <input type="text" name="name" id="unit-name" required placeholder="Contoh: PS 09 - PS5 VIP Arena" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
      </div>

      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Tipe Konsol</label>
          <select name="type" id="unit-type" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
            <option value="ps3">PS3</option>
            <option value="ps4">PS4</option>
            <option value="ps5">PS5</option>
            <option value="sim_racing">Sim Racing</option>
            <option value="nintendo_switch">Nintendo Switch</option>
          </select>
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Status</label>
          <select name="status" id="unit-status" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
            <option value="available">Tersedia</option>
            <option value="maintenance">Maintenance</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Tarif Per Jam (Rp)</label>
        <input type="number" name="price_per_hour" id="unit-price" required min="1000" step="1000" value="15000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-sm font-bold text-primary neo-shadow-sm">
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface mt-2">
        <button type="button" onclick="closeUnitModal()" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2 px-5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container">
          Simpan Unit
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openUnitModal() {
    document.getElementById('unit-form').action = '{{ route("admin.units.store") }}';
    document.getElementById('unit-form-method').value = 'POST';
    document.getElementById('unit-name').value = '';
    document.getElementById('unit-type').value = 'ps5';
    document.getElementById('unit-status').value = 'available';
    document.getElementById('unit-price').value = 25000;
    document.getElementById('unit-modal-title').innerText = 'Tambah Unit Konsol';
    document.getElementById('modal-unit').classList.remove('hidden');
  }

  function editUnit(id, name, type, price, status) {
    document.getElementById('unit-form').action = '/admin/units/' + id;
    document.getElementById('unit-form-method').value = 'PUT';
    document.getElementById('unit-name').value = name;
    document.getElementById('unit-type').value = type;
    document.getElementById('unit-status').value = status;
    document.getElementById('unit-price').value = price;
    document.getElementById('unit-modal-title').innerText = 'Edit Unit — ' + name;
    document.getElementById('modal-unit').classList.remove('hidden');
  }

  function closeUnitModal() {
    document.getElementById('modal-unit').classList.add('hidden');
  }
</script>
@endsection
