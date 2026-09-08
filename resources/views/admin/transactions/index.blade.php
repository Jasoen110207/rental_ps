@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi & Invoicing')

@section('content')
<div class="flex flex-col gap-6">

  <!-- Summary Metric Strip -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="p-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex justify-between items-center">
      <div>
        <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Total Pendapatan Keseluruhan</span>
        <span class="text-2xl font-headline-lg font-black text-emerald-700">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
      </div>
      <span class="material-symbols-outlined text-3xl text-emerald-600">account_balance_wallet</span>
    </div>

    <div class="p-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex justify-between items-center">
      <div>
        <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Pendapatan Rental PS</span>
        <span class="text-2xl font-headline-lg font-black text-primary">Rp {{ number_format($totalRentalRevenue, 0, ',', '.') }}</span>
      </div>
      <span class="material-symbols-outlined text-3xl text-primary">sports_esports</span>
    </div>

    <div class="p-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex justify-between items-center">
      <div>
        <span class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant block">Pendapatan F&B / Kantin</span>
        <span class="text-2xl font-headline-lg font-black text-secondary">Rp {{ number_format($totalFnbRevenue, 0, ',', '.') }}</span>
      </div>
      <span class="material-symbols-outlined text-3xl text-secondary">restaurant</span>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="p-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
    <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
      <!-- Search Input -->
      <div>
        <label class="block font-headline-sm text-[11px] uppercase font-bold mb-1">Cari ID / Meja</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nomor transaksi / nama meja" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
      </div>

      <!-- TV Unit Filter -->
      <div>
        <label class="block font-headline-sm text-[11px] uppercase font-bold mb-1">Filter Meja</label>
        <select name="tv_id" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
          <option value="">Semua Meja</option>
          @foreach ($tvs as $tv)
            <option value="{{ $tv->id }}" {{ request('tv_id') == $tv->id ? 'selected' : '' }}>
              {{ $tv->name }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Billing Type -->
      <div>
        <label class="block font-headline-sm text-[11px] uppercase font-bold mb-1">Tipe Billing</label>
        <select name="billing_type" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
          <option value="">Semua Tipe</option>
          <option value="prepaid" {{ request('billing_type') == 'prepaid' ? 'selected' : '' }}>Prepaid</option>
          <option value="postpaid" {{ request('billing_type') == 'postpaid' ? 'selected' : '' }}>Postpaid</option>
        </select>
      </div>

      <!-- Submit & Reset -->
      <div class="flex items-end gap-2">
        <button type="submit" class="flex-1 py-2 bg-primary text-on-primary font-headline-sm text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container flex items-center justify-center gap-1">
          <span class="material-symbols-outlined text-sm">filter_alt</span>
          <span>Filter</span>
        </button>
        <a href="{{ route('admin.transactions.index') }}" class="p-2 bg-surface border-2 border-on-surface neo-shadow-sm btn-press hover:bg-surface-container-high" title="Reset Filter">
          <span class="material-symbols-outlined text-sm">refresh</span>
        </a>
      </div>
    </form>
  </div>

  <!-- Transactions Table -->
  <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow overflow-x-auto">
    <table class="w-full text-left border-collapse text-xs">
      <thead>
        <tr class="bg-surface-container-high border-b-2 border-on-surface font-headline-sm uppercase text-on-surface">
          <th class="p-3">ID & Waktu</th>
          <th class="p-3">Unit PlayStation</th>
          <th class="p-3">Kasir</th>
          <th class="p-3">Tipe</th>
          <th class="p-3">Durasi</th>
          <th class="p-3">Rental</th>
          <th class="p-3">F&B</th>
          <th class="p-3">Total Lunas</th>
          <th class="p-3">Metode</th>
          <th class="p-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y-2 divide-on-surface/10 font-body-md">
        @forelse ($transactions as $tx)
          @php
            $durationMinutes = $tx->start_time && $tx->end_time ? $tx->start_time->diffInMinutes($tx->end_time) : 0;
            $durationFormatted = floor($durationMinutes / 60) . 'j ' . ($durationMinutes % 60) . 'm';
          @endphp
          <tr class="hover:bg-surface-container-low transition">
            <td class="p-3 font-label-sm font-bold">
              <span class="text-primary block font-black">#TB-{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</span>
              <span class="text-[10px] text-on-surface-variant">{{ $tx->end_time ? $tx->end_time->format('d/m/Y H:i') : '-' }}</span>
            </td>
            <td class="p-3 font-headline-sm font-bold text-on-surface">
              {{ $tx->tv ? $tx->tv->name : 'Meja' }}
            </td>
            <td class="p-3 text-on-surface-variant font-medium">
              {{ $tx->user ? $tx->user->name : 'Kasir' }}
            </td>
            <td class="p-3">
              <span class="px-2 py-0.5 text-[10px] font-headline-sm font-bold uppercase border border-on-surface {{ $tx->billing_type === 'prepaid' ? 'bg-primary-fixed text-primary' : 'bg-secondary-fixed text-secondary' }}">
                {{ $tx->billing_type }}
              </span>
            </td>
            <td class="p-3 font-label-sm font-bold">
              {{ $durationFormatted }}
            </td>
            <td class="p-3 font-label-sm">
              Rp {{ number_format($tx->rental_amount, 0, ',', '.') }}
            </td>
            <td class="p-3 font-label-sm">
              Rp {{ number_format($tx->fnb_amount, 0, ',', '.') }}
            </td>
            <td class="p-3 font-headline-sm font-black text-emerald-700 text-sm">
              Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
            </td>
            <td class="p-3">
              <span class="px-2 py-0.5 font-label-sm text-[10px] uppercase font-bold border border-on-surface bg-surface">
                {{ $tx->payment_method }}
              </span>
            </td>
            <td class="p-3 text-right">
              <a href="{{ route('admin.transactions.invoice', $tx->id) }}" target="_blank" class="py-1 px-2.5 bg-surface border-2 border-on-surface font-headline-sm text-[10px] uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high inline-flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">print</span>
                <span>Struk</span>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="p-8 text-center text-on-surface-variant font-headline-sm uppercase font-bold">
              Belum ada riwayat transaksi yang ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>
    {{ $transactions->links() }}
  </div>

</div>
@endsection
