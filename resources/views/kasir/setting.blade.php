@extends('layouts.app')

@section('title', 'Settings - TambahBang')
@section('page_title', 'Pengaturan')

@section('content')
<div class="max-w-4xl flex flex-col gap-6">
  <div class="pb-4 border-b-2 border-on-surface">
    <h2 class="text-xl font-black uppercase">Konfigurasi Rental & POS</h2>
    <p class="text-xs text-on-surface-variant mt-0.5">Tersambung ke database — sama dengan pengaturan admin.</p>
  </div>

  <form method="POST" action="{{ route('kasir.setting.update') }}" class="flex flex-col gap-6">
    @csrf
    <div class="p-6 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col gap-4">
      <h3 class="font-black uppercase border-b-2 border-on-surface pb-3">Identitas Toko</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><label class="block text-xs uppercase font-bold mb-1">Nama Bisnis</label>
        <input type="text" name="store_name" value="{{ $settings['store_name'] ?? 'TambahBang Rental PS' }}" required class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs"></div>
        <div><label class="block text-xs uppercase font-bold mb-1">Tagline</label>
        <input type="text" name="store_tagline" value="{{ $settings['store_tagline'] ?? 'Smart POS & Real-Time Monitoring Hub' }}" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs"></div>
        <div><label class="block text-xs uppercase font-bold mb-1">WhatsApp</label>
        <input type="text" name="store_phone" value="{{ $settings['store_phone'] ?? '0812-3456-7890' }}" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs"></div>
        <div><label class="block text-xs uppercase font-bold mb-1">Alamat</label>
        <input type="text" name="store_address" value="{{ $settings['store_address'] ?? 'Jl. Game Arena No. 42' }}" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs"></div>
      </div>
    </div>

    <div class="p-6 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col gap-4">
      <h3 class="font-black uppercase border-b-2 border-on-surface pb-3">Tarif Bawaan (Rp/jam)</h3>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div><label class="block text-xs uppercase font-bold mb-1">PS3</label>
        <input type="number" name="rate_ps3" value="{{ $settings['rate_ps3'] ?? '8000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs font-bold"></div>
        <div><label class="block text-xs uppercase font-bold mb-1">PS4</label>
        <input type="number" name="rate_ps4" value="{{ $settings['rate_ps4'] ?? '15000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs font-bold"></div>
        <div><label class="block text-xs uppercase font-bold mb-1">PS5</label>
        <input type="number" name="rate_ps5" value="{{ $settings['rate_ps5'] ?? '25000' }}" step="1000" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs font-bold"></div>
      </div>
    </div>

    <div class="p-6 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col gap-4">
      <h3 class="font-black uppercase border-b-2 border-on-surface pb-3">Buzzer & Peringatan</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><label class="block text-xs uppercase font-bold mb-1">Peringatan (menit)</label>
        <input type="number" name="warning_minutes" value="{{ $settings['warning_minutes'] ?? '10' }}" min="1" max="30" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs font-bold"></div>
        <div><label class="block text-xs uppercase font-bold mb-1">Suara Alarm</label>
        <select name="sound_notifications" class="w-full px-3 py-2 bg-surface border-2 border-on-surface text-xs font-bold uppercase">
          <option value="1" {{ ($settings['sound_notifications'] ?? '1') == '1' ? 'selected' : '' }}>Aktif</option>
          <option value="0" {{ ($settings['sound_notifications'] ?? '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
        </select></div>
      </div>
    </div>

    <div class="flex justify-end">
      <button class="py-3 px-8 bg-primary text-white text-xs uppercase font-black border-2 border-on-surface neo-shadow btn-press">SIMPAN PENGATURAN</button>
    </div>
  </form>
</div>
@endsection
