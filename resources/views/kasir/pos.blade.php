@extends('layouts.app')

@section('title', 'Rental / POS - TambahBang')

@section('content')

    {{-- Top Row Live Status Ribbon --}}
    <section
        class="mb-5 flex flex-wrap items-center justify-between gap-4 p-3 bg-surface-container border-2 border-on-surface shadow-[3px_3px_0px_#131b2e]">
        <div class="flex items-center gap-3">
            <span
                class="px-2.5 py-1 bg-on-surface text-surface-container-lowest text-label-sm font-label-sm font-bold tracking-wider">LIVE
                BAY TRACKER</span>
            <span class="text-body-sm font-body-sm text-on-surface font-medium">8 of 10 Stations Active • Peak Afternoon
                Gaming Shift</span>
        </div>
        <div class="flex items-center gap-2">
            <span
                class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-surface-container-lowest border border-on-surface text-label-sm font-label-sm font-bold">
                <span class="w-2.5 h-2.5 bg-tertiary border border-on-surface"></span> PS5: 5 Busy / 1 Ready
            </span>
            <span
                class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-surface-container-lowest border border-on-surface text-label-sm font-label-sm font-bold">
                <span class="w-2.5 h-2.5 bg-primary border border-on-surface"></span> PS4: 3 Busy / 1 Ready
            </span>
        </div>
    </section>

    {{-- Main Grid: Left/Center Panel (Station Detail) + Right Panel (Quick Start Dock) --}}
    <div class="grid grid-cols-12 gap-6 items-start">

        {{-- ================= LEFT/CENTER PANEL: ACTIVE SESSION DETAIL (Col 8) ================= --}}
        <section class="col-span-12 xl:col-span-8 space-y-6">

            <div class="bg-surface-container-lowest border-2 border-on-surface shadow-[6px_6px_0px_#131b2e]">

                {{-- Card Header Banner --}}
                <div
                    class="bg-primary text-on-primary p-4 border-b-2 border-on-surface flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-1.5 bg-surface-container-lowest text-on-surface border-2 border-on-surface shadow-[2px_2px_0px_#131b2e]">
                            <span class="material-symbols-outlined text-2xl font-bold" data-icon="tv">tv</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-headline-md font-headline-md uppercase tracking-tight">PS 03 (PS5 Disc) -
                                    VIP Bay A</h2>
                                <span
                                    class="px-2 py-0.5 bg-surface-container-lowest text-on-surface text-label-sm font-label-sm border border-on-surface font-bold">4K
                                    120Hz OLED</span>
                            </div>
                            <p class="text-label-sm font-label-sm text-on-primary-container tracking-wider">HARDWARE: SONY
                                PS5 DISC ED. #HW-003 • 2x DUALSENSE CONNECTED</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="px-3 py-1.5 bg-tertiary-fixed text-on-tertiary-fixed font-label-lg text-label-lg font-extrabold border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 bg-tertiary rounded-full animate-ping"></span>
                            PLAYING PREPAID
                        </span>
                    </div>
                </div>

                {{-- Card Content Body --}}
                <div class="p-5 space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-stretch">

                        {{-- Countdown Display --}}
                        <div
                            class="md:col-span-7 bg-surface-container-low border-2 border-on-surface p-4 flex flex-col justify-between shadow-[inset_2px_2px_0px_#131b2e]">
                            <div class="flex items-center justify-between border-b-2 border-on-surface pb-2">
                                <span
                                    class="text-label-sm font-label-sm uppercase font-bold text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-secondary-container"
                                        data-icon="timer">timer</span>
                                    REMAINING RENTAL TIME
                                </span>
                                <span
                                    class="px-2 py-0.5 bg-secondary-container text-on-primary text-label-sm font-label-sm font-bold border border-on-surface">AUTO
                                    CUT-OFF ARMED</span>
                            </div>
                            <div class="py-4 text-center">
                                <p class="text-timer-display font-timer-display text-on-surface tracking-tighter tabular-nums font-bold leading-none"
                                    id="pos-timer-display">
                                    01:15:31
                                </p>
                                <p
                                    class="text-label-sm font-label-sm text-on-surface-variant mt-2 tracking-widest font-semibold uppercase">
                                    HOURS : MINUTES : SECONDS</p>
                            </div>
                            <div class="space-y-1.5 pt-2 border-t-2 border-on-surface">
                                <div class="flex justify-between text-label-sm font-label-sm font-bold">
                                    <span>ELAPSED: 44m 29s (37%)</span>
                                    <span>TOTAL: 2 JAM</span>
                                </div>
                                <div class="w-full h-3 bg-surface-container-highest border border-on-surface">
                                    <div class="h-full bg-primary-container border-r border-on-surface" style="width: 37%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rental Session Info Ledger --}}
                        <div
                            class="md:col-span-5 bg-surface-container-lowest border-2 border-on-surface p-4 flex flex-col justify-between shadow-[3px_3px_0px_#131b2e]">
                            <div>
                                <div
                                    class="flex items-center justify-between border-b-2 border-dashed border-on-surface pb-2 mb-3">
                                    <span class="text-label-md font-label-md font-bold text-on-surface uppercase">SESSION
                                        SUMMARY</span>
                                    <span
                                        class="px-2 py-0.5 bg-surface-container-high text-label-sm font-label-sm font-bold border border-on-surface">#TB-9482</span>
                                </div>
                                <dl class="space-y-2 text-body-sm font-body-sm">
                                    <div class="flex justify-between">
                                        <dt class="text-on-surface-variant font-medium">Customer Guest:</dt>
                                        <dd class="font-bold text-on-surface">Rian &amp; Friends (4P)</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-on-surface-variant font-medium">Session Start:</dt>
                                        <dd class="font-bold text-on-surface">13:00 WIB</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-on-surface-variant font-medium">Estimated End:</dt>
                                        <dd class="font-bold text-on-surface">15:00 WIB</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-on-surface-variant font-medium">Hourly Base Rate:</dt>
                                        <dd class="font-bold text-on-surface font-label-sm">Rp 25.000 / jam</dd>
                                    </div>
                                </dl>
                            </div>
                            <div
                                class="pt-3 mt-3 border-t-2 border-on-surface flex items-center justify-between bg-surface-container p-2 border">
                                <span class="text-label-md font-label-md font-bold">Subtotal Rental:</span>
                                <span class="text-headline-sm font-headline-sm font-extrabold text-primary">Rp 50.000</span>
                            </div>
                        </div>

                    </div>

                    {{-- Connected Customer Status & Live Mobile Orders Banner --}}
                    <div
                        class="bg-surface-container-high border-2 border-on-surface p-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-surface-container-lowest border-2 border-on-surface">
                                <span class="material-symbols-outlined text-xl text-primary font-bold"
                                    data-icon="qr_code_scanner">qr_code_scanner</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-label-md font-label-md font-bold uppercase">Customer Device
                                        Synced:</span>
                                    <span
                                        class="px-2 py-0.2 bg-tertiary-fixed text-on-tertiary-fixed text-label-sm font-label-sm font-bold border border-on-surface">QR
                                        SCANNED • TABLET BAY A</span>
                                </div>
                                <p class="text-body-sm font-body-sm text-on-surface-variant">Live extension portal enabled
                                    on TV HDMI overlay</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-secondary-container text-on-primary px-3 py-1.5 border-2 border-on-surface shadow-[2px_2px_0px_#131b2e]">
                            <span class="material-symbols-outlined animate-bounce text-base"
                                data-icon="notifications_active">notifications_active</span>
                            <span class="text-label-sm font-label-sm font-bold uppercase">1 REQUEST PENDING (+30 MIN
                                EXTENSION)</span>
                            <button
                                class="ml-2 px-2 py-0.5 bg-surface-container-lowest text-on-surface border border-on-surface text-label-sm font-label-sm font-bold hover:bg-surface-container transition-all">APPROVE</button>
                        </div>
                    </div>

                    {{-- Ordered F&B Items Section --}}
                    <div class="border-2 border-on-surface bg-surface-container-lowest shadow-[3px_3px_0px_#131b2e]">
                        <div
                            class="p-3 bg-surface-container border-b-2 border-on-surface flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface"
                                    data-icon="restaurant">restaurant</span>
                                <h3 class="text-headline-sm font-headline-sm uppercase">F&amp;B Orders Attached to Bay</h3>
                                <span
                                    class="text-label-sm font-label-sm bg-surface-container-lowest px-2 py-0.5 border border-on-surface font-bold">2
                                    Items / 3 Qty</span>
                            </div>
                            <button
                                class="px-3 py-1 bg-surface-container-lowest border-2 border-on-surface text-label-sm font-label-sm font-bold uppercase hover:bg-surface-container-high transition-all shadow-[2px_2px_0px_#131b2e] active:translate-x-0.5 active:translate-y-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm font-bold" data-icon="add">add</span> Quick
                                Add Snack
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="border-b-2 border-on-surface bg-surface-container-low text-label-sm font-label-sm uppercase text-on-surface-variant font-bold">
                                        <th class="py-2.5 px-4">Item Name</th>
                                        <th class="py-2.5 px-4 text-center">Qty</th>
                                        <th class="py-2.5 px-4 text-right">Unit Price</th>
                                        <th class="py-2.5 px-4 text-right">Subtotal</th>
                                        <th class="py-2.5 px-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-2 divide-on-surface text-body-sm font-body-sm font-medium">
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="py-3 px-4 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-secondary-container inline-block"></span>
                                            <div>
                                                <p class="font-bold text-on-surface">Es Teh Manis Jumbo</p>
                                                <p class="text-label-sm font-label-sm text-on-surface-variant">Less Ice •
                                                    Kitchen Station #1</p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center font-label-md font-bold">2x</td>
                                        <td class="py-3 px-4 text-right font-label-md">Rp 6.000</td>
                                        <td class="py-3 px-4 text-right font-label-md font-bold text-on-surface">Rp 12.000
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span
                                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed text-label-sm font-label-sm font-bold border border-on-surface">DELIVERED</span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="py-3 px-4 flex items-center gap-2">
                                            <span class="w-2 h-2 bg-secondary-container inline-block"></span>
                                            <div>
                                                <p class="font-bold text-on-surface">Indomie Goreng Spesial</p>
                                                <p class="text-label-sm font-label-sm text-on-surface-variant">+ Telur
                                                    Setengah Matang • Kornet</p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center font-label-md font-bold">1x</td>
                                        <td class="py-3 px-4 text-right font-label-md">Rp 15.000</td>
                                        <td class="py-3 px-4 text-right font-label-md font-bold text-on-surface">Rp 15.000
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span
                                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed text-label-sm font-label-sm font-bold border border-on-surface">DELIVERED</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Live Grand Total Summary Card --}}
                    <div
                        class="p-4 bg-surface-container-high border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-6">
                            <div>
                                <p class="text-label-sm font-label-sm text-on-surface-variant uppercase font-bold">Rental
                                    Charges</p>
                                <p class="text-headline-sm font-headline-sm font-bold text-on-surface">Rp 50.000</p>
                            </div>
                            <div class="text-headline-sm font-bold text-outline">+</div>
                            <div>
                                <p class="text-label-sm font-label-sm text-on-surface-variant uppercase font-bold">F&amp;B
                                    Total (3 items)</p>
                                <p class="text-headline-sm font-headline-sm font-bold text-on-surface">Rp 27.000</p>
                            </div>
                        </div>
                        <div class="border-l-2 border-on-surface pl-6">
                            <p
                                class="text-label-sm font-label-sm uppercase font-bold tracking-wider text-secondary-container">
                                LIVE GRAND TOTAL</p>
                            <p class="text-headline-lg font-headline-lg font-extrabold text-primary">Rp 77.000</p>
                        </div>
                    </div>

                    {{-- Session Operational Action Buttons Tray --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                        <button
                            class="p-3 bg-secondary-container text-on-primary font-headline-sm text-headline-sm uppercase border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex flex-col items-center justify-center text-center gap-1 hover:brightness-110">
                            <span class="material-symbols-outlined text-2xl" data-icon="more_time">more_time</span>
                            <span class="leading-tight">TAMBAH WAKTU / EXTEND</span>
                        </button>
                        <button
                            class="p-3 bg-surface-container-lowest text-on-surface font-headline-sm text-headline-sm uppercase border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex flex-col items-center justify-center text-center gap-1 hover:bg-surface-container">
                            <span class="material-symbols-outlined text-2xl text-primary"
                                data-icon="fastfood">fastfood</span>
                            <span class="leading-tight">+ TAMBAH F&amp;B</span>
                        </button>
                        <button
                            class="p-3 bg-surface-container-lowest text-on-surface font-headline-sm text-headline-sm uppercase border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex flex-col items-center justify-center text-center gap-1 hover:bg-surface-container">
                            <span class="material-symbols-outlined text-2xl text-secondary"
                                data-icon="campaign">campaign</span>
                            <span class="leading-tight">BUZZER TEST</span>
                        </button>
                        <button
                            class="p-3 bg-error text-on-error font-headline-sm text-headline-sm uppercase border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex flex-col items-center justify-center text-center gap-1 hover:brightness-110">
                            <span class="material-symbols-outlined text-2xl"
                                data-icon="power_settings_new">power_settings_new</span>
                            <span class="leading-tight">STOP &amp; CHECKOUT</span>
                        </button>
                    </div>

                </div>
            </div>
        </section>

        {{-- ================= RIGHT PANEL: START NEW RENTAL QUICK DOCK (Col 4) ================= --}}
        <section class="col-span-12 xl:col-span-4 space-y-6">

            <div class="bg-surface-container-lowest border-2 border-on-surface shadow-[6px_6px_0px_#131b2e]">

                <div
                    class="p-4 bg-on-surface text-surface-container-lowest border-b-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-tertiary-fixed text-2xl" data-icon="bolt">bolt</span>
                        <div>
                            <h3 class="text-headline-sm font-headline-sm uppercase tracking-wide">Start New Rental</h3>
                            <p class="text-label-sm font-label-sm text-outline-variant uppercase">Express Bay Dispatch</p>
                        </div>
                    </div>
                    <span
                        class="px-2 py-0.5 bg-tertiary text-on-tertiary text-label-sm font-label-sm font-bold border border-surface-container-lowest">FAST
                        POS</span>
                </div>

                <div class="p-5 space-y-5">

                    {{-- Available Unit Picker --}}
                    <div class="space-y-2">
                        <label
                            class="text-label-sm font-label-sm font-bold uppercase text-on-surface flex items-center justify-between">
                            <span>Select Available Station:</span>
                            <span class="text-tertiary font-bold">2 Units Standby</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                class="p-3 border-2 border-on-surface bg-primary-fixed text-on-primary-fixed shadow-[3px_3px_0px_#131b2e] text-left transition-all active:translate-x-0.5 active:translate-y-0.5 ring-2 ring-primary"
                                type="button">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-label-lg font-label-lg font-bold">PS 04</span>
                                    <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                                </div>
                                <p class="text-body-sm font-body-sm font-bold">PS5 Slim Digital</p>
                                <p class="text-label-sm font-label-sm text-on-surface-variant mt-1">Rp 20.000/jam • Main
                                    Bay</p>
                            </button>
                            <button
                                class="p-3 border-2 border-on-surface bg-surface-container-lowest hover:bg-surface-container-low text-on-surface shadow-[3px_3px_0px_#131b2e] text-left transition-all active:translate-x-0.5 active:translate-y-0.5"
                                type="button">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-label-lg font-label-lg font-bold">PS 06</span>
                                    <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                                </div>
                                <p class="text-body-sm font-body-sm font-bold">PS4 Pro 1TB</p>
                                <p class="text-label-sm font-label-sm text-on-surface-variant mt-1">Rp 15.000/jam • Lounge
                                </p>
                            </button>
                        </div>
                    </div>

                    {{-- Segmented Billing Mode Selector --}}
                    <div class="space-y-2">
                        <label class="text-label-sm font-label-sm font-bold uppercase text-on-surface">Billing Paradigm
                            Mode:</label>
                        <div
                            class="grid grid-cols-2 gap-2 border-2 border-on-surface p-1 bg-surface-container-high shadow-[2px_2px_0px_#131b2e]">
                            <button
                                class="py-2 px-3 bg-primary text-on-primary font-label-md text-label-md font-extrabold uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] flex items-center justify-center gap-1.5"
                                type="button">
                                <span class="material-symbols-outlined text-sm" data-icon="lock_clock">lock_clock</span>
                                <span>PREPAID</span>
                            </button>
                            <button
                                class="py-2 px-3 bg-surface-container-lowest text-on-surface font-label-md text-label-md font-bold uppercase border-2 border-transparent hover:border-on-surface transition-all flex items-center justify-center gap-1.5"
                                type="button">
                                <span class="material-symbols-outlined text-sm"
                                    data-icon="hourglass_empty">hourglass_empty</span>
                                <span>POSTPAID / LOSS</span>
                            </button>
                        </div>
                    </div>

                    {{-- Duration Presets --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="text-label-sm font-label-sm font-bold uppercase text-on-surface">Duration
                                Selection:</label>
                            <span class="text-label-sm font-label-sm font-bold text-primary">Est. End: 15:30 WIB</span>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <button
                                class="py-2 bg-surface-container-lowest border-2 border-on-surface text-label-md font-label-md font-bold hover:bg-surface-container transition-all shadow-[2px_2px_0px_#131b2e] active:translate-x-0.5 active:translate-y-0.5"
                                type="button">1 Jam</button>
                            <button
                                class="py-2 bg-secondary-container text-on-primary border-2 border-on-surface text-label-md font-label-md font-bold transition-all shadow-[2px_2px_0px_#131b2e] active:translate-x-0.5 active:translate-y-0.5"
                                type="button">2 Jam</button>
                            <button
                                class="py-2 bg-surface-container-lowest border-2 border-on-surface text-label-md font-label-md font-bold hover:bg-surface-container transition-all shadow-[2px_2px_0px_#131b2e] active:translate-x-0.5 active:translate-y-0.5"
                                type="button">3 Jam</button>
                            <button
                                class="py-2 bg-surface-container-lowest border-2 border-on-surface text-label-md font-label-md font-bold hover:bg-surface-container transition-all shadow-[2px_2px_0px_#131b2e] active:translate-x-0.5 active:translate-y-0.5"
                                type="button">Custom</button>
                        </div>
                    </div>

                    {{-- Customer Info Input --}}
                    <div class="space-y-2">
                        <label class="text-label-sm font-label-sm font-bold uppercase text-on-surface">Customer Identifier
                            / Phone:</label>
                        <div class="relative">
                            <input
                                class="w-full bg-surface-container-lowest border-2 border-on-surface p-2.5 text-body-sm font-body-sm font-bold shadow-[inset_2px_2px_0px_rgba(15,23,42,0.08)] focus:border-primary focus:ring-0"
                                type="text" value="Mas Dimas (0812-9921-xxxx)" />
                            <span class="material-symbols-outlined absolute right-2.5 top-2.5 text-on-surface-variant"
                                data-icon="badge">badge</span>
                        </div>
                    </div>

                    {{-- Initial DualSense Accessories --}}
                    <div class="p-3 bg-surface-container border-2 border-on-surface space-y-2">
                        <div class="flex items-center justify-between text-label-sm font-label-sm font-bold">
                            <span class="uppercase">DualSense Controllers:</span>
                            <span class="text-primary font-bold">2 Included (Free)</span>
                        </div>
                        <div class="flex items-center justify-between text-body-sm font-body-sm">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    class="w-4 h-4 border-2 border-on-surface text-primary focus:ring-0 rounded-none shadow-[1px_1px_0px_#131b2e]"
                                    type="checkbox" />
                                <span>+ Tambah Stick 3 (Rp 5.000/jam)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Rate Calculation Breakdown --}}
                    <div class="p-3 bg-surface-container-low border-2 border-on-surface space-y-1.5">
                        <div class="flex justify-between text-label-sm font-label-sm">
                            <span>Unit Rate (PS 04 x 2 Jam):</span>
                            <span class="font-bold">Rp 40.000</span>
                        </div>
                        <div class="flex justify-between text-label-sm font-label-sm">
                            <span>PPN / Operational Fee (0%):</span>
                            <span class="font-bold">Rp 0</span>
                        </div>
                        <div
                            class="pt-2 border-t-2 border-dashed border-on-surface flex justify-between items-center text-on-surface">
                            <span class="text-label-md font-label-md font-bold uppercase">Estimated Bill:</span>
                            <span class="text-headline-sm font-headline-sm font-black text-secondary">Rp 40.000</span>
                        </div>
                    </div>

                    {{-- Massive High-Impact CTA Button --}}
                    <button
                        class="w-full py-4 bg-primary text-on-primary font-headline-md text-headline-md uppercase tracking-wider border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3 hover:bg-primary-container">
                        <span class="material-symbols-outlined text-2xl font-bold"
                            data-icon="play_arrow">play_arrow</span>
                        <span>MULAI SEWA UNIT</span>
                    </button>
                    <p class="text-center text-label-sm font-label-sm text-on-surface-variant font-medium">
                        Relay switch to TV power outlet will activate instantly.
                    </p>

                </div>
            </div>

            {{-- System Quick Stat Peripheral --}}
            <div
                class="bg-surface-container-high border-2 border-on-surface p-4 shadow-[4px_4px_0px_#131b2e] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-2xl text-secondary-container" data-icon="bolt">bolt</span>
                    <div>
                        <p class="text-label-sm font-label-sm font-bold uppercase">Total Power Draw</p>
                        <p class="text-headline-sm font-headline-sm font-bold">1,840 W / 3,500 W</p>
                    </div>
                </div>
                <span
                    class="px-2 py-1 bg-surface-container-lowest border border-on-surface text-label-sm font-label-sm font-bold">PLN
                    STABLE</span>
            </div>

        </section>

    </div>

@endsection

@push('scripts')
    <script>
        (function() {
            let totalSeconds = (1 * 3600) + (15 * 60) + 31;
            const timerDisplay = document.getElementById('pos-timer-display');

            setInterval(() => {
                if (totalSeconds > 0) {
                    totalSeconds--;
                    const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
                    const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
                    const seconds = String(totalSeconds % 60).padStart(2, '0');
                    if (timerDisplay) {
                        timerDisplay.textContent = `${hours}:${minutes}:${seconds}`;
                    }
                }
            }, 1000);
        })();
    </script>
@endpush
