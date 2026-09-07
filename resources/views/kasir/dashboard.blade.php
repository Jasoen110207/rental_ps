@extends('layouts.app')

@section('title', 'Dashboard - TambahBang')

@section('content')
<main class="flex-1 p-5 flex flex-col gap-4 overflow-y-auto">

  
    <div class="p-3 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-wrap items-center justify-between gap-3">
   
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-label-md text-xs text-on-surface font-bold flex items-center gap-1 mr-1">
                <span class="material-symbols-outlined text-sm" data-icon="filter_list">filter_list</span> 
                FILTER:
            </span>
            <button class="px-3 py-1 bg-on-surface text-on-primary font-label-md text-xs font-bold border-2 border-on-surface shadow-[1.5px_1.5px_0px_#fd761a]">
                Semua (8)
            </button>
            <button class="px-3 py-1 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface hover:bg-surface-container neo-shadow-sm btn-press">
                PS5 (5)
            </button>
            <button class="px-3 py-1 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface hover:bg-surface-container neo-shadow-sm btn-press">
                PS4 (3)
            </button>
            <button class="px-3 py-1 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface hover:bg-surface-container neo-shadow-sm btn-press">
                Tersedia (2)
            </button>
        </div>

       
        <div class="flex items-center gap-2 flex-grow sm:flex-grow-0 justify-end">
            <div class="relative w-full sm:w-64">
                <input class="w-full pl-8 pr-3 py-1 font-label-md text-xs border-2 border-on-surface neo-shadow-inset bg-surface-container-lowest focus:outline-none focus:border-primary" placeholder="Scan Barcode / Cari Unit..." type="text">
                <span class="material-symbols-outlined absolute left-2 top-1.5 text-on-surface-variant text-base" data-icon="search">search</span>
            </div>
            <button class="px-2.5 py-1 bg-surface-container-high border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press flex items-center gap-1 hover:bg-surface-container">
                <span class="material-symbols-outlined text-sm" data-icon="refresh">refresh</span>
                <span>SYNC</span>
            </button>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-4">

   
        <div class="bg-surface-container-lowest border-2 border-error neo-shadow-lg alarm-card flex flex-col justify-between">
            <div>
           
                <div class="bg-error text-on-error px-3 py-2 border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 01</span>
                        <span class="px-1.5 py-0.2 bg-surface-container-lowest text-error font-label-sm text-[10px] font-bold border border-on-surface">PS5 DIGITAL</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-error-container text-on-error-container font-label-sm text-[10px] font-extrabold border border-on-surface">
                        TIME UP
                    </span>
                </div>

              
                <div class="p-3 flex flex-col gap-2.5">
                  
                    <div class="flex items-center justify-between px-2 py-1 bg-error-container border border-on-surface text-on-error-container">
                        <span class="font-label-sm text-[11px] font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm animate-pulse" data-icon="campaign">campaign</span> BUZZER BUNYI
                        </span>
                        <button class="px-1.5 py-0.5 bg-surface-container-lowest text-error border border-on-surface text-[10px] font-label-sm font-bold hover:bg-error hover:text-on-error btn-press">
                            MUTE
                        </button>
                    </div>

                
                    <div class="bg-surface-container-low p-2.5 border-2 border-on-surface neo-shadow-inset text-center">
                        <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block mb-0.5">SISA WAKTU</span>
                        <div class="font-timer-display text-3xl font-extrabold text-error tracking-tight leading-none my-1">
                            00:00:00
                        </div>
                        <span class="font-label-sm text-[11px] text-error font-bold block">OVERTIME +00:04:12</span>
                    </div>

                   
                    <div class="border-t-2 border-dashed border-on-surface/40 pt-2 flex flex-col gap-1 text-xs font-label-sm">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Rental (2 Jam)</span>
                            <span class="font-bold text-on-surface">Rp 45.000</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>F&amp;B (1x Cola + Snack)</span>
                            <span class="font-bold text-on-surface">Rp 18.000</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-on-surface/20 font-bold text-on-surface text-xs">
                            <span>TOTAL BILL:</span>
                            <span class="text-error font-extrabold">Rp 63.000</span>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="p-2.5 bg-surface-container-high border-t-2 border-on-surface grid grid-cols-2 gap-2">
                <button class="py-1.5 bg-error text-on-error font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:brightness-110" onclick="openCheckoutModal('PS 01', 'Rp 63.000')">
                    <span class="material-symbols-outlined text-sm" data-icon="receipt">receipt</span> CHECKOUT
                </button>
                <button class="py-1.5 bg-secondary-container text-on-secondary font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-secondary">
                    <span class="material-symbols-outlined text-sm" data-icon="more_time">more_time</span> + EXTEND
                </button>
            </div>
        </div>

   
        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
            <div>
         
                <div class="bg-secondary-container text-on-secondary px-3 py-2 border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 02</span>
                        <span class="px-1.5 py-0.2 bg-surface-container-lowest text-on-surface font-label-sm text-[10px] font-bold border border-on-surface">PS5 DISC</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-surface-container-lowest text-secondary font-label-sm text-[10px] font-extrabold border border-on-surface">
                        &lt; 10 MENIT
                    </span>
                </div>

         
                <div class="p-3 flex flex-col gap-2.5">
                    
                    <div class="flex items-center justify-between px-2 py-1 bg-secondary-fixed border border-on-surface text-on-secondary-fixed">
                        <span class="font-label-sm text-[11px] font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-secondary" data-icon="add_circle">add_circle</span> +30m Diminta
                        </span>
                        <button class="px-2 py-0.5 bg-tertiary text-on-tertiary border border-on-surface text-[10px] font-label-sm font-bold btn-press" onclick="approveRequest('PS 02', '+30 Menit')">
                            TERIMA
                        </button>
                    </div>

                
                    <div class="bg-surface-container-low p-2.5 border-2 border-on-surface neo-shadow-inset text-center">
                        <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block mb-0.5">SISA WAKTU</span>
                        <div class="font-timer-display text-3xl font-extrabold text-secondary tracking-tight leading-none my-1">
                            00:07:45
                        </div>
                        <span class="font-label-sm text-[11px] text-on-surface-variant">Prepaid: Paket 2 Jam</span>
                    </div>

                
                    <div class="border-t-2 border-dashed border-on-surface/40 pt-2 flex flex-col gap-1 text-xs font-label-sm">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Rental PS5 (2 Jam)</span>
                            <span class="font-bold text-on-surface">Rp 50.000</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>F&amp;B (2x Teh Botol)</span>
                            <span class="font-bold text-on-surface">Rp 12.000</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-on-surface/20 font-bold text-on-surface text-xs">
                            <span>TOTAL SAAT INI:</span>
                            <span class="text-secondary font-extrabold">Rp 62.000</span>
                        </div>
                    </div>
                </div>
            </div>

         
            <div class="p-2.5 bg-surface-container-high border-t-2 border-on-surface grid grid-cols-2 gap-2">
                <button class="py-1.5 bg-secondary text-on-secondary font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:brightness-110">
                    <span class="material-symbols-outlined text-sm" data-icon="schedule_send">schedule_send</span> + EXTEND
                </button>
                <button class="py-1.5 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-surface-container">
                    <span class="material-symbols-outlined text-sm" data-icon="fastfood">fastfood</span> + F&amp;B
                </button>
            </div>
        </div>

        
        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
            <div>
             
                <div class="bg-primary text-on-primary px-3 py-2 border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 03</span>
                        <span class="px-1.5 py-0.2 bg-surface-container-lowest text-primary font-label-sm text-[10px] font-bold border border-on-surface">PS5 DISC</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-surface-container-lowest text-primary font-label-sm text-[10px] font-extrabold border border-on-surface">
                        PLAYING
                    </span>
                </div>

         
                <div class="p-3 flex flex-col gap-2.5">
               
                    <div class="flex items-center justify-between px-2 py-1 bg-surface-container border border-on-surface font-label-sm text-[11px]">
                        <span class="text-on-surface-variant font-bold">KONTROLER:</span>
                        <span class="font-bold flex items-center gap-1 text-primary">
                            <span class="material-symbols-outlined text-sm" data-icon="sports_esports">sports_esports</span> 2 DUALSENSE
                        </span>
                    </div>

                 
                    <div class="bg-surface-container-low p-2.5 border-2 border-on-surface neo-shadow-inset text-center">
                        <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block mb-0.5">SISA WAKTU</span>
                        <div class="font-timer-display text-3xl font-extrabold text-primary tracking-tight leading-none my-1">
                            01:24:18
                        </div>
                        <span class="font-label-sm text-[11px] text-on-surface-variant">Sesi: 13:00 - 15:00 WIB</span>
                    </div>

                   
                    <div class="border-t-2 border-dashed border-on-surface/40 pt-2 flex flex-col gap-1 text-xs font-label-sm">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Prepaid PS5 (2 Jam)</span>
                            <span class="font-bold text-on-surface">Rp 50.000</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>F&amp;B (2x Teh Botol)</span>
                            <span class="font-bold text-on-surface">Rp 12.000</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-on-surface/20 font-bold text-on-surface text-xs">
                            <span>TOTAL SAAT INI:</span>
                            <span class="text-primary font-extrabold">Rp 62.000</span>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="p-2.5 bg-surface-container-high border-t-2 border-on-surface grid grid-cols-2 gap-2">
                <button class="py-1.5 bg-primary text-on-primary font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-primary-container">
                    <span class="material-symbols-outlined text-sm" data-icon="add_circle">add_circle</span> + EXTEND
                </button>
                <button class="py-1.5 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-surface-container">
                    <span class="material-symbols-outlined text-sm" data-icon="lunch_dining">lunch_dining</span> + F&amp;B
                </button>
            </div>
        </div>

        
        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
            <div>
          
                <div class="bg-tertiary text-on-tertiary px-3 py-2 border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 04</span>
                        <span class="px-1.5 py-0.2 bg-surface-container-lowest text-tertiary font-label-sm text-[10px] font-bold border border-on-surface">PS4 PRO</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-[10px] font-extrabold border border-on-surface">
                        AVAILABLE
                    </span>
                </div>

             
                <div class="p-4 flex flex-col items-center justify-center text-center gap-2 min-h-[190px]">
                    <div class="w-12 h-12 bg-surface-container border-2 border-on-surface flex items-center justify-center neo-shadow-sm">
                        <span class="material-symbols-outlined text-2xl text-tertiary" data-icon="tv">tv</span>
                    </div>
                    <div>
                        <h4 class="font-headline-sm text-sm font-extrabold text-on-surface">SIAP DISEWA</h4>
                        <p class="font-label-md text-xs font-bold text-tertiary mt-0.5">Rp 15.000 / Jam</p>
                        <p class="font-label-sm text-[11px] text-on-surface-variant mt-1">TV Sony Bravia 4K • 2 Stik</p>
                    </div>
                </div>
            </div>

     
            <div class="p-2.5 bg-surface-container-high border-t-2 border-on-surface">
                <button class="w-full py-2 bg-tertiary text-on-tertiary font-headline-sm text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1.5 hover:bg-tertiary-container" onclick="openRentalModalWithUnit('PS 04 (PS4 Pro)', '15.000')">
                    <span class="material-symbols-outlined text-base" data-icon="sports_esports">sports_esports</span>
                    <span>MULAI RENTAL</span>
                </button>
            </div>
        </div>


        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
            <div>
    
                <div class="bg-surface-container-highest text-on-surface px-3 py-2 border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 05</span>
                        <span class="px-1.5 py-0.2 bg-primary text-on-primary font-label-sm text-[10px] font-bold border border-on-surface">PS5 SLIM</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-surface-container-lowest text-primary font-label-sm text-[10px] font-extrabold border border-on-surface">
                        POSTPAID
                    </span>
                </div>

                
                <div class="p-3 flex flex-col gap-2.5">
                   
                    <div class="flex items-center justify-between px-2 py-1 bg-surface-container-high border border-on-surface font-label-sm text-[11px]">
                        <span class="text-on-surface-variant font-bold">MODE: OPEN BILL</span>
                        <span class="font-bold text-on-surface">Rp 25.000 / Jam</span>
                    </div>

               
                    <div class="bg-surface-container-low p-2.5 border-2 border-on-surface neo-shadow-inset text-center">
                        <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block mb-0.5">WAKTU BERJALAN</span>
                        <div class="font-timer-display text-3xl font-extrabold text-on-surface tracking-tight leading-none my-1">
                            02:41:10
                        </div>
                        <span class="font-label-sm text-[11px] text-primary font-bold">Billing Berjalan Otomatis</span>
                    </div>

              
                    <div class="border-t-2 border-dashed border-on-surface/40 pt-2 flex flex-col gap-1 text-xs font-label-sm">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Rental (2.7 Jam x 25k)</span>
                            <span class="font-bold text-on-surface">Rp 67.500</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant">
                            <span>F&amp;B (1x Mie + Telur)</span>
                            <span class="font-bold text-on-surface">Rp 15.000</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-on-surface/20 font-bold text-on-surface text-xs">
                            <span>TOTAL SAAT INI:</span>
                            <span class="text-primary font-extrabold">Rp 82.500</span>
                        </div>
                    </div>
                </div>
            </div>

       
            <div class="p-2.5 bg-surface-container-high border-t-2 border-on-surface grid grid-cols-2 gap-2">
                <button class="py-1.5 bg-error text-on-error font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:brightness-110" onclick="openCheckoutModal('PS 05', 'Rp 82.500')">
                    <span class="material-symbols-outlined text-sm" data-icon="stop_circle">stop_circle</span> STOP &amp; PAY
                </button>
                <button class="py-1.5 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-surface-container">
                    <span class="material-symbols-outlined text-sm" data-icon="ramen_dining">ramen_dining</span> + F&amp;B
                </button>
            </div>
        </div>

   
        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
            <div>
                
                <div class="bg-tertiary text-on-tertiary px-3 py-2 border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 06</span>
                        <span class="px-1.5 py-0.2 bg-surface-container-lowest text-tertiary font-label-sm text-[10px] font-bold border border-on-surface">PS4 PRO</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-[10px] font-extrabold border border-on-surface">
                        AVAILABLE
                    </span>
                </div>

           
                <div class="p-4 flex flex-col items-center justify-center text-center gap-2 min-h-[190px]">
                    <div class="w-12 h-12 bg-surface-container border-2 border-on-surface flex items-center justify-center neo-shadow-sm">
                        <span class="material-symbols-outlined text-2xl text-tertiary" data-icon="sports_esports">sports_esports</span>
                    </div>
                    <div>
                        <h4 class="font-headline-sm text-sm font-extrabold text-on-surface">SIAP DISEWA</h4>
                        <p class="font-label-md text-xs font-bold text-tertiary mt-0.5">Rp 15.000 / Jam</p>
                        <p class="font-label-sm text-[11px] text-on-surface-variant mt-1">TV LG 4K UHD • DualShock Charged</p>
                    </div>
                </div>
            </div>

       
            <div class="p-2.5 bg-surface-container-high border-t-2 border-on-surface">
                <button class="w-full py-2 bg-tertiary text-on-tertiary font-headline-sm text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1.5 hover:bg-tertiary-container" onclick="openRentalModalWithUnit('PS 06 (PS4 Pro)', '15.000')">
                    <span class="material-symbols-outlined text-base" data-icon="sports_esports">sports_esports</span>
                    <span>MULAI RENTAL</span>
                </button>
            </div>
        </div>

       
        <div class="bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
            <div>
          
                <div class="bg-primary text-on-primary px-3 py-2 border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 07</span>
                        <span class="px-1.5 py-0.2 bg-surface-container-lowest text-primary font-label-sm text-[10px] font-bold border border-on-surface">PS5 DISC</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-surface-container-lowest text-primary font-label-sm text-[10px] font-extrabold border border-on-surface">
                        PLAYING
                    </span>
                </div>

          
                <div class="p-3 flex flex-col gap-2.5">
             
                    <div class="flex items-center justify-between px-2 py-1 bg-secondary-fixed border border-on-surface text-on-secondary-fixed">
                        <span class="font-label-sm text-[11px] font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-secondary animate-bounce" data-icon="restaurant">restaurant</span> Order Masuk
                        </span>
                        <button class="px-2 py-0.5 bg-secondary-container text-on-secondary border border-on-surface text-[10px] font-label-sm font-bold btn-press" onclick="approveRequest('PS 07', 'Order F&amp;B')">
                            ACC
                        </button>
                    </div>

                    
                    <div class="bg-surface-container-low p-2.5 border-2 border-on-surface neo-shadow-inset text-center">
                        <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block mb-0.5">SISA WAKTU</span>
                        <div class="font-timer-display text-3xl font-extrabold text-primary tracking-tight leading-none my-1">
                            00:48:22
                        </div>
                        <span class="font-label-sm text-[11px] text-on-surface-variant">Sesi 14:00 - 15:30 WIB</span>
                    </div>

                    <div class="border-t-2 border-dashed border-on-surface/40 pt-2 flex flex-col gap-1 text-xs font-label-sm">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Prepaid PS5 (1 Jam)</span>
                            <span class="font-bold text-on-surface">Rp 25.000</span>
                        </div>
                        <div class="flex justify-between text-secondary font-bold">
                            <span>Order F&amp;B (Menunggu)</span>
                            <span>+Rp 25.000</span>
                        </div>
                        <div class="flex justify-between pt-1 border-t border-on-surface/20 font-bold text-on-surface text-xs">
                            <span>EST. TOTAL BILL:</span>
                            <span class="text-primary font-extrabold">Rp 50.000</span>
                        </div>
                    </div>
                </div>
            </div>

    
            <div class="p-2.5 bg-surface-container-high border-t-2 border-on-surface grid grid-cols-2 gap-2">
                <button class="py-1.5 bg-primary text-on-primary font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-primary-container">
                    <span class="material-symbols-outlined text-sm" data-icon="more_time">more_time</span> + EXTEND
                </button>
                <button class="py-1.5 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press flex items-center justify-center gap-1 hover:bg-surface-container">
                    <span class="material-symbols-outlined text-sm" data-icon="receipt">receipt</span> RINCIAN
                </button>
            </div>
        </div>

  
        <div class="bg-surface-dim border-2 border-outline text-on-surface-variant neo-shadow flex flex-col justify-between">
            <div>
      
                <div class="bg-inverse-surface text-inverse-on-surface px-3 py-2 border-b-2 border-outline flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="font-headline-md text-base font-extrabold tracking-tight">PS 08</span>
                        <span class="px-1.5 py-0.2 bg-surface-container-lowest text-on-surface font-label-sm text-[10px] font-bold border border-outline">PS4 SLIM</span>
                    </div>
                    <span class="px-1.5 py-0.5 bg-outline text-surface-container-lowest font-label-sm text-[10px] font-extrabold">
                        OFFLINE
                    </span>
                </div>

             
                <div class="p-4 flex flex-col items-center justify-center text-center gap-2 min-h-[190px]">
                    <div class="w-12 h-12 bg-surface-container-highest border-2 border-outline flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl text-outline" data-icon="build">build</span>
                    </div>
                    <div>
                        <h4 class="font-headline-sm text-sm font-extrabold text-on-surface">MAINTENANCE</h4>
                        <p class="font-label-md text-xs text-on-surface-variant font-bold mt-0.5">CLEANING &amp; PASTA</p>
                        <p class="font-label-sm text-[11px] text-outline mt-1">Estimasi selesai: 16:00 WIB</p>
                    </div>
                </div>
            </div>

            <div class="p-2.5 bg-surface-container border-t-2 border-outline">
                <button class="w-full py-2 bg-surface-container-lowest text-on-surface font-label-md text-xs font-bold border-2 border-on-surface neo-shadow-sm btn-press hover:bg-tertiary hover:text-on-tertiary flex items-center justify-center gap-1" onclick="alert('Unit PS 08 diaktifkan kembali!')">
                    <span class="material-symbols-outlined text-base" data-icon="power_settings_new">power_settings_new</span>
                    <span>ENABLE UNIT</span>
                </button>
            </div>
        </div>

    </div>

    <div class="p-3 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-primary-fixed border-2 border-on-surface flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-xl" data-icon="payments">payments</span>
            </div>
            <div>
                <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block">OMSET KASIR HARI INI (SHIFT #02)</span>
                <span class="text-xl font-headline-lg text-on-surface font-extrabold leading-none">Rp 1.485.000</span>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block">TOTAL TRANSAKSI</span>
                <span class="text-base font-headline-md font-bold text-on-surface">24 Sesi</span>
            </div>
            <div class="h-7 w-0.5 bg-outline-variant"></div>
            <div class="text-right">
                <span class="text-[10px] font-label-sm text-on-surface-variant font-bold block">F&amp;B TERJUAL</span>
                <span class="text-base font-headline-md font-bold text-secondary">38 Item</span>
            </div>
        </div>
    </div>

