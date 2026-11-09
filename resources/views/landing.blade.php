<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Pilih konsol, cek tarif, dan rencanakan waktu mainmu di {{ $settings['store_name'] ?? 'TambahBang Rental PS' }}. Kelola sesi bermain langsung dari QR di meja.">
    <meta name="theme-color" content="#004ac6">
    <title>{{ $settings['store_name'] ?? 'TambahBang Rental PS' }} — Waktunya main bareng.</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;600;700;900&family=Space+Grotesk:wght@500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<a class="skip-link" href="#main">Lewati ke konten</a>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ $settings['store_name'] ?? 'TambahBang' }} — beranda">
            <img class="brand-logo" src="{{ asset('images/logo.png') }}" alt="{{ $settings['store_name'] ?? 'TambahBang Rental PS' }}" width="900" height="438">
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="navigation" hidden>Menu <span aria-hidden="true">☰</span></button>
        <nav id="navigation" class="navigation" aria-label="Navigasi utama">
            <a href="#konsol">Konsol & tarif</a><a href="#cara-main">Cara main</a><a href="#faq">FAQ</a>
            <a class="nav-contact" href="#kunjungi">Hubungi kami <span aria-hidden="true">↗</span></a>
        </nav>
    </div>
</header>
<main id="main">
    <section class="hero container" aria-labelledby="hero-title">
        <div class="hero-copy">
            <p class="eyebrow"><span class="tiny-square" aria-hidden="true"></span> TEMPAT KUMPUL. TEMPAT MAIN.</p>
            <h1 id="hero-title">Jeda dulu.<br>Main <span>bareng.</span></h1>
            <p class="hero-description">Tinggal pilih konsol, ajak teman, dan nikmati waktu mainmu. Urusan tambah waktu dan pesan camilan? Cukup scan QR di meja.</p>
            <div class="hero-actions"><a class="button button-primary neo-shadow btn-press" href="#konsol">Pilih konsolmu <span aria-hidden="true">↗</span></a><a class="text-link" href="#cara-main">Gimana cara mainnya? <span aria-hidden="true">↓</span></a></div>
            <p class="hero-note"><span aria-hidden="true">✳</span> Datang, duduk, lanjutkan keseruannya.</p>
        </div>
        <div class="play-poster neo-shadow-lg">
            <div class="poster-top"><span>TAMBAHBANG / PLAY CLUB</span><span aria-hidden="true">READY?</span></div>
            <div class="poster-title" aria-hidden="true">PRESS<br><span>PLAY.</span></div>
            <svg class="controller-art" viewBox="0 0 500 300" role="img" aria-label="Ilustrasi stik permainan dengan tombol arah dan dua analog">
                <path d="M122 68 Q98 66 83 98 L35 220 Q23 267 61 270 Q82 272 103 244 L146 205 H354 L397 244 Q418 272 439 270 Q477 267 465 220 L417 98 Q402 66 378 68Z" fill="#131b2e" transform="translate(7 12)"/>
                <path d="M122 68 Q98 66 83 98 L35 220 Q23 267 61 270 Q82 272 103 244 L146 205 H354 L397 244 Q418 272 439 270 Q477 267 465 220 L417 98 Q402 66 378 68Z" fill="#faf8ff" stroke="#131b2e" stroke-width="6"/>
                <path d="M191 78 H309 L299 133 H201Z" fill="#dbe1ff" stroke="#131b2e" stroke-width="5"/>
                <path d="M116 103 H137 V125 H159 V146 H137 V168 H116 V146 H94 V125 H116Z" fill="#131b2e"/>
                <circle cx="191" cy="182" r="29" fill="#131b2e"/><circle cx="191" cy="182" r="18" fill="#434655"/>
                <circle cx="309" cy="182" r="29" fill="#131b2e"/><circle cx="309" cy="182" r="18" fill="#434655"/>
                <g fill="#131b2e"><circle cx="375" cy="111" r="13"/><circle cx="401" cy="137" r="13"/><circle cx="349" cy="137" r="13"/><circle cx="375" cy="163" r="13"/></g>
                <path d="M375 104 L369 116 H381Z" fill="none" stroke="#6ffbbe" stroke-width="2"/><circle cx="401" cy="137" r="6" fill="none" stroke="#fd761a" stroke-width="2"/><path d="M344 132 H354 V142 H344Z" fill="none" stroke="#dbe1ff" stroke-width="2"/><path d="M370 158 L380 168 M380 158 L370 168" stroke="#dbe1ff" stroke-width="2"/>
                <circle cx="250" cy="167" r="7" fill="#004ac6"/>
            </svg>
            <div class="poster-sticker">LESS SCROLL.<br>MORE PLAY. <span aria-hidden="true">↗</span></div>
            <div class="poster-bottom"><span>PLAYER 01 + PLAYER 02</span><span aria-hidden="true">✚ &nbsp; ○ &nbsp; △ &nbsp; □</span></div>
        </div>
    </section>
    <div class="service-strip"><div class="container strip-inner"><span>MAIN SESUAI DURASIMU</span><span aria-hidden="true">✳</span><span>PESAN LEWAT QR</span><span aria-hidden="true">✳</span><span>TAMBAH WAKTU DARI MEJA</span></div></div>
    <section id="konsol" class="section container" aria-labelledby="console-title">
        <div class="section-heading"><div><p class="eyebrow">01 / PILIH ARENAMU</p><h2 id="console-title">Beda konsol.<br>Sama serunya.</h2></div><p>Mau nostalgia atau coba generasi baru?<br>Cek pilihan unit dan tarif per jam di sini.</p></div>
        <div class="console-grid">
            @forelse ($units as $unit)
                <article class="console-card neo-shadow">
                    <div class="console-card-top"><span class="eyebrow">{{ str_replace('_', ' ', strtoupper($unit->type)) }}</span><span class="unit-count">{{ $unit->total }} unit</span></div>
                    <div class="console-monogram" aria-hidden="true">{{ match($unit->type) { 'sim_racing' => 'RACE', 'nintendo_switch' => 'SW', default => strtoupper($unit->type) } }}<span>↗</span></div>
                    <h3>{{ $labels[$unit->type] ?? strtoupper($unit->type) }}</h3>
                    <p class="price-label">Mulai dari</p><p class="price">Rp{{ number_format($unit->price, 0, ',', '.') }}<span> / jam</span></p>
                    <a class="button button-outline" href="{{ $contactUrl ?? '#kunjungi' }}">Tanya ketersediaan <span aria-hidden="true">↗</span></a>
                </article>
            @empty
                <div class="empty-units"><h3>Pilihan konsol sedang disiapkan.</h3><p>Hubungi kasir untuk mengetahui unit dan tarif yang tersedia.</p><a class="text-link" href="#kunjungi">Informasi kontak <span aria-hidden="true">↗</span></a></div>
            @endforelse
        </div>
        <p class="section-note">Jumlah unit adalah total koleksi. Konfirmasi ketersediaan dan jadwal bermain dengan kasir sebelum datang.</p>
    </section>
    <section id="cara-main" class="how-section" aria-labelledby="how-title"><div class="container section how-grid">
        <div><p class="eyebrow">02 / GAMPANG MULAINYA</p><h2 id="how-title">Dari pilih konsol<br>sampai <span>“tambah, Bang!”</span></h2><p class="how-intro">Waktu main buat main. Kelola kebutuhan sesimu langsung dari ponsel.</p><a class="button button-primary neo-shadow btn-press" href="{{ $contactUrl ?? '#kunjungi' }}">Tanya ke kasir <span aria-hidden="true">↗</span></a></div>
        <ol class="steps"><li><span class="step-number">01</span><div><h3>Pilih unit & durasi</h3><p>Hubungi kasir atau datang langsung. Tentukan konsol dan berapa lama kamu ingin bermain.</p></div></li><li><span class="step-number">02</span><div><h3>Scan QR di mejamu</h3><p>Setelah sesi dimulai, scan kode QR pada unit untuk membuka portal dan melihat sisa waktu.</p></div></li><li><span class="step-number">03</span><div><h3>Main dulu, tambah kemudian</h3><p>Ajukan tambah waktu, pesan makanan dan minuman, atau panggil kasir melalui portal. Kasir akan memproses permintaanmu.</p></div></li></ol>
    </div></section>
    <section id="faq" class="section container faq-grid" aria-labelledby="faq-title"><div><p class="eyebrow">03 / SEBELUM MAIN</p><h2 id="faq-title">Masih<br>penasaran?</h2><p>Ini yang perlu kamu tahu sebelum pegang stik.</p></div><div class="faq-list">
        <details><summary>Harus booking dulu?<span aria-hidden="true">+</span></summary><p>Kamu bisa datang langsung. Untuk memastikan unit tersedia pada waktu yang kamu inginkan, hubungi kasir terlebih dahulu.</p></details>
        <details><summary>Perlu akun untuk membuka portal?<span aria-hidden="true">+</span></summary><p>Tidak perlu. Scan QR pada unit yang kamu gunakan untuk mengakses portal pelanggan. Akun login diperuntukkan bagi staf.</p></details>
        <details><summary>Bisa tambah waktu saat sedang main?<span aria-hidden="true">+</span></summary><p>Bisa ajukan lewat portal QR. Penambahan waktu diproses kasir sesuai ketersediaan unit dan jadwal berikutnya.</p></details>
        <details><summary>Bagaimana pesan makanan dan minuman?<span aria-hidden="true">+</span></summary><p>Buka menu pesanan di portal QR, pilih makanan atau minuman yang tersedia, lalu kirim permintaan ke kasir.</p></details>
    </div></section>
    <section id="kunjungi" class="contact-section"><div class="container contact-grid"><div><p class="eyebrow">NEXT ROUND?</p><h2>Ajak temanmu.<br>Kita ketemu di sini.</h2><p>{{ $settings['store_name'] ?? 'TambahBang Rental PS' }}</p></div><div class="contact-details">
        @if (!empty($settings['store_address']))<p class="eyebrow">ALAMAT</p><p class="address">{{ $settings['store_address'] }}</p>@endif
        @if ($contactUrl)<p class="eyebrow">KONTAK TOKO</p><p>{{ $settings['store_phone'] }}</p><a class="button button-light neo-shadow btn-press" href="{{ $contactUrl }}">Hubungi via WhatsApp <span aria-hidden="true">↗</span></a>
        @else<p>Silakan temui kasir di lokasi untuk informasi unit, jadwal, dan pemesanan.</p><p class="contact-note">Kontak online belum tersedia.</p>@endif
    </div></div></section>
</main>
<footer class="container footer"><a class="footer-brand" href="{{ route('home') }}">{{ $settings['store_name'] ?? 'TambahBang' }}</a><p>© {{ date('Y') }}  BesokAja Team · Waktunya main bareng.</p></footer>
<script src="{{ asset('js/landing.js') }}" defer></script>
</body>
</html>
