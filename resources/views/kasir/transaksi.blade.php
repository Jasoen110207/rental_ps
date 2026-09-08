@extends('layouts.app')

@section('title', 'Transactions - TambahBang')
@section('page_title', 'Riwayat Transaksi & Invoicing')

@section('content')
<div class="flex flex-col gap-6">
  <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <span class="text-[11px] uppercase font-bold text-on-surface-variant">TOTAL OMSET</span>
      <p class="font-black text-2xl">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
      <p class="text-xs text-on-surface-variant mt-1">{{ $transactions->total() }} transaksi lunas</p>
    </div>
    <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <span class="text-[11px] uppercase font-bold text-on-surface-variant">RENTAL</span>
      <p class="font-black text-2xl text-primary">Rp {{ number_format($totalRentalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <span class="text-[11px] uppercase font-bold text-on-surface-variant">F&B</span>
      <p class="font-black text-2xl text-secondary">Rp {{ number_format($totalFnbRevenue, 0, ',', '.') }}</p>
    </div>
  </section>

  <section class="p-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
    <form method="GET" action="{{ route('kasir.transaksi') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
      <div class="md:col-span-3">
        <label class="text-[11px] uppercase font-bold text-on-surface-variant">Unit</label>
        <select name="tv_id" class="w-full px-3 py-2 border-2 border-on-surface text-xs font-bold">
          <option value="">Semua Unit</option>
          @foreach ($tvs as $tv)
            <option value="{{ $tv->id }}" {{ request('tv_id') == $tv->id ? 'selected' : '' }}>{{ $tv->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-3">
        <label class="text-[11px] uppercase font-bold text-on-surface-variant">Tipe</label>
        <select name="billing_type" class="w-full px-3 py-2 border-2 border-on-surface text-xs font-bold">
          <option value="">Semua</option>
          <option value="prepaid" {{ request('billing_type') === 'prepaid' ? 'selected' : '' }}>Prepaid</option>
          <option value="postpaid" {{ request('billing_type') === 'postpaid' ? 'selected' : '' }}>Postpaid</option>
        </select>
      </div>
      <div class="md:col-span-4">
        <label class="text-[11px] uppercase font-bold text-on-surface-variant">Cari ID / Unit</label>
        <input name="search" value="{{ request('search') }}" placeholder="#ID atau nama unit..." class="w-full px-3 py-2 border-2 border-on-surface text-xs">
      </div>
      <div class="md:col-span-2">
        <button class="w-full py-2 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">FILTER</button>
      </div>
    </form>
  </section>

  <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    <div class="lg:col-span-8 bg-surface-container-lowest border-2 border-on-surface neo-shadow overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead><tr class="border-b-2 border-on-surface bg-surface-container uppercase">
          <th class="p-3">ID / Waktu</th><th class="p-3">Unit</th><th class="p-3 text-right">Rental</th><th class="p-3 text-right">F&B</th><th class="p-3 text-right">Total</th><th class="p-3 text-center">Struk</th>
        </tr></thead>
        <tbody>
          @forelse ($transactions as $trx)
            <tr class="border-b border-on-surface/20 hover:bg-surface-container-low">
              <td class="p-3"><p class="font-bold text-primary">#{{ $trx->id }}</p><p class="text-[11px] text-on-surface-variant">{{ $trx->end_time ? $trx->end_time->format('d M H:i') : '-' }}</p></td>
              <td class="p-3 font-bold">{{ $trx->tv ? $trx->tv->name : '-' }} <span class="text-[10px] text-on-surface-variant block">{{ strtoupper($trx->billing_type) }}</span></td>
              <td class="p-3 text-right">Rp {{ number_format($trx->rental_amount, 0, ',', '.') }}</td>
              <td class="p-3 text-right text-secondary">Rp {{ number_format($trx->fnb_amount, 0, ',', '.') }}</td>
              <td class="p-3 text-right font-black">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
              <td class="p-3 text-center"><a href="{{ route('admin.transactions.invoice', $trx->id) }}" target="_blank" class="px-2 py-1 bg-surface border-2 border-on-surface text-[11px] font-bold btn-press inline-block">STRUK</a></td>
            </tr>
          @empty
            <tr><td colspan="6" class="p-8 text-center text-on-surface-variant">Belum ada transaksi lunas.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="p-3">{{ $transactions->links() }}</div>
    </div>

    <div class="lg:col-span-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow p-5">
      <h3 class="font-black uppercase text-sm border-b-2 border-dashed border-on-surface pb-3 text-center">STRUK TERAKHIR</h3>
      @if ($preview)
        <div class="text-center py-3 border-b-2 border-dashed border-on-surface">
          <p class="font-black uppercase">TambahBang PS Hub</p>
          <p class="text-[11px] text-on-surface-variant">#{{ $preview->id }} • {{ $preview->tv ? $preview->tv->name : '' }} • {{ $preview->end_time ? $preview->end_time->format('d/m H:i') : '' }}</p>
        </div>
        <div class="py-3 flex flex-col gap-1.5 text-xs">
          @foreach ($preview->sessionOrders as $o)
            <div class="flex justify-between"><span>{{ $o->quantity }}x {{ $o->product ? $o->product->name : 'Item' }}</span><span class="font-bold">Rp {{ number_format($o->subtotal, 0, ',', '.') }}</span></div>
          @endforeach
          <div class="flex justify-between text-on-surface-variant"><span>Rental:</span><span>Rp {{ number_format($preview->rental_amount, 0, ',', '.') }}</span></div>
          <div class="flex justify-between font-black text-base pt-2 border-t-2 border-on-surface"><span>TOTAL:</span><span class="text-primary">Rp {{ number_format($preview->total_amount, 0, ',', '.') }}</span></div>
        </div>
        <a href="{{ route('admin.transactions.invoice', $preview->id) }}" target="_blank" class="w-full py-2.5 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press flex items-center justify-center gap-1">CETAK STRUK</a>
      @else
        <p class="text-xs text-on-surface-variant text-center py-8">Belum ada struk.</p>
      @endif
    </div>
  </section>
</div>
@endsection
