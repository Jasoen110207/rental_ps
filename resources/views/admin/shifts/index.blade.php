@extends('layouts.admin')

@section('title', 'Manajemen Shift Kasir')
@section('page_title', 'Manajemen Shift & Serah Terima')

@section('content')
<div class="flex flex-col gap-6">

  <!-- ACTIVE SHIFT HERO CARD -->
  @if ($activeShift)
    <div class="p-6 bg-surface-container-lowest border-2 border-on-surface neo-shadow-lg relative">
      <div class="absolute -top-3.5 right-6 px-3 py-1 bg-emerald-500 text-white font-label-sm text-xs font-bold border-2 border-on-surface neo-shadow-sm uppercase">
        ● SHIFT AKTIF
      </div>

      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b-2 border-on-surface">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-primary text-on-primary border-2 border-on-surface flex items-center justify-center neo-shadow">
            <span class="material-symbols-outlined text-3xl">badge</span>
          </div>
          <div>
            <span class="text-xs font-label-sm text-secondary font-bold uppercase tracking-wider block">Petugas Kasir Berjaga</span>
            <h2 class="text-2xl font-headline-lg font-black uppercase text-on-surface">{{ $activeShift->user ? $activeShift->user->name : 'Kasir' }}</h2>
            <p class="text-xs font-label-sm text-on-surface-variant font-bold mt-0.5">
              Mulai: {{ $activeShift->start_time->format('d M Y • H:i') }} ({{ $activeShift->start_time->diffForHumans() }})
            </p>
          </div>
        </div>

        <!-- End Shift Action Button -->
        <button onclick="openEndShiftModal({{ $activeShift->id }}, {{ $liveShiftRevenue }}, {{ $liveShiftTransactions }})" class="py-3 px-6 bg-secondary-container text-on-secondary font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-secondary flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-xl">logout</span>
          <span>Tutup Shift & Serah Terima</span>
        </button>
      </div>

      <!-- Shift Metrics Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm">
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Modal Kas Awal</span>
          <span class="font-headline-lg font-black text-lg text-on-surface">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</span>
        </div>

        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm">
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Total Omset Shift Ini</span>
          <span class="font-headline-lg font-black text-xl text-emerald-700">Rp {{ number_format($liveShiftRevenue, 0, ',', '.') }}</span>
        </div>

        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm">
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Jumlah Transaksi Lunas</span>
          <span class="font-headline-lg font-black text-lg text-primary">{{ $liveShiftTransactions }} Transaksi</span>
        </div>

        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm">
          <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Rental PS Berjalan</span>
          <span class="font-headline-lg font-black text-lg text-secondary">{{ $ongoingRentalsCount }} Meja Aktif</span>
        </div>
      </div>

      <!-- Notice for Handover -->
      <div class="mt-4 p-3 bg-blue-50 border-2 border-on-surface text-xs font-body-md flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-base">info</span>
        <span>Sesi rental PlayStation yang sedang berjalan akan <strong>tetap aktif dan tidak terputus</strong> saat pergantian shift.</span>
      </div>
    </div>
  @else
    <div class="p-8 bg-surface-container-lowest border-2 border-on-surface neo-shadow text-center flex flex-col items-center gap-4">
      <span class="material-symbols-outlined text-5xl text-on-surface-variant">history_toggle_off</span>
      <div>
        <h3 class="font-headline-lg font-bold text-lg uppercase">Belum Ada Shift Aktif</h3>
        <p class="text-xs text-on-surface-variant mt-1">Mulai shift baru untuk mencatat transaksi dan serah terima kasir.</p>
      </div>
      <button onclick="openStartShiftModal()" class="py-2.5 px-6 bg-primary text-on-primary font-headline-lg text-xs font-black uppercase tracking-wider border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container">
        + Buka Shift Baru
      </button>
    </div>
  @endif

  <!-- SHIFT HISTORY TABLE -->
  <div class="flex flex-col gap-3 mt-4">
    <div class="flex items-center justify-between">
      <h3 class="font-headline-lg font-black text-base uppercase text-on-surface">Riwayat Shift Kasir</h3>
      <button onclick="openStartShiftModal()" class="py-1.5 px-3 bg-surface border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
        + Buka Shift Baru
      </button>
    </div>

    <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow overflow-x-auto">
      <table class="w-full text-left border-collapse text-xs">
        <thead>
          <tr class="bg-surface-container-high border-b-2 border-on-surface font-headline-sm uppercase text-on-surface">
            <th class="p-3">Kasir</th>
            <th class="p-3">Waktu Mulai</th>
            <th class="p-3">Waktu Selesai</th>
            <th class="p-3">Modal Awal</th>
            <th class="p-3">Total Omset</th>
            <th class="p-3">Transaksi</th>
            <th class="p-3">Status</th>
            <th class="p-3">Catatan</th>
          </tr>
        </thead>
        <tbody class="divide-y-2 divide-on-surface/10 font-body-md">
          @forelse ($shiftHistory as $shift)
            <tr class="hover:bg-surface-container-low transition">
              <td class="p-3 font-headline-sm font-bold text-on-surface">
                {{ $shift->user ? $shift->user->name : 'Kasir' }}
              </td>
              <td class="p-3 font-label-sm">
                {{ $shift->start_time->format('d/m/Y H:i') }}
              </td>
              <td class="p-3 font-label-sm">
                {{ $shift->end_time ? $shift->end_time->format('d/m/Y H:i') : '-' }}
              </td>
              <td class="p-3 font-label-sm">
                Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}
              </td>
              <td class="p-3 font-headline-sm font-black text-emerald-700">
                Rp {{ number_format($shift->total_revenue, 0, ',', '.') }}
              </td>
              <td class="p-3 font-label-sm font-bold">
                {{ $shift->transactions_count }}
              </td>
              <td class="p-3">
                <span class="px-2 py-0.5 text-[10px] font-headline-sm font-bold uppercase border border-on-surface {{ $shift->status === 'active' ? 'bg-emerald-100 text-emerald-900 animate-pulse' : 'bg-surface' }}">
                  {{ $shift->status === 'active' ? 'Aktif' : 'Ditutup' }}
                </span>
              </td>
              <td class="p-3 text-on-surface-variant max-w-xs truncate">
                {{ $shift->notes ?? '-' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="p-8 text-center text-on-surface-variant font-headline-sm uppercase font-bold">
                Belum ada riwayat shift tersimpan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div>
      {{ $shiftHistory->links() }}
    </div>
  </div>

</div>

<!-- START SHIFT MODAL -->
<div id="modal-start-shift" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-headline-lg font-black uppercase text-base">Buka Shift Kasir Baru</h3>
      <button onclick="closeModal('modal-start-shift')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form method="POST" action="{{ route('admin.shifts.start') }}" class="flex flex-col gap-3">
      @csrf

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Kasir Bertugas</label>
        <input type="text" value="{{ auth()->user()->name ?? 'Kasir' }}" disabled class="w-full px-3 py-2 bg-surface-container-high border-2 border-on-surface font-headline-sm text-xs font-bold uppercase">
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Modal Kas Awal (Rp)</label>
        <input type="number" name="starting_cash" required min="0" step="10000" value="200000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-sm font-bold text-primary neo-shadow-sm">
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Catatan Pembukaan</label>
        <textarea name="notes" rows="2" placeholder="Kondisi meja / stok awal" class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface mt-2">
        <button type="button" onclick="closeModal('modal-start-shift')" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2 px-5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container">
          Mulai Shift
        </button>
      </div>
    </form>
  </div>
</div>

<!-- END SHIFT MODAL -->
<div id="modal-end-shift" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-headline-lg font-black uppercase text-base text-secondary">Tutup Shift & Handover</h3>
      <button onclick="closeModal('modal-end-shift')" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form id="end-shift-form" method="POST" action="" class="flex flex-col gap-3">
      @csrf

      <div class="p-3.5 bg-surface-container-high border-2 border-on-surface neo-shadow-sm flex flex-col gap-1.5 text-xs font-label-sm">
        <div class="flex justify-between">
          <span>Total Omset Shift:</span>
          <span class="font-bold text-emerald-700 text-sm" id="end-shift-revenue-display">Rp 0</span>
        </div>
        <div class="flex justify-between">
          <span>Jumlah Transaksi:</span>
          <span class="font-bold text-on-surface" id="end-shift-tx-display">0 Transaksi</span>
        </div>
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Catatan Serah Terima (Handover)</label>
        <textarea name="notes" rows="3" required placeholder="Contoh: Saldo kas sesuai, stok mie aman, serah terima ke shift malam." class="w-full px-3 py-1.5 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t-2 border-on-surface mt-2">
        <button type="button" onclick="closeModal('modal-end-shift')" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
          Batal
        </button>
        <button type="submit" class="py-2 px-5 bg-secondary-container text-on-secondary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-secondary">
          Konfirmasi Tutup Shift
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openStartShiftModal() {
    document.getElementById('modal-start-shift').classList.remove('hidden');
  }

  function openEndShiftModal(shiftId, revenue, txCount) {
    document.getElementById('end-shift-form').action = '/admin/shifts/' + shiftId + '/end';
    document.getElementById('end-shift-revenue-display').innerText = 'Rp ' + revenue.toLocaleString('id-ID');
    document.getElementById('end-shift-tx-display').innerText = txCount + ' Transaksi Lunas';
    document.getElementById('modal-end-shift').classList.remove('hidden');
  }

  function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
  }
</script>
@endsection
