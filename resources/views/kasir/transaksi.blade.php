@extends('layouts.app')

@section('title', 'Transactions - TambahBang')

@section('content')
<main class="min-h-screen">
    <div class="p-6 max-w-[1720px] mx-auto space-y-6">
        
        
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-label-sm font-label-sm text-on-surface-variant mb-1">
                    <span>TERMINAL #02</span>
                    <span>/</span>
                    <span>POS BILLING RECORD</span>
                    <span>/</span>
                    <span class="text-primary font-bold">SETTLED INVOICES</span>
                </div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface uppercase tracking-tight">
                    Riwayat Transaksi &amp; Invoicing
                </h2>
            </div>
            <div class="flex items-center gap-3">
         
                <div class="px-4 py-2 bg-surface-container-lowest border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl" data-icon="schedule">schedule</span>
                    <span class="font-timer-display text-label-lg tracking-wider" id="cashierClock">12:58:44 WIB</span>
                </div>
                
                <button class="flex items-center gap-2 px-4 py-2 bg-surface-container-lowest text-on-surface font-label-md text-label-md border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] hover:bg-surface-container active:translate-x-1 active:translate-y-1 transition-all">
                    <span class="material-symbols-outlined" data-icon="download">download</span>
                    EXPORT EXCEL (CSV)
                </button>
                <button class="flex items-center gap-2 px-4 py-2 bg-primary-container text-on-primary font-label-md text-label-md border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] hover:bg-primary active:translate-x-1 active:translate-y-1 transition-all">
                    <span class="material-symbols-outlined" data-icon="print">print</span>
                    REKAP SHIFT #02
                </button>
            </div>
        </div>

       
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
          
            <div class="bg-surface-container-lowest p-5 border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] relative overflow-hidden group">
                <div class="flex items-center justify-between border-b-2 border-on-surface pb-2 mb-3">
                    <span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider font-bold">TOTAL OMSET SHIFT INI</span>
                    <span class="px-2 py-0.5 bg-primary text-on-primary text-[10px] font-label-sm border border-on-surface font-bold">LIVE</span>
                </div>
                <div class="font-timer-display text-headline-lg font-bold text-on-surface tracking-tight mb-1">
                    Rp 1.485.000
                </div>
                <div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant pt-2 border-t border-dashed border-outline-variant">
                    <span>Volume Sesi:</span>
                    <span class="font-label-md text-label-md font-bold text-primary">24 Sesi Selesai</span>
                </div>
                <div class="absolute -bottom-3 -right-2 opacity-10 pointer-events-none group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-7xl" data-icon="payments">payments</span>
                </div>
            </div>

         
            <div class="bg-surface-container-lowest p-5 border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] relative overflow-hidden group">
                <div class="flex items-center justify-between border-b-2 border-on-surface pb-2 mb-3">
                    <span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider font-bold">PENDAPATAN RENTAL</span>
                    <span class="px-1.5 py-0.5 bg-surface-container-high text-on-surface text-[10px] font-label-sm border border-on-surface font-bold">75.4%</span>
                </div>
                <div class="font-timer-display text-headline-lg font-bold text-primary tracking-tight mb-1">
                    Rp 1.120.000
                </div>
                <div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant pt-2 border-t border-dashed border-outline-variant">
                    <span>Durasi Terjual:</span>
                    <span class="font-label-md text-label-md font-bold text-on-surface">48 Jam PS4/PS5</span>
                </div>
                <div class="absolute -bottom-3 -right-2 opacity-10 pointer-events-none group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-7xl" data-icon="sports_esports">sports_esports</span>
                </div>
            </div>

          
            <div class="bg-surface-container-lowest p-5 border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] relative overflow-hidden group">
                <div class="flex items-center justify-between border-b-2 border-on-surface pb-2 mb-3">
                    <span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider font-bold">PENDAPATAN F&amp;B</span>
                    <span class="px-1.5 py-0.5 bg-secondary-container text-on-secondary text-[10px] font-label-sm border border-on-surface font-bold">24.6%</span>
                </div>
                <div class="font-timer-display text-headline-lg font-bold text-secondary tracking-tight mb-1">
                    Rp 365.000
                </div>
                <div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant pt-2 border-t border-dashed border-outline-variant">
                    <span>Item Terjual:</span>
                    <span class="font-label-md text-label-md font-bold text-on-surface">32 Snack &amp; Drinks</span>
                </div>
                <div class="absolute -bottom-3 -right-2 opacity-10 pointer-events-none group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-7xl" data-icon="restaurant">restaurant</span>
                </div>
            </div>

        
            <div class="bg-surface-container-lowest p-5 border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] relative overflow-hidden">
                <div class="flex items-center justify-between border-b-2 border-on-surface pb-2 mb-3">
                    <span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider font-bold">METODE PEMBAYARAN</span>
                    <span class="px-1.5 py-0.5 bg-surface-container text-on-surface text-[10px] font-label-sm border border-on-surface font-bold">RATIO</span>
                </div>
                <div class="font-timer-display text-headline-md font-bold text-on-surface tracking-tight mb-2 flex items-center justify-between">
                    <span>Tunai: 70%</span>
                    <span class="text-tertiary">QRIS: 30%</span>
                </div>
               
                <div class="w-full h-4 border-2 border-on-surface flex overflow-hidden shadow-[2px_2px_0px_#131b2e]">
                    <div class="bg-on-surface h-full w-[70%]" title="Tunai 70%"></div>
                    <div class="bg-tertiary-container h-full w-[30%]" title="QRIS 30%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] font-label-sm text-on-surface-variant pt-2">
                    <span>Rp 1.040.000 (Cash)</span>
                    <span>Rp 445.000 (Digital)</span>
                </div>
            </div>
        </section>

        
        <section class="p-4 bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e]">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
             
                <div class="md:col-span-3">
                    <label class="block text-label-sm font-label-sm text-on-surface-variant mb-1 uppercase font-bold">Pilih Tanggal Transaksi</label>
                    <div class="flex items-center bg-surface-container-low border-2 border-on-surface px-3 py-2 shadow-[2px_2px_0px_#131b2e] cursor-pointer">
                        <span class="material-symbols-outlined text-primary text-xl mr-2" data-icon="calendar_today">calendar_today</span>
                        <span class="font-label-md text-label-md font-bold text-on-surface">Hari ini: Senin, 7 Sep 2025</span>
                    </div>
                </div>

                
                <div class="md:col-span-2">
                    <label class="block text-label-sm font-label-sm text-on-surface-variant mb-1 uppercase font-bold">Filter Unit</label>
                    <div class="relative">
                        <select class="w-full bg-surface-container-lowest border-2 border-on-surface px-3 py-2 font-label-md text-label-md appearance-none focus:outline-none focus:ring-0 shadow-[2px_2px_0px_#131b2e] cursor-pointer">
                            <option selected="">Semua Unit (PS4/PS5)</option>
                            <option>PS 01 - PS5 VIP</option>
                            <option>PS 02 - PS5 Regular</option>
                            <option>PS 03 - PS4 Slim</option>
                            <option>PS 04 - PS4 Pro</option>
                            <option>PS 05 - PS5 VIP Lounge</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-2.5 top-2.5 pointer-events-none text-on-surface" data-icon="expand_more">expand_more</span>
                    </div>
                </div>

               
                <div class="md:col-span-3">
                    <label class="block text-label-sm font-label-sm text-on-surface-variant mb-1 uppercase font-bold">Filter Tipe Billing</label>
                    <div class="flex border-2 border-on-surface shadow-[2px_2px_0px_#131b2e]">
                        <button class="flex-1 py-1.5 px-2 bg-on-surface text-on-primary font-label-sm text-label-sm font-bold text-center">
                            Semua
                        </button>
                        <button class="flex-1 py-1.5 px-2 bg-surface-container-lowest text-on-surface hover:bg-surface-container font-label-sm text-label-sm border-l-2 border-on-surface text-center">
                            Prepaid
                        </button>
                        <button class="flex-1 py-1.5 px-2 bg-surface-container-lowest text-on-surface hover:bg-surface-container font-label-sm text-label-sm border-l-2 border-on-surface text-center">
                            Postpaid
                        </button>
                    </div>
                </div>

                <div class="md:col-span-4">
                    <label class="block text-label-sm font-label-sm text-on-surface-variant mb-1 uppercase font-bold">Search Transaction ID / Kasir</label>
                    <div class="relative flex items-center">
                        <input class="w-full bg-surface-container-lowest border-2 border-on-surface pl-10 pr-24 py-2 font-label-md text-label-md shadow-[2px_2px_0px_#131b2e] focus:outline-none focus:border-primary" placeholder="Ketik #TRX-20250907... atau Scan QR" type="text" value="#TRX-20250907" />
                        <span class="material-symbols-outlined absolute left-3 text-on-surface-variant" data-icon="search">search</span>
                        <button class="absolute right-1.5 px-2 py-1 bg-surface-container border border-on-surface font-label-sm text-label-sm hover:bg-surface-variant active:translate-x-0.5 active:translate-y-0.5">
                            CARI
                        </button>
                    </div>
                </div>
            </div>
        </section>

    
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e]">
                    
                    <div class="px-4 py-3 border-b-2 border-on-surface bg-surface-container-low flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary" data-icon="list_alt">list_alt</span>
                            <span class="font-headline-sm text-headline-sm font-bold uppercase tracking-tight">Daftar Transaksi Selesai (Shift Kasir #02)</span>
                        </div>
                        <span class="px-2 py-0.5 bg-surface-container border border-on-surface font-label-sm text-label-sm font-bold">
                            Showing 3 of 24 Records
                        </span>
                    </div>

                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b-2 border-on-surface bg-surface-container text-on-surface font-label-sm text-label-sm uppercase tracking-wider">
                                    <th class="p-3 border-r-2 border-on-surface">ID TRX / WAKTU</th>
                                    <th class="p-3 border-r-2 border-on-surface">UNIT &amp; TIPE</th>
                                    <th class="p-3 border-r-2 border-on-surface text-right">RENTAL</th>
                                    <th class="p-3 border-r-2 border-on-surface text-right">F&amp;B</th>
                                    <th class="p-3 border-r-2 border-on-surface text-right">TOTAL</th>
                                    <th class="p-3 border-r-2 border-on-surface">KASIR</th>
                                    <th class="p-3 border-r-2 border-on-surface text-center">STATUS</th>
                                    <th class="p-3 text-center">AKSI CEPAT</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-on-surface font-body-sm text-body-sm">
                              
                                <tr class="bg-surface-container-high/40 hover:bg-surface-container transition-colors">
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md font-bold text-primary flex items-center gap-1">
                                            <span class="w-2 h-2 bg-primary"></span>
                                            #TRX-20250907-042
                                        </div>
                                        <div class="text-[11px] font-label-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
                                            <span class="material-symbols-outlined text-xs" data-icon="schedule">schedule</span>
                                            Jam: 12:45 WIB
                                        </div>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md font-bold text-on-surface">PS 01</div>
                                        <span class="inline-block px-1.5 py-0.2 text-[10px] font-label-sm bg-surface-container border border-on-surface font-bold text-on-surface">
                                            Prepaid (2 Jam)
                                        </span>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md">
                                        Rp 45.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md text-secondary">
                                        Rp 18.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md font-bold text-on-surface bg-surface-container-low/50">
                                        Rp 63.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md">Rian H.</div>
                                        <div class="text-[10px] font-label-sm text-on-surface-variant">Kasir Siang</div>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-center">
                                        
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-tertiary text-on-tertiary border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e]">
                                            <span class="material-symbols-outlined text-xs" data-icon="check_circle">check_circle</span>
                                            LUNAS
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button class="px-2 py-1 bg-surface-container-lowest border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container active:translate-x-0.5 active:translate-y-0.5 flex items-center gap-1" title="Cetak Slip">
                                                <span class="material-symbols-outlined text-sm" data-icon="print">print</span>
                                                STRUK
                                            </button>
                                            <button class="px-2 py-1 bg-primary text-on-primary border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e] hover:bg-primary-container active:translate-x-0.5 active:translate-y-0.5 flex items-center gap-1" title="Buka Detail">
                                                <span class="material-symbols-outlined text-sm" data-icon="visibility">visibility</span>
                                                DETAIL
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                            
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md font-bold text-on-surface">
                                            #TRX-20250907-041
                                        </div>
                                        <div class="text-[11px] font-label-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
                                            <span class="material-symbols-outlined text-xs" data-icon="schedule">schedule</span>
                                            Jam: 12:10 WIB
                                        </div>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md font-bold text-on-surface">PS 05</div>
                                        <span class="inline-block px-1.5 py-0.2 text-[10px] font-label-sm bg-secondary-fixed text-on-secondary-fixed border border-on-surface font-bold">
                                            Postpaid (3 Jam)
                                        </span>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md">
                                        Rp 75.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md text-secondary">
                                        Rp 32.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md font-bold text-on-surface bg-surface-container-low/50">
                                        Rp 107.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md">Rian H.</div>
                                        <div class="text-[10px] font-label-sm text-on-surface-variant">Kasir Siang</div>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-tertiary text-on-tertiary border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e]">
                                            <span class="material-symbols-outlined text-xs" data-icon="check_circle">check_circle</span>
                                            LUNAS
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button class="px-2 py-1 bg-surface-container-lowest border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container active:translate-x-0.5 active:translate-y-0.5 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm" data-icon="print">print</span>
                                                STRUK
                                            </button>
                                            <button class="px-2 py-1 bg-surface-container-lowest border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container active:translate-x-0.5 active:translate-y-0.5 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm" data-icon="visibility">visibility</span>
                                                DETAIL
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                          
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md font-bold text-on-surface">
                                            #TRX-20250907-040
                                        </div>
                                        <div class="text-[11px] font-label-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
                                            <span class="material-symbols-outlined text-xs" data-icon="schedule">schedule</span>
                                            Jam: 11:30 WIB
                                        </div>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md font-bold text-on-surface">PS 02</div>
                                        <span class="inline-block px-1.5 py-0.2 text-[10px] font-label-sm bg-surface-container border border-on-surface font-bold text-on-surface">
                                            Prepaid (1 Jam)
                                        </span>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md">
                                        Rp 25.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md text-secondary">
                                        Rp 6.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-right font-label-md text-label-md font-bold text-on-surface bg-surface-container-low/50">
                                        Rp 31.000
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface">
                                        <div class="font-label-md text-label-md">Rian H.</div>
                                        <div class="text-[10px] font-label-sm text-on-surface-variant">Kasir Siang</div>
                                    </td>
                                    <td class="p-3 border-r-2 border-on-surface text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-tertiary text-on-tertiary border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e]">
                                            <span class="material-symbols-outlined text-xs" data-icon="check_circle">check_circle</span>
                                            LUNAS
                                        </span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button class="px-2 py-1 bg-surface-container-lowest border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container active:translate-x-0.5 active:translate-y-0.5 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm" data-icon="print">print</span>
                                                STRUK
                                            </button>
                                            <button class="px-2 py-1 bg-surface-container-lowest border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container active:translate-x-0.5 active:translate-y-0.5 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm" data-icon="visibility">visibility</span>
                                                DETAIL
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                  
                    <div class="px-4 py-3 border-t-2 border-on-surface bg-surface-container-low flex flex-wrap items-center justify-between gap-3 font-label-sm text-label-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-on-surface-variant">Baris per halaman:</span>
                            <select class="border-2 border-on-surface bg-surface-container-lowest px-2 py-0.5 font-bold">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <span class="text-on-surface-variant ml-2">Total 24 Transaksi Selesai</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <button class="px-2 py-1 bg-surface-container-lowest border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container disabled:opacity-50" disabled="">
                                &lt; SEBELUMNYA
                            </button>
                            <button class="px-2.5 py-1 bg-primary text-on-primary border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] font-bold">
                                1
                            </button>
                            <button class="px-2.5 py-1 bg-surface-container-lowest text-on-surface border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container">
                                2
                            </button>
                            <button class="px-2.5 py-1 bg-surface-container-lowest text-on-surface border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container">
                                3
                            </button>
                            <button class="px-2 py-1 bg-surface-container-lowest border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container">
                                SELANJUTNYA &gt;
                            </button>
                        </div>
                    </div>
                </div>

              
                <div class="p-4 bg-surface-container-high border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-secondary text-2xl" data-icon="info">info</span>
                        <div class="text-body-sm font-body-sm">
                            <span class="font-bold text-on-surface">Prosedur Void Transaksi:</span> Pembatalan billing yang sudah berstatus LUNAS hanya dapat diotorisasi menggunakan kunci kartu Supervisor (RFID).
                        </div>
                    </div>
                    <button class="px-3 py-1.5 bg-surface-container-lowest text-error border-2 border-on-surface font-label-sm text-label-sm font-bold shadow-[2px_2px_0px_#131b2e] hover:bg-error-container">
                        REQUEST VOID
                    </button>
                </div>
            </div>

            
            <div class="lg:col-span-4 space-y-4">
              
                <div class="bg-surface-container-lowest border-2 border-on-surface shadow-[6px_6px_0px_#131b2e] relative">
                
                    <div class="bg-on-surface text-on-primary px-4 py-2 flex items-center justify-between border-b-2 border-on-surface">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-secondary-container text-sm" data-icon="receipt">receipt</span>
                            <span class="font-label-sm text-label-sm uppercase font-bold tracking-wider">LIVE RECEIPT PREVIEW</span>
                        </div>
                        <span class="text-xs font-label-sm text-tertiary-fixed font-bold">VERIFIED #042</span>
                    </div>

                    <div class="p-5 space-y-4">
                     
                        <div class="text-center border-b-2 border-dashed border-on-surface pb-4">
                            <div class="font-headline-md font-headline-md font-extrabold uppercase tracking-tight text-on-surface">
                                TAMBAHBANG PS HUB
                            </div>
                            <div class="font-label-sm text-label-sm text-on-surface-variant mt-0.5">
                                Jl. Boulevard Esports No. 88, Jak-Sel
                            </div>
                            <div class="font-label-sm text-label-sm text-on-surface-variant">
                                Telp / WA: 0812-9988-7766
                            </div>
                            <div class="mt-2 inline-block px-2 py-0.5 bg-surface-container border border-on-surface font-label-sm text-[11px] font-bold">
                                KASIR: RIAN H. (#02) - TANGGAL: 07/09/2025
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-label-sm font-label-sm bg-surface-container-low p-2.5 border-2 border-on-surface">
                            <div>
                                <span class="text-on-surface-variant block text-[10px]">NOMOR STRUK</span>
                                <span class="font-bold text-primary">#TRX-042</span>
                            </div>
                            <div class="text-right">
                                <span class="text-on-surface-variant block text-[10px]">WAKTU CHECKOUT</span>
                                <span class="font-bold text-on-surface">12:45:12 WIB</span>
                            </div>
                            <div>
                                <span class="text-on-surface-variant block text-[10px]">STATION ID</span>
                                <span class="font-bold text-on-surface">BAY #01 (PS5 VIP)</span>
                            </div>
                            <div class="text-right">
                                <span class="text-on-surface-variant block text-[10px]">TIPE RENTAL</span>
                                <span class="font-bold text-secondary">PREPAID (2 JAM)</span>
                            </div>
                        </div>

                       
                        <div>
                            <div class="font-label-sm text-label-sm font-bold text-on-surface uppercase mb-2 flex items-center justify-between border-b border-on-surface pb-1">
                                <span>ITEM RINCIAN</span>
                                <span>SUBTOTAL</span>
                            </div>
                            <div class="space-y-2 font-label-md text-label-md">
                             
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-bold text-on-surface">Sesi PS5 VIP (2 Jam)</div>
                                        <div class="text-[11px] text-on-surface-variant font-label-sm">@ Rp 22.500 / Jam</div>
                                    </div>
                                    <div class="font-bold text-on-surface">Rp 45.000</div>
                                </div>
                                
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-bold text-on-surface">1x Indomie Goreng Jumbo</div>
                                        <div class="text-[11px] text-on-surface-variant font-label-sm">Topping: Telur + Kornet</div>
                                    </div>
                                    <div class="font-bold text-on-surface">Rp 12.000</div>
                                </div>
                              
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-bold text-on-surface">1x Teh Botol Sosro Dingin</div>
                                        <div class="text-[11px] text-on-surface-variant font-label-sm">Kemasan Botol Beling</div>
                                    </div>
                                    <div class="font-bold text-on-surface">Rp 6.000</div>
                                </div>
                            </div>
                        </div>

                       
                        <div class="border-b-2 border-dashed border-on-surface pt-1"></div>

                    
                        <div class="space-y-1.5 font-label-sm text-label-sm">
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Subtotal Rental:</span>
                                <span>Rp 45.000</span>
                            </div>
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Subtotal F&amp;B:</span>
                                <span>Rp 18.000</span>
                            </div>
                            <div class="flex justify-between text-on-surface-variant">
                                <span>PPN (0% UMKM Tax):</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="flex justify-between font-headline-sm text-headline-sm font-extrabold text-on-surface pt-2 border-t-2 border-on-surface">
                                <span>TOTAL BILL:</span>
                                <span class="text-primary font-timer-display">Rp 63.000</span>
                            </div>
                            <div class="flex justify-between text-on-surface font-bold pt-1">
                                <span>BAYAR (TUNAI):</span>
                                <span>Rp 100.000</span>
                            </div>
                            <div class="flex justify-between text-tertiary font-bold">
                                <span>KEMBALIAN:</span>
                                <span>Rp 37.000</span>
                            </div>
                        </div>

                     
                        <div class="p-3 bg-surface-container-low border-2 border-on-surface flex items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="text-[10px] font-label-sm font-bold uppercase text-on-surface">KODE VALIDASI QRIS / SERVER</div>
                                <div class="font-label-sm text-[11px] text-on-surface-variant">TMB-SEC-883921-OK</div>
                                <div class="text-[9px] font-body-sm text-on-surface-variant">Simpan struk sebagai bukti sewa controllers &amp; stik PlayStation.</div>
                            </div>
                            
                            <div class="w-14 h-14 bg-surface-container-lowest border-2 border-on-surface flex items-center justify-center p-1 shadow-[2px_2px_0px_#131b2e] shrink-0">
                                <span class="material-symbols-outlined text-4xl text-on-surface" data-icon="qr_code_2">qr_code_2</span>
                            </div>
                        </div>

         
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <button class="py-2.5 px-3 bg-primary text-on-primary font-label-md text-label-md uppercase border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] hover:bg-primary-container active:translate-x-1 active:translate-y-1 transition-all flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-base" data-icon="print">print</span>
                                CETAK STRUK
                            </button>
                            <button class="py-2.5 px-3 bg-surface-container-lowest text-on-surface font-label-md text-label-md uppercase border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] hover:bg-surface-container active:translate-x-1 active:translate-y-1 transition-all flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-base" data-icon="share">share</span>
                                WA INVOICE
                            </button>
                        </div>
                    </div>

                    
                    <div class="absolute -top-3 right-4 px-2 py-0.5 bg-secondary text-on-secondary text-[9px] font-label-sm font-bold border border-on-surface shadow-[2px_2px_0px_#131b2e] rotate-1">
                        PAID IN FULL
                    </div>
                </div>

                
                <div class="p-3.5 bg-surface-container-lowest border-2 border-on-surface shadow-[3px_3px_0px_#131b2e]">
                    <div class="font-label-sm text-label-sm font-bold uppercase mb-2 flex items-center gap-1 text-on-surface">
                        <span class="material-symbols-outlined text-sm text-primary" data-icon="keyboard">keyboard</span>
                        KASIR SHORTCUTS
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[11px] font-label-sm">
                        <div class="flex items-center justify-between p-1.5 bg-surface-container border border-on-surface">
                            <span class="text-on-surface-variant">Cetak Terakhir</span>
                            <span class="px-1 bg-surface-container-lowest border border-on-surface font-bold">F12</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 bg-surface-container border border-on-surface">
                            <span class="text-on-surface-variant">Cari Transaksi</span>
                            <span class="px-1 bg-surface-container-lowest border border-on-surface font-bold">Ctrl+F</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 bg-surface-container border border-on-surface">
                            <span class="text-on-surface-variant">Tutup Shift</span>
                            <span class="px-1 bg-surface-container-lowest border border-on-surface font-bold">Alt+S</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 bg-surface-container border border-on-surface">
                            <span class="text-on-surface-variant">Drawer Kas</span>
                            <span class="px-1 bg-surface-container-lowest border border-on-surface font-bold">F9</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const clockEl = document.getElementById('cashierClock');
        if (clockEl) {
            clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush