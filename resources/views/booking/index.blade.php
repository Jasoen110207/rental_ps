@extends('layouts.app')

@section('title', 'Booking Rental PS')
@section('page_title', 'Booking Rental PS')

@section('content')
<div class="flex flex-col gap-6">
  <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_1.6fr] gap-6">
    <section class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <div class="p-4 border-b-2 border-on-surface bg-primary text-on-primary">
        <div class="flex items-center justify-between gap-3">
          <div>
            <p class="text-[10px] uppercase tracking-[0.2em] font-bold opacity-80">Form Booking</p>
            <h2 class="font-headline-lg text-xl uppercase font-black">Tambah Booking</h2>
          </div>
          <span class="material-symbols-outlined text-3xl">event_available</span>
        </div>
      </div>

      <form method="POST" action="{{ route('booking.store') }}" class="p-5 flex flex-col gap-4">
        @csrf
        <div>
          <label class="block text-[11px] font-bold uppercase tracking-wide text-on-surface-variant mb-2">Nama Pelanggan</label>
          <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Masukkan nama pelanggan" class="w-full px-3 py-2.5 border-2 border-on-surface bg-surface text-sm font-body-md focus:outline-none neo-shadow-sm" required>
        </div>

        <div>
          <label class="block text-[11px] font-bold uppercase tracking-wide text-on-surface-variant mb-2">Nomor HP</label>
          <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2.5 border-2 border-on-surface bg-surface text-sm font-body-md focus:outline-none neo-shadow-sm" required>
        </div>

        <div>
          <label class="block text-[11px] font-bold uppercase tracking-wide text-on-surface-variant mb-2">Pilih Unit PS</label>
          <select name="tv_id" class="w-full px-3 py-2.5 border-2 border-on-surface bg-surface text-sm font-body-md focus:outline-none neo-shadow-sm" required>
            <option value="">-- Pilih unit --</option>
            @foreach ($tvs as $tv)
              @php
                $isPlaying = $tv->status === 'playing' || ($tv->has_active_session ?? false);
                $isBooked = (bool) ($tv->is_booked ?? false);
              @endphp
              <option
                value="{{ $tv->id }}"
                data-name="{{ $tv->name }}"
                data-playing="{{ $isPlaying ? '1' : '0' }}"
                data-booked="{{ $isBooked ? '1' : '0' }}"
                {{ old('tv_id') == $tv->id ? 'selected' : '' }}
              >
                {{ $tv->name }} — {{ strtoupper($tv->type) }}
                @if ($isPlaying)
                  (Sedang Digunakan)
                @elseif ($isBooked)
                  (Sudah Dibooking)
                @endif
              </option>
            @endforeach
          </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-[11px] font-bold uppercase tracking-wide text-on-surface-variant mb-2">Tanggal & Jam Mulai</label>
            <input type="datetime-local" name="start_datetime" value="{{ old('start_datetime') }}" class="w-full px-3 py-2.5 border-2 border-on-surface bg-surface text-sm font-body-md focus:outline-none neo-shadow-sm" required>
          </div>

          <div>
            <label class="block text-[11px] font-bold uppercase tracking-wide text-on-surface-variant mb-2">Durasi (Jam)</label>
            <input type="number" name="duration_hours" min="1" max="12" value="{{ old('duration_hours', 2) }}" class="w-full px-3 py-2.5 border-2 border-on-surface bg-surface text-sm font-body-md focus:outline-none neo-shadow-sm" required>
          </div>
        </div>

        <div class="p-4 border-2 border-on-surface bg-surface-container-high neo-shadow-sm">
          <div class="flex items-center justify-between gap-3">
            <span class="text-[11px] uppercase font-bold text-on-surface-variant">Estimasi Total</span>
            <span class="text-xl font-headline-lg font-black text-primary">
              @php
                $selectedTv = $tvs->firstWhere('id', old('tv_id'));
                $est = old('duration_hours', 2);
                $amount = $selectedTv ? (int) round($est * $selectedTv->price_per_hour) : 0;
              @endphp
              Rp {{ number_format($amount, 0, ',', '.') }}
            </span>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <button type="reset" class="px-4 py-2.5 border-2 border-on-surface bg-surface text-xs uppercase font-black tracking-wide neo-shadow-sm btn-press">Reset</button>
          <button type="submit" class="px-5 py-2.5 border-2 border-on-surface bg-secondary-container text-on-secondary text-xs uppercase font-black tracking-wide neo-shadow btn-press">Simpan Booking</button>
        </div>
      </form>
    </section>

    <section class="bg-surface-container-lowest border-2 border-on-surface neo-shadow">
      <div class="p-4 border-b-2 border-on-surface bg-surface-container-high flex items-center justify-between gap-3">
        <div>
          <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-on-surface-variant">Daftar Booking</p>
          <h2 class="font-headline-lg text-xl uppercase font-black">Riwayat Booking</h2>
        </div>
        <span class="px-2.5 py-1 border-2 border-on-surface bg-primary text-on-primary text-[10px] font-black uppercase neo-shadow-sm">{{ $bookings->count() }} Data</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-surface-container-high border-b-2 border-on-surface text-[11px] uppercase tracking-wide">
            <tr>
              <th class="py-3 px-4">Booking</th>
              <th class="py-3 px-4">Pelanggan</th>
              <th class="py-3 px-4">Unit</th>
              <th class="py-3 px-4">Waktu</th>
              <th class="py-3 px-4">Durasi</th>
              <th class="py-3 px-4">Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($bookings as $booking)
              <tr class="border-b border-on-surface/20 align-top">
                <td class="py-3 px-4 font-bold text-primary">BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td class="py-3 px-4">
                  <div class="font-bold">{{ $booking->customer_name }}</div>
                  <div class="text-[11px] text-on-surface-variant">{{ $booking->phone }}</div>
                </td>
                <td class="py-3 px-4 font-bold">{{ $booking->tv?->name ?? '—' }}</td>
                <td class="py-3 px-4 text-[12px]">
                  <div>{{ $booking->start_datetime?->translatedFormat('d M Y') }}</div>
                  <div class="text-on-surface-variant">{{ $booking->start_datetime?->format('H:i') }}</div>
                </td>
                <td class="py-3 px-4 text-[12px]">{{ $booking->duration_hours }} jam</td>
                <td class="py-3 px-4">
                  @php
                    $statusClass = match ($booking->status) {
                        'Pending' => 'bg-amber-100 text-amber-900 border-amber-900',
                        'Active' => 'bg-emerald-100 text-emerald-900 border-emerald-900',
                        'Completed' => 'bg-sky-100 text-sky-900 border-sky-900',
                        default => 'bg-slate-100 text-slate-800 border-slate-700',
                    };
                  @endphp
                  <span class="inline-flex items-center px-2.5 py-1 border-2 font-black text-[10px] uppercase tracking-wide {{ $statusClass }} neo-shadow-sm">
                    {{ $booking->status }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-6 px-4 text-center text-on-surface-variant">Belum ada data booking.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const bookingForm = document.querySelector('form[action="{{ route('booking.store') }}"]');
    const unitSelect = bookingForm?.querySelector('select[name="tv_id"]');

    if (!bookingForm || !unitSelect) {
      return;
    }

    bookingForm.addEventListener('submit', function () {
      const selectedOption = unitSelect.options[unitSelect.selectedIndex];
      if (!selectedOption || !selectedOption.value) {
        return;
      }

      const unitName = selectedOption.dataset.name || 'Unit ini';

      if (selectedOption.dataset.playing === '1') {
        alert(`Peringatan: Unit ${unitName} sedang digunakan (dimainkan) dan tidak dapat dibooking!`);
      } else if (selectedOption.dataset.booked === '1') {
        alert(`Peringatan: Unit ${unitName} sudah dibooking!`);
      }
    });
  });
</script>
@endpush
