@extends('layouts.app')

@section('title', 'Requests - TambahBang')

@section('content')
    <main class="min-h-screen">
        <div class="p-6 max-w-7xl mx-auto">
            <!-- ================= PAGE HEADER & REAL-TIME CONTROLS ================= -->
            <div
                class="bg-surface-container-lowest border-2 border-on-surface p-5 mb-6 neo-box-md flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5 mb-1">
                        <span
                            class="px-2 py-0.5 bg-secondary-container text-white text-label-sm font-label-sm font-bold border border-on-surface">INCOMING
                            LIVE QUEUE</span>
                        <span class="text-label-sm font-label-sm text-outline font-bold">INTERVAL SYNC: 1s</span>
                    </div>
                    <h1 class="text-headline-lg font-headline-lg font-extrabold text-on-surface tracking-tight">
                        Pusat Permintaan Pelanggan <span class="text-primary font-headline-md">(Customer Requests Hub)</span>
                    </h1>
                    <p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">
                        Kelola persetujuan sewa jam tambahan, orderan F&amp;B dari konsol, dan panggilan bantuan teknisi
                        secara instan.
                    </p>
                </div>
                <!-- Audio Alert & Auto-Sync Switch Controls -->
                <div class="flex items-center gap-3">
                    <!-- Audio Beep Status Pill -->
                    <button
                        class="flex items-center gap-2 px-3 py-2 bg-surface-container-high border-2 border-on-surface neo-box-sm neo-btn cursor-pointer"
                        id="soundToggleBtn" onclick="toggleSound()">
                        <span class="material-symbols-outlined text-primary text-xl" id="soundIcon"
                            style="font-variation-settings: 'FILL' 1;">volume_up</span>
                        <div class="text-left">
                            <span class="block text-label-sm font-label-sm font-bold leading-none text-on-surface">AUDIO
                                BEEP</span>
                            <span class="text-label-sm text-emerald-700 font-bold" id="soundLabel">AKTIF (85dB)</span>
                        </div>
                    </button>
                    <!-- Auto-Sync Real-Time Toggle -->
                    <div
                        class="flex items-center gap-2 px-3 py-2 bg-surface-container-lowest border-2 border-on-surface neo-box-sm">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 live-dot"></span>
                        <div>
                            <span
                                class="block text-label-sm font-label-sm font-bold leading-none text-on-surface">AUTO-SYNC</span>
                            <span class="text-label-sm text-primary font-bold">REALTIME WS</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer ml-1">
                            <input checked="" class="sr-only peer" type="checkbox" />
                            <div
                                class="w-9 h-5 bg-outline-variant peer-focus:outline-none border-2 border-on-surface peer peer-checked:after:translate-x-full peer-checked:after:border-on-surface peer-checked:bg-primary after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-2 after:border-on-surface after:h-4 after:w-4 after:transition-all">
                            </div>
                        </label>
                    </div>
                    <!-- Manual Refresh Button -->
                    <button
                        class="p-2 bg-surface border-2 border-on-surface neo-box-sm neo-btn text-on-surface hover:bg-surface-container"
                        title="Paksa Refresh Matrix">
                        <span class="material-symbols-outlined text-xl">refresh</span>
                    </button>
                </div>
            </div>
            <!-- ================= FILTER TABS ================= -->
            <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
                <!-- Tab 1: Menunggu Persetujuan (Active) -->
                <button
                    class="flex items-center gap-2 px-4 py-2.5 bg-on-surface text-white border-2 border-on-surface font-label-md font-bold shadow-[2px_2px_0px_#fd761a] whitespace-nowrap">
                    <span class="material-symbols-outlined text-base text-secondary-container"
                        style="font-variation-settings: 'FILL' 1;">pending_actions</span>
                    <span>Menunggu Persetujuan</span>
                    <span
                        class="ml-1 px-1.5 py-0.2 bg-secondary-container text-white text-label-sm font-bold border border-white">3</span>
                </button>
                <!-- Tab 2: Disetujui Hari Ini -->
                <button
                    class="flex items-center gap-2 px-4 py-2.5 bg-surface-container-lowest text-on-surface border-2 border-on-surface font-label-md font-bold neo-box-sm neo-btn whitespace-nowrap hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base text-emerald-600">check_circle</span>
                    <span>Disetujui Hari Ini</span>
                    <span
                        class="ml-1 px-1.5 py-0.2 bg-surface-container text-on-surface text-label-sm font-bold border border-on-surface">18</span>
                </button>
                <!-- Tab 3: Ditolak -->
                <button
                    class="flex items-center gap-2 px-4 py-2.5 bg-surface-container-lowest text-on-surface border-2 border-on-surface font-label-md font-bold neo-box-sm neo-btn whitespace-nowrap hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base text-red-600">cancel</span>
                    <span>Ditolak</span>
                    <span
                        class="ml-1 px-1.5 py-0.2 bg-red-100 text-error text-label-sm font-bold border border-on-surface">2</span>
                </button>
                <!-- Tab 4: Semua Permintaan -->
                <button
                    class="flex items-center gap-2 px-4 py-2.5 bg-surface-container-lowest text-on-surface border-2 border-on-surface font-label-md font-bold neo-box-sm neo-btn whitespace-nowrap hover:bg-surface-container">
                    <span class="material-symbols-outlined text-base text-on-surface-variant">list_alt</span>
                    <span>Semua Permintaan</span>
                </button>
                <div
                    class="ml-auto text-label-sm font-label-sm font-bold text-on-surface-variant flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">info</span>
                    <span>3 request membutuhkan otorisasi kasir</span>
                </div>
            </div>
            <!-- ================= MAIN LAYOUT: REQUESTS CARDS & LIVE LOG DOCK ================= -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <!-- ================= LEFT / CENTER: ACTIVE REQUEST CARDS (8 Cols) ================= -->
                <div class="xl:col-span-8 space-y-5">
                    <!-- CARD 1: PS 03 - Tambah Waktu -->
                    <div class="bg-surface-container-lowest border-[2.5px] border-on-surface neo-box-md overflow-hidden transition-all duration-150"
                        id="card-ps03">
                        <!-- Header Bar -->
                        <div
                            class="bg-amber-400 text-on-surface px-4 py-2.5 border-b-[2.5px] border-on-surface flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span
                                    class="px-2 py-0.5 bg-on-surface text-white font-label-md font-extrabold text-label-sm border border-on-surface">
                                    BAY #03
                                </span>
                                <span class="font-headline-sm text-headline-sm font-extrabold tracking-tight">
                                    PS5 REGULAR 03
                                </span>
                                <span
                                    class="text-label-sm font-label-sm bg-white px-2 py-0.5 border border-on-surface font-bold">
                                    SISA WAKTU: 00:07:45
                                </span>
                            </div>
                            <div
                                class="flex items-center gap-1 text-label-sm font-label-sm font-bold bg-on-surface text-white px-2 py-0.5">
                                <span class="material-symbols-outlined text-sm text-amber-300">alarm</span>
                                <span>14:24 WIB (4m lalu)</span>
                            </div>
                        </div>
                        <!-- Content Body -->
                        <div class="p-5">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <!-- Request Info Column -->
                                <div class="md:col-span-7 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-2.5 py-1 bg-secondary text-white text-label-sm font-label-sm font-bold border-2 border-on-surface neo-box-sm">
                                            TAMBAH JAM SEWA
                                        </span>
                                        <span class="text-headline-md font-headline-md text-on-surface font-extrabold">
                                            Tambah Waktu +30 Menit
                                        </span>
                                    </div>
                                    <p class="text-body-sm text-on-surface-variant font-body-sm">
                                        Pelanggan mengajukan ekstensi durasi main sebelum sesi berakhir via menu interaktif
                                        gamepad konsol.
                                    </p>
                                    <div class="flex flex-wrap items-center gap-3 pt-1">
                                        <div
                                            class="px-2.5 py-1 bg-surface-container-high border-2 border-on-surface neo-box-sm">
                                            <span class="text-label-sm text-on-surface-variant block font-bold">WAKTU SAAT
                                                INI</span>
                                            <span
                                                class="font-timer-display-mobile text-label-lg font-bold text-error">00:07:45</span>
                                        </div>
                                        <div
                                            class="px-2.5 py-1 bg-surface-container-high border-2 border-on-surface neo-box-sm">
                                            <span class="text-label-sm text-on-surface-variant block font-bold">TAMBAHAN
                                                BIAYA</span>
                                            <span class="font-timer-display-mobile text-label-lg font-bold text-primary">Rp
                                                12.500</span>
                                        </div>
                                        <div
                                            class="px-2 py-1 bg-surface-container-low border border-on-surface text-label-sm font-bold">
                                            Paket: Normal Non-VIP
                                        </div>
                                    </div>
                                </div>
                                <!-- Live Timer Inset Widget -->
                                <div
                                    class="md:col-span-5 bg-surface-container-low p-3.5 border-2 border-on-surface neo-box-sm text-center">
                                    <p class="text-label-sm font-label-sm text-on-surface-variant font-bold uppercase mb-1">
                                        DURASI BARU SETELAH APPROVE</p>
                                    <div
                                        class="font-timer-display-mobile text-headline-lg font-bold text-on-surface bg-white border-2 border-on-surface py-1">
                                        00:37:45
                                    </div>
                                    <p
                                        class="text-label-sm text-tertiary font-bold mt-1.5 flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-sm">lock_reset</span> Otomatis
                                        perpanjang timer TV
                                    </p>
                                </div>
                            </div>
                            <!-- Card Action Buttons -->
                            <div
                                class="mt-5 pt-4 border-t-2 border-dashed border-on-surface flex flex-wrap items-center justify-between gap-3">
                                <span class="text-label-sm font-label-sm text-outline font-bold">ID REQ:
                                    #REQ-2024-0981</span>
                                <div class="flex items-center gap-3">
                                    <!-- TOLAK -->
                                    <button
                                        class="px-4 py-2 bg-white text-error border-[2.5px] border-on-surface font-headline-sm text-label-lg font-bold neo-box-sm neo-btn flex items-center gap-1.5 hover:bg-red-50"
                                        onclick="rejectRequest('card-ps03', 'PS 03')">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                        <span>✕ TOLAK</span>
                                    </button>
                                    <!-- APPROVE -->
                                    <button
                                        class="px-5 py-2 bg-emerald-600 text-white border-[2.5px] border-on-surface font-headline-sm text-label-lg font-bold neo-box-sm neo-btn flex items-center gap-2 hover:bg-emerald-700"
                                        onclick="approveRequest('card-ps03', 'PS 03', 'Tambah Waktu +30m (Rp 12.500)')">
                                        <span class="material-symbols-outlined text-xl"
                                            style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                        <span>✓ SETUJUI (APPROVE)</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CARD 2: PS 07 - Pesanan F&B Meja -->
                    <div class="bg-surface-container-lowest border-[2.5px] border-on-surface neo-box-md overflow-hidden transition-all duration-150"
                        id="card-ps07">
                        <!-- Header Bar -->
                        <div
                            class="bg-primary text-white px-4 py-2.5 border-b-[2.5px] border-on-surface flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span
                                    class="px-2 py-0.5 bg-white text-on-surface font-label-md font-extrabold text-label-sm border border-on-surface">
                                    BAY #07
                                </span>
                                <span class="font-headline-sm text-headline-sm font-extrabold tracking-tight">
                                    PS5 VIP ROOM ALPHA
                                </span>
                                <span
                                    class="text-label-sm font-label-sm bg-tertiary-fixed text-on-tertiary-fixed px-2 py-0.5 border border-white font-bold">
                                    SESI AKTIF: SISA 01:42:10
                                </span>
                            </div>
                            <div
                                class="flex items-center gap-1 text-label-sm font-label-sm font-bold bg-on-surface text-white px-2 py-0.5 border border-white">
                                <span class="material-symbols-outlined text-sm text-amber-300">alarm</span>
                                <span>14:26 WIB (2m lalu)</span>
                            </div>
                        </div>
                        <!-- Content Body -->
                        <div class="p-5">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <!-- Request Info Column -->
                                <div class="md:col-span-7 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-2.5 py-1 bg-primary text-white text-label-sm font-label-sm font-bold border-2 border-on-surface neo-box-sm">
                                            F&amp;B ORDER MEJA
                                        </span>
                                        <span class="text-headline-md font-headline-md text-on-surface font-extrabold">
                                            Pesanan F&amp;B Meja
                                        </span>
                                    </div>
                                    <p class="text-body-sm text-on-surface-variant font-body-sm">
                                        Order kudapan &amp; minuman masuk dari konsol. Perlu diteruskan ke dapur / bar
                                        kasir.
                                    </p>
                                    <!-- Order Details Box -->
                                    <div class="bg-surface-container-low border-2 border-on-surface p-3 neo-box-sm">
                                        <div
                                            class="text-label-sm font-label-sm font-bold text-on-surface-variant mb-1.5 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">receipt</span>
                                            <span>RINCIAN ITEM PESANAN:</span>
                                        </div>
                                        <ul class="space-y-1 font-label-md text-on-surface">
                                            <li
                                                class="flex justify-between items-center border-b border-dashed border-outline-variant pb-1">
                                                <span class="font-bold">• 2x Es Teh Manis Jumbo</span>
                                                <span class="font-timer-display-mobile">Rp 10.000</span>
                                            </li>
                                            <li class="flex justify-between items-center pt-0.5">
                                                <span class="font-bold">• 1x Indomie Goreng Telur (Level 2)</span>
                                                <span class="font-timer-display-mobile">Rp 15.000</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Total Amount Summary Box -->
                                <div
                                    class="md:col-span-5 bg-amber-50 p-4 border-2 border-on-surface neo-box-sm text-center">
                                    <span class="text-label-sm font-label-sm text-secondary font-bold block mb-1">TOTAL
                                        TAGIHAN F&amp;B</span>
                                    <div
                                        class="font-timer-display-mobile text-headline-xl text-secondary font-extrabold bg-white border-2 border-on-surface py-2">
                                        Rp 25.000
                                    </div>
                                    <div
                                        class="mt-2 text-label-sm text-on-surface-variant font-bold flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                                        <span>Masuk ke Tagihan Bay #07</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Action Buttons -->
                            <div
                                class="mt-5 pt-4 border-t-2 border-dashed border-on-surface flex flex-wrap items-center justify-between gap-3">
                                <span class="text-label-sm font-label-sm text-outline font-bold">ID REQ:
                                    #REQ-2024-0982</span>
                                <div class="flex items-center gap-3">
                                    <!-- TOLAK -->
                                    <button
                                        class="px-4 py-2 bg-white text-error border-[2.5px] border-on-surface font-headline-sm text-label-lg font-bold neo-box-sm neo-btn flex items-center gap-1.5 hover:bg-red-50"
                                        onclick="rejectRequest('card-ps07', 'PS 07')">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                        <span>✕ TOLAK</span>
                                    </button>
                                    <!-- APPROVE & MASUK BILL -->
                                    <button
                                        class="px-5 py-2 bg-primary-container text-white border-[2.5px] border-on-surface font-headline-sm text-label-lg font-bold neo-box-sm neo-btn flex items-center gap-2 hover:bg-blue-700"
                                        onclick="approveRequest('card-ps07', 'PS 07', 'Order F&amp;B (Rp 25.000)')">
                                        <span class="material-symbols-outlined text-xl"
                                            style="font-variation-settings: 'FILL' 1;">add_task</span>
                                        <span>✓ SETUJUI &amp; MASUK BILL</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CARD 3: PS 02 - Panggil Kasir / Bantuan Stik -->
                    <div class="bg-surface-container-lowest border-[2.5px] border-on-surface neo-box-md overflow-hidden transition-all duration-150"
                        id="card-ps02">
                        <!-- Header Bar -->
                        <div
                            class="bg-rose-500 text-white px-4 py-2.5 border-b-[2.5px] border-on-surface flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span
                                    class="px-2 py-0.5 bg-white text-on-surface font-label-md font-extrabold text-label-sm border border-on-surface">
                                    BAY #02
                                </span>
                                <span class="font-headline-sm text-headline-sm font-extrabold tracking-tight">
                                    PS4 PRO CORNER 02
                                </span>
                                <span
                                    class="text-label-sm font-label-sm bg-white text-error px-2 py-0.5 border border-on-surface font-bold">
                                    BANTUAN HARDWARE
                                </span>
                            </div>
                            <div
                                class="flex items-center gap-1 text-label-sm font-label-sm font-bold bg-on-surface text-white px-2 py-0.5">
                                <span class="material-symbols-outlined text-sm text-amber-300">alarm</span>
                                <span>14:28 WIB (Baru Saja)</span>
                            </div>
                        </div>
                        <!-- Content Body -->
                        <div class="p-5">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                <!-- Request Info Column -->
                                <div class="md:col-span-8 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-2.5 py-1 bg-red-600 text-white text-label-sm font-label-sm font-bold border-2 border-on-surface neo-box-sm">
                                            SERVICE CALL
                                        </span>
                                        <span class="text-headline-md font-headline-md text-on-surface font-extrabold">
                                            Panggil Kasir / Bantuan Stik
                                        </span>
                                    </div>
                                    <p class="text-body-sm text-on-surface-variant font-body-sm">
                                        Pelanggan menekan tombol darurat bantuan teknis pada layar konsol.
                                    </p>
                                    <div
                                        class="bg-error-container text-on-error-container p-3 border-2 border-on-surface neo-box-sm">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="material-symbols-outlined text-error text-xl font-bold">battery_alert</span>
                                            <div>
                                                <span class="font-label-md font-bold block text-on-surface">KENDALA:
                                                    DualSense 2 baterai lemah / Disconnect</span>
                                                <span class="text-body-sm">Segera bawakan DualSense cadangan #DS-09 dari
                                                    rak charging dock kasir.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fast Indicator Status -->
                                <div
                                    class="md:col-span-4 bg-surface-container-high p-4 border-2 border-on-surface neo-box-sm text-center">
                                    <span
                                        class="material-symbols-outlined text-3xl text-secondary mb-1">support_agent</span>
                                    <span class="block font-label-sm font-bold text-on-surface">STATUS TIKET</span>
                                    <span
                                        class="inline-block mt-1 px-3 py-1 bg-secondary-container text-white font-label-sm font-bold border border-on-surface">
                                        MENUNGGU PETUGAS
                                    </span>
                                </div>
                            </div>
                            <!-- Card Action Buttons -->
                            <div
                                class="mt-5 pt-4 border-t-2 border-dashed border-on-surface flex flex-wrap items-center justify-between gap-3">
                                <span class="text-label-sm font-label-sm text-outline font-bold">ID REQ:
                                    #REQ-2024-0983</span>
                                <div class="flex items-center gap-3">
                                    <!-- SELESAI -->
                                    <button
                                        class="px-4 py-2 bg-white text-on-surface border-[2.5px] border-on-surface font-headline-sm text-label-lg font-bold neo-box-sm neo-btn flex items-center gap-1.5 hover:bg-surface-container"
                                        onclick="approveRequest('card-ps02', 'PS 02', 'Bantuan Stik Terselesaikan')">
                                        <span class="material-symbols-outlined text-lg">done_all</span>
                                        <span>SELESAI</span>
                                    </button>
                                    <!-- TANGANI SEKARANG -->
                                    <button
                                        class="px-5 py-2 bg-secondary text-white border-[2.5px] border-on-surface font-headline-sm text-label-lg font-bold neo-box-sm neo-btn flex items-center gap-2 hover:bg-amber-700"
                                        onclick="handleAssistance('card-ps02', 'PS 02')">
                                        <span class="material-symbols-outlined text-xl"
                                            style="font-variation-settings: 'FILL' 1;">directions_run</span>
                                        <span>✓ TANGANI SEKARANG</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ================= RIGHT COLUMN: APPROVED HISTORY & AUDIT TRAIL (4 Cols) ================= -->
                <div class="xl:col-span-4 space-y-5">
                    <!-- Section Panel Container -->
                    <div class="bg-surface-container-lowest border-[2.5px] border-on-surface neo-box-md p-4">
                        <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-tertiary text-xl"
                                    style="font-variation-settings: 'FILL' 1;">verified</span>
                                <h2 class="text-headline-sm font-headline-sm font-bold text-on-surface">
                                    Approved History
                                </h2>
                            </div>
                            <span
                                class="text-label-sm font-label-sm bg-tertiary-container text-on-tertiary-container px-2 py-0.5 font-bold border border-on-surface">
                                HARI INI: 18
                            </span>
                        </div>
                        <p class="text-body-sm font-body-sm text-on-surface-variant mb-3">
                            Log aktivitas realtime kasir dengan audit timestamp dan konfirmasi unit meja.
                        </p>
                        <!-- Real-Time Activity Log Feed -->
                        <div class="space-y-3" id="activity-log-feed">
                            <!-- Log Item 1 -->
                            <div class="p-3 bg-surface-container-low border-2 border-on-surface neo-box-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="px-1.5 py-0.2 bg-emerald-600 text-white font-label-sm text-[10px] font-bold">APPROVED</span>
                                    <span class="text-label-sm font-label-sm text-outline font-bold">14:18:02 WIB</span>
                                </div>
                                <div class="font-headline-sm text-body-md font-bold text-on-surface">
                                    PS 05 • Tambah Waktu +1 Jam
                                </div>
                                <div class="text-body-sm text-on-surface-variant mt-0.5">
                                    Rp 25.000 • Kasir: #02 (Budi)
                                </div>
                                <div class="mt-2 text-label-sm text-tertiary font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">check</span>
                                    <span>Timer konsol sinkron otomatis</span>
                                </div>
                            </div>
                            <!-- Log Item 2 -->
                            <div class="p-3 bg-surface-container-low border-2 border-on-surface neo-box-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="px-1.5 py-0.2 bg-primary text-white font-label-sm text-[10px] font-bold">BILLED
                                        F&amp;B</span>
                                    <span class="text-label-sm font-label-sm text-outline font-bold">14:10:45 WIB</span>
                                </div>
                                <div class="font-headline-sm text-body-md font-bold text-on-surface">
                                    PS 01 • 1x Kopi Susu Aren, 1x Taro
                                </div>
                                <div class="text-body-sm text-on-surface-variant mt-0.5">
                                    Rp 28.000 • Kasir: #02 (Budi)
                                </div>
                                <div class="mt-2 text-label-sm text-primary font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">done_all</span>
                                    <span>Kertas printer dapur tercetak</span>
                                </div>
                            </div>
                            <!-- Log Item 3 -->
                            <div class="p-3 bg-surface-container-low border-2 border-on-surface neo-box-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="px-1.5 py-0.2 bg-emerald-600 text-white font-label-sm text-[10px] font-bold">APPROVED</span>
                                    <span class="text-label-sm font-label-sm text-outline font-bold">13:52:11 WIB</span>
                                </div>
                                <div class="font-headline-sm text-body-md font-bold text-on-surface">
                                    PS 09 (VIP) • Tambah Waktu +2 Jam
                                </div>
                                <div class="text-body-sm text-on-surface-variant mt-0.5">
                                    Rp 60.000 • Kasir: #02 (Budi)
                                </div>
                                <div class="mt-2 text-label-sm text-tertiary font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">check</span>
                                    <span>Diskon Member Platinum 10%</span>
                                </div>
                            </div>
                            <!-- Log Item 4 (Rejected Case) -->
                            <div class="p-3 bg-red-50 border-2 border-on-surface neo-box-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <span
                                        class="px-1.5 py-0.2 bg-red-600 text-white font-label-sm text-[10px] font-bold">REJECTED</span>
                                    <span class="text-label-sm font-label-sm text-outline font-bold">13:40:19 WIB</span>
                                </div>
                                <div class="font-headline-sm text-body-md font-bold text-on-surface">
                                    PS 04 • Tambah Waktu +1 Jam
                                </div>
                                <div class="text-body-sm text-error mt-0.5 font-bold">
                                    Ditolak: Booking berikutnya jam 14:00
                                </div>
                            </div>
                        </div>
                        <!-- Full Audit Log Link -->
                        <button
                            class="w-full mt-4 py-2 bg-surface text-on-surface border-2 border-on-surface font-label-md font-bold neo-box-sm neo-btn flex items-center justify-center gap-2 hover:bg-surface-container">
                            <span class="material-symbols-outlined text-sm">receipt_long</span>
                            <span>Buka Audit Log Lengkap</span>
                        </button>
                    </div>
                    <!-- Quick Cashier Shift Stats Card -->
                    <div class="bg-surface-container-high border-[2.5px] border-on-surface neo-box-md p-4">
                        <h3
                            class="font-headline-sm text-headline-sm font-bold text-on-surface mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">point_of_sale</span>
                            <span>Ringkasan Kasir Shift Ini</span>
                        </h3>
                        <div class="space-y-2 text-body-sm font-body-sm">
                            <div class="flex justify-between items-center py-1 border-b border-on-surface">
                                <span class="font-bold">Total Request Disetujui:</span>
                                <span class="font-timer-display-mobile font-bold text-tertiary">18 Transaksi</span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-on-surface">
                                <span class="font-bold">Total Tambahan Jam:</span>
                                <span class="font-timer-display-mobile font-bold text-primary">Rp 235.000</span>
                            </div>
                            <div class="flex justify-between items-center py-1">
                                <span class="font-bold">Total Tambahan F&amp;B:</span>
                                <span class="font-timer-display-mobile font-bold text-secondary">Rp 142.000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="fixed bottom-6 right-6 z-50 pointer-events-none flex flex-col gap-2" id="toastContainer"></div>

