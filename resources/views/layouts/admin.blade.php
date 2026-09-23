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

  <!-- Tailwind CSS (config terpusat di public/js/tailwind-theme.js) -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="{{ asset('js/tailwind-theme.js') }}"></script>

  <!-- External CSS: sistem desain Neo-Brutalism -->
  <link rel="stylesheet" href="{{ asset('css/base.css') }}">
  @stack('styles')
</head>

<body class="bg-background text-on-surface antialiased select-none min-h-screen flex flex-col font-body-md">

  <!-- AUDIO UNLOCK OVERLAY MODAL -->
  <div id="audio-unlock-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-on-surface/80 backdrop-blur-sm">
    <div class="bg-surface-container-lowest p-6 max-w-sm w-full border-2 border-on-surface neo-shadow-lg text-center flex flex-col items-center gap-4 animate-in zoom-in-95 duration-200">
      <div class="w-16 h-16 bg-primary text-on-primary border-2 border-on-surface flex items-center justify-center neo-shadow-sm rounded-full">
        <span class="material-symbols-outlined text-3xl">volume_up</span>
      </div>
      <div>
        <h2 class="font-headline-lg font-black text-xl uppercase mb-1">Inisialisasi Sistem</h2>
        <p class="text-sm font-body-md text-on-surface-variant">Browser mewajibkan interaksi Anda terlebih dahulu agar suara notifikasi dan buzzer dapat otomatis berbunyi.</p>
      </div>
      <button onclick="unlockAudioSystem()" class="w-full mt-2 py-3 bg-secondary-container text-on-secondary font-headline-lg font-black tracking-wider uppercase border-2 border-on-surface neo-shadow btn-press hover:bg-secondary">
        MULAI DASHBOARD
      </button>
    </div>
  </div>

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
          <span id="nav-requests-badge" class="px-1.5 py-0.5 text-[10px] font-label-sm font-bold bg-secondary-container text-on-secondary border border-on-surface {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}">
            {{ auth()->user()->unreadNotifications->count() }}
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

        <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between px-3 py-2.5 font-headline-sm text-xs uppercase tracking-wider border-2 border-on-surface transition-all {{ request()->routeIs('admin.users.*') ? 'bg-primary text-on-primary neo-shadow' : 'bg-surface hover:bg-surface-container-high' }}">
          <div class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-lg">group</span>
            <span>Manajemen Kasir</span>
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
          <span id="top-notif-badge" class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-secondary-container text-on-secondary border border-on-surface rounded-full text-[10px] font-bold flex items-center justify-center {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}">
            {{ auth()->user()->unreadNotifications->count() }}
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

  <!-- TOAST CONTAINER -->
  <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"></div>

  <!-- NOTIFICATION SLIDE-OVER DRAWER -->
  <div id="notification-drawer" class="fixed inset-0 z-50 hidden">
    <div onclick="toggleNotificationDrawer()" class="fixed inset-0 bg-on-surface/40 backdrop-blur-xs"></div>
    <div class="fixed right-0 top-0 h-screen w-96 bg-surface-container-lowest border-l-2 border-on-surface p-6 overflow-y-auto flex flex-col justify-between neo-shadow-lg transform translate-x-full transition-transform duration-200" id="drawer-panel">
      <div>
        <div class="flex items-center justify-between pb-4 border-b-2 border-on-surface mb-4">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-2xl text-primary">notifications_active</span>
            <h3 class="font-headline-md font-bold uppercase text-lg">Notifikasi (<span id="drawer-notif-count">{{ auth()->user()->unreadNotifications->count() }}</span>)</h3>
          </div>
          <button onclick="toggleNotificationDrawer()" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>

        <div id="drawer-notifications-list" class="flex flex-col gap-3">
          @forelse (auth()->user()->unreadNotifications()->take(10)->get() as $notif)
            <div class="p-2.5 bg-surface-container-low border-2 border-on-surface neo-shadow-sm flex flex-col gap-2 notif-item" id="notif-{{ $notif->id }}">
              <div class="flex items-center justify-between">
                <span class="px-1.5 py-0.5 bg-primary text-white font-label-sm text-[10px] font-bold border border-on-surface">{{ $notif->data['tv_name'] ?? 'Meja' }}</span>
                <span class="font-label-sm text-[11px] text-outline font-bold">{{ $notif->created_at->format('H:i') }}</span>
              </div>
              <p class="font-headline-sm text-xs font-bold">{{ $notif->data['message'] }}</p>
              <button onclick="markNotifAsRead('{{ $notif->id }}')" class="w-full py-1 bg-tertiary text-white font-label-md text-xs font-bold border-2 border-on-surface btn-press">TANDAI TERBACA & LIHAT DETAIL</button>
            </div>
          @empty
            <p class="text-xs text-on-surface-variant font-bold text-center py-6">Tidak ada notifikasi baru.</p>
          @endforelse
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

    /* Polling Notification */
    let currentUnreadCount = parseInt(document.getElementById('top-notif-badge')?.innerText || '0');
    
    function updateNotifBadges(count) {
      const badges = ['nav-requests-badge', 'top-notif-badge'];
      badges.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          el.innerText = count;
          if (count > 0) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
      });
      const drawerCount = document.getElementById('drawer-notif-count');
      if(drawerCount) drawerCount.innerText = count;
    }

    async function checkNotifications() {
      try {
        const res = await fetch('{{ route("notifications.unread-count") }}', {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        
        if (data.count > currentUnreadCount) {
          playAlarmBeep(); // Alert admin!
          
          if (data.latest) {
             showToastNotification(data.latest);
             showOSNotification(data.latest);
          }
        }
        currentUnreadCount = data.count;
        updateNotifBadges(currentUnreadCount);
      } catch (e) {
        console.error('Failed to fetch notifications', e);
      }
    }

    function showToastNotification(latest) {
      const container = document.getElementById('toast-container');
      if(!container) return;

      const toast = document.createElement('div');
      toast.className = 'bg-surface-container-lowest border-2 border-on-surface neo-shadow p-4 min-w-[300px] max-w-[350px] pointer-events-auto transform translate-x-full transition-transform duration-300 animate-slide-in';
      toast.innerHTML = `
        <div class="flex items-center justify-between mb-2 pb-2 border-b-2 border-on-surface">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl">notifications_active</span>
            <span class="font-headline-sm font-bold text-xs uppercase">${latest.tv_name}</span>
          </div>
          <button onclick="this.parentElement.parentElement.remove()" class="text-on-surface-variant hover:text-error btn-press"><span class="material-symbols-outlined text-lg">close</span></button>
        </div>
        <p class="text-xs font-bold">${latest.message}</p>
        <button onclick="window.location.href='{{ route('admin.requests.index') }}'" class="mt-3 w-full py-1.5 bg-primary text-on-primary border-2 border-on-surface text-xs font-bold uppercase btn-press neo-shadow-sm">Lihat Request</button>
      `;

      container.appendChild(toast);
      
      // Animate in
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          toast.classList.remove('translate-x-full');
        });
      });

      // Auto remove after 8 seconds
      setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
      }, 8000);
    }

    function showOSNotification(latest) {
      if (!("Notification" in window)) return;
      if (Notification.permission === "granted") {
        new Notification("Request Baru: " + latest.tv_name, {
          body: latest.message,
          icon: '/favicon.ico' // You can change this if you have an icon
        });
      } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
          if (permission === "granted") {
            new Notification("Request Baru: " + latest.tv_name, {
              body: latest.message
            });
          }
        });
      }
    }

    async function markNotifAsRead(id) {
      try {
        await fetch(`/notifications/${id}/mark-as-read`, {
          method: 'POST',
          headers: { 
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        const notifCard = document.getElementById('notif-' + id);
        if (notifCard) {
          notifCard.remove();
        }
        currentUnreadCount = Math.max(0, currentUnreadCount - 1);
        updateNotifBadges(currentUnreadCount);
        
        window.location.href = '{{ route("admin.requests.index") }}';
      } catch (e) {
        console.error('Failed to mark as read', e);
      }
    }

    setInterval(checkNotifications, 15000); // 15 detik polling

    function unlockAudioSystem() {
      if (!audioCtx) {
        audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      }
      if (audioCtx.state === 'suspended') {
        audioCtx.resume();
      }
      
      const osc = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      gain.gain.value = 0;
      osc.connect(gain);
      gain.connect(audioCtx.destination);
      osc.start();
      osc.stop(audioCtx.currentTime + 0.01);
      
      // Minta izin notifikasi OS sekalian
      if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
      }
      
      document.getElementById('audio-unlock-modal').remove();
    }
  </script>
  @stack('scripts')
</body>
</html>
