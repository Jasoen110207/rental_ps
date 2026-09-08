@extends('layouts.app')

@section('title', 'F&B Menu - TambahBang')

@section('content')
    <main class="h-screen flex overflow-hidden">


        <section class="w-[65%] h-full flex flex-col border-r-2 border-on-surface bg-surface-container-low overflow-y-auto">


            <div
                class="sticky top-0 z-20 bg-surface-container-lowest p-unit-5 border-b-2 border-on-surface space-y-3 shadow-[0px_3px_0px_#131b2e]">

                <div class="relative w-full">
                    <span
                        class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface font-bold">search</span>
                    <input
                        class="w-full pl-11 pr-24 py-2.5 bg-surface text-on-surface font-body-md text-body-md border-2 border-on-surface shadow-[inset_2px_2px_0px_rgba(15,23,42,0.1)] focus:border-primary focus:outline-none focus:ring-0 focus:shadow-[3px_3px_0px_#004ac6] transition-all"
                        placeholder="Cari nama mie, kopi, snack..." type="text" />
                    <span
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-surface-container border border-on-surface px-2 py-0.5 text-label-sm font-label-sm text-on-surface-variant font-bold">
                        CTRL + F
                    </span>
                </div>


                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">

                    <button
                        class="flex-shrink-0 px-4 py-1.5 bg-on-surface text-surface-container-lowest border-2 border-on-surface text-label-md font-label-md font-bold shadow-[2px_2px_0px_#fd761a] transition-transform active:translate-x-0.5 active:translate-y-0.5">
                        Semua Menu (24)
                    </button>

                    <button
                        class="flex-shrink-0 px-4 py-1.5 bg-surface-container-lowest text-on-surface border-2 border-on-surface text-label-md font-label-md hover:bg-surface-container hover:shadow-[2px_2px_0px_#131b2e] transition-all">
                        Makanan Berat (8)
                    </button>

                    <button
                        class="flex-shrink-0 px-4 py-1.5 bg-surface-container-lowest text-on-surface border-2 border-on-surface text-label-md font-label-md hover:bg-surface-container hover:shadow-[2px_2px_0px_#131b2e] transition-all">
                        Minuman Segar (10)
                    </button>

                    <button
                        class="flex-shrink-0 px-4 py-1.5 bg-surface-container-lowest text-on-surface border-2 border-on-surface text-label-md font-label-md hover:bg-surface-container hover:shadow-[2px_2px_0px_#131b2e] transition-all">
                        Snack / Cemilan (6)
                    </button>
                </div>
            </div>


            <div class="p-unit-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-unit-4">


                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Tersedia / Stok 15
                            </span>
                            <span class="material-symbols-outlined text-secondary font-bold">ramen_dining</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Mie Instan + Telur
                            Kornet</h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Rebus / Goreng + Kornet Sapi</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 15.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>


                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Tersedia / Stok 12
                            </span>
                            <span class="material-symbols-outlined text-secondary font-bold">dinner_dining</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Nasi Goreng Spesial
                            PS</h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Ayam Suwir + Kerupuk &amp; Telur</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 20.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>

                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Tersedia / Stok 20
                            </span>
                            <span class="material-symbols-outlined text-primary font-bold">coffee</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Kopi Susu Gula Aren
                        </h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Espresso Blend + Fresh Milk</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 12.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>

                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Tersedia / Stok 50
                            </span>
                            <span class="material-symbols-outlined text-primary font-bold">local_drink</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Es Teh Manis Jumbo
                        </h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Cangkir Jumbo 500ml Es Dingin</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 6.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>


                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-secondary-fixed text-on-secondary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Sisa 5 Pcs
                            </span>
                            <span class="material-symbols-outlined text-secondary-container font-bold">lunch_dining</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Snack Keripik Pedas
                        </h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Keripik Singkong Level 5 Pedas</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 8.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>


                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Tersedia / Stok 18
                            </span>
                            <span class="material-symbols-outlined text-secondary font-bold">fastfood</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Kentang Goreng
                            French Fries</h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Saus BBQ + Mayonaise</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 14.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>


                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Tersedia / Stok 24
                            </span>
                            <span class="material-symbols-outlined text-error font-bold">sports_bar</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Coca Cola Dingin
                        </h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Can 330ml Chilled Fridge</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 8.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>


                <div
                    class="bg-surface-container-lowest border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] flex flex-col justify-between p-3.5 hover:-translate-x-0.5 hover:-translate-y-0.5 transition-transform group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed border border-on-surface text-label-sm font-label-sm font-bold">
                                Tersedia / Stok 40
                            </span>
                            <span class="material-symbols-outlined text-primary font-bold">water_drop</span>
                        </div>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface leading-tight mb-1">Air Mineral Dingin
                        </h3>
                        <p class="text-body-sm font-body-sm text-on-surface-variant">Botol 600ml Segar &amp; Sejuk</p>
                    </div>
                    <div
                        class="mt-4 pt-3 border-t-2 border-dashed border-outline-variant flex items-center justify-between">
                        <span class="text-label-lg font-label-lg text-primary font-bold">Rp 5.000</span>
                        <button
                            class="px-3 py-1.5 bg-primary text-on-primary font-headline-sm text-headline-sm text-xs uppercase border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] brutal-btn-press hover:bg-primary-container">
                            + TAMBAH
                        </button>
                    </div>
                </div>

            </div>
        </section>


        <section
            class="w-[35%] h-full bg-surface-container-lowest flex flex-col justify-between shadow-[-4px_0px_0px_#131b2e]">


            <div class="p-unit-5 border-b-2 border-on-surface bg-surface-container-low">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">point_of_sale</span>
                        <span class="text-headline-sm font-headline-sm font-bold tracking-tight">ORDER BILLING DOCK</span>
                    </div>
                    <span
                        class="px-2 py-0.5 bg-secondary-container text-on-secondary font-label-sm text-label-sm font-bold border border-on-surface">
                        ORDER #084
                    </span>
                </div>


                <div class="space-y-1">
                    <label class="text-label-sm font-label-sm text-on-surface-variant font-bold block uppercase">
                        Pilih Meja / Unit PS (Target Tagihan)
                    </label>
                    <div class="relative">
                        <select
                            class="w-full pl-3 pr-10 py-2.5 bg-surface text-on-surface font-label-md text-label-md font-bold border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] focus:outline-none focus:border-primary appearance-none cursor-pointer">
                            <option selected="" value="ps03">🎮 PS 03 - Sesi Aktif (BAY #03 PS5 VIP)</option>
                            <option value="ps01">🎮 PS 01 - Sesi Aktif (PS4 Regular)</option>
                            <option value="ps02">🎮 PS 02 - Sesi Aktif (PS5 Regular)</option>
                            <option value="ps04">🎮 PS 04 - Sesi Aktif (PS5 VIP Room)</option>
                            <option value="takeaway">🥡 Kasir Non-Rental / Takeaway Langsung</option>
                        </select>
                        <span
                            class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface font-bold">
                            arrow_drop_down
                        </span>
                    </div>
                </div>


                <div class="mt-3 p-2.5 bg-surface border-2 border-on-surface flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span
                            class="w-2.5 h-2.5 bg-tertiary-fixed-dim rounded-full border border-on-surface animate-pulse"></span>
                        <span class="text-label-sm font-label-sm font-bold text-on-surface">User: Budi (Billing:
                            Open)</span>
                    </div>
                    <span class="text-label-sm font-label-sm text-primary font-bold">Sisa Waktu: 01:24:12</span>
                </div>
            </div>


            <div class="flex-1 p-unit-5 overflow-y-auto space-y-3">
                <div class="flex items-center justify-between pb-1 border-b border-outline-variant">
                    <span class="text-label-sm font-label-sm text-on-surface-variant font-bold uppercase">Item F&amp;B
                        Dipesan</span>
                    <span class="text-label-sm font-label-sm text-on-surface-variant font-bold">2 Item</span>
                </div>


                <div class="p-3 bg-surface border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] flex flex-col gap-2">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-label-md font-label-md font-bold text-on-surface">Mie Instan + Telur Kornet
                            </h4>
                            <p class="text-label-sm font-label-sm text-on-surface-variant">@ Rp 15.000 (Catatan: Pedas)</p>
                        </div>
                        <span class="text-label-md font-label-md font-bold text-primary">Rp 15.000</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-dashed border-outline-variant">
                        <button class="text-error text-label-sm font-label-sm flex items-center gap-0.5 hover:underline">
                            <span class="material-symbols-outlined text-sm">delete</span> Hapus
                        </button>

                        <div class="flex items-center border-2 border-on-surface bg-surface-container-lowest">
                            <button
                                class="w-7 h-7 flex items-center justify-center font-bold text-on-surface hover:bg-surface-container active:bg-outline-variant">-</button>
                            <span
                                class="w-8 text-center text-label-md font-label-md font-bold text-on-surface border-x-2 border-on-surface bg-surface">1</span>
                            <button
                                class="w-7 h-7 flex items-center justify-center font-bold text-on-surface hover:bg-surface-container active:bg-outline-variant">+</button>
                        </div>
                    </div>
                </div>


                <div class="p-3 bg-surface border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] flex flex-col gap-2">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-label-md font-label-md font-bold text-on-surface">Nasi Goreng Spesial PS</h4>
                            <p class="text-label-sm font-label-sm text-on-surface-variant">@ Rp 18.000 (Promo Game Combo)
                            </p>
                        </div>
                        <span class="text-label-md font-label-md font-bold text-primary">Rp 18.000</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-dashed border-outline-variant">
                        <button class="text-error text-label-sm font-label-sm flex items-center gap-0.5 hover:underline">
                            <span class="material-symbols-outlined text-sm">delete</span> Hapus
                        </button>

                        <div class="flex items-center border-2 border-on-surface bg-surface-container-lowest">
                            <button
                                class="w-7 h-7 flex items-center justify-center font-bold text-on-surface hover:bg-surface-container active:bg-outline-variant">-</button>
                            <span
                                class="w-8 text-center text-label-md font-label-md font-bold text-on-surface border-x-2 border-on-surface bg-surface">1</span>
                            <button
                                class="w-7 h-7 flex items-center justify-center font-bold text-on-surface hover:bg-surface-container active:bg-outline-variant">+</button>
                        </div>
                    </div>
                </div>


                <div class="p-2 bg-surface-container-low border border-on-surface">
                    <p class="text-label-sm font-label-sm text-on-surface-variant flex items-center gap-1 font-bold">
                        <span class="material-symbols-outlined text-sm">edit_note</span> CATATAN DAPUR:
                    </p>
                    <p class="text-body-sm font-body-sm italic text-on-surface">"Mie jangan terlalu lembek, antar barengan
                        ke meja PS 03."</p>
                </div>
            </div>


            <div class="p-unit-5 border-t-2 border-on-surface bg-surface-container-low space-y-3">

                <div
                    class="p-3 bg-surface-container-lowest border-2 border-on-surface shadow-[2px_2px_0px_#131b2e] space-y-1.5">
                    <div class="flex justify-between text-label-md font-label-md text-on-surface-variant">
                        <span>Subtotal Item (2 pcs)</span>
                        <span>Rp 33.000</span>
                    </div>
                    <div class="flex justify-between text-label-md font-label-md text-on-surface-variant">
                        <span>Biaya Layanan / POS</span>
                        <span class="text-tertiary font-bold">Rp 0 (GRATIS)</span>
                    </div>
                    <div class="dashed-receipt-divider my-2"></div>
                    <div class="flex justify-between items-center text-headline-sm font-headline-sm text-on-surface">
                        <span class="font-extrabold uppercase">Subtotal F&amp;B:</span>
                        <span class="font-bold text-primary text-headline-md font-headline-md">Rp 33.000</span>
                    </div>
                </div>


                <div class="space-y-2">

                    <button
                        class="w-full py-3.5 bg-primary-container text-on-primary font-headline-sm text-headline-sm uppercase tracking-wide border-2 border-on-surface shadow-[4px_4px_0px_#131b2e] brutal-btn-press hover:bg-primary flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">post_add</span>
                        <span>MASUKKAN KE TAGIHAN UNIT PS 03</span>
                    </button>

                    <button
                        class="w-full py-2.5 bg-surface-container-lowest text-on-surface font-headline-sm text-headline-sm text-sm uppercase tracking-wide border-2 border-on-surface shadow-[3px_3px_0px_#131b2e] brutal-btn-press hover:bg-surface-container flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-tertiary">payments</span>
                        <span>BAYAR LANGSUNG TUNAI (NON-RENTAL)</span>
                    </button>
                </div>
            </div>

        </section>

    </main>


    <div
        class="fixed bottom-4 left-72 z-50 bg-on-surface text-surface px-4 py-2 border-2 border-on-surface shadow-[3px_3px_0px_#fd761a] flex items-center gap-3">
        <span class="w-2.5 h-2.5 bg-tertiary-fixed rounded-full animate-pulse"></span>
        <span class="text-label-sm font-label-sm font-bold tracking-wider">POS READY // STOK SYNCHRONIZED</span>
    </div>

@endsection
