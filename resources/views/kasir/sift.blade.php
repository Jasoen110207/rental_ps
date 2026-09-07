@extends('layouts.app')

@section('title', 'Manajemen Shift Kasir - TambahBang')

@section('content')
<main class="flex-1 p-6 flex flex-col gap-6 max-w-[1600px] overflow-y-auto">

   
    <div class="flex flex-wrap items-center gap-3 p-3 bg-surface-container-lowest border-2 border-on-surface neo-shadow">
        <div class="flex items-center gap-2 bg-surface-container px-3 py-1.5 border-2 border-on-surface">
            <span class="w-2.5 h-2.5 rounded-full bg-secondary-container animate-pulse"></span>
            <span class="font-headline-sm text-sm font-bold text-on-surface">TambahBang Operational Matrix</span>
        </div>
        <div class="hidden xl:flex items-center gap-2 bg-surface-bright px-3 py-1.5 border-2 border-on-surface">
            <span class="font-label-sm text-xs font-bold text-on-surface-variant">MODUL:</span>
            <span class="font-label-sm text-xs font-bold bg-primary text-white px-1.5 py-0.5">KASIR HANDOVER v2.4</span>
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-surface-container-low border-2 border-on-surface">
            <span class="material-symbols-outlined text-secondary text-lg">timer</span>
            <span class="font-label-sm text-xs font-bold text-on-surface">SHIFT RUNTIME:</span>
            <span class="font-label-md text-xs font-bold text-primary tracking-wider" id="runtime-ticker">04:32:18</span>
        </div>
    </div>

   
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 font-label-md text-label-md">
            <span class="text-on-surface-variant">CONSOLE DECK</span>
            <span class="text-on-surface-variant font-bold">/</span>
            <span class="text-on-surface-variant">OPERASIONAL KASIR</span>
            <span class="text-on-surface-variant font-bold">/</span>
            <span class="bg-primary text-white px-2 py-0.5 font-bold neo-border">MANAJEMEN SHIFT &amp; HANDOVER</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-label-sm text-label-sm text-on-surface-variant">TANGGAL OPERASIONAL:</span>
            <span class="font-label-md text-label-md font-bold bg-surface-container-low px-2 py-1 neo-border">SELASA, 24 OKTOBER 2023</span>
        </div>
    </div>

   
    <section class="bg-surface-container-lowest neo-border-thick neo-shadow-lg flex flex-col overflow-hidden">
       
        <div class="bg-on-surface text-white px-6 py-3 flex flex-wrap items-center justify-between gap-3 border-b-2 border-on-surface">
            <div class="flex items-center gap-3">
                <span class="w-3.5 h-3.5 rounded-full bg-secondary-container animate-ping"></span>
                <h2 class="font-headline-md text-headline-md tracking-tight uppercase">SHIFT SIANG #02 — KASIR RIAN HIDAYAT</h2>
                <span class="px-2.5 py-0.5 bg-tertiary text-on-tertiary font-label-sm text-label-sm font-bold neo-border">STATUS: AKTIF / BERJALAN</span>
            </div>
            <div class="flex items-center gap-4 text-white font-label-md text-label-md">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">login</span>
                    <span>Jam Mulai: <strong class="text-primary-fixed">08:00 WIB</strong></span>
                </div>
                <div class="w-px h-4 bg-outline"></div>
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base text-secondary-container">timelapse</span>
                    <span>Durasi Berjalan: <strong class="text-secondary-container font-headline-sm">4 Jam 32 Menit</strong></span>
                </div>
            </div>
        </div>

 
        <div class="p-6 bg-surface-bright grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
           
            <div class="bg-white p-4 neo-border neo-shadow-md flex flex-col justify-between">
                <div class="flex items-center justify-between pb-2 border-b-2 border-outline-variant">
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-bold uppercase">1. Saldo Kas Awal</span>
                    <span class="material-symbols-outlined text-primary text-xl">payments</span>
                </div>
                <div class="py-3">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Modal Uang Kembalian</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Rp 200.000</p>
                </div>
                <div class="pt-2 border-t border-dashed border-outline-variant flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm">
                    <span>Diserahterimakan dari:</span>
                    <span class="font-bold text-on-surface">Kasir #01</span>
                </div>
            </div>

            
            <div class="bg-white p-4 neo-border neo-shadow-md flex flex-col justify-between">
                <div class="flex items-center justify-between pb-2 border-b-2 border-outline-variant">
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-bold uppercase">2. Penerimaan Rental PS</span>
                    <span class="material-symbols-outlined text-primary text-xl material-symbols-fill">sports_esports</span>
                </div>
                <div class="py-3">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">24 Sesi Billing Terbayar</p>
                    <p class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight">Rp 1.120.000</p>
                </div>
                <div class="pt-2 border-t border-dashed border-outline-variant flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm">
                    <span>Rata-rata/jam:</span>
                    <span class="font-bold text-primary">Rp 247.000</span>
                </div>
            </div>

          
            <div class="bg-white p-4 neo-border neo-shadow-md flex flex-col justify-between">
                <div class="flex items-center justify-between pb-2 border-b-2 border-outline-variant">
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-bold uppercase">3. Penerimaan F&amp;B Lounge</span>
                    <span class="material-symbols-outlined text-secondary text-xl">local_cafe</span>
                </div>
                <div class="py-3">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Mi Instan, Kopi, Minuman Dingin</p>
                    <p class="font-headline-lg text-headline-lg text-secondary font-bold tracking-tight">Rp 365.000</p>
                </div>
                <div class="pt-2 border-t border-dashed border-outline-variant flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm">
                    <span>Total Item Terjual:</span>
                    <span class="font-bold text-on-surface">38 Pcs</span>
                </div>
            </div>

       
            <div class="bg-surface-container-high p-4 neo-border-thick neo-shadow-md flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-on-surface/5 select-none pointer-events-none">
                    <span class="material-symbols-outlined text-8xl">point_of_sale</span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b-2 border-on-surface">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-on-secondary-container text-xl">lock_clock</span>
                        <span class="font-label-sm text-label-sm text-on-surface font-extrabold uppercase">KAS FISIK DI LACI</span>
                    </div>
                    <span class="bg-secondary-container text-white px-2 py-0.5 font-label-sm text-[10px] font-bold neo-border">EXPECTED CASH</span>
                </div>
                <div class="py-3 z-10">
                    <p class="font-label-sm text-label-sm text-on-surface-variant font-bold">Total Wajib Fisik Laci Kasir:</p>
                    <p class="font-headline-xl text-headline-xl text-on-surface font-extrabold tracking-tight leading-none mt-1">Rp 1.685.000</p>
                </div>
                <div class="pt-2 border-t-2 border-on-surface flex items-center justify-between text-on-surface font-label-sm text-label-sm z-10">
                    <span>Rumus:</span>
                    <span class="font-label-sm text-label-sm font-bold bg-white px-1.5 py-0.5 neo-border">Awal + Rental + F&amp;B</span>
                </div>
            </div>
        </div>

     
        <div class="mx-6 mb-6 p-4 bg-surface-container-low neo-border flex items-start gap-4">
            <div class="w-9 h-9 bg-secondary-container text-white flex items-center justify-center neo-border flex-shrink-0 mt-0.5">
                <span class="material-symbols-outlined text-2xl">verified_user</span>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h3 class="font-headline-sm text-headline-sm text-on-surface uppercase">JAMINAN KONTINUITAS OPERASIONAL SEAMLESS</h3>
                    <span class="px-2 py-0.2 bg-primary text-white font-label-sm text-[10px] font-bold uppercase">Zero-Downtime Handover</span>
                </div>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    <strong>Catatan Sistem:</strong> Pergantian shift <span class="text-error font-bold underline decoration-2">TIDAK AKAN menghentikan sesi rental yang sedang aktif</span> pada konsol PlayStation. Seluruh timer konsol, billing terbuka, dan pesanan makanan bayar-nanti akan otomatis dialihkan secara utuh ke tanggung jawab shift berikutnya (Shift Sore #03).
                </p>
            </div>
        </div>

   
        <div class="px-6 py-4 bg-surface-container border-t-2 border-on-surface flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button class="px-5 py-3 bg-white text-on-surface neo-border neo-shadow-sm font-label-md text-label-md font-bold flex items-center gap-2 neo-btn hover:bg-surface-bright">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                    <span>REKONSILIASI KAS &amp; CETAK X-REPORT</span>
                </button>
                <button class="px-5 py-3 bg-white text-on-surface neo-border neo-shadow-sm font-label-md text-label-md font-bold flex items-center gap-2 neo-btn hover:bg-surface-bright">
                    <span class="material-symbols-outlined text-xl">inventory_2</span>
                    <span>STOK FISIK F&amp;B AUDIT</span>
                </button>
            </div>
            <div class="flex items-center gap-3">
                <button class="px-4 py-3 bg-surface-container-high text-on-surface neo-border font-label-md text-label-md font-bold flex items-center gap-2 neo-btn hover:bg-white">
                    <span class="material-symbols-outlined text-xl">emergency</span>
                    <span>LAPOR SELISIH KAS (INCIDENT)</span>
                </button>
              
                <button class="px-7 py-3 bg-secondary-container text-white neo-border-thick neo-shadow-lg font-headline-sm text-headline-sm font-extrabold flex items-center gap-3 neo-btn hover:bg-secondary tracking-wide">
                    <span class="material-symbols-outlined text-2xl material-symbols-fill">logout</span>
                    <span>TUTUP &amp; AKHIRI SHIFT (HANDOVER)</span>
                </button>
            </div>
        </div>
    </section>

  
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        
        <div class="xl:col-span-5 bg-white neo-border-thick neo-shadow-md flex flex-col">
         
            <div class="p-4 bg-surface-container-low border-b-2 border-on-surface flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-tertiary-container rounded-full animate-ping"></span>
                        <h3 class="font-headline-sm text-headline-sm text-on-surface uppercase">ACTIVE SESSIONS SNAPSHOT</h3>
                    </div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">5 Konsol Aktif Akan Dialihkan ke Shift #03</p>
                </div>
                <span class="px-2.5 py-1 bg-primary text-white font-label-sm text-label-sm font-bold neo-border">5/10 RUNNING</span>
            </div>

            
            <div class="p-4 flex flex-col gap-3 flex-1 overflow-y-auto max-h-[460px]">
                
                <div class="p-3 bg-white neo-border neo-shadow-sm flex items-center justify-between hover:bg-surface-bright transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-container text-white flex flex-col items-center justify-center neo-border font-headline-sm">
                            <span class="text-xs font-bold leading-none">PS</span>
                            <span class="text-lg font-extrabold leading-none">01</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-headline-sm text-headline-sm text-on-surface">BAY #01 PS5 VIP</span>
                                <span class="bg-tertiary text-white px-1.5 py-0.2 font-label-sm text-[10px] font-bold neo-border">OPEN BILL</span>
                            </div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Customer: <strong>Aldo &amp; Kawan (3 Stik)</strong></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-label-sm text-label-sm text-on-surface-variant block">Sisa Waktu:</span>
                        <span class="font-timer-display text-lg text-primary font-bold tracking-tight">01:45:10</span>
                        <span class="text-[11px] block text-on-surface-variant font-label-sm font-bold">Billing: Rp 95.000 (Belum Bayar)</span>
                    </div>
                </div>

              
                <div class="p-3 bg-white neo-border neo-shadow-sm flex items-center justify-between hover:bg-surface-bright transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary-container text-white flex flex-col items-center justify-center neo-border font-headline-sm">
                            <span class="text-xs font-bold leading-none">PS</span>
                            <span class="text-lg font-extrabold leading-none">02</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-headline-sm text-headline-sm text-on-surface">BAY #02 PS5 REG</span>
                                <span class="bg-secondary-container text-white px-1.5 py-0.2 font-label-sm text-[10px] font-bold neo-border">EXTENDED</span>
                            </div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Customer: <strong>Dimas (FC 24)</strong></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-label-sm text-label-sm text-on-surface-variant block">Sisa Waktu:</span>
                        <span class="font-timer-display text-lg text-secondary font-bold tracking-tight">00:18:42</span>
                        <span class="text-[11px] block text-on-surface-variant font-label-sm font-bold">Billing: Lunas (+F&amp;B Rp 20k)</span>
                    </div>
                </div>

               
                <div class="p-3 bg-white neo-border neo-shadow-sm flex items-center justify-between hover:bg-surface-bright transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary text-white flex flex-col items-center justify-center neo-border font-headline-sm">
                            <span class="text-xs font-bold leading-none">PS</span>
                            <span class="text-lg font-extrabold leading-none">03</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-headline-sm text-headline-sm text-on-surface">BAY #03 PS5 REG</span>
                                <span class="bg-surface-container-highest text-on-surface px-1.5 py-0.2 font-label-sm text-[10px] font-bold neo-border">RUNNING</span>
                            </div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Customer: <strong>Bagus (Tekken 8)</strong></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-label-sm text-label-sm text-on-surface-variant block">Sisa Waktu:</span>
                        <span class="font-timer-display text-lg text-on-surface font-bold tracking-tight">02:12:00</span>
                        <span class="text-[11px] block text-on-surface-variant font-label-sm font-bold">Billing: Lunas (Paket 3 Jam)</span>
                    </div>
                </div>

                <div class="p-3 bg-white neo-border neo-shadow-sm flex items-center justify-between hover:bg-surface-bright transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-on-surface text-white flex flex-col items-center justify-center neo-border font-headline-sm">
                            <span class="text-xs font-bold leading-none">PS</span>
                            <span class="text-lg font-extrabold leading-none">05</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-headline-sm text-headline-sm text-on-surface">BAY #05 PS4 PRO</span>
                                <span class="bg-error text-white px-1.5 py-0.2 font-label-sm text-[10px] font-bold neo-border">&lt; 10M LEFT</span>
                            </div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Customer: <strong>Eko &amp; Rama</strong></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-label-sm text-label-sm text-on-surface-variant block">Sisa Waktu:</span>
                        <span class="font-timer-display text-lg text-error font-bold tracking-tight">00:06:55</span>
                        <span class="text-[11px] block text-on-surface-variant font-label-sm font-bold">Konfirmasi Tambah Jam?</span>
                    </div>
                </div>

    
                <div class="p-3 bg-white neo-border neo-shadow-sm flex items-center justify-between hover:bg-surface-bright transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-on-surface text-white flex flex-col items-center justify-center neo-border font-headline-sm">
                            <span class="text-xs font-bold leading-none">PS</span>
                            <span class="text-lg font-extrabold leading-none">07</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-headline-sm text-headline-sm text-on-surface">BAY #07 PS4 PRO</span>
                                <span class="bg-surface-container-highest text-on-surface px-1.5 py-0.2 font-label-sm text-[10px] font-bold neo-border">RUNNING</span>
                            </div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant">Customer: <strong>Fajar (GTA V)</strong></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-label-sm text-label-sm text-on-surface-variant block">Sisa Waktu:</span>
                        <span class="font-timer-display text-lg text-on-surface font-bold tracking-tight">00:54:19</span>
                        <span class="text-[11px] block text-on-surface-variant font-label-sm font-bold">Billing: Lunas (Kasir #02)</span>
                    </div>
                </div>
            </div>

          
            <div class="p-3 bg-surface-container border-t-2 border-on-surface font-label-sm text-label-sm flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">info</span>
                    <span>Total Nilai Pending Bill Konsol:</span>
                </div>
                <span class="font-bold text-on-surface font-label-md text-label-md bg-white px-2 py-0.5 neo-border">Rp 95.000</span>
            </div>
        </div>

      
        <div class="xl:col-span-7 bg-white neo-border-thick neo-shadow-md flex flex-col">
           
            <div class="p-4 bg-surface-container-low border-b-2 border-on-surface flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface uppercase">RIWAYAT SHIFT TERAKHIR &amp; AUDIT LOG</h3>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Histori Verifikasi Supervisor &amp; Rekap Kasir Sebelumnya</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <button class="px-2.5 py-1 bg-white text-on-surface font-label-sm text-label-sm font-bold neo-border neo-btn">Filter: 7 Hari</button>
                    <button class="px-2.5 py-1 bg-white text-on-surface font-label-sm text-label-sm font-bold neo-border neo-btn flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">download</span>
                        <span>Export CSV</span>
                    </button>
                </div>
            </div>

            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-high border-b-2 border-on-surface font-label-sm text-label-sm text-on-surface uppercase tracking-wider">
                            <th class="p-3">Shift &amp; Tanggal</th>
                            <th class="p-3">Kasir Petugas</th>
                            <th class="p-3">Jam Masuk - Keluar</th>
                            <th class="p-3 text-center">Trx</th>
                            <th class="p-3 text-right">Total Omset</th>
                            <th class="p-3 text-center">Status Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-on-surface font-body-sm text-body-sm">
                     
                        <tr class="hover:bg-surface-bright">
                            <td class="p-3 font-label-md text-label-md font-bold text-on-surface">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                                    <span>Shift Pagi #01</span>
                                </div>
                                <span class="text-[10px] text-on-surface-variant block font-normal">24 Okt 2023</span>
                            </td>
                            <td class="p-3 font-medium text-on-surface">
                                <div>Gilang Ramadhan</div>
                                <span class="font-label-sm text-[10px] text-on-surface-variant">ID: KSR-008</span>
                            </td>
                            <td class="p-3 font-label-sm text-label-sm text-on-surface">
                                <span class="bg-surface-container px-1.5 py-0.5 neo-border">00:00 - 08:00</span>
                                <span class="block text-[10px] text-on-surface-variant mt-0.5">Durasi: 8 Jam</span>
                            </td>
                            <td class="p-3 text-center font-label-md text-label-md font-bold">19</td>
                            <td class="p-3 text-right">
                                <span class="font-label-md text-label-md font-bold text-primary">Rp 980.000</span>
                                <span class="text-[10px] text-on-surface-variant block">Kas Pas (Diff: Rp 0)</span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-tertiary-fixed-dim text-on-tertiary-fixed font-label-sm text-label-sm font-bold neo-border">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    <span>VERIFIED SPV</span>
                                </span>
                            </td>
                        </tr>

                        <tr class="hover:bg-surface-bright">
                            <td class="p-3 font-label-md text-label-md font-bold text-on-surface">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-on-surface"></span>
                                    <span>Shift Malam #03</span>
                                </div>
                                <span class="text-[10px] text-on-surface-variant block font-normal">23 Okt 2023</span>
                            </td>
                            <td class="p-3 font-medium text-on-surface">
                                <div>Yusuf Maulana</div>
                                <span class="font-label-sm text-[10px] text-on-surface-variant">ID: KSR-004</span>
                            </td>
                            <td class="p-3 font-label-sm text-label-sm text-on-surface">
                                <span class="bg-surface-container px-1.5 py-0.5 neo-border">16:00 - 00:00</span>
                                <span class="block text-[10px] text-on-surface-variant mt-0.5">Durasi: 8 Jam</span>
                            </td>
                            <td class="p-3 text-center font-label-md text-label-md font-bold">42</td>
                            <td class="p-3 text-right">
                                <span class="font-label-md text-label-md font-bold text-primary">Rp 2.450.000</span>
                                <span class="text-[10px] text-on-surface-variant block">Kas Pas (Diff: Rp 0)</span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-tertiary-fixed-dim text-on-tertiary-fixed font-label-sm text-label-sm font-bold neo-border">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    <span>VERIFIED SPV</span>
                                </span>
                            </td>
                        </tr>

                      
                        <tr class="hover:bg-surface-bright">
                            <td class="p-3 font-label-md text-label-md font-bold text-on-surface">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
                                    <span>Shift Siang #02</span>
                                </div>
                                <span class="text-[10px] text-on-surface-variant block font-normal">23 Okt 2023</span>
                            </td>
                            <td class="p-3 font-medium text-on-surface">
                                <div>Rian Hidayat</div>
                                <span class="font-label-sm text-[10px] text-on-surface-variant">ID: KSR-002</span>
                            </td>
                            <td class="p-3 font-label-sm text-label-sm text-on-surface">
                                <span class="bg-surface-container px-1.5 py-0.5 neo-border">08:00 - 16:00</span>
                                <span class="block text-[10px] text-on-surface-variant mt-0.5">Durasi: 8 Jam</span>
                            </td>
                            <td class="p-3 text-center font-label-md text-label-md font-bold">31</td>
                            <td class="p-3 text-right">
                                <span class="font-label-md text-label-md font-bold text-primary">Rp 1.830.000</span>
                                <span class="text-[10px] text-secondary font-bold block">+Rp 5.000 (Selisih Lebih)</span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-bold neo-border">
                                    <span class="material-symbols-outlined text-xs">flag</span>
                                    <span>NOTE RESOLVED</span>
                                </span>
                            </td>
                        </tr>

                   
                        <tr class="hover:bg-surface-bright">
                            <td class="p-3 font-label-md text-label-md font-bold text-on-surface">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-outline"></span>
                                    <span>Shift Pagi #01</span>
                                </div>
                                <span class="text-[10px] text-on-surface-variant block font-normal">23 Okt 2023</span>
                            </td>
                            <td class="p-3 font-medium text-on-surface">
                                <div>Gilang Ramadhan</div>
                                <span class="font-label-sm text-[10px] text-on-surface-variant">ID: KSR-008</span>
                            </td>
                            <td class="p-3 font-label-sm text-label-sm text-on-surface">
                                <span class="bg-surface-container px-1.5 py-0.5 neo-border">00:00 - 08:00</span>
                                <span class="block text-[10px] text-on-surface-variant mt-0.5">Durasi: 8 Jam</span>
                            </td>
                            <td class="p-3 text-center font-label-md text-label-md font-bold">14</td>
                            <td class="p-3 text-right">
                                <span class="font-label-md text-label-md font-bold text-primary">Rp 720.000</span>
                                <span class="text-[10px] text-on-surface-variant block">Kas Pas (Diff: Rp 0)</span>
                            </td>
                            <td class="p-3 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-tertiary-fixed-dim text-on-tertiary-fixed font-label-sm text-label-sm font-bold neo-border">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    <span>VERIFIED SPV</span>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

          
            <div class="p-3 bg-surface-container-low border-t-2 border-on-surface flex flex-wrap items-center justify-between text-on-surface font-label-sm text-label-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-primary">security</span>
                    <span>Supervisor Jaga Hari Ini: <strong>Ferry Ananda (SPV-01)</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-2 py-0.5 bg-white neo-border font-bold">PREV</button>
                    <span class="font-bold">Page 1 of 12</span>
                    <button class="px-2 py-0.5 bg-white neo-border font-bold">NEXT</button>
                </div>
            </div>
        </div>

    </div>

    
    <div class="bg-on-surface text-white p-4 neo-border neo-shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary-container text-2xl">print</span>
            <div>
                <p class="font-label-sm text-label-sm font-bold text-white">POS HARDWARE STATUS: EPSON TM-T82X THERMAL PRINTER READY</p>
                <p class="font-body-sm text-body-sm text-outline-variant">Auto-cutter enabled, Cash Drawer kick-pulse connected via RJ11 (Pin 2).</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button class="px-3 py-1.5 bg-surface-container-highest text-on-surface font-label-sm text-label-sm font-bold neo-border neo-btn">TEST LACI KAS (KICK)</button>
            <button class="px-3 py-1.5 bg-primary text-white font-label-sm text-label-sm font-bold neo-border neo-btn">PRINT SUMMARY TEST</button>
        </div>
    </div>

</main>
@endsection

@push('scripts')
<script>
   
    let seconds = 18;
    let minutes = 32;
    let hours = 4;

    const ticker = document.getElementById('runtime-ticker');
    if (ticker) {
        setInterval(() => {
            seconds++;
            if (seconds >= 60) {
                seconds = 0;
                minutes++;
                if (minutes >= 60) {
                    minutes = 0;
                    hours++;
                }
            }
            const formatted = 
                String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            ticker.textContent = formatted;
        }, 1000);
    }
</script>
@endpush