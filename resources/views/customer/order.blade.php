@extends('layouts.customer')

@section('title', 'Order - TambahBang')

@section('content')

    <main class="w-full max-w-[420px] bg-background min-h-screen flex flex-col relative px-3.5 pt-3 pb-8 neo-border-2 border-y-0 sm:border-y-2 sm:my-3">

        {{-- ===================== HEADER ===================== --}}
        <header class="w-full flex flex-col gap-2.5 pb-3 mb-3 border-b-2 border-dashed border-on-surface">

            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 bg-primary-container text-on-primary neo-border-2 flex items-center justify-center neo-shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">
                            sports_esports
                        </span>
                    </div>
                    <div>
                        <h1 class="font-headline-sm text-[17px] font-extrabold tracking-tight text-on-surface leading-tight uppercase">
                            TAMBAHBANG HUB
                        </h1>
                        <p class="font-label-sm text-[11px] text-on-surface-variant font-bold tracking-wider leading-none mt-0.5">
                            PS RENTAL &amp; POS CONSOLE
                        </p>
                    </div>
                </div>

                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-tertiary-fixed neo-border-2 text-on-tertiary-fixed neo-shadow-sm shrink-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-tertiary inline-block pulse-dot"></span>
                    <span class="font-label-sm text-[11px] font-black tracking-wider whitespace-nowrap">SESI AKTIF</span>
                </div>
            </div>

            <div class="w-full bg-[#FFE500] neo-border-2 px-3 py-2 flex items-center justify-between neo-shadow-sm">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="material-symbols-outlined text-on-surface text-lg shrink-0">tv</span>
                    <span class="font-label-md text-xs sm:text-sm text-on-surface font-black tracking-wide truncate">
                        UNIT: PS 03 (PS5 DISC)
                    </span>
                </div>
                <div class="bg-on-surface text-surface-container-lowest font-label-sm text-[10px] px-2 py-0.5 font-extrabold shrink-0 border border-on-surface">
                    VIP BAY A
                </div>
            </div>

        </header>

       
        <section class="w-full bg-surface-container-lowest neo-border-3 neo-shadow-md p-3.5 mb-3.5 flex flex-col relative">

            <div class="flex items-center justify-between pb-2.5 border-b-2 border-on-surface mb-3">
                <div class="flex items-center gap-1.5 text-on-surface font-label-md text-xs font-bold tracking-wide">
                    <span class="material-symbols-outlined text-base text-primary">timer</span>
                    <span>SISA WAKTU BERMAIN</span>
                </div>
                <span class="font-label-sm text-[10px] bg-surface-container-high px-2 py-0.5 neo-border-2 font-bold tracking-wider uppercase">
                    PREPAID
                </span>
            </div>

            <div class="w-full bg-surface-container-low neo-border-2 p-3 text-center mb-3 neo-shadow-sm">
                <div class="font-timer-display text-[40px] leading-tight sm:text-[46px] font-bold tracking-tight text-primary tabular-nums select-none" id="countdown-timer">
                    01:15:33
                </div>
                <div class="flex items-center justify-center gap-1.5 mt-1.5 pt-1 border-t border-outline-variant/60">
                    <span class="inline-block w-2 h-2 rounded-full bg-primary animate-ping"></span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-widest font-bold">
                        Auto-Syncing with Matrix
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-1 p-2 bg-surface neo-border-2 mb-3 font-label-sm text-center">
                <div class="border-r-2 border-on-surface px-1">
                    <span class="text-on-surface-variant text-[10px] block font-semibold uppercase">Paket</span>
                    <span class="text-on-surface font-extrabold text-xs sm:text-sm">2 Jam</span>
                </div>
                <div class="border-r-2 border-on-surface px-1">
                    <span class="text-on-surface-variant text-[10px] block font-semibold uppercase">Mulai</span>
                    <span class="text-on-surface font-extrabold text-xs sm:text-sm">13:00</span>
                </div>
                <div class="px-1">
                    <span class="text-on-surface-variant text-[10px] block font-semibold uppercase">Berakhir</span>
                    <span class="text-primary font-extrabold text-xs sm:text-sm">15:00</span>
                </div>
            </div>

            {{-- Structured Billing Receipt Strip --}}
            <div class="bg-surface-container-high neo-border-2 p-3 flex flex-col gap-2 font-label-sm">
                <div class="flex justify-between items-center text-xs text-on-surface-variant font-medium">
                    <span>Rental PS5 (2 Jam)</span>
                    <span class="font-bold text-on-surface">Rp 50.000</span>
                </div>
                <div class="flex justify-between items-center text-xs text-on-surface-variant font-medium">
                    <span>Pesanan F&amp;B (1 Item)</span>
                    <span class="font-bold text-on-surface">Rp 12.000</span>
                </div>
                <div class="border-t-2 border-dashed border-on-surface pt-2 mt-0.5 flex justify-between items-baseline gap-2">
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-xs font-black uppercase tracking-wider text-on-surface">
                            TOTAL TAGIHAN
                        </span>
                        <span class="text-[10px] text-on-surface-variant italic">
                            Bayar di kasir saat checkout
                        </span>
                    </div>
                    <span class="font-timer-display text-2xl font-black text-primary tracking-tight tabular-nums">
                        Rp 62.000
                    </span>
                </div>
            </div>

        </section>

    
        <section class="w-full flex flex-col gap-2.5 mb-4">

            {{-- Extend Button: High energy Orange --}}
            <button
                class="neo-btn w-full min-h-[50px] bg-secondary-container hover:bg-secondary text-surface-container-lowest px-4 py-3 neo-border-3 neo-shadow-md flex items-center justify-between font-headline-sm text-sm font-black tracking-tight"
                onclick="handleAction('extend')">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-2xl shrink-0" style="font-variation-settings: 'FILL' 1;">bolt</span>
                    <span class="uppercase">TAMBAH WAKTU / EXTEND</span>
                </div>
                <span class="material-symbols-outlined text-xl font-black">arrow_forward</span>
            </button>

            {{-- F&B Order Button: Royal Blue --}}
            <button
                class="neo-btn w-full min-h-[50px] bg-primary hover:bg-primary-container text-on-primary px-4 py-3 neo-border-3 neo-shadow-md flex items-center justify-between font-headline-sm text-sm font-black tracking-tight"
                onclick="handleAction('menu')">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-2xl shrink-0" style="font-variation-settings: 'FILL' 1;">ramen_dining</span>
                    <span class="uppercase">PESAN MAKANAN &amp; MINUMAN</span>
                </div>
                <span class="material-symbols-outlined text-xl font-black">add_shopping_cart</span>
            </button>

        </section>

       
        <section class="w-full mb-4">

            <div class="flex items-center justify-between mb-2 px-0.5">
                <h2 class="font-headline-sm text-sm font-extrabold tracking-tight flex items-center gap-1.5 uppercase text-on-surface">
                    <span class="material-symbols-outlined text-primary text-lg">sync_saved_locally</span>
                    STATUS PERMINTAAN ANDA
                </h2>
                <span class="font-label-sm text-[10px] bg-surface-container-high text-on-surface px-2 py-0.5 neo-border-2 font-black">
                    2 AKTIF
                </span>
            </div>

            <div class="flex flex-col gap-2.5">

                {{-- Request 1: Pending Extend Request --}}
                <div class="bg-surface-container-lowest neo-border-2 p-3 neo-shadow-sm flex flex-col gap-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-outlined text-secondary text-xl shrink-0">more_time</span>
                            <span class="font-headline-sm text-xs font-bold text-on-surface truncate">
                                Tambah Waktu +30 Menit
                            </span>
                        </div>
                        <div class="bg-[#FFF4D6] text-secondary neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold flex items-center gap-1 shrink-0">
                            <span class="material-symbols-outlined text-xs animate-spin" style="animation-duration: 4s;">hourglass_top</span>
                            <span class="whitespace-nowrap">MENUNGGU KASIR</span>
                        </div>
                    </div>
                    <p class="font-body-sm text-xs text-on-surface-variant pl-7 leading-normal">
                        Kasir sedang mengonfirmasi request Anda. Unit timer akan otomatis bertambah saat disetujui.
                    </p>
                </div>

                {{-- Request 2: Approved F&B Delivery --}}
                <div class="bg-surface-container-lowest neo-border-2 p-3 neo-shadow-sm flex flex-col gap-1.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-outlined text-tertiary text-xl shrink-0" style="font-variation-settings: 'FILL' 1;">sports_bar</span>
                            <span class="font-headline-sm text-xs font-bold text-on-surface truncate">
                                2x Es Teh Manis Jumbo
                            </span>
                        </div>
                        <div class="bg-tertiary-fixed text-on-tertiary-fixed neo-border-2 px-2 py-0.5 font-label-sm text-[10px] font-bold flex items-center gap-1 shrink-0">
                            <span class="material-symbols-outlined text-xs font-black">check_circle</span>
                            <span class="whitespace-nowrap">DISETUJUI (APPROVED)</span>
                        </div>
                    </div>
                    <p class="font-body-sm text-xs text-on-surface-variant pl-7 leading-normal">
                        Pesanan sudah siap dan sedang diantar oleh kru ke meja <strong class="text-on-surface">PS 03</strong>.
                    </p>
                </div>

            </div>

        </section>

        {{-- ===================== 5. F&B QUICK MENU PREVIEW (Grid 2 Kolom) ===================== --}}
        <section class="w-full bg-surface-container-lowest neo-border-3 neo-shadow-md p-3 mb-3.5">

            <div class="flex items-center justify-between pb-2 border-b-2 border-on-surface mb-2.5">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary font-bold text-lg">restaurant_menu</span>
                    <h3 class="font-headline-sm text-xs sm:text-sm font-black tracking-tight uppercase">
                        MENU CEPAT POPULER
                    </h3>
                </div>
                <span class="font-label-sm text-[10px] bg-secondary-fixed text-on-secondary-fixed font-bold px-2 py-0.5 neo-border-2">
                    STATION SNACKS
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2">

                {{-- Item 1 --}}
                <div class="bg-surface-container-low neo-border-2 p-2.5 flex flex-col justify-between">
                    <div class="min-h-[42px]">
                        <div class="font-headline-sm text-xs font-bold text-on-surface leading-snug line-clamp-2">
                            Mie Instan + Telur Kornet
                        </div>
                        <div class="font-label-sm text-xs text-primary font-bold mt-1">Rp 15.000</div>
                    </div>
                    <button
                        class="neo-btn mt-2.5 w-full bg-on-surface hover:bg-slate-800 text-surface-container-lowest font-label-sm text-[11px] font-bold py-1.5 px-2 neo-border-2 neo-shadow-sm flex items-center justify-center gap-1"
                        onclick="orderQuick('Mie Instan Telur Kornet', 15000)">
                        <span class="material-symbols-outlined text-xs">add</span> PESAN
                    </button>
                </div>

                {{-- Item 2 --}}
                <div class="bg-surface-container-low neo-border-2 p-2.5 flex flex-col justify-between">
                    <div class="min-h-[42px]">
                        <div class="font-headline-sm text-xs font-bold text-on-surface leading-snug line-clamp-2">
                            Kopi Susu Gula Aren
                        </div>
                        <div class="font-label-sm text-xs text-primary font-bold mt-1">Rp 12.000</div>
                    </div>
                    <button
                        class="neo-btn mt-2.5 w-full bg-on-surface hover:bg-slate-800 text-surface-container-lowest font-label-sm text-[11px] font-bold py-1.5 px-2 neo-border-2 neo-shadow-sm flex items-center justify-center gap-1"
                        onclick="orderQuick('Kopi Susu Aren', 12000)">
                        <span class="material-symbols-outlined text-xs">add</span> PESAN
                    </button>
                </div>

                {{-- Item 3 --}}
                <div class="bg-surface-container-low neo-border-2 p-2.5 flex flex-col justify-between">
                    <div class="min-h-[42px]">
                        <div class="font-headline-sm text-xs font-bold text-on-surface leading-snug line-clamp-2">
                            Es Teh Manis Jumbo
                        </div>
                        <div class="font-label-sm text-xs text-primary font-bold mt-1">Rp 6.000</div>
                    </div>
                    <button
                        class="neo-btn mt-2.5 w-full bg-on-surface hover:bg-slate-800 text-surface-container-lowest font-label-sm text-[11px] font-bold py-1.5 px-2 neo-border-2 neo-shadow-sm flex items-center justify-center gap-1"
                        onclick="orderQuick('Es Teh Manis Jumbo', 6000)">
                        <span class="material-symbols-outlined text-xs">add</span> PESAN
                    </button>
                </div>

                {{-- Item 4 --}}
                <div class="bg-surface-container-low neo-border-2 p-2.5 flex flex-col justify-between">
                    <div class="min-h-[42px]">
                        <div class="font-headline-sm text-xs font-bold text-on-surface leading-snug line-clamp-2">
                            Snack Keripik Pedas
                        </div>
                        <div class="font-label-sm text-xs text-primary font-bold mt-1">Rp 8.000</div>
                    </div>
                    <button
                        class="neo-btn mt-2.5 w-full bg-on-surface hover:bg-slate-800 text-surface-container-lowest font-label-sm text-[11px] font-bold py-1.5 px-2 neo-border-2 neo-shadow-sm flex items-center justify-center gap-1"
                        onclick="orderQuick('Snack Keripik', 8000)">
                        <span class="material-symbols-outlined text-xs">add</span> PESAN
                    </button>
                </div>

            </div>

        </section>

        {{-- ===================== 6. OPERATIONAL ALERT NOTICE ===================== --}}
        <section class="w-full bg-[#FFFBEB] neo-border-2 p-3 mb-3.5 flex items-start gap-2.5 neo-shadow-sm">
            <span class="material-symbols-outlined text-secondary font-bold text-xl shrink-0 mt-0.5">warning</span>
            <div class="font-body-sm text-xs text-on-surface leading-relaxed">
                <strong class="font-headline-sm uppercase text-xs font-bold text-secondary">Peringatan:</strong>
                Buzzer otomatis dan layar TV akan mati saat waktu habis. Perpanjang waktu sebelum timer mencapai
                <span class="font-label-sm font-bold bg-[#FEE2E2] text-error px-1 py-0.5 neo-border-2">00:00:00</span>
                untuk terus bermain tanpa jeda.
            </div>
        </section>

        {{-- ===================== 7. FOOTER & EMERGENCY ACTION ===================== --}}
        <footer class="w-full mt-auto flex flex-col gap-2.5 pt-1">
            <button
                class="neo-btn w-full min-h-[48px] bg-surface-container-lowest hover:bg-surface-container text-on-surface p-3 neo-border-2 neo-shadow-md flex items-center justify-center gap-2 font-headline-sm text-xs sm:text-sm font-extrabold tracking-tight uppercase"
                onclick="callCashier()">
                <span class="material-symbols-outlined text-primary text-xl">support_agent</span>
                <span>PANGGIL KASIR KE MEJA PS 03</span>
            </button>
            <div class="text-center font-label-sm text-[10px] text-on-surface-variant flex items-center justify-center gap-1.5 py-1">
                <span class="material-symbols-outlined text-xs">lock</span>
                <span>KONEKSI ENKRIPSI SISTEM TAMBAHBANG MATRIX &bull; TANPA LOGIN</span>
            </div>
        </footer>

        {{-- ===================== MODAL: EXTEND TIME ===================== --}}
        <div class="fixed inset-0 bg-on-surface/60 z-50 hidden flex-col justify-end backdrop-blur-[1px]" id="modal-extend">
            <div class="w-full max-w-md mx-auto bg-surface-container-lowest neo-border-3 border-b-0 p-4 neo-shadow-lg flex flex-col gap-3.5">

                <div class="flex items-center justify-between border-b-2 border-on-surface pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary-container text-2xl font-bold">bolt</span>
                        <h3 class="font-headline-sm text-sm font-extrabold uppercase">TAMBAH WAKTU SEWA</h3>
                    </div>
                    <button
                        class="neo-btn w-8 h-8 bg-surface-container neo-border-2 flex items-center justify-center font-bold"
                        onclick="closeModal('modal-extend')">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <p class="font-body-sm text-xs text-on-surface-variant leading-relaxed">
                    Pilih durasi tambahan waktu bermain Anda. Tagihan akan otomatis dimasukkan ke kasir.
                </p>

                <div class="grid grid-cols-3 gap-2 font-label-md">
                    <button
                        class="neo-btn p-2.5 bg-surface neo-border-2 neo-shadow-sm flex flex-col items-center gap-1 hover:bg-surface-container-high"
                        onclick="submitExtend('+30 Menit', 15000)">
                        <span class="font-bold text-sm text-on-surface">+30m</span>
                        <span class="text-[10px] text-on-surface-variant">Rp 15.000</span>
                    </button>
                    <button
                        class="neo-btn p-2.5 bg-secondary-container text-surface-container-lowest neo-border-2 neo-shadow-sm flex flex-col items-center gap-1"
                        onclick="submitExtend('+1 Jam', 25000)">
                        <span class="font-bold text-sm">+1 Jam</span>
                        <span class="text-[10px]">Rp 25.000</span>
                    </button>
                    <button
                        class="neo-btn p-2.5 bg-surface neo-border-2 neo-shadow-sm flex flex-col items-center gap-1 hover:bg-surface-container-high"
                        onclick="submitExtend('+2 Jam', 45000)">
                        <span class="font-bold text-sm text-on-surface">+2 Jam</span>
                        <span class="text-[10px] text-on-surface-variant">Rp 45.000</span>
                    </button>
                </div>

                <button
                    class="neo-btn w-full bg-surface-container neo-border-2 py-2.5 font-label-md text-xs font-bold text-on-surface mt-1"
                    onclick="closeModal('modal-extend')">
                    BATALKAN
                </button>

            </div>
        </div>

        {{-- ===================== NOTIFICATION TOAST ===================== --}}
        <div
            class="fixed top-4 left-1/2 -translate-x-1/2 w-11/12 max-w-sm bg-on-surface text-surface-container-lowest neo-border-2 p-3 neo-shadow-lg z-50 hidden items-center gap-2 font-label-md text-xs"
            id="toast">
            <span class="material-symbols-outlined text-tertiary-fixed text-lg shrink-0" style="font-variation-settings: 'FILL' 1;">
                check_circle
            </span>
            <span id="toast-msg">Permintaan terkirim ke kasir!</span>
        </div>

    </main>

@endsection