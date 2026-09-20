<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Self-Service Portal') — TambahBang</title>

  <!-- Google Fonts: Space Grotesk, Space Mono, Chivo -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,400;0,600;0,700;0,900;1,400&family=Space+Grotesk:wght@500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

  <!-- Material Symbols Outlined -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  <!-- Tailwind CSS (config terpusat di public/js/tailwind-theme.js) -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="{{ asset('js/tailwind-theme.js') }}"></script>

  <!-- External CSS: sistem desain Neo-Brutalism + mobile portal -->
  <link rel="stylesheet" href="{{ asset('css/base.css') }}">
  <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
  @stack('styles')
</head>

<body class="bg-surface-container-high text-on-surface antialiased min-h-screen flex justify-center font-body-md">
  <!-- Mobile Container Constraint (max 420px for perfect QR mobile experience) -->
  <div class="w-full max-w-md bg-background min-h-screen flex flex-col border-x-2 border-on-surface shadow-2xl relative">

    <!-- Top Mobile Header (satu-satunya brand header) -->
    <header class="p-4 bg-surface-container-lowest border-b-2 border-on-surface flex items-center justify-between sticky top-0 z-30 neo-shadow-sm">
      <div class="flex items-center gap-2.5">
        <div class="w-9 h-9 bg-primary text-on-primary border-2 border-on-surface flex items-center justify-center neo-shadow-sm">
          <span class="material-symbols-outlined text-xl">sports_esports</span>
        </div>
        <div>
          <h1 class="text-base font-headline-md font-extrabold uppercase tracking-tight text-on-surface leading-none">TAMBAHBANG</h1>
          <p class="text-[9px] font-label-sm text-secondary uppercase font-bold tracking-wider mt-0.5">Self-Service QR Portal</p>
        </div>
      </div>

      <div class="flex items-center gap-1.5 px-2 py-1 bg-surface-container border border-on-surface text-[10px] font-label-sm font-bold">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span class="text-on-surface uppercase">Live</span>
      </div>
    </header>

    <!-- Flash Messages -->
    <div class="px-4 pt-3">
      @if (session('success'))
        <div class="p-3 bg-emerald-100 border-2 border-on-surface text-emerald-950 font-headline-sm text-xs neo-shadow flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-base text-emerald-700">check_circle</span>
            <span>{{ session('success') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="font-bold text-base">&times;</button>
        </div>
      @endif

      @if (session('error'))
        <div class="p-3 bg-red-100 border-2 border-on-surface text-red-950 font-headline-sm text-xs neo-shadow flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-base text-error">error</span>
            <span>{{ session('error') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="font-bold text-base">&times;</button>
        </div>
      @endif
    </div>

    <!-- Main Content -->
    <main class="p-4 flex-1 pb-20">
      @yield('content')
    </main>

    <!-- Customer Footer -->
    <footer class="p-3 bg-surface-container-lowest border-t-2 border-on-surface text-[10px] text-center text-on-surface-variant font-bold uppercase tracking-wider">
      {{ $storeName ?? 'TambahBang Rental PS' }} • Scan & Play
    </footer>
  </div>

  @stack('scripts')
</body>
</html>
