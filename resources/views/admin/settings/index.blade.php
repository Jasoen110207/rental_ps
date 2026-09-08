@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan & Konfigurasi Sistem')

@section('content')
<div class="max-w-4xl flex flex-col gap-6">

  <!-- Header -->
  <div class="pb-4 border-b-2 border-on-surface">
    <h2 class="text-xl font-headline-lg font-black uppercase text-on-surface">Konfigurasi Rental & POS</h2>
    <p class="text-xs text-on-surface-variant font-medium mt-0.5">Kelola identitas toko, default tarif per jam, dan sistem notifikasi/buzzer.</p>
  </div>

  <form method="POST" action="{{ route('admin.settings.update') }}" class="flex flex-col gap-6">
    @csrf

    <!-- 1. IDENTITAS RENTAL -->
    <div class="p-6 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col gap-4">
      <div class="flex items-center gap-2 pb-3 border-b-2 border-on-surface">
        <span class="material-symbols-outlined text-xl text-primary">storefront</span>
        <h3 class="font-headline-lg font-black uppercase text-base">Identitas & Informasi Toko</h3>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Nama Bisnis / Rental</label>
          <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'TambahBang Rental PS' }}" required class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Tagline Slogan</label>
          <input type="text" name="store_tagline" value="{{ $settings['store_tagline'] ?? 'Smart POS & Real-Time Monitoring Hub' }}" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Nomor WhatsApp / Kontak</label>
          <input type="text" name="store_phone" value="{{ $settings['store_phone'] ?? '0812-3456-7890' }}" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Alamat Lengkap</label>
          <input type="text" name="store_address" value="{{ $settings['store_address'] ?? 'Jl. Game Arena No. 42, Kota Digital' }}" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
        </div>
      </div>
    </div>

    <!-- 2. DEFAULT TARIF PER JAM -->
    <div class="p-6 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col gap-4">
      <div class="flex items-center gap-2 pb-3 border-b-2 border-on-surface">
        <span class="material-symbols-outlined text-xl text-secondary">payments</span>
        <h3 class="font-headline-lg font-black uppercase text-base">Tarif Bawaan (Default Hourly Rates)</h3>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">PlayStation 3 (Rp/jam)</label>
          <input type="number" name="rate_ps3" value="{{ $settings['rate_ps3'] ?? '8000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">PlayStation 4 (Rp/jam)</label>
          <input type="number" name="rate_ps4" value="{{ $settings['rate_ps4'] ?? '15000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">PlayStation 5 (Rp/jam)</label>
          <input type="number" name="rate_ps5" value="{{ $settings['rate_ps5'] ?? '25000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Sim Racing Cockpit (Rp/jam)</label>
          <input type="number" name="rate_sim_racing" value="{{ $settings['rate_sim_racing'] ?? '35000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Nintendo Switch (Rp/jam)</label>
          <input type="number" name="rate_nintendo_switch" value="{{ $settings['rate_nintendo_switch'] ?? '15000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm">
        </div>
      </div>
    </div>

    <!-- 3. NOTIFIKASI & BUZZER -->
    <div class="p-6 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col gap-4">
      <div class="flex items-center gap-2 pb-3 border-b-2 border-on-surface">
        <span class="material-symbols-outlined text-xl text-error">notifications_active</span>
        <h3 class="font-headline-lg font-black uppercase text-base">Buzzer & Ambang Batas Waktu</h3>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Peringatan Waktu Hampir Habis (Menit)</label>
          <input type="number" name="warning_minutes" value="{{ $settings['warning_minutes'] ?? '10' }}" min="1" max="30" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm">
        </div>

        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Fitur Suara Alarm</label>
          <select name="sound_notifications" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
            <option value="1" {{ ($settings['sound_notifications'] ?? '1') == '1' ? 'selected' : '' }}>Aktif (Beep Audio Web Synth)</option>
            <option value="0" {{ ($settings['sound_notifications'] ?? '1') == '0' ? 'selected' : '' }}>Nonaktif (Hanya Visual)</option>
          </select>
        </div>
      </div>
    </div>

    <div class="flex justify-end">
      <button type="submit" class="py-3 px-8 bg-primary text-on-primary font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container flex items-center gap-2">
        <span class="material-symbols-outlined text-base">save</span>
        <span>Simpan Semua Pengaturan</span>
      </button>
    </div>
  </form>

</div>
@endsection