</main>


<div class="fixed inset-0 bg-on-surface/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4" id="checkout-modal">
    <div class="bg-surface-container-lowest border-[3px] border-on-surface neo-shadow-lg w-full max-w-sm flex flex-col overflow-hidden">
        <!-- Modal Header -->
        <div class="p-3.5 bg-error text-on-error border-b-2 border-on-surface flex items-center justify-between">
            <h3 class="font-headline-md text-sm font-extrabold uppercase">CHECKOUT &amp; CETAK STRUK</h3>
            <button class="w-7 h-7 bg-surface-container-lowest text-on-surface border-2 border-on-surface flex items-center justify-center font-bold hover:bg-error-container neo-shadow-sm btn-press text-xs" onclick="toggleCheckoutModal(false)">
                ✕
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4 flex flex-col gap-3 bg-surface">
            <div class="p-3 bg-surface-container-lowest border-2 border-on-surface neo-shadow-inset flex flex-col gap-1.5 font-label-md text-xs">
                <div class="flex justify-between">
                    <span class="font-bold" id="chk-unit-name">UNIT PS 01</span>
                    <span class="text-tertiary font-bold">SELESAI</span>
                </div>
                <div class="border-t border-dashed border-on-surface/40 my-1"></div>
                <div class="flex justify-between text-on-surface-variant">
                    <span>Tagihan Rental</span>
                    <span class="font-bold text-on-surface">Rp 45.000</span>
                </div>
                <div class="flex justify-between text-on-surface-variant">
                    <span>F&amp;B Total</span>
                    <span class="font-bold text-on-surface">Rp 18.000</span>
                </div>
                <div class="border-t-2 border-on-surface my-1"></div>
                <div class="flex justify-between text-sm font-headline-sm font-extrabold text-error">
                    <span>TOTAL BAYAR:</span>
                    <span id="chk-total-bill">Rp 63.000</span>
                </div>
            </div>

            <!-- Payment Method Options -->
            <div class="flex flex-col gap-1">
                <label class="font-label-md text-xs font-bold text-on-surface">METODE PEMBAYARAN</label>
                <div class="grid grid-cols-3 gap-2">
                    <button class="py-1.5 border-2 border-on-surface bg-primary text-on-primary font-bold text-xs neo-shadow-sm">TUNAI</button>
                    <button class="py-1.5 border-2 border-on-surface bg-surface-container-lowest font-bold text-xs neo-shadow-sm hover:bg-surface-container">QRIS</button>
                    <button class="py-1.5 border-2 border-on-surface bg-surface-container-lowest font-bold text-xs neo-shadow-sm hover:bg-surface-container">TRANSFER</button>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="grid grid-cols-2 gap-2 pt-2">
                <button class="py-2 bg-surface-container-lowest border-2 border-on-surface font-label-md text-xs font-bold neo-shadow-sm btn-press" onclick="toggleCheckoutModal(false)">
                    KEMBALI
                </button>
                <button class="py-2 bg-tertiary text-on-tertiary border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow btn-press hover:bg-tertiary-container flex items-center justify-center gap-1" onclick="completePayment()">
                    <span class="material-symbols-outlined text-sm" data-icon="print">print</span>
                    <span>BAYAR &amp; STRUK</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openCheckoutModal(unit, amount) {
        document.getElementById('chk-unit-name').textContent = 'UNIT ' + unit;
        document.getElementById('chk-total-bill').textContent = amount;
        document.getElementById('checkout-modal').classList.remove('hidden');
    }

    function toggleCheckoutModal(show) {
        const modal = document.getElementById('checkout-modal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function completePayment() {
        alert('Pembayaran Sukses diterima! Struk kasir telah dicetak.');
        toggleCheckoutModal(false);
    }
</script>
@endpush