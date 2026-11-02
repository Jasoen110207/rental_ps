@extends('layouts.admin')

@section('title', 'Payroll Kasir')
@section('page_title', 'Payroll Kasir')

@section('content')
<div class="flex flex-col gap-6">
  <div class="flex items-center justify-between gap-4 bg-surface-container-lowest border-2 border-on-surface neo-shadow p-4">
    <div>
      <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Payroll</p>
      <h1 class="font-headline-lg text-2xl uppercase font-black">PAYROLL KASIR</h1>
    </div>

    <div class="flex items-center gap-3">
      <form method="GET" action="{{ route('admin.payroll.index') }}" class="flex items-center gap-2">
        <div>
          <label for="kasir-select" class="sr-only">Kasir</label>
          <select name="kasir_id" id="kasir-select" onchange="this.form.submit()" class="border-2 border-on-surface p-2 font-bold text-xs uppercase bg-surface-container-low">
            @if($kasirList->isNotEmpty())
              @foreach($kasirList as $kasirItem)
                <option value="{{ $kasirItem->id }}" {{ ($selectedKasir?->id == $kasirItem->id) ? 'selected' : '' }}>
                  {{ $kasirItem->name }}
                </option>
              @endforeach
            @else
              <option value="">Belum ada kasir</option>
            @endif
          </select>
        </div>
        <div>
          <label for="month-select" class="sr-only">Bulan</label>
          <input type="month" name="month" id="month-select" value="{{ $periodMonth ?? request('month', date('Y-m')) }}" onchange="this.form.submit()" class="border-2 border-on-surface p-2 font-bold text-xs uppercase bg-surface-container-low">
        </div>
      </form>
    </div>
  </div>

  @if ($kasir && $selectedKasir)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="p-4 bg-emerald-50 border-2 border-on-surface neo-shadow">
        <div class="flex items-center justify-between">
          <span class="text-[11px] uppercase font-bold text-emerald-900">Nama Kasir</span>
          <span class="material-symbols-outlined text-xl text-emerald-700">person</span>
        </div>
        <div class="mt-2 font-headline-lg font-black text-emerald-950 text-lg">{{ $kasir['name'] }}</div>
      </div>

      <div class="p-4 bg-blue-50 border-2 border-on-surface neo-shadow">
        <div class="flex items-center justify-between">
          <span class="text-[11px] uppercase font-bold text-blue-900">Shift / Jam Kerja</span>
          <span class="material-symbols-outlined text-xl text-primary">schedule</span>
        </div>
        <div class="mt-2 font-headline-lg font-black text-blue-950 text-lg">{{ $kasir['shift'] }}</div>
        <div class="text-[11px] text-blue-800 font-bold">{{ $kasir['work_hours'] }}</div>
      </div>

      <div class="p-4 bg-amber-50 border-2 border-on-surface neo-shadow">
        <div class="flex items-center justify-between">
          <span class="text-[11px] uppercase font-bold text-amber-900">Total Absen Shift</span>
          <span class="material-symbols-outlined text-xl text-amber-600">calendar_month</span>
        </div>
        <div class="mt-2 font-headline-lg font-black text-amber-950 text-lg">{{ $kasir['total_shifts'] ?? $kasir['total_work_days'] }} Shift</div>
      </div>

      <div class="p-4 bg-primary-fixed border-2 border-on-surface neo-shadow">
        <div class="flex items-center justify-between">
          <span class="text-[11px] uppercase font-bold text-primary">Total Gaji</span>
          <span class="material-symbols-outlined text-xl text-primary">payments</span>
        </div>
        <div class="mt-2 font-headline-lg font-black text-on-surface text-lg">Rp {{ number_format($kasir['net_salary'], 0, ',', '.') }}</div>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[1.5fr_0.9fr] gap-6">
      <section class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
        <div class="p-4 border-b-2 border-on-surface bg-surface-container-high flex items-center justify-between">
          <div>
            <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Slip Gaji</p>
            <h2 class="font-headline-lg text-xl uppercase font-black">Rincian Komponen Gaji</h2>
          </div>
          <span class="px-2.5 py-1 border-2 border-on-surface bg-secondary-container text-on-secondary text-[10px] font-black uppercase neo-shadow-sm">{{ $kasir['period'] }}</span>
        </div>

        <div class="p-5 flex flex-col gap-4">
          <div class="flex items-center justify-between p-3 border-2 border-on-surface bg-surface-container-low">
            <span class="text-[11px] uppercase font-bold text-on-surface-variant">Upah per Shift</span>
            <span class="font-headline-lg font-black text-on-surface">Rp {{ number_format($kasir['shift_rate'] ?? 50000, 0, ',', '.') }}</span>
          </div>

          <div class="flex items-center justify-between p-3 border-2 border-on-surface bg-surface-container-low">
            <span class="text-[11px] uppercase font-bold text-on-surface-variant">Total Absen Shift Bulan Ini</span>
            <span class="font-headline-lg font-black text-on-surface">{{ $kasir['total_shifts'] ?? $kasir['total_work_days'] }} Shift</span>
          </div>

          <div class="flex items-center justify-between p-3 border-2 border-on-surface bg-surface-container-low">
            <span class="text-[11px] uppercase font-bold text-on-surface-variant">Perhitungan Gaji</span>
            <span class="font-headline-lg font-black text-primary">{{ $kasir['total_shifts'] ?? $kasir['total_work_days'] }} Shift × Rp {{ number_format($kasir['shift_rate'] ?? 50000, 0, ',', '.') }}</span>
          </div>

          <div class="p-4 border-2 border-on-surface bg-primary text-on-primary neo-shadow">
            <div class="flex items-center justify-between">
              <span class="text-[11px] uppercase font-bold tracking-wide opacity-85">Total Gaji Bersih / Nett</span>
              <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div class="mt-2 text-3xl font-headline-lg font-black">Rp {{ number_format($kasir['net_salary'], 0, ',', '.') }}</div>
          </div>
        </div>
      </section>

      <aside class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
        <div class="p-4 border-b-2 border-on-surface bg-surface-container-high">
          <h2 class="font-headline-lg text-xl uppercase font-black">Status Pembayaran</h2>
        </div>

        <div class="p-5 flex flex-col gap-4">
          <div class="p-4 border-2 border-on-surface bg-surface-container-low">
            <p class="text-[11px] uppercase font-bold text-on-surface-variant">Status</p>
            <div class="mt-2 inline-flex items-center px-2.5 py-1 border-2 {{ !empty($kasir['is_paid']) ? 'border-emerald-900 bg-emerald-100 text-emerald-900' : 'border-amber-900 bg-amber-100 text-amber-900' }} font-black text-[10px] uppercase tracking-wide neo-shadow-sm">
              {{ $kasir['status'] }}
            </div>
          </div>

          <div class="p-4 border-2 border-on-surface bg-surface-container-low">
            <p class="text-[11px] uppercase font-bold text-on-surface-variant">Nama & Jabatan</p>
            <div class="mt-2 font-headline-lg font-black text-on-surface text-lg">{{ $kasir['name'] }}</div>
            <div class="text-sm text-on-surface-variant">{{ $kasir['position'] }}</div>
          </div>

          <div class="flex flex-col gap-3">
            <a href="{{ route('admin.payroll.print', ['kasir_id' => optional($selectedKasir)->id, 'month' => $periodMonth ?? request('month', date('Y-m'))]) }}" class="w-full py-3 border-2 border-on-surface bg-secondary-container text-on-secondary font-black uppercase text-xs tracking-wide neo-shadow btn-press text-center">
              Cetak Slip Gaji
            </a>
            <a href="{{ route('admin.payroll.pdf', ['kasir_id' => optional($selectedKasir)->id, 'month' => $periodMonth ?? request('month', date('Y-m'))]) }}" class="w-full py-3 border-2 border-on-surface bg-primary text-on-primary font-black uppercase text-xs tracking-wide neo-shadow btn-press text-center">
              Download PDF
            </a>
            <form method="POST" action="{{ route('admin.payroll.confirm', ['kasir_id' => optional($selectedKasir)->id, 'month' => $periodMonth ?? request('month', date('Y-m'))]) }}">
              @csrf
              <button type="submit" class="w-full py-3 border-2 border-on-surface bg-emerald-600 text-white font-black uppercase text-xs tracking-wide neo-shadow btn-press">
                Konfirmasi Pembayaran
              </button>
            </form>
          </div>
        </div>
      </aside>
    </div>

    <section class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <div class="p-4 border-b-2 border-on-surface bg-surface-container-high flex items-center justify-between">
        <div>
          <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Log Kehadiran</p>
          <h2 class="font-headline-lg text-xl uppercase font-black">Riwayat Absen Shift Bulan Ini</h2>
        </div>
        <span class="px-2.5 py-1 border-2 border-on-surface bg-amber-400 text-on-surface text-[10px] font-black uppercase neo-shadow-sm">{{ isset($attendanceShifts) ? $attendanceShifts->count() : 0 }} shift</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-surface-container-high border-b-2 border-on-surface text-[11px] uppercase tracking-wide">
            <tr>
              <th class="py-3 px-4">Tipe Shift</th>
              <th class="py-3 px-4">Jam Masuk (Absen)</th>
              <th class="py-3 px-4">Jam Selesai</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4">Upah Shift</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($attendanceShifts ?? [] as $shiftItem)
              <tr class="border-b border-on-surface/20 align-top">
                <td class="py-3 px-4 font-bold">{{ $shiftItem->shift_label }}</td>
                <td class="py-3 px-4 font-mono text-xs">{{ $shiftItem->start_time ? $shiftItem->start_time->format('d M Y, H:i') : '-' }}</td>
                <td class="py-3 px-4 font-mono text-xs">
                  {{ $shiftItem->end_time ? $shiftItem->end_time->format('d M Y, H:i') : 'Sedang Berjalan' }}
                </td>
                <td class="py-3 px-4">
                  @if($shiftItem->status === 'active')
                    <span class="inline-flex items-center px-2 py-0.5 border-2 border-emerald-900 bg-emerald-100 text-emerald-950 font-black text-[10px] uppercase tracking-wide">
                      Aktif
                    </span>
                  @else
                    <span class="inline-flex items-center px-2 py-0.5 border-2 border-on-surface/40 bg-surface-container text-on-surface font-black text-[10px] uppercase tracking-wide">
                      Selesai
                    </span>
                  @endif
                </td>
                <td class="py-3 px-4 font-bold text-primary">Rp 50.000</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-6 px-4 text-center text-on-surface-variant">Belum ada catatan absensi shift untuk bulan ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    <section class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <div class="p-4 border-b-2 border-on-surface bg-surface-container-high flex items-center justify-between">
        <div>
          <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">History Pembayaran</p>
          <h2 class="font-headline-lg text-xl uppercase font-black">Riwayat Gaji Per Bulan</h2>
        </div>
        <span class="px-2.5 py-1 border-2 border-on-surface bg-primary text-on-primary text-[10px] font-black uppercase neo-shadow-sm">{{ $history->count() }} record</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-surface-container-high border-b-2 border-on-surface text-[11px] uppercase tracking-wide">
            <tr>
              <th class="py-3 px-4">Bulan</th>
              <th class="py-3 px-4">Jumlah</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4">Tanggal Bayar</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($history as $item)
              <tr class="border-b border-on-surface/20 align-top">
                <td class="py-3 px-4 font-bold text-primary">{{ \Carbon\Carbon::createFromFormat('Y-m', $item['period'])->translatedFormat('F Y') }}</td>
                <td class="py-3 px-4 font-bold">Rp {{ number_format($item['amount'], 0, ',', '.') }}</td>
                <td class="py-3 px-4">
                  <span class="inline-flex items-center px-2.5 py-1 border-2 border-on-surface bg-emerald-100 text-emerald-900 font-black text-[10px] uppercase tracking-wide neo-shadow-sm">
                    {{ $item['status'] }}
                  </span>
                </td>
                <td class="py-3 px-4 text-[12px]">{{ $item['paid_at'] }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="py-6 px-4 text-center text-on-surface-variant">Belum ada riwayat pembayaran gaji.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  @else
    <div class="p-8 border-2 border-on-surface bg-surface-container-lowest neo-shadow text-center">
      <p class="text-[11px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Payroll</p>
      <h2 class="mt-3 font-headline-lg text-2xl uppercase font-black">Belum ada data kasir</h2>
      <p class="mt-2 text-sm text-on-surface-variant">Silakan tambahkan user dengan role <strong>kasir</strong> terlebih dahulu agar payroll bisa dipilih dan dihitung.</p>
    </div>
  @endif
</div>
@endsection