@endsection

@push('scripts')
    <script>
        let soundEnabled = true;

        function toggleSound() {
            soundEnabled = !soundEnabled;
            const soundLabel = document.getElementById('soundLabel');
            const soundIcon = document.getElementById('soundIcon');
            if (soundEnabled) {
                soundLabel.innerText = 'AKTIF (85dB)';
                soundLabel.className = 'text-label-sm text-emerald-700 font-bold';
                soundIcon.innerText = 'volume_up';
                showToast('Suara notifikasi audio diaktifkan', 'tertiary');
            } else {
                soundLabel.innerText = 'SENYAP (MUTE)';
                soundLabel.className = 'text-label-sm text-error font-bold';
                soundIcon.innerText = 'volume_off';
                showToast('Suara notifikasi disenyapkan', 'error');
            }
        }

        function approveRequest(cardId, bayName, desc) {
            const card = document.getElementById(cardId);
            if (!card) return;

            // Animate card removal
            card.style.opacity = '0.5';
            card.style.transform = 'scale(0.98)';

            setTimeout(() => {
                card.remove();
                showToast(`Request ${bayName} BERHASIL DISETUJUI!`, 'emerald');

                // Append to Recent History Log
                const logFeed = document.getElementById('activity-log-feed');
                const now = new Date();
                const timeStr = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                }) + ' WIB';

                const newLog = document.createElement('div');
                newLog.className = 'p-3 bg-surface-container-low border-2 border-on-surface neo-box-sm';
                newLog.innerHTML = `
          <div class="flex items-center justify-between mb-1">
            <span class="px-1.5 py-0.2 bg-emerald-600 text-white font-label-sm text-[10px] font-bold">APPROVED</span>
            <span class="text-label-sm font-label-sm text-outline font-bold">${timeStr}</span>
          </div>
          <div class="font-headline-sm text-body-md font-bold text-on-surface">
            ${bayName} • ${desc}
          </div>
          <div class="text-body-sm text-on-surface-variant mt-0.5">
            Kasir: #02 (Budi) • Eksekusi Instan
          </div>
        `;
                logFeed.insertBefore(newLog, logFeed.firstChild);
            }, 200);
        }

        function rejectRequest(cardId, bayName) {
            const card = document.getElementById(cardId);
            if (!card) return;

            if (!confirm(`Tolak permintaan dari ${bayName}?`)) return;

            card.style.opacity = '0.4';
            card.style.borderColor = '#ba1a1a';

            setTimeout(() => {
                card.remove();
                showToast(`Request ${bayName} Ditolak oleh Kasir`, 'error');

                // Append to Recent History Log
                const logFeed = document.getElementById('activity-log-feed');
                const now = new Date();
                const timeStr = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                }) + ' WIB';

                const newLog = document.createElement('div');
                newLog.className = 'p-3 bg-red-50 border-2 border-on-surface neo-box-sm';
                newLog.innerHTML = `
          <div class="flex items-center justify-between mb-1">
            <span class="px-1.5 py-0.2 bg-red-600 text-white font-label-sm text-[10px] font-bold">REJECTED</span>
            <span class="text-label-sm font-label-sm text-outline font-bold">${timeStr}</span>
          </div>
          <div class="font-headline-sm text-body-md font-bold text-on-surface">
            ${bayName} • Ditolak Operator Kasir
          </div>
          <div class="text-body-sm text-error mt-0.5 font-bold">
            Alasan: Alokasi unit terbatas
          </div>
        `;
                logFeed.insertBefore(newLog, logFeed.firstChild);
            }, 200);
        }

        function handleAssistance(cardId, bayName) {
            showToast(`Teknisi / Kasir meluncur ke ${bayName}!`, 'secondary');
            const card = document.getElementById(cardId);
            if (card) {
                card.classList.add('ring-4', 'ring-amber-500');
            }
        }

        function showToast(message, type) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className =
                'pointer-events-auto px-4 py-3 bg-surface-container-lowest text-on-surface border-[2.5px] border-on-surface neo-box-md font-label-md font-bold flex items-center gap-3 animate-bounce';

            let icon = 'check_circle';
            let iconColor = 'text-emerald-600';
            if (type === 'error') {
                icon = 'cancel';
                iconColor = 'text-red-600';
            } else if (type === 'secondary') {
                icon = 'notifications';
                iconColor = 'text-secondary';
            }

            toast.innerHTML = `
        <span class="material-symbols-outlined ${iconColor}">${icon}</span>
        <span>${message}</span>
      `;
            container.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3200);
        }
    </script>
@endpush
