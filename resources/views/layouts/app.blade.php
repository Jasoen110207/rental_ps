<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'TambahBang — Smart POS & Real-Time Monitoring Rental PlayStation')</title>
    
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,400;0,600;0,700;0,900;1,400&amp;family=Space+Grotesk:wght@500;600;700;800&amp;family=Space+Mono:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary-fixed-dim": "#b4c5ff",
                        "surface-container-high": "#e2e7ff",
                        "outline": "#737686",
                        "tertiary-fixed-dim": "#4edea3",
                        "primary": "#004ac6",
                        "inverse-primary": "#b4c5ff",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-surface-variant": "#434655",
                        "on-secondary-fixed": "#341100",
                        "on-secondary-container": "#5c2400",
                        "on-error-container": "#93000a",
                        "on-background": "#131b2e",
                        "on-tertiary-fixed": "#002113",
                        "background": "#faf8ff",
                        "surface-container-low": "#f2f3ff",
                        "secondary": "#9d4300",
                        "tertiary-container": "#007d55",
                        "tertiary": "#006242",
                        "inverse-on-surface": "#eef0ff",
                        "on-tertiary": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-tertiary-container": "#bdffdb",
                        "on-primary-fixed-variant": "#003ea8",
                        "surface-variant": "#dae2fd",
                        "primary-container": "#2563eb",
                        "secondary-fixed-dim": "#ffb690",
                        "on-error": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-container": "#eeefff",
                        "surface-bright": "#faf8ff",
                        "secondary-fixed": "#ffdbca",
                        "inverse-surface": "#283044",
                        "error": "#ba1a1a",
                        "surface-dim": "#d2d9f4",
                        "on-primary-fixed": "#00174b",
                        "surface-container": "#eaedff",
                        "tertiary-fixed": "#6ffbbe",
                        "surface": "#faf8ff",
                        "on-secondary-fixed-variant": "#783200",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#0053db",
                        "on-surface": "#131b2e",
                        "secondary-container": "#fd761a",
                        "primary-fixed": "#dbe1ff"
                    },
                    fontFamily: {
                        "body-md": ["Chivo", "sans-serif"],
                        "label-lg": ["Space Mono", "monospace"],
                        "headline-md": ["Space Grotesk", "sans-serif"],
                        "headline-sm": ["Space Grotesk", "sans-serif"],
                        "headline-lg": ["Space Grotesk", "sans-serif"],
                        "label-md": ["Space Mono", "monospace"],
                        "timer-display": ["Space Mono", "monospace"],
                        "headline-xl-mobile": ["Space Grotesk", "sans-serif"],
                        "label-sm": ["Space Mono", "monospace"],
                        "body-sm": ["Chivo", "sans-serif"],
                        "headline-xl": ["Space Grotesk", "sans-serif"],
                        "body-lg": ["Chivo", "sans-serif"],
                        "timer-display-mobile": ["Space Mono", "monospace"]
                    },
                    fontSize: {
                        "headline-xl-mobile": ["30px", {"lineHeight": "36px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-md": ["22px", {"lineHeight": "28px", "fontWeight": "700"}],
                        "timer-display-mobile": ["36px", {"lineHeight": "40px", "letterSpacing": "-0.03em", "fontWeight": "700"}],
                        "label-lg": ["14px", {"lineHeight": "18px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                        "headline-lg": ["28px", {"lineHeight": "34px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "26px", "fontWeight": "500"}],
                        "headline-sm": ["18px", {"lineHeight": "24px", "fontWeight": "700"}],
                        "headline-xl": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.03em", "fontWeight": "700"}],
                        "body-md": ["15px", {"lineHeight": "22px", "fontWeight": "400"}],
                        "timer-display": ["56px", {"lineHeight": "60px", "letterSpacing": "-0.04em", "fontWeight": "700"}],
                        "label-sm": ["10px", {"lineHeight": "14px", "letterSpacing": "0.06em", "fontWeight": "700"}],
                        "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.04em", "fontWeight": "700"}],
                        "body-sm": ["13px", {"lineHeight": "18px", "fontWeight": "400"}]
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
        .neo-shadow-sm { box-shadow: 2px 2px 0px #131b2e; }
        .neo-shadow { box-shadow: 3.5px 3.5px 0px #131b2e; }
        .neo-shadow-lg { box-shadow: 5px 5px 0px #131b2e; }
        .neo-shadow-inset { box-shadow: inset 2px 2px 0px rgba(19, 27, 46, 0.08); }
        .btn-press:active { transform: translate(2px, 2px); box-shadow: 0px 0px 0px #131b2e; }
        @keyframes pulse-alarm {
            0%, 100% { border-color: #ba1a1a; box-shadow: 0 0 0 3px #ffdad6, 4px 4px 0px #131b2e; }
            50% { border-color: #ba1a1a; box-shadow: 0 0 0 6px #ba1a1a, 4px 4px 0px #131b2e; }
        }
        .alarm-card { animation: pulse-alarm 1.2s infinite; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased select-none min-h-screen flex flex-col font-body-md">

    
    <aside class="fixed left-0 top-0 h-screen w-64 flex flex-col justify-between p-4 z-40 bg-surface-container-lowest border-r-2 border-on-surface neo-shadow">
        <div class="flex flex-col gap-4">
            
            <div class="flex items-center gap-3 pb-3 border-b-2 border-on-surface">
                <div class="w-10 h-10 bg-primary text-on-primary border-2 border-on-surface flex items-center justify-center neo-shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl" data-icon="sports_esports">sports_esports</span>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-headline-md font-extrabold uppercase tracking-tight text-on-surface leading-none">TAMBAHBANG</h1>
                    <p class="text-[10px] font-label-sm text-secondary uppercase font-bold tracking-wider mt-1">PS Rental &amp; POS Hub</p>
                </div>
            </div>

      
            <button class="w-full py-2.5 bg-secondary-container text-on-secondary font-headline-sm text-sm tracking-wider border-2 border-on-surface neo-shadow btn-press flex items-center justify-center gap-2 hover:bg-secondary transition-all" onclick="toggleModal(true)">
                <span class="material-symbols-outlined text-lg" data-icon="add_circle">add_circle</span>
                <span class="font-bold">NEW RENTAL +</span>
            </button>

            
            <nav aria-label="Main Navigation" class="flex flex-col gap-1.5 pt-1">
                <a class="flex items-center gap-3 px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.dashboard') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.dashboard') }}">
                    <span class="material-symbols-outlined text-lg" data-icon="dashboard">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.pos') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.pos') }}">
                    <span class="material-symbols-outlined text-lg" data-icon="sports_esports">sports_esports</span>
                    <span>Rental / POS</span>
                </a>
                <a class="flex items-center justify-between px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.request') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.request') }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-lg" data-icon="notifications_active">notifications_active</span>
                        <span>Requests</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-secondary-container text-on-secondary font-label-sm text-[10px] border border-on-surface font-bold animate-pulse">3</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.menu') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.menu') }}">
                    <span class="material-symbols-outlined text-lg" data-icon="restaurant">restaurant</span>
                    <span>F&amp;B Menu</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.transaksi') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.transaksi') }}">
                    <span class="material-symbols-outlined text-lg" data-icon="receipt_long">receipt_long</span>
                    <span>Transactions</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.sift') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.sift') }}">
                    <span class="material-symbols-outlined text-lg" data-icon="schedule">schedule</span>
                    <span>Shifts</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.unit') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.unit') }}">
                    <span class="material-symbols-outlined text-lg" data-icon="tv">tv</span>
                    <span>Units</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 font-label-md text-xs border-2 neo-shadow-sm font-bold {{ request()->routeIs('kasir.setting') ? 'bg-primary text-on-primary border-on-surface' : 'text-on-surface border-transparent hover:bg-surface-container hover:border-on-surface transition-all btn-press' }}" href="{{ route('kasir.setting') }}">
                    <span class="material-symbols-outlined text-lg" data-icon="settings">settings</span>
                    <span>Settings</span>
                </a>
            </nav>
        </div>

    
        <div class="pt-3 border-t-2 border-on-surface flex flex-col gap-2">
            <div class="flex items-center gap-2.5 p-2 bg-surface-container-low border-2 border-on-surface neo-shadow-sm">
                <div class="w-8 h-8 bg-primary-fixed border border-on-surface flex items-center justify-center text-primary font-bold text-xs flex-shrink-0">
                    RH
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-xs truncate text-on-surface">Rian Hidayat</p>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-tertiary flex-shrink-0"></span>
                        <p class="font-label-sm text-[10px] text-on-surface-variant truncate">Shift #02 (Online)</p>
                    </div>
                </div>
            </div>
            <button class="w-full py-1.5 px-2 bg-surface-container-highest border-2 border-on-surface font-label-sm text-xs text-on-surface font-bold flex items-center justify-center gap-1.5 hover:bg-error hover:text-on-error transition-colors btn-press">
                <span class="material-symbols-outlined text-base" data-icon="logout">logout</span>
                <span>KELUAR SHIFT</span>
            </button>
        </div>
    </aside>


    <header class="fixed top-0 left-64 right-0 h-16 px-6 flex items-center justify-between z-30 bg-surface-container-lowest border-b-2 border-on-surface shadow-[0px_3px_0px_#131b2e]">
      
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl" data-icon="grid_view">grid_view</span>
                <h2 class="text-base font-headline-sm font-bold text-on-surface uppercase tracking-tight">Operational Matrix &amp; Monitoring</h2>
            </div>
            <div class="h-5 w-0.5 bg-outline-variant hidden sm:block"></div>
            <div class="hidden sm:flex items-center gap-2 font-label-md text-xs px-3 py-1 bg-surface-container-high border-2 border-on-surface neo-shadow-sm">
                <span class="material-symbols-outlined text-sm text-primary" data-icon="alarm">alarm</span>
                <span class="font-bold" id="live-clock">14:28:45 WIB — Kamis, 24 Okt</span>
            </div>
        </div>

        
        <div class="flex items-center gap-3">
           
            <div class="hidden md:flex items-center gap-2 px-2.5 py-1 bg-surface-container border-2 border-on-surface neo-shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-tertiary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-tertiary"></span>
                </span>
                <span class="font-label-sm text-[11px] font-bold text-on-surface tracking-wide">WS CONNECTED</span>
            </div>
           
            <button class="flex items-center gap-1.5 px-3 py-1 bg-secondary-container text-on-secondary border-2 border-on-surface neo-shadow-sm btn-press hover:bg-secondary font-label-md text-xs font-bold" onclick="toggleRequestsDrawer()">
                <span class="material-symbols-outlined text-base" data-icon="notifications_active">notifications_active</span>
                <span>3 PENDING</span>
            </button>
       
            <div class="hidden 2xl:flex items-center gap-1 bg-surface-container-low p-1 border-2 border-on-surface">
                <span class="px-2 py-0.5 bg-surface-container-lowest border border-on-surface text-label-sm text-[10px] font-bold">8 TOTAL</span>
                <span class="px-2 py-0.5 bg-tertiary text-on-tertiary border border-on-surface text-label-sm text-[10px] font-bold">2 READY</span>
                <span class="px-2 py-0.5 bg-primary text-on-primary border border-on-surface text-label-sm text-[10px] font-bold">3 PLAYING</span>
                <span class="px-2 py-0.5 bg-secondary-container text-on-secondary border border-on-surface text-label-sm text-[10px] font-bold">1 ENDING</span>
                <span class="px-2 py-0.5 bg-error text-on-error border border-on-surface text-label-sm text-[10px] font-bold animate-pulse">1 TIME UP</span>
            </div>
        </div>
    </header>


    <div class="ml-64 pt-16 flex min-h-screen bg-surface-container-low">
        @yield('content')

      
        <aside class="hidden w-80 lg:w-88 border-l-2 border-on-surface bg-surface-container-lowest flex-col justify-between flex-shrink-0 z-20" id="requests-drawer">
            <div>
             
                <div class="p-3 bg-secondary-container text-on-secondary border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg" data-icon="inbox">inbox</span>
                        <h3 class="font-headline-sm text-xs font-extrabold uppercase tracking-wide">Permintaan Meja (3)</h3>
                    </div>
                    <span class="px-1.5 py-0.5 bg-surface-container-lowest text-secondary font-label-sm text-[10px] font-extrabold border border-on-surface">
                        REAL-TIME
                    </span>
                </div>

                <div class="p-3 flex flex-col gap-3 overflow-y-auto max-h-[calc(100vh-140px)]">
                    
                    <div class="p-2.5 bg-surface-container-low border-2 border-on-surface neo-shadow-sm flex flex-col gap-2" id="req-1">
                        <div class="flex items-center justify-between">
                            <span class="px-1.5 py-0.5 bg-secondary text-on-secondary font-label-sm text-[10px] font-bold border border-on-surface">PS 02</span>
                            <span class="font-label-sm text-[11px] text-outline font-bold">14:24 WIB</span>
                        </div>
                        <div>
                            <h4 class="font-headline-sm text-xs font-bold text-on-surface">Tambah Waktu (+30 Menit)</h4>
                            <div class="mt-1 p-1.5 bg-surface-container-lowest border border-on-surface text-[11px] font-label-sm flex justify-between">
                                <span>Biaya Tambahan:</span>
                                <span class="font-bold text-secondary">Rp 12.500</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-1.5">
                            <button class="py-1 bg-tertiary text-on-tertiary font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-tertiary-container" onclick="approveRequestCard('req-1', 'PS 02 Tambah 30 Menit')">
                                <span class="material-symbols-outlined text-xs" data-icon="check">check</span> TERIMA
                            </button>
                            <button class="py-1 bg-surface-container-lowest text-error font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-error-container" onclick="rejectRequestCard('req-1')">
                                <span class="material-symbols-outlined text-xs" data-icon="close">close</span> TOLAK
                            </button>
                        </div>
                    </div>

         
                    <div class="p-2.5 bg-surface-container-low border-2 border-on-surface neo-shadow-sm flex flex-col gap-2" id="req-2">
                        <div class="flex items-center justify-between">
                            <span class="px-1.5 py-0.5 bg-primary text-on-primary font-label-sm text-[10px] font-bold border border-on-surface">PS 07</span>
                            <span class="font-label-sm text-[11px] text-outline font-bold">14:26 WIB</span>
                        </div>
                        <div>
                            <h4 class="font-headline-sm text-xs font-bold text-on-surface">Pesanan Menu F&amp;B</h4>
                            <div class="mt-1 p-1.5 bg-surface-container-lowest border border-on-surface text-[11px] font-label-sm flex flex-col gap-0.5">
                                <div class="flex justify-between">
                                    <span>• 1x Mie Goreng Spesial</span>
                                    <span class="font-bold">Rp 15.000</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>• 2x Es Teh Manis</span>
                                    <span class="font-bold">Rp 10.000</span>
                                </div>
                                <div class="pt-1 mt-0.5 border-t border-on-surface/20 flex justify-between font-bold text-primary">
                                    <span>Total F&amp;B:</span>
                                    <span>Rp 25.000</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-1.5">
                            <button class="py-1 bg-primary text-on-primary font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-primary-container" onclick="approveRequestCard('req-2', 'Pesanan F&amp;B PS 07')">
                                <span class="material-symbols-outlined text-xs" data-icon="check_circle">check_circle</span> TERIMA
                            </button>
                            <button class="py-1 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-surface-container" onclick="rejectRequestCard('req-2')">
                                <span class="material-symbols-outlined text-xs" data-icon="close">close</span> TOLAK
                            </button>
                        </div>
                    </div>

          
                    <div class="p-2.5 bg-surface-container-low border-2 border-on-surface neo-shadow-sm flex flex-col gap-2" id="req-3">
                        <div class="flex items-center justify-between">
                            <span class="px-1.5 py-0.5 bg-primary-container text-on-primary font-label-sm text-[10px] font-bold border border-on-surface">PS 03</span>
                            <span class="font-label-sm text-[11px] text-outline font-bold">14:27 WIB</span>
                        </div>
                        <div>
                            <h4 class="font-headline-sm text-xs font-bold text-on-surface">Tambah Waktu (+1 Jam)</h4>
                            <div class="mt-1 p-1.5 bg-surface-container-lowest border border-on-surface text-[11px] font-label-sm flex justify-between">
                                <span>Biaya Tambahan:</span>
                                <span class="font-bold text-primary">Rp 25.000</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-1.5">
                            <button class="py-1 bg-tertiary text-on-tertiary font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-tertiary-container" onclick="approveRequestCard('req-3', 'PS 03 Tambah 1 Jam')">
                                <span class="material-symbols-outlined text-xs" data-icon="check">check</span> TERIMA
                            </button>
                            <button class="py-1 bg-surface-container-lowest text-error font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-error-container" onclick="rejectRequestCard('req-3')">
                                <span class="material-symbols-outlined text-xs" data-icon="close">close</span> TOLAK
                            </button>
                        </div>
                    </div>
                </div>
            </div>

       
            <div class="p-3 border-t-2 border-on-surface bg-surface-container-high">
                <button class="w-full py-1.5 bg-surface-container-lowest border-2 border-on-surface text-label-md text-xs font-bold neo-shadow-sm btn-press hover:bg-surface-container flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-sm" data-icon="volume_up">volume_up</span>
                    <span>TEST AUDIO BEEP</span>
                </button>
            </div>
        </aside>
    </div>

 
    <div class="fixed inset-0 bg-on-surface/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4" id="rental-modal">
        <div class="bg-surface-container-lowest border-[3px] border-on-surface neo-shadow-lg w-full max-w-lg flex flex-col overflow-hidden">
            <div class="p-3.5 bg-primary text-on-primary border-b-2 border-on-surface flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl" data-icon="sports_esports">sports_esports</span>
                    <h3 class="font-headline-md text-sm font-extrabold uppercase">MULAI SEWA BARU</h3>
                </div>
                <button class="w-7 h-7 bg-surface-container-lowest text-on-surface border-2 border-on-surface flex items-center justify-center font-bold hover:bg-error hover:text-on-error neo-shadow-sm btn-press text-xs" onclick="toggleModal(false)">
                    ✕
                </button>
            </div>

            <form class="p-5 flex flex-col gap-4 bg-surface" onsubmit="handleStartRental(event)">
                <div class="flex flex-col gap-1">
                    <label class="font-label-md text-xs font-bold text-on-surface">PILIH UNIT PLAYSTATION</label>
                    <select class="w-full p-2 font-label-md text-xs border-2 border-on-surface neo-shadow-sm bg-surface-container-lowest focus:outline-none focus:border-primary" id="modal-station-select">
                        <option value="PS 04">PS 04 - PS4 Pro (Tersedia - Rp 15.000/Jam)</option>
                        <option value="PS 06">PS 06 - PS4 Pro (Tersedia - Rp 15.000/Jam)</option>
                        <option value="PS 01">PS 01 - PS5 Digital (Selesai)</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-label-md text-xs font-bold text-on-surface">TIPE BILLING / SEWA</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-2 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm cursor-pointer">
                            <input checked="" class="w-4 h-4 text-primary border-2 border-on-surface focus:ring-0" name="rental-type" type="radio" value="prepaid">
                            <div>
                                <p class="font-headline-sm text-xs font-bold">PREPAID</p>
                                <p class="font-label-sm text-[10px] text-on-surface-variant">Bayar di Awal</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 p-2 bg-surface-container-lowest border-2 border-on-surface neo-shadow-sm cursor-pointer">
                            <input class="w-4 h-4 text-primary border-2 border-on-surface focus:ring-0" name="rental-type" type="radio" value="postpaid">
                            <div>
                                <p class="font-headline-sm text-xs font-bold">POSTPAID</p>
                                <p class="font-label-sm text-[10px] text-on-surface-variant">Open / Bayar Nanti</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-label-md text-xs font-bold text-on-surface">DURASI SEWA (PREPAID)</label>
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
                    <button class="px-4 py-1.5 bg-surface-container-lowest border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press" onclick="toggleModal(false)" type="button">
                        BATAL
                    </button>
                    <button class="px-5 py-1.5 bg-tertiary text-on-tertiary font-headline-sm text-xs font-bold border-2 border-on-surface neo-shadow btn-press hover:bg-tertiary-container flex items-center gap-1.5" type="submit">
                        <span class="material-symbols-outlined text-base" data-icon="play_arrow">play_arrow</span>
                        <span>AKTIFKAN RENTAL</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            const day = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const clockElement = document.getElementById('live-clock');
            if (clockElement) {
                clockElement.textContent = `${hours}:${minutes}:${seconds} WIB — ${day}, ${date} ${month}`;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        function toggleModal(show) {
            const modal = document.getElementById('rental-modal');
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
            const buttons = document.querySelectorAll('.duration-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-primary', 'text-on-primary');
                btn.classList.add('bg-surface-container-lowest', 'text-on-surface');
            });
            event.target.classList.add('bg-primary', 'text-on-primary');
            event.target.classList.remove('bg-surface-container-lowest', 'text-on-surface');
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

        function toggleRequestsDrawer() {
            const drawer = document.getElementById('requests-drawer');
            const isHidden = drawer.classList.contains('hidden');
            if (isHidden) {
                drawer.classList.remove('hidden');
                drawer.classList.add('flex');
            } else {
                drawer.classList.add('hidden');
                drawer.classList.remove('flex');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>