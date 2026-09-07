@extends('layouts.app')

@section('title', 'Units - TambahBang')

@section('content')
<main class="bg-background">
    <div class="p-6 max-w-[1680px] mx-auto space-y-6">
        
        
        <div class="flex flex-wrap items-center justify-between gap-4 p-4 brutal-card bg-surface-container-lowest">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-headline-lg font-headline-lg text-on-surface">Manajemen Unit PlayStation &amp; QR Meja</h2>
                    <span class="px-2.5 py-0.5 bg-tertiary text-on-tertiary font-label-sm text-label-sm font-bold border border-on-surface">8 STASIUN TOTAL</span>
                </div>
                <p class="text-body-sm font-body-sm text-on-surface-variant mt-0.5">Konfigurasi perangkat keras bay, integrasi IoT buzzer overstay, tarif rental per jam, dan QR Code Self-Service meja pelanggan.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <button class="px-3.5 py-1.5 bg-on-surface text-surface-container-lowest font-label-md text-label-md border-2 border-on-surface shadow-[2px_2px_0px_#fd761a]">
                    Semua (8)
                </button>
                <button class="px-3 py-1.5 bg-surface-container-lowest text-on-surface font-label-md text-label-md border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container">
                    PS5 Saja (5)
                </button>
                <button class="px-3 py-1.5 bg-surface-container-lowest text-on-surface font-label-md text-label-md border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container">
                    PS4 Saja (3)
                </button>
                <button class="px-3 py-1.5 bg-surface-container-lowest text-on-surface font-label-md text-label-md border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] hover:bg-surface-container">
                    VIP Bay (1)
                </button>
                <button class="px-3 py-1.5 bg-surface-container-lowest text-error font-label-md text-label-md border-2 border-error shadow-[2px_2px_0px_#131b2e] hover:bg-error-container">
                    Maintenance (1)
                </button>
            </div>
        </div>

        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
            
            
            <div class="xl:col-span-8 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <span class="text-label-lg font-label-lg uppercase tracking-wider text-on-surface font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">grid_view</span>
                        DAFTAR RACK &amp; BAY PS01 - PS08
                    </span>
                    <div class="flex items-center gap-3 text-label-sm font-label-sm text-on-surface-variant font-bold">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-tertiary"></span> 7 Aktif</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-error"></span> 1 Nonaktif</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary"></span> IoT 100% OK</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  
                    <div class="brutal-card p-4 flex flex-col justify-between hover:-translate-y-0.5 transition-transform">
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 01</span>
                                        <span class="brutal-badge-green px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">AKTIF</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">Sony PlayStation 5 Digital Edition</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Dasar</span>
                                    <span class="font-label-lg text-label-lg text-primary font-bold">Rp 25.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                          
                            <div class="my-3 py-2 px-3 bg-surface-container border border-on-surface flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-tertiary-container font-bold">
                                    <span class="material-symbols-outlined text-sm">sensors</span>
                                    <span>IoT Buzzer: Terhubung (Hardware OK)</span>
                                </div>
                                <span class="text-on-surface-variant">DualSense: 2 Unit</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </button>
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span>
                                Cetak QR
                            </button>
                            <button class="brutal-btn-orange py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1" onclick="triggerBuzzerFeedback('PS 01')">
                                <span class="material-symbols-outlined text-sm">volume_up</span>
                                Test Buzzer
                            </button>
                        </div>
                    </div>

                  
                    <div class="brutal-card p-4 flex flex-col justify-between hover:-translate-y-0.5 transition-transform">
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 02</span>
                                        <span class="brutal-badge-green px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">AKTIF</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">Sony PlayStation 5 Disc Edition</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Dasar</span>
                                    <span class="font-label-lg text-label-lg text-primary font-bold">Rp 25.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                            <div class="my-3 py-2 px-3 bg-surface-container border border-on-surface flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-tertiary-container font-bold">
                                    <span class="material-symbols-outlined text-sm">sensors</span>
                                    <span>IoT Buzzer: Terhubung</span>
                                </div>
                                <span class="text-on-surface-variant">DualSense: 2 Unit</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </button>
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span>
                                Cetak QR
                            </button>
                            <button class="brutal-btn-orange py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1" onclick="triggerBuzzerFeedback('PS 02')">
                                <span class="material-symbols-outlined text-sm">volume_up</span>
                                Test Buzzer
                            </button>
                        </div>
                    </div>

                    
                    <div class="brutal-card p-4 flex flex-col justify-between ring-2 ring-primary relative bg-surface-bright">
                        <div class="absolute -top-3 right-4 bg-primary text-on-primary border-2 border-on-surface px-2.5 py-0.5 text-label-sm font-label-sm font-bold uppercase shadow-[2px_2px_0px_#131b2e]">
                            PREVIEW MEJA TERPILIH
                        </div>
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 03</span>
                                        <span class="brutal-badge-blue px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">VIP BAY A</span>
                                        <span class="brutal-badge-green px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">AKTIF</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">PS5 Disc (Sofa Recliner 65" 4K)</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Dasar</span>
                                    <span class="font-label-lg text-label-lg text-primary font-bold">Rp 25.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                            <div class="my-3 py-2 px-3 bg-surface-container-high border border-on-surface flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-tertiary font-bold">
                                    <span class="material-symbols-outlined text-sm">sensors</span>
                                    <span>IoT Buzzer: Standby (Ping 12ms)</span>
                                </div>
                                <span class="text-on-surface-variant font-bold text-primary">DualSense Edge: 2 Unit</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </button>
                            <button class="bg-primary text-on-primary border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code_2</span>
                                QR Aktif
                            </button>
                            <button class="brutal-btn-orange py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1" onclick="triggerBuzzerFeedback('PS 03')">
                                <span class="material-symbols-outlined text-sm">volume_up</span>
                                Test Buzzer
                            </button>
                        </div>
                    </div>

                  
                    <div class="brutal-card p-4 flex flex-col justify-between hover:-translate-y-0.5 transition-transform">
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 04</span>
                                        <span class="brutal-badge-green px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">AKTIF</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">Sony PlayStation 4 Pro 1TB</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Dasar</span>
                                    <span class="font-label-lg text-label-lg text-secondary font-bold">Rp 15.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                            <div class="my-3 py-2 px-3 bg-surface-container border border-on-surface flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-tertiary-container font-bold">
                                    <span class="material-symbols-outlined text-sm">sensors</span>
                                    <span>IoT Buzzer: Terhubung</span>
                                </div>
                                <span class="text-on-surface-variant">DualShock 4: 2 Unit</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </button>
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span>
                                Cetak QR
                            </button>
                            <button class="brutal-btn-orange py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1" onclick="triggerBuzzerFeedback('PS 04')">
                                <span class="material-symbols-outlined text-sm">volume_up</span>
                                Test Buzzer
                            </button>
                        </div>
                    </div>

               
                    <div class="brutal-card p-4 flex flex-col justify-between hover:-translate-y-0.5 transition-transform">
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 05</span>
                                        <span class="brutal-badge-green px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">AKTIF</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">Sony PlayStation 5 Slim 1TB</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Dasar</span>
                                    <span class="font-label-lg text-label-lg text-primary font-bold">Rp 25.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                            <div class="my-3 py-2 px-3 bg-surface-container border border-on-surface flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-tertiary-container font-bold">
                                    <span class="material-symbols-outlined text-sm">sensors</span>
                                    <span>IoT Buzzer: Terhubung</span>
                                </div>
                                <span class="text-on-surface-variant">DualSense: 2 Unit</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </button>
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span>
                                Cetak QR
                            </button>
                            <button class="brutal-btn-orange py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1" onclick="triggerBuzzerFeedback('PS 05')">
                                <span class="material-symbols-outlined text-sm">volume_up</span>
                                Test Buzzer
                            </button>
                        </div>
                    </div>

                  
                    <div class="brutal-card p-4 flex flex-col justify-between hover:-translate-y-0.5 transition-transform">
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 06</span>
                                        <span class="brutal-badge-green px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">AKTIF</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">Sony PlayStation 4 Pro 1TB</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Dasar</span>
                                    <span class="font-label-lg text-label-lg text-secondary font-bold">Rp 15.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                            <div class="my-3 py-2 px-3 bg-surface-container border border-on-surface flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-tertiary-container font-bold">
                                    <span class="material-symbols-outlined text-sm">sensors</span>
                                    <span>IoT Buzzer: Terhubung</span>
                                </div>
                                <span class="text-on-surface-variant">DualShock 4: 2 Unit</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </button>
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span>
                                Cetak QR
                            </button>
                            <button class="brutal-btn-orange py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1" onclick="triggerBuzzerFeedback('PS 06')">
                                <span class="material-symbols-outlined text-sm">volume_up</span>
                                Test Buzzer
                            </button>
                        </div>
                    </div>

                    <div class="brutal-card p-4 flex flex-col justify-between hover:-translate-y-0.5 transition-transform">
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 07</span>
                                        <span class="brutal-badge-green px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">AKTIF</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">Sony PlayStation 5 Disc Edition</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Dasar</span>
                                    <span class="font-label-lg text-label-lg text-primary font-bold">Rp 25.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                            <div class="my-3 py-2 px-3 bg-surface-container border border-on-surface flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-tertiary-container font-bold">
                                    <span class="material-symbols-outlined text-sm">sensors</span>
                                    <span>IoT Buzzer: Terhubung</span>
                                </div>
                                <span class="text-on-surface-variant">DualSense: 2 Unit</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t-2 border-dashed border-on-surface">
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Edit
                            </button>
                            <button class="brutal-btn-secondary py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span>
                                Cetak QR
                            </button>
                            <button class="brutal-btn-orange py-1.5 px-2 text-label-sm font-label-sm font-bold flex items-center justify-center gap-1" onclick="triggerBuzzerFeedback('PS 07')">
                                <span class="material-symbols-outlined text-sm">volume_up</span>
                                Test Buzzer
                            </button>
                        </div>
                    </div>

                   
                    <div class="brutal-card p-4 flex flex-col justify-between bg-error-container/20 border-error">
                        <div>
                            <div class="flex items-start justify-between border-b-2 border-on-surface pb-2.5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-headline-sm text-headline-sm font-bold text-on-surface">PS 08</span>
                                        <span class="brutal-badge-red px-2 py-0.5 text-label-sm font-label-sm font-bold uppercase">MAINTENANCE</span>
                                    </div>
                                    <span class="text-body-sm font-body-sm text-on-surface-variant font-medium">Sony PlayStation 4 Slim 500GB</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-label-sm font-label-sm text-on-surface-variant block uppercase font-bold">Tarif Standar</span>
                                    <span class="font-label-lg text-label-lg text-outline font-bold">Rp 15.000<span class="text-label-sm font-normal text-on-surface">/jam</span></span>
                                </div>
                            </div>
                            <div class="my-3 py-2 px-3 bg-white border border-error flex items-center justify-between text-label-sm font-label-sm">
                                <div class="flex items-center gap-1.5 text-error font-bold">
                                    <span class="material-symbols-outlined text-sm">build</span>
                                    <span>Status: Ganti Pasta &amp; Fan Cleaning</span>
                                </div>
                                <span class="text-error font-mono">Buzzer Muted</span>
                            </div>
                        </div>
                       
                        <div class="pt-2 border-t-2 border-dashed border-error/50 flex items-center justify-between">
                            <span class="text-label-sm font-label-sm text-error font-bold uppercase">Kondisi: Nonaktif</span>
                            <button class="bg-tertiary text-on-tertiary px-4 py-2 text-label-md font-label-md font-bold border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] hover:bg-tertiary-container active:translate-x-0.5 active:translate-y-0.5">
                                Aktifkan Unit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="xl:col-span-4 sticky top-24 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <span class="text-label-lg font-label-lg uppercase tracking-wider text-on-surface font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-xl">print</span>
                        FLYER MEJA SIAP CETAK (PS 03)
                    </span>
                    <span class="px-2 py-0.5 bg-surface-container-high border border-on-surface font-label-sm text-label-sm font-mono">Akrilik A5</span>
                </div>
                
               
                <div class="brutal-card p-6 bg-surface-container-lowest relative border-[3px]">
                   
                    <div class="absolute -top-3 -left-3 bg-secondary-container text-on-secondary px-3 py-1 font-label-sm text-label-sm font-bold border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] rotate-[-4deg]">
                        LIVE PREVIEW #03
                    </div>
                    
                  
                    <div class="text-center pb-4 border-b-2 border-on-surface">
                        <div class="inline-block bg-on-surface text-surface px-3 py-1 text-label-sm font-label-sm font-extrabold uppercase tracking-widest mb-1.5">
                            TAMBAHBANG ARCADE HUB
                        </div>
                        <h3 class="text-headline-md font-headline-md text-on-surface uppercase tracking-tight">
                            SCAN UNTUK KONTROL SESI PS 03
                        </h3>
                        <p class="text-label-sm font-label-sm text-on-surface-variant font-bold mt-1">
                            BAY VIP SOFA A • HIGH-SPEED CONTROLLER
                        </p>
                    </div>
                    
                   
                    <div class="my-5 p-4 bg-surface-container-low border-[2.5px] border-on-surface text-center flex flex-col items-center justify-center relative">
                     
                        <div class="w-48 h-48 bg-white border-2 border-on-surface p-3 flex flex-col items-center justify-center relative shadow-[4px_4px_0px_#131b2e]">
                           
                            <div class="absolute top-1 left-1 w-5 h-5 border-t-4 border-l-4 border-on-surface"></div>
                            <div class="absolute top-1 right-1 w-5 h-5 border-t-4 border-r-4 border-on-surface"></div>
                            <div class="absolute bottom-1 left-1 w-5 h-5 border-b-4 border-l-4 border-on-surface"></div>
                            <div class="absolute bottom-1 right-1 w-5 h-5 border-b-4 border-r-4 border-on-surface"></div>
                            
                           
                            <div class="w-36 h-36 border-2 border-on-surface bg-white p-2 relative flex items-center justify-center">
                              
                                <div class="w-full h-full brutal-qr-pattern opacity-95"></div>
                              
                                <div class="absolute inset-0 m-auto w-10 h-10 bg-primary border-2 border-on-surface text-white flex items-center justify-center shadow-[2px_2px_0px_#131b2e]">
                                    <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">sports_esports</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 flex items-center gap-2">
                            <span class="font-timer-display text-label-md font-bold px-2 py-0.5 bg-on-surface text-white border border-on-surface">BAY ID: PS03-VIP-A</span>
                            <span class="text-label-sm font-label-sm text-tertiary font-bold">TOKEN AKTIF</span>
                        </div>
                    </div>
                    
                
                    <div class="space-y-2.5 pb-5 border-b-2 dashed border-on-surface">
                        <span class="text-label-sm font-label-sm uppercase font-bold text-on-surface-variant block tracking-wider">
                            CARA PENGGUNAAN MANDIRI:
                        </span>
                       
                        <div class="flex items-start gap-2.5 p-2 bg-surface border border-on-surface">
                            <div class="w-6 h-6 rounded bg-primary text-on-primary font-label-sm font-bold flex items-center justify-center shrink-0 border border-on-surface">
                                1
                            </div>
                            <p class="text-body-sm font-body-sm leading-tight text-on-surface">
                                <span class="font-bold">Scan QR</span> kamera HP tanpa install aplikasi apapun.
                            </p>
                        </div>
         
                        <div class="flex items-start gap-2.5 p-2 bg-surface border border-on-surface">
                            <div class="w-6 h-6 rounded bg-primary text-on-primary font-label-sm font-bold flex items-center justify-center shrink-0 border border-on-surface">
                                2
                            </div>
                            <p class="text-body-sm font-body-sm leading-tight text-on-surface">
                                <span class="font-bold">Pantau sisa timer</span> sewa secara live langsung di layar smartphone Anda.
                            </p>
                        </div>
                       
                        <div class="flex items-start gap-2.5 p-2 bg-surface border border-on-surface">
                            <div class="w-6 h-6 rounded bg-secondary-container text-on-secondary font-label-sm font-bold flex items-center justify-center shrink-0 border border-on-surface">
                                3
                            </div>
                            <p class="text-body-sm font-body-sm leading-tight text-on-surface">
                                <span class="font-bold">Tambah waktu &amp; pesan snack / minuman</span> instan tanpa harus beranjak ke kasir.
                            </p>
                        </div>
                    </div>
                    
          
                    <div class="pt-5 space-y-2.5">
                        <button class="w-full brutal-btn-primary py-3 px-4 text-label-lg font-label-lg uppercase flex items-center justify-center gap-2 tracking-wide font-bold" onclick="handlePrint()">
                            <span class="material-symbols-outlined text-lg">print</span>
                            <span>CETAK / PRINT STANDING BANNER</span>
                        </button>
                        <button class="w-full brutal-btn-secondary py-2.5 px-4 text-label-md font-label-md uppercase flex items-center justify-center gap-2 font-bold">
                            <span class="material-symbols-outlined text-lg">download</span>
                            <span>DOWNLOAD PDF (VEKTOR HI-RES)</span>
                        </button>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="text-label-sm font-label-sm text-on-surface-variant">Format Siap Potong • Dimensi Standar Standing Akrilik A5 (148 x 210 mm)</span>
                    </div>
                </div>
                
               
                <div class="brutal-card p-3.5 bg-surface-container border-2 flex items-center gap-3">
                    <span class="material-symbols-outlined text-secondary-container text-2xl">campaign</span>
                    <div>
                        <span class="text-label-sm font-label-sm font-bold block text-on-surface">TIPS KASIR TAMBAHBANG:</span>
                        <p class="text-body-sm font-body-sm text-on-surface-variant leading-snug">Ganti stiker QR jika terdapat goresan berat. Kode QR terenkripsi otomatis mengikat stasiun tanpa risiko tertukar bay.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="fixed bottom-6 right-6 hidden bg-on-surface text-surface-bright px-5 py-3 border-2 border-primary-container shadow-[4px_4px_0px_#2563eb] z-50 transition-all transform translate-y-2" id="toastNotification">
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-secondary-container animate-bounce">volume_up</span>
        <div>
            <div class="text-label-md font-label-md font-bold uppercase" id="toastTitle">BUZZER TRIGGERED</div>
            <div class="text-body-sm font-body-sm text-surface-container-high" id="toastSubtitle">Hardware buzzer berbunyi 2x bip (Sinyal normal)</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function triggerBuzzerFeedback(stationName) {
        const toast = document.getElementById('toastNotification');
        const title = document.getElementById('toastTitle');
        const sub = document.getElementById('toastSubtitle');
        
        title.innerText = "BUZZER TESTED: " + stationName;
        sub.innerText = "Sinyal IoT 2.4GHz terkirim ke buzzer pod " + stationName + ". Status: OK (0 delay)";
        
        toast.classList.remove('hidden');
        toast.classList.remove('translate-y-2');
        
        setTimeout(() => {
            toast.classList.add('translate-y-2');
            setTimeout(() => toast.classList.add('hidden'), 200);
        }, 2500);
    }

    function handlePrint() {
        window.print();
    }
</script>
@endpush