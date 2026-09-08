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

  <!-- Tailwind CSS (sama persis dengan sistem desain admin) -->
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
    /* ===== Kompatibilitas kelas lama halaman kasir → dipetakan ke sistem admin (desain saja) ===== */
    .neo-box-sm { box-shadow: 2px 2px 0px #131b2e; }
    .neo-box-md { box-shadow: 3.5px 3.5px 0px #131b2e; }
    .neo-btn:active { transform: translate(2px, 2px); box-shadow: 0px 0px 0px #131b2e !important; }
    .neo-border { border: 2px solid #131b2e; }
    .neo-border-thick { border: 3px solid #131b2e; }
    .neo-shadow-md { box-shadow: 3.5px 3.5px 0px #131b2e; }
    .brutal-card { background: #ffffff; border: 2px solid #131b2e; box-shadow: 4px 4px 0px #131b2e; }
    .brutal-btn-press:active { transform: translate(2px, 2px); box-shadow: 0px 0px 0px #131b2e !important; }
    .brutal-btn-primary { background: #004ac6; color: #fff; border: 2px solid #131b2e; box-shadow: 3px 3px 0px #131b2e; }
    .brutal-btn-secondary { background: #ffffff; color: #131b2e; border: 2px solid #131b2e; box-shadow: 2px 2px 0px #131b2e; }
    .brutal-btn-orange { background: #fd761a; color: #5c2400; border: 2px solid #131b2e; box-shadow: 2px 2px 0px #131b2e; }
    .brutal-badge-green { background: #6ffbbe; color: #002113; border: 1px solid #131b2e; }
    .brutal-badge-blue { background: #dbe1ff; color: #00174b; border: 1px solid #131b2e; }
    .brutal-badge-red { background: #ffdad6; color: #93000a; border: 1px solid #131b2e; }
    .brutal-qr-pattern {
      background-image: repeating-linear-gradient(0deg, #131b2e 0 2px, transparent 2px 5px),
                        repeating-linear-gradient(90deg, #131b2e 0 2px, transparent 2px 5px);
    }
    .dashed-receipt-divider { border-top: 2px dashed rgba(19,27,46,.4); }
    .live-dot { animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .35; } }
    .scrollbar-none::-webkit-scrollbar { display: none; }
    .scrollbar-none { scrollbar-width: none; }
  </style>
  @stack('styles')
</head>

<body class="bg-background text-on-surface antialiased select-none min-h-screen flex flex-col font-body-md">

  <!-- LEFT PERSISTENT SIDEBAR (Fixed w-64) — struktur sama dengan admin -->
  <aside class="fixed left-0 top-0 h-screen w-64 flex flex-col justify-between p-4 z-40 bg-surface-container-lowest border-r-2 border-on-surface neo-shadow">
    <div class="flex flex-col gap-4">
      <!-- Brand Emblem & Title -->
      <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 pb-3 border-b-2 border-on-surface hover:opacity-90 transition">
        <div class="w-10 h-10 bg-primary text-on-primary border-2 border-on-surface flex items-center justify-center neo-shadow-sm flex-shrink-0">
          <span class="material-symbols-outlined text-2xl">sports_esports</span>
        </div>
        <div class="min-w-0">
          <h1 class="text-lg font-headline-md font-extrabold uppercase tracking-tight text-on-surface leading-none">TAMBAHBANG</h1>
          <p class="text-[10px] font-label-sm text-secondary uppercase font-bold tracking-wider mt-1">PS Rental & POS Hub</p>
        </div>
      </a>

      <!-- Quick Action / Start Button (fitur kasir: buka modal rental) -->
      <button onclick="toggleModal(true)" class="w-full py-2.5 bg-secondary-container text-on-secondary font-headline-sm text-sm tracking-wider border-2 border-on-surface neo-shadow btn-press flex items-center justify-center gap-2 hover:bg-secondary transition-all">
        <span class="material-symbols-outlined text-xl">add_circle</span>
        <span>NEW RENTAL +</span>
      </button>

      <!-- Navigation Menu (fitur kasir dipertahankan, gaya mengikuti admin) -->
      <nav class="flex flex-col gap-1.5 mt-1">
        <a href="{{ route('kasir.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.dashboard') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">dashboard</span>
            <span>Dashboard</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('kasir.pos') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.pos') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">sports_esports</span>
            <span>Rental / POS</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('kasir.request') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.request') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">notifications_active</span>
            <span>Requests</span>
          </div>
          <span id="nav-requests-badge" class="px-1.5 py-0.5 text-[10px] font-label-sm font-bold bg-secondary-container text-on-secondary border border-on-surface {{ \App\Models\CustomerRequest::where('status', 'pending')->count() > 0 ? '' : 'hidden' }}">{{ \App\Models\CustomerRequest::where('status', 'pending')->count() }}</span>
        </a>

        <a href="{{ route('kasir.menu') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.menu') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">restaurant</span>
            <span>F&B Menu</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('kasir.transaksi') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.transaksi') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">receipt_long</span>
            <span>Transactions</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('kasir.sift') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.sift') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">schedule</span>
            <span>Shifts</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('kasir.unit') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.unit') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">tv</span>
            <span>Units</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>

        <a href="{{ route('kasir.setting') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('kasir.setting') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">settings</span>
            <span>Settings</span>
          </div>
          <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
        </a>
      </nav>
    </div>

    <!-- Bottom Sidebar: Cashier Identity & Actions -->
    <div class="pt-3 border-t-2 border-on-surface flex flex-col gap-2">
      <div class="p-2.5 bg-surface-container-high border-2 border-on-surface flex items-center justify-between neo-shadow-sm">
        <div class="flex items-center gap-2 min-w-0">
          <div class="w-7 h-7 bg-primary text-on-primary border border-on-surface flex items-center justify-center font-bold text-xs">RH</div>
          <div class="min-w-0">
            <p class="font-headline-sm text-xs font-bold truncate">Rian Hidayat</p>
            <p class="text-[10px] font-label-sm uppercase text-on-surface-variant font-bold">Kasir • Shift #02 Online</p>
          </div>
        </div>
        <button type="button" title="Keluar Shift" class="p-1.5 bg-error text-white border border-on-surface hover:bg-red-700 btn-press flex items-center justify-center">
          <span class="material-symbols-outlined text-base">logout</span>
        </button>
      </div>

      <!-- Quick Sound Alert Toggle (sama seperti admin) -->
      <button onclick="toggleAudioAlerts()" id="audio-toggle-btn" class="w-full py-1.5 px-2 bg-surface border-2 border-on-surface text-[10px] font-label-sm font-bold uppercase flex items-center justify-center gap-1.5 hover:bg-surface-container-high">
        <span class="material-symbols-outlined text-sm" id="audio-icon">volume_up</span>
        <span id="audio-label">Sound Alert: ON</span>
      </button>
    </div>
  </aside>

  <!-- MAIN WRAPPER (Offset by sidebar w-64) -->
  <div class="ml-64 flex flex-col min-h-screen bg-background">

    <!-- TOP HEADER BAR (struktur sama dengan admin) -->
    <header class="h-16 bg-surface-container-lowest border-b-2 border-on-surface px-6 flex items-center justify-between sticky top-0 z-30 neo-shadow-sm">
      <div class="flex items-center gap-4">
        <h2 class="text-xl font-headline-md font-extrabold uppercase tracking-tight text-on-surface">
          @yield('page_title', 'Operational Matrix & Monitoring')
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
        @php $kasirShift = \App\Models\Shift::where('status', 'active')->latest()->first(); @endphp
        <a href="{{ route('kasir.sift') }}" class="px-3 py-1.5 bg-primary-fixed border-2 border-on-surface font-label-sm text-xs font-bold neo-shadow-sm hover:bg-surface-container-high transition flex items-center gap-1.5">
          <span class="material-symbols-outlined text-base text-primary">schedule</span>
          <span>Shift: {{ $kasirShift ? 'Aktif (#'.$kasirShift->id.')' : 'Belum Ada' }}</span>
        </a>

        <!-- Notifications Slide-over Button -->
        <button onclick="toggleNotificationDrawer()" class="relative p-2 bg-surface border-2 border-on-surface neo-shadow-sm btn-press hover:bg-surface-container-high">
          <span class="material-symbols-outlined text-xl">notifications</span>
          <span id="top-notif-badge" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-secondary-container text-on-secondary border border-on-surface rounded-full text-[10px] font-bold flex items-center justify-center {{ \App\Models\CustomerRequest::where('status', 'pending')->count() > 0 ? '' : 'hidden' }}">{{ \App\Models\CustomerRequest::where('status', 'pending')->count() }}</span>
        </button>
      </div>
    </header>

    <!-- FLASH MESSAGES (mengikuti admin) -->
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

    <!-- FOOTER (sama dengan admin) -->
    <footer class="p-4 border-t-2 border-on-surface bg-surface-container-lowest text-xs text-on-surface-variant flex justify-between items-center">
      <p class="font-bold uppercase tracking-wider">TambahBang PS Management System &copy; {{ date('Y') }}</p>
      <p class="font-label-sm">Built with Laravel & Neo-Brutalism UI</p>
    </footer>
  </div>

  <!-- NOTIFICATION SLIDE-OVER DRAWER (struktur sama dengan admin, isi fitur kasir) -->
  <div id="notification-drawer" class="fixed inset-0 z-50 hidden">
    <div onclick="toggleNotificationDrawer()" class="fixed inset-0 bg-on-surface/40 backdrop-blur-xs"></div>
    <div class="fixed right-0 top-0 h-screen w-96 bg-surface-container-lowest border-l-2 border-on-surface p-6 overflow-y-auto flex flex-col justify-between neo-shadow-lg transform translate-x-full transition-transform duration-200" id="drawer-panel">
      <div>
        <div class="flex items-center justify-between pb-4 border-b-2 border-on-surface mb-4">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-2xl text-primary">notifications_active</span>
            <h3 class="font-headline-md font-bold uppercase text-lg">Permintaan ({{ \App\Models\CustomerRequest::where('status', 'pending')->count() }})</h3>
          </div>
          <button onclick="toggleNotificationDrawer()" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>

        <div id="drawer-notifications-list" class="flex flex-col gap-3">
          {{-- Data real dari DB --}}
          @php $kasirPending = \App\Models\CustomerRequest::with('tv')->where('status', 'pending')->orderBy('created_at', 'desc')->take(5)->get(); @endphp
          @forelse ($kasirPending as $pr)
            <div class="p-2.5 bg-surface-container-low border-2 border-on-surface neo-shadow-sm flex flex-col gap-2">
              <div class="flex items-center justify-between">
                <span class="px-1.5 py-0.5 bg-primary text-white font-label-sm text-[10px] font-bold border border-on-surface">{{ $pr->tv ? $pr->tv->name : 'Meja' }}</span>
                <span class="font-label-sm text-[11px] text-outline font-bold">{{ $pr->created_at->format('H:i') }}</span>
              </div>
              <p class="font-headline-sm text-xs font-bold">{{ $pr->type === 'add_time' ? 'Tambah Waktu (+'.($pr->payload['duration_hours'] ?? 1).' Jam)' : ($pr->type === 'service_call' ? 'Panggil Kasir ke Meja' : 'Pesanan F&B') }}</p>
              <div class="grid grid-cols-2 gap-1.5">
                <form method="POST" action="{{ route('kasir.request.approve', $pr->id) }}">@csrf<button class="w-full py-1 bg-tertiary text-white font-label-md text-xs font-bold border-2 border-on-surface btn-press">TERIMA</button></form>
                <form method="POST" action="{{ route('kasir.request.reject', $pr->id) }}">@csrf<button class="w-full py-1 bg-surface-container-lowest text-error font-label-md text-xs font-bold border-2 border-on-surface btn-press">TOLAK</button></form>
              </div>
            </div>
          @empty
            <p class="text-xs text-on-surface-variant font-bold text-center py-6">Tidak ada request pending.</p>
          @endforelse
        </div>
      </div>

      <div class="pt-4 border-t-2 border-on-surface flex flex-col gap-2">
        <a href="{{ route('kasir.request') }}" class="w-full py-2.5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface neo-shadow btn-press flex items-center justify-center gap-2">
          <span>Buka Request Hub</span>
          <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
        <button onclick="playBeep(880, 'square', 0.15)" class="w-full py-1.5 bg-surface-container-lowest border-2 border-on-surface text-label-md text-xs font-bold neo-shadow-sm btn-press hover:bg-surface-container flex items-center justify-center gap-1.5">
          <span class="material-symbols-outlined text-sm">volume_up</span>
          <span>TEST AUDIO BEEP</span>
        </button>
      </div>
    </div>
  </div>

  <!-- RENTAL MODAL (fitur kasir dipertahankan, gaya mengikuti admin) -->
  <div class="fixed inset-0 bg-on-surface/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4" id="rental-modal">
    <div class="bg-surface-container-lowest border-[3px] border-on-surface neo-shadow-lg w-full max-w-lg flex flex-col overflow-hidden">
      <div class="p-3.5 bg-primary text-on-primary border-b-2 border-on-surface flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-xl">sports_esports</span>
          <h3 class="font-headline-md text-sm font-extrabold uppercase">MULAI SEWA BARU</h3>
        </div>
        <button class="w-7 h-7 bg-surface-container-lowest text-on-surface border-2 border-on-surface flex items-center justify-center font-bold hover:bg-error hover:text-white neo-shadow-sm btn-press text-xs" onclick="toggleModal(false)">✕</button>
      </div>

      <form method="POST" action="{{ route('kasir.rental.start') }}" class="p-5 flex flex-col gap-4 bg-surface">
        @csrf
        <div class="flex flex-col gap-1">
          <label class="font-label-md text-xs font-bold text-on-surface">PILIH UNIT PLAYSTATION</label>
          <select name="tv_id" required class="w-full p-2 font-label-md text-xs border-2 border-on-surface neo-shadow-sm bg-surface-container-lowest focus:outline-none focus:border-primary" id="modal-station-select">
            @php $kasirAvail = \App\Models\Tv::where('status', 'available')->orderBy('id')->get(); @endphp
            @forelse ($kasirAvail as $av)
              <option value="{{ $av->id }}">{{ $av->name }} - {{ strtoupper($av->type) }} (Rp {{ number_format($av->price_per_hour, 0, ',', '.') }}/Jam)</option>
            @empty
              <option value="">Tidak ada unit tersedia</option>
            @endforelse
          </select>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-label-md text-xs font-bold text-on-surface">TIPE BILLING / SEWA</label>
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-2 p-2 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm cursor-pointer">
              <input checked="" class="w-4 h-4 text-primary border-2 border-on-surface focus:ring-0" name="billing_type" type="radio" value="prepaid">
              <div>
                <p class="font-headline-sm text-xs font-bold">PREPAID</p>
                <p class="font-label-sm text-[10px] text-on-surface-variant">Bayar di Awal</p>
              </div>
            </label>
            <label class="flex items-center gap-2 p-2 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm cursor-pointer">
              <input class="w-4 h-4 text-primary border-2 border-on-surface focus:ring-0" name="billing_type" type="radio" value="postpaid">
              <div>
                <p class="font-headline-sm text-xs font-bold">POSTPAID</p>
                <p class="font-label-sm text-[10px] text-on-surface-variant">Open / Bayar Nanti</p>
              </div>
            </label>
          </div>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-label-md text-xs font-bold text-on-surface">DURASI SEWA (PREPAID)</label>
          <input type="hidden" name="duration_hours" id="layout-duration-input" value="2">
          <div class="grid grid-cols-4 gap-2">
            <button class="py-2 bg-surface-container-lowest border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press duration-btn" onclick="selectDuration(1)" type="button">1 Jam</button>
            <button class="py-2 bg-primary text-on-primary border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press duration-btn" onclick="selectDuration(2)" type="button">2 Jam</button>
            <button class="py-2 bg-surface-container-lowest border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press duration-btn" onclick="selectDuration(3)" type="button">3 Jam</button>
            <button class="py-2 bg-surface-container-lowest border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press duration-btn" onclick="selectDuration(5)" type="button">Paket 5J</button>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="flex flex-col gap-1">
            <label class="font-label-md text-xs font-bold text-on-surface">NAMA PENYEWA (OPSIONAL)</label>
            <input class="p-2 border-2 border-on-surface neo-shadow-inset font-label-md text-xs" placeholder="Contoh: Mas Budi" type="text">
          </div>
          <div class="flex flex-col gap-1">
            <label class="font-label-md text-xs font-bold text-on-surface">STIK KONTROLER</label>
            <select class="p-2 border-2 border-on-surface neo-shadow-sm bg-surface-container-lowest font-label-md text-xs">
              <option>2 Stik (Bawaan)</option>
              <option>3 Stik (+ Rp 5.000)</option>
              <option>4 Stik (+ Rp 10.000)</option>
            </select>
          </div>
        </div>

        <div class="p-2.5 bg-surface-container-high border-2 border-on-surface flex items-center justify-between">
          <div>
            <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block">ESTIMASI TOTAL</span>
            <span class="text-base font-headline-md font-extrabold text-primary">Rp 30.000</span>
          </div>
          <span class="text-[11px] font-label-sm text-on-surface-variant">TV Aktif Otomatis</span>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
          <button class="px-4 py-1.5 bg-surface-container-lowest border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press" onclick="toggleModal(false)" type="button">BATAL</button>
          <button class="px-5 py-1.5 bg-tertiary text-on-tertiary font-headline-sm text-xs font-bold border-2 border-on-surface neo-shadow btn-press hover:bg-tertiary-container flex items-center gap-1.5" type="submit">
            <span class="material-symbols-outlined text-base">play_arrow</span>
            <span>AKTIFKAN RENTAL</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- GLOBAL LIVE SYNC & SOUND SCRIPT (sama dengan admin) + fitur kasir -->
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

    function toggleAudioAlerts() {
      soundEnabled = !soundEnabled;
      const icon = document.getElementById('audio-icon');
      const label = document.getElementById('audio-label');
      if (soundEnabled) {
        if (icon) icon.innerText = 'volume_up';
        if (label) label.innerText = 'Sound Alert: ON';
        playBeep(600, 'sine', 0.1);
      } else {
        if (icon) icon.innerText = 'volume_off';
        if (label) label.innerText = 'Sound Alert: OFF';
      }
    }

    function toggleNotificationDrawer() {
      const drawer = document.getElementById('notification-drawer');
      const panel = document.getElementById('drawer-panel');
      if (!drawer || !panel) return;
      if (drawer.classList.contains('hidden')) {
        drawer.classList.remove('hidden');
        requestAnimationFrame(() => {
          requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
        });
      } else {
        panel.classList.add('translate-x-full');
        setTimeout(() => drawer.classList.add('hidden'), 200);
      }
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        const drawer = document.getElementById('notification-drawer');
        if (drawer && !drawer.classList.contains('hidden')) toggleNotificationDrawer();
      }
    });

    setInterval(() => {
      const now = new Date();
      const clockEl = document.getElementById('live-clock');
      if (clockEl) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        clockEl.innerText = now.toLocaleDateString('id-ID', options);
      }
    }, 1000);

    /* ===== Fitur kasir dipertahankan (nama & perilaku sama) ===== */
    function toggleModal(show) {
      const modal = document.getElementById('rental-modal');
      if (!modal) return;
      if (show) modal.classList.remove('hidden');
      else modal.classList.add('hidden');
    }

    function openRentalModalWithUnit(unitName, price) {
      toggleModal(true);
    }

    function handleStartRental(e) {
      e.preventDefault();
      alert('Sewa Berhasil Dimulai! Daya TV dinyalakan dan timer mulai berjalan.');
      toggleModal(false);
    }

    function selectDuration(hours) {
      const hidden = document.getElementById('layout-duration-input');
      if (hidden) hidden.value = hours;
      const buttons = document.querySelectorAll('#rental-modal .duration-btn');
      buttons.forEach(btn => {
        btn.classList.remove('bg-primary', 'text-on-primary');
        btn.classList.add('bg-surface-container-lowest');
      });
      if (event && event.target) {
        event.target.classList.add('bg-primary', 'text-on-primary');
        event.target.classList.remove('bg-surface-container-lowest');
      }
    }

    function approveRequest(unit, type) {
      alert(`Permintaan ${type} untuk ${unit} berhasil DISETUJUI & ditambahkan ke sistem.`);
    }

    function approveRequestCard(cardId, desc) {
      const card = document.getElementById(cardId);
      if (card) {
        card.style.transition = 'all 0.25s ease';
        card.style.opacity = '0';
        card.style.transform = 'translateX(20px)';
        setTimeout(() => {
          card.remove();
          alert(`Sukses: Permintaan "${desc}" telah disetujui!`);
        }, 250);
      }
    }

    function rejectRequestCard(cardId) {
      const card = document.getElementById(cardId);
      if (card) {
        card.style.transition = 'all 0.25s ease';
        card.style.opacity = '0';
        setTimeout(() => {
          card.remove();
        }, 250);
      }
    }
  </script>
  @stack('scripts')
</body>
</html>
