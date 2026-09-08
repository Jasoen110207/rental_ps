<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — TambahBang Smart POS & Rental PS</title>

  <!-- Google Fonts: Space Grotesk, Space Mono, Chivo -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,400;0,600;0,700;0,900;1,400&family=Space+Grotesk:wght@500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
  
  <!-- Material Symbols Outlined -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#004ac6",
            "primary-container": "#2563eb",
            "on-primary": "#ffffff",
            "on-primary-container": "#eeefff",
            "primary-fixed": "#dbe1ff",
            "secondary": "#9d4300",
            "secondary-container": "#fd761a",
            "on-secondary": "#ffffff",
            "on-secondary-container": "#5c2400",
            "secondary-fixed": "#ffdbca",
            "tertiary": "#006242",
            "tertiary-container": "#007d55",
            "on-tertiary": "#ffffff",
            "error": "#ba1a1a",
            "error-container": "#ffdad6",
            "background": "#faf8ff",
            "surface": "#faf8ff",
            "surface-dim": "#d2d9f4",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f2f3ff",
            "surface-container": "#eaedff",
            "surface-container-high": "#e2e7ff",
            "surface-container-highest": "#dae2fd",
            "on-surface": "#131b2e",
            "on-surface-variant": "#434655",
            "outline": "#737686",
          },
          fontFamily: {
            "body-md": ["Chivo", "sans-serif"],
            "body-sm": ["Chivo", "sans-serif"],
            "headline-lg": ["Space Grotesk", "sans-serif"],
            "headline-md": ["Space Grotesk", "sans-serif"],
            "headline-sm": ["Space Grotesk", "sans-serif"],
            "headline-xl": ["Space Grotesk", "sans-serif"],
            "timer-display": ["Space Mono", "monospace"],
            "label-lg": ["Space Mono", "monospace"],
            "label-md": ["Space Mono", "monospace"],
            "label-sm": ["Space Mono", "monospace"],
          }
        }
      }
    }
  </script>

  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 600, 'GRAD' 0, 'opsz' 24;
      display: inline-block;
      vertical-align: middle;
      line-height: 1;
    }
    .neo-shadow-sm {
      box-shadow: 2px 2px 0px #131b2e;
    }
    .neo-shadow {
      box-shadow: 3.5px 3.5px 0px #131b2e;
    }
    .neo-shadow-lg {
      box-shadow: 5px 5px 0px #131b2e;
    }
    .neo-shadow-inset {
      box-shadow: inset 2px 2px 0px rgba(19, 27, 46, 0.08);
    }
    .btn-press:active {
      transform: translate(2px, 2px);
      box-shadow: 0px 0px 0px #131b2e !important;
    }
    @keyframes pulse-alarm {
      0%, 100% {
        border-color: #ba1a1a;
        box-shadow: 0 0 0 3px #ffdad6, 4px 4px 0px #131b2e;
      }
      50% {
        border-color: #ba1a1a;
        box-shadow: 0 0 0 6px #ba1a1a, 4px 4px 0px #131b2e;
      }
    }
    .alarm-card {
      animation: pulse-alarm 1.2s infinite;
    }
    @keyframes warning-pulse {
      0%, 100% { border-color: #fd761a; box-shadow: 0 0 0 2px #ffdbca, 3.5px 3.5px 0px #131b2e; }
      50% { border-color: #9d4300; box-shadow: 0 0 0 4px #fd761a, 3.5px 3.5px 0px #131b2e; }
    }
    .warning-card {
      animation: warning-pulse 2s infinite;
    }
  </style>
  @stack('styles')
</head>

<body class="bg-background text-on-surface antialiased select-none min-h-screen flex flex-col font-body-md">

  <!-- LEFT PERSISTENT SIDEBAR (Fixed w-64) -->
  <aside class="fixed left-0 top-0 h-screen w-64 flex flex-col justify-between p-4 z-40 bg-surface-container-lowest border-r-2 border-on-surface neo-shadow">
    <div class="flex flex-col gap-4">
      <!-- Brand Emblem & Title -->
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 pb-3 border-b-2 border-on-surface hover:opacity-90 transition">
        <div class="w-10 h-10 bg-primary text-on-primary border-2 border-on-surface flex items-center justify-center neo-shadow-sm flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">sports_esports</span>
        </div>
        <div class="min-w-0">
          <h1 class="text-lg font-headline-md font-extrabold uppercase tracking-tight text-on-surface leading-none">TAMBAHBANG</h1>
          <p class="text-[10px] font-label-sm text-secondary uppercase font-bold tracking-wider mt-1">PS Rental & POS Hub</p>
        </div>
      </a>

      <!-- Quick Action / Start Button -->
      <button onclick="window.openStartModal ? window.openStartModal() : window.location.href='{{ route('admin.dashboard') }}'" class="w-full py-2.5 bg-secondary-container text-on-secondary font-headline-sm text-sm tracking-wider border-2 border-on-surface neo-shadow btn-press flex items-center justify-center gap-2 hover:bg-secondary transition-all">
        <span class="material-symbols-outlined text-xl">play_circle</span>
        <span>MULAI SEWA BARU</span>
      </button>

      <!-- Navigation Menu -->
      <nav class="flex flex-col gap-1.5 mt-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">grid_view</span>
            <span>Dashboard</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('admin.requests.index') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.requests.*') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">receipt_long</span>
            <span>Customer Request</span>
          </div>
          <span id="nav-requests-badge" class="px-1.5 py-0.5 text-[10px] font-label-sm font-bold bg-secondary-container text-on-secondary border border-on-surface {{ \App\Models\CustomerRequest::where('status', 'pending')->count() > 0 ? '' : 'hidden' }}">
            {{ \App\Models\CustomerRequest::where('status', 'pending')->count() }}
          </span>
        </a>

        <a href="{{ route('admin.pos.index') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.pos.*') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">restaurant</span>
            <span>F&B Catalog POS</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('admin.transactions.index') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.transactions.*') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">payments</span>
            <span>Riwayat Transaksi</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('admin.shifts.index') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.shifts.*') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">history_toggle_off</span>
            <span>Manajemen Shift</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('admin.units.index') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.units.*') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">videogame_asset</span>
            <span>Unit Konsol & QR</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">settings</span>
            <span>Pengaturan</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>
      </nav>
    </div>

    <!-- Bottom Sidebar: Cashier Identity & Logout -->
    <div class="pt-3 border-t-2 border-on-surface flex flex-col gap-2">
      <div class="p-2.5 bg-surface-container-high border-2 border-on-surface flex items-center justify-between neo-shadow-sm">
        <div class="flex items-center gap-2 min-w-0">
          <div class="w-7 h-7 bg-primary text-on-primary border border-on-surface flex items-center justify-center font-bold text-xs">
            {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
          </div>
          <div class="min-w-0">
            <p class="font-headline-sm text-xs font-bold truncate">{{ auth()->user()->name ?? 'Kasir' }}</p>
            <p class="text-[10px] font-label-sm uppercase text-on-surface-variant font-bold">
              {{ auth()->user()->role ?? 'kasir' }} • Online
            </p>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" title="Logout" class="p-1.5 bg-error text-on-error border border-on-surface hover:bg-red-700 btn-press flex items-center justify-center">
            <span class="material-symbols-outlined text-base">logout</span>
          </button>
        </form>
      </div>

      <!-- Quick Sound Alert Toggle -->
      <button onclick="toggleAudioAlerts()" id="audio-toggle-btn" class="w-full py-1.5 px-2 bg-surface border-2 border-on-surface text-[10px] font-label-sm font-bold uppercase flex items-center justify-center gap-1.5 hover:bg-surface-container-high">
        <span class="material-symbols-outlined text-sm" id="audio-icon">volume_up</span>
        <span id="audio-label">Sound Alert: ON</span>
      </button>
    </div>
  </aside>

  <!-- MAIN WRAPPER (Offset by sidebar w-64) -->
  <div class="ml-64 flex flex-col min-h-screen bg-background">
    
    <!-- TOP HEADER BAR -->
    <header class="h-16 bg-surface-container-lowest border-b-2 border-on-surface px-6 flex items-center justify-between sticky top-0 z-30 neo-shadow-sm">
      <div class="flex items-center gap-4">
        <h2 class="text-xl font-headline-md font-extrabold uppercase tracking-tight text-on-surface">
          @yield('page_title', 'Operational Monitoring Board')
        </h2>
        <!-- Live status indicator pill -->
        <div class="flex items-center gap-2 px-2.5 py-1 bg-surface-container-high border-2 border-on-surface font-label-sm text-xs font-bold neo-shadow-sm">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping inline-block"></span>
          <span class="text-on-surface uppercase tracking-wider">LIVE SYNC</span>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <!-- Live Clock -->
        <div class="px-3 py-1.5 bg-surface border-2 border-on-surface font-timer-display text-xs font-bold neo-shadow-sm text-on-surface" id="live-clock">
          {{ now()->isoFormat('dddd, D MMMM YYYY • HH:mm:ss') }}
        </div>

        <!-- Shift Info Badge -->
        @php
          $currentShift = \App\Models\Shift::where('status', 'active')->latest()->first();
        @endphp
        <a href="{{ route('admin.shifts.index') }}" class="px-3 py-1.5 bg-primary-fixed border-2 border-on-surface font-label-sm text-xs font-bold neo-shadow-sm hover:bg-primary-fixed-dim transition flex items-center gap-1.5">
          <span class="material-symbols-outlined text-base text-primary">schedule</span>
          <span>Shift: {{ $currentShift ? 'Aktif (#' . $currentShift->id . ')' : 'Belum Ada' }}</span>
        </a>

        <!-- Notifications Slide-over Button -->
        <button onclick="toggleNotificationDrawer()" class="relative p-2 bg-surface border-2 border-on-surface neo-shadow-sm btn-press hover:bg-surface-container-high">
          <span class="material-symbols-outlined text-xl">notifications</span>
          <span id="top-notif-badge" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-secondary-container text-on-secondary border border-on-surface rounded-full text-[10px] font-bold flex items-center justify-center {{ \App\Models\CustomerRequest::where('status', 'pending')->count() > 0 ? '' : 'hidden' }}">
            {{ \App\Models\CustomerRequest::where('status', 'pending')->count() }}
          </span>
        </button>
      </div>
    </header>

    <!-- FLASH MESSAGES -->
    <div class="px-6 pt-4">
      @if (session('success'))
        <div class="p-3.5 bg-emerald-100 border-2 border-on-surface text-emerald-950 font-headline-sm text-sm neo-shadow flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-xl text-emerald-700">check_circle</span>
            <span>{{ session('success') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="font-bold text-lg">&times;</button>
        </div>
      @endif

      @if (session('error'))
        <div class="p-3.5 bg-red-100 border-2 border-on-surface text-red-950 font-headline-sm text-sm neo-shadow flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-xl text-error">error</span>
            <span>{{ session('error') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="font-bold text-lg">&times;</button>
        </div>
      @endif

      @if ($errors->any())
        <div class="p-3.5 bg-red-100 border-2 border-on-surface text-red-950 font-headline-sm text-sm neo-shadow mb-4">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>

    <!-- MAIN PAGE CONTENT -->
    <main class="p-6 flex-1">
      @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="p-4 border-t-2 border-on-surface bg-surface-container-lowest text-xs text-on-surface-variant flex justify-between items-center">
      <p class="font-bold uppercase tracking-wider">TambahBang PS Management System &copy; {{ date('Y') }}</p>
      <p class="font-label-sm">Built with Laravel & Neo-Brutalism UI</p>
    </footer>
  </div>

  <!-- NOTIFICATION SLIDE-OVER DRAWER -->
  <div id="notification-drawer" class="fixed inset-0 z-50 hidden">
    <div onclick="toggleNotificationDrawer()" class="fixed inset-0 bg-on-surface/40 backdrop-blur-xs"></div>
    <div class="fixed right-0 top-0 h-screen w-96 bg-surface-container-lowest border-l-2 border-on-surface p-6 overflow-y-auto flex flex-col justify-between neo-shadow-lg transform translate-x-full transition-transform duration-200" id="drawer-panel">
      <div>
        <div class="flex items-center justify-between pb-4 border-b-2 border-on-surface mb-4">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-2xl text-primary">notifications_active</span>
            <h3 class="font-headline-md font-bold uppercase text-lg">Notifikasi & Request</h3>
          </div>
          <button onclick="toggleNotificationDrawer()" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>

        <div id="drawer-notifications-list" class="flex flex-col gap-3">
          <!-- Populated by JS -->
          <p class="text-xs text-on-surface-variant font-bold text-center py-6">Memuat notifikasi...</p>
        </div>
      </div>

      <div class="pt-4 border-t-2 border-on-surface">
        <a href="{{ route('admin.requests.index') }}" class="w-full py-2.5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface neo-shadow btn-press flex items-center justify-center gap-2">
          <span>Buka Customer Request Hub</span>
          <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
      </div>
    </div>
  </div>

  <!-- GLOBAL LIVE SYNC & SOUND SCRIPT -->
  <script>
    let soundEnabled = true;
    let audioCtx = null;

    function playBeep(freq = 800, type = 'square', duration = 0.2) {
      if (!soundEnabled) return;
      try {
        if (!audioCtx) {
          audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = type;
        osc.frequency.value = freq;
        gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + duration);
      } catch (e) {
        console.log('Audio playback blocked or unavailable', e);
      }
    }

    function playAlarmBeep() {
      if (!soundEnabled) return;
      try {
        if (!audioCtx) {
          audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        const now = audioCtx.currentTime;
        for (let i = 0; i < 6; i++) {
          const t = now + i * 0.11;
          const osc = audioCtx.createOscillator();
          const gain = audioCtx.createGain();
          osc.type = 'square';
          osc.frequency.value = 900;
          gain.gain.setValueAtTime(0.12, t);
          gain.gain.exponentialRampToValueAtTime(0.01, t + 0.08);
          osc.connect(gain);
          gain.connect(audioCtx.destination);
          osc.start(t);
          osc.stop(t + 0.08);
        }
      } catch (e) {
        console.log('Audio playback blocked or unavailable', e);
      }
    }

    function toggleAudioAlerts() {
      soundEnabled = !soundEnabled;
      const icon = document.getElementById('audio-icon');
      const label = document.getElementById('audio-label');
      if (soundEnabled) {
        icon.innerText = 'volume_up';
        label.innerText = 'Sound Alert: ON';
        playBeep(600, 'sine', 0.1);
      } else {
        icon.innerText = 'volume_off';
        label.innerText = 'Sound Alert: OFF';
      }
    }

    function toggleNotificationDrawer() {
      const drawer = document.getElementById('notification-drawer');
      const panel = document.getElementById('drawer-panel');
      if (!drawer || !panel) return;
      if (drawer.classList.contains('hidden')) {
        // Open: tampilkan wrapper dulu, lalu geser panel masuk
        drawer.classList.remove('hidden');
        requestAnimationFrame(() => {
          requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
        });
      } else {
        // Close: geser panel keluar dulu, lalu sembunyikan wrapper
        panel.classList.add('translate-x-full');
        setTimeout(() => drawer.classList.add('hidden'), 200);
      }
    }

    // Tutup drawer dengan tombol Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const drawer = document.getElementById('notification-drawer');
        if (drawer && !drawer.classList.contains('hidden')) toggleNotificationDrawer();
      }
    });

    // Live Clock Update
    setInterval(() => {
      const now = new Date();
      const clockEl = document.getElementById('live-clock');
      if (clockEl) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        clockEl.innerText = now.toLocaleDateString('id-ID', options);
      }
    }, 1000);
  </script>
  @stack('scripts')
</body>
</html>
