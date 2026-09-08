@extends('layouts.app')

@section('title', 'Manajemen Shift Kasir - TambahBang')
@section('page_title', 'Manajemen Shift & Handover')

@section('content')
<div class="flex flex-col gap-6">
  @if ($activeShift)
    <section class="bg-surface-container-lowest border-2 border-on-surface neo-shadow-lg p-6 relative">
      <span class="absolute -top-3.5 right-6 px-3 py-1 bg-emerald-500 text-white text-xs font-bold border-2 border-on-surface">● SHIFT AKTIF</span>
      <div class="flex flex-col lg:flex-row justify-between gap-6 pb-6 border-b-2 border-on-surface">
        <div>
          <span class="text-xs font-bold uppercase text-secondary">Petugas Berjaga</span>
          <h2 class="text-2xl font-black uppercase">{{ $activeShift->user ? $activeShift->user->name : 'Kasir' }}</h2>
          <p class="text-xs font-bold text-on-surface-variant mt-0.5">Mulai: {{ $activeShift->start_time->format('d M Y • H:i') }} ({{ $activeShift->start_time->diffForHumans() }})</p>
        </div>
        <button onclick="document.getElementById('kasir-end-shift').classList.remove('hidden')" class="py-3 px-6 bg-secondary-container text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">Tutup Shift & Serah Terima</button>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm"><span class="text-[10px] uppercase font-bold block text-on-surface-variant">Modal Awal</span><span class="font-black text-lg">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</span></div>
        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm"><span class="text-[10px] uppercase font-bold block text-on-surface-variant">Omset Shift</span><span class="font-black text-xl text-emerald-700">Rp {{ number_format($liveShiftRevenue, 0, ',', '.') }}</span></div>
        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm"><span class="text-[10px] uppercase font-bold block text-on-surface-variant">Transaksi</span><span class="font-black text-lg text-primary">{{ $liveShiftTransactions }} Transaksi</span></div>
        <div class="p-3.5 bg-surface border-2 border-on-surface neo-shadow-sm"><span class="text-[10px] uppercase font-bold block text-on-surface-variant">Meja Aktif</span><span class="font-black text-lg text-secondary">{{ $ongoingRentalsCount }} Aktif</span></div>
      </div>
      <p class="mt-4 p-3 bg-blue-50 border-2 border-on-surface text-xs">Sesi rental yang berjalan <strong>tetap aktif</strong> saat ganti shift.</p>
    </section>

    <div id="kasir-end-shift" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 p-4">
      <form method="POST" action="{{ route('kasir.shifts.end', $activeShift->id) }}" class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg flex flex-col gap-4">
        @csrf
        <h3 class="font-black uppercase">Tutup Shift #{{ $activeShift->id }}</h3>
        <p class="text-xs text-on-surface-variant">Omset tercatat Rp {{ number_format($liveShiftRevenue, 0, ',', '.') }} dari {{ $liveShiftTransactions }} transaksi. {{ $ongoingRentalsCount }} sesi tetap berjalan.</p>
        <textarea name="notes" placeholder="Catatan serah terima..." class="px-3 py-2 border-2 border-on-surface text-xs"></textarea>
        <div class="flex justify-end gap-2">
          <button type="button" onclick="document.getElementById('kasir-end-shift').classList.add('hidden')" class="px-4 py-2 border-2 border-on-surface text-xs font-bold btn-press">BATAL</button>
          <button class="px-5 py-2 bg-secondary-container text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">TUTUP SHIFT</button>
        </div>
      </form>
    </div>
  @else
    <section class="p-8 bg-surface-container-lowest border-2 border-on-surface neo-shadow text-center flex flex-col items-center gap-4">
      <h3 class="font-bold uppercase">Belum Ada Shift Aktif</h3>
      <button onclick="document.getElementById('kasir-start-shift').classList.remove('hidden')" class="py-2.5 px-6 bg-primary text-white text-xs font-black uppercase border-2 border-on-surface neo-shadow btn-press">+ Buka Shift Baru</button>
    </section>
  @endif

  <div id="kasir-start-shift" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 p-4">
    <form method="POST" action="{{ route('kasir.shifts.start') }}" class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg flex flex-col gap-4">
      @csrf
      <h3 class="font-black uppercase">Buka Shift Baru</h3>
      <div><label class="text-xs font-bold uppercase">Modal kas awal (Rp)</label>
      <input type="number" name="starting_cash" value="200000" min="0" required class="w-full mt-1 px-3 py-2 border-2 border-on-surface text-xs font-bold"></div>
      <textarea name="notes" placeholder="Catatan..." class="px-3 py-2 border-2 border-on-surface text-xs"></textarea>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="document.getElementById('kasir-start-shift').classList.add('hidden')" class="px-4 py-2 border-2 border-on-surface text-xs font-bold btn-press">BATAL</button>
        <button class="px-5 py-2 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">MULAI SHIFT</button>
      </div>
    </form>
  </div>

  <section class="grid grid-cols-1 xl:grid-cols-12 gap-6">
    <div class="xl:col-span-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <div class="p-4 bg-surface-container-low border-b-2 border-on-surface"><h3 class="font-black uppercase text-sm">Sesi Aktif ({{ $activeSessions->count() }})</h3></div>
      <div class="p-4 flex flex-col gap-3 max-h-[420px] overflow-y-auto">
        @forelse ($activeSessions as $s)
          <div class="p-3 border-2 border-on-surface flex justify-between items-center text-xs">
            <div><p class="font-black">{{ $s->tv ? $s->tv->name : 'Meja' }}</p><p class="text-on-surface-variant">{{ $s->start_time->format('H:i') }} • Rp {{ number_format($s->total_amount, 0, ',', '.') }}</p></div>
            <a href="{{ route('kasir.pos', ['session_id' => $s->id]) }}" class="px-3 py-1.5 bg-primary text-white font-bold border-2 border-on-surface btn-press">KELOLA</a>
          </div>
        @empty
          <p class="text-xs text-center text-on-surface-variant py-6">Tidak ada sesi berjalan.</p>
        @endforelse
      </div>
    </div>
    <div class="xl:col-span-7 bg-surface-container-lowest border-2 border-on-surface neo-shadow overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead><tr class="bg-surface-container-high border-b-2 border-on-surface uppercase"><th class="p-3">Shift</th><th class="p-3">Kasir</th><th class="p-3 text-right">Omset</th><th class="p-3 text-center">Status</th></tr></thead>
        <tbody>
          @foreach ($shiftHistory as $sh)
            <tr class="border-b border-on-surface/20">
              <td class="p-3 font-bold">#{{ $sh->id }} • {{ $sh->start_time->format('d M H:i') }}</td>
              <td class="p-3">{{ $sh->user ? $sh->user->name : '-' }}</td>
              <td class="p-3 text-right font-bold text-primary">Rp {{ number_format($sh->total_revenue, 0, ',', '.') }}</td>
              <td class="p-3 text-center"><span class="px-2 py-0.5 text-[10px] font-bold uppercase border border-on-surface {{ $sh->status === 'active' ? 'bg-emerald-100 text-emerald-900' : 'bg-surface-container text-on-surface-variant' }}">{{ $sh->status }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="p-3">{{ $shiftHistory->links() }}</div>
    </div>
  </section>
</div>
@endsection
