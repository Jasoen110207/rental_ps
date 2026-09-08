<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login Kasir & Admin — TambahBang Rental PS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Chivo:ital,wght@0,400;0,700;0,900;1,400&family=Space+Grotesk:wght@500;600;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "primary": "#004ac6",
            "primary-container": "#2563eb",
            "on-primary": "#ffffff",
            "secondary": "#9d4300",
            "secondary-container": "#fd761a",
            "on-secondary": "#ffffff",
            "background": "#faf8ff",
            "surface": "#faf8ff",
            "surface-container-lowest": "#ffffff",
            "surface-container-high": "#e2e7ff",
            "on-surface": "#131b2e",
            "on-surface-variant": "#434655",
            "error": "#ba1a1a",
          },
          fontFamily: {
            "body-md": ["Chivo", "sans-serif"],
            "headline-lg": ["Space Grotesk", "sans-serif"],
            "headline-md": ["Space Grotesk", "sans-serif"],
            "headline-sm": ["Space Grotesk", "sans-serif"],
            "label-sm": ["Space Mono", "monospace"],
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
    }
    .neo-shadow {
      box-shadow: 4px 4px 0px #131b2e;
    }
    .neo-shadow-lg {
      box-shadow: 6px 6px 0px #131b2e;
    }
    .btn-press:active {
      transform: translate(3px, 3px);
      box-shadow: 0px 0px 0px #131b2e !important;
    }
  </style>
</head>

<body class="bg-background text-on-surface min-h-screen flex items-center justify-center p-4 font-body-md">
  
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-8 neo-shadow-lg relative">
    
    <!-- Top Decorative Badge -->
    <div class="absolute -top-4 -right-3 px-3 py-1 bg-secondary-container text-on-secondary border-2 border-on-surface font-label-sm text-xs font-bold neo-shadow rotate-3 uppercase">
      ⚡ Control Hub
    </div>

    <!-- Brand Header -->
    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-on-surface">
      <div class="w-12 h-12 bg-primary text-on-primary border-2 border-on-surface flex items-center justify-center neo-shadow">
        <span class="material-symbols-outlined text-3xl">sports_esports</span>
      </div>
      <div>
        <h1 class="text-2xl font-headline-lg font-black uppercase tracking-tight text-on-surface leading-none">TAMBAHBANG</h1>
        <p class="text-xs font-label-sm text-secondary uppercase font-bold tracking-wider mt-1">Rental PlayStation & POS</p>
      </div>
    </div>

    <div class="mb-6">
      <h2 class="text-lg font-headline-md font-bold uppercase text-on-surface">Kasir / Admin Login</h2>
      <p class="text-xs text-on-surface-variant font-medium mt-1">Masuk untuk mengelola operasional rental dan billing.</p>
    </div>

    <!-- Errors -->
    @if ($errors->any())
      <div class="p-3 bg-red-100 border-2 border-on-surface text-red-950 font-headline-sm text-xs neo-shadow mb-5">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-base text-error">error</span>
          <span>{{ $errors->first() }}</span>
        </div>
      </div>
    @endif

    @if (session('success'))
      <div class="p-3 bg-emerald-100 border-2 border-on-surface text-emerald-950 font-headline-sm text-xs neo-shadow mb-5">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-base text-emerald-700">check_circle</span>
          <span>{{ session('success') }}</span>
        </div>
      </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('login.post') }}" class="flex flex-col gap-4">
      @csrf

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1.5 text-on-surface">
          Email / Username
        </label>
        <div class="relative">
          <input type="text" name="email" id="email" required autofocus value="{{ old('email', 'kasir@rental.com') }}" class="w-full px-3.5 py-2.5 bg-surface border-2 border-on-surface font-body-md text-sm text-on-surface neo-shadow focus:outline-none focus:ring-0 focus:bg-white" placeholder="kasir@rental.com">
          <span class="material-symbols-outlined absolute right-3 top-2.5 text-on-surface-variant">person</span>
        </div>
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1.5 text-on-surface">
          Password
        </label>
        <div class="relative">
          <input type="password" name="password" id="password" required value="password" class="w-full px-3.5 py-2.5 bg-surface border-2 border-on-surface font-body-md text-sm text-on-surface neo-shadow focus:outline-none focus:ring-0 focus:bg-white" placeholder="••••••••">
          <button type="button" onclick="togglePassword()" class="absolute right-3 top-2.5 text-on-surface-variant hover:text-on-surface">
            <span class="material-symbols-outlined" id="eye-icon">visibility</span>
          </button>
        </div>
      </div>

      <div class="flex items-center justify-between text-xs font-headline-sm my-1">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="remember" class="w-4 h-4 rounded-none border-2 border-on-surface text-primary focus:ring-0">
          <span class="font-bold">Ingat Saya</span>
        </label>
        <span class="text-on-surface-variant font-label-sm text-[10px]">v1.0 Production</span>
      </div>

      <button type="submit" class="w-full py-3 bg-primary text-on-primary font-headline-lg font-black text-sm uppercase tracking-wider border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container transition-all flex items-center justify-center gap-2 mt-2">
        <span>MASUK KE SISTEM</span>
        <span class="material-symbols-outlined text-lg">arrow_forward</span>
      </button>
    </form>

    <!-- Quick Demo Logins -->
    <div class="mt-6 pt-4 border-t-2 border-on-surface">
      <p class="text-[10px] font-label-sm uppercase font-bold text-on-surface-variant mb-2 text-center">Akun Bawaan (Klik Cepat):</p>
      <div class="grid grid-cols-2 gap-2">
        <button type="button" onclick="setCredentials('kasir@rental.com', 'password')" class="px-2 py-1.5 bg-surface-container-high border-2 border-on-surface text-xs font-headline-sm font-bold neo-shadow-sm btn-press hover:bg-primary-fixed">
          🔑 Kasir 1
        </button>
        <button type="button" onclick="setCredentials('admin@rental.com', 'password')" class="px-2 py-1.5 bg-surface-container-high border-2 border-on-surface text-xs font-headline-sm font-bold neo-shadow-sm btn-press hover:bg-primary-fixed">
          🛡️ Admin
        </button>
      </div>
    </div>

  </div>

  <script>
    function togglePassword() {
      const pass = document.getElementById('password');
      const icon = document.getElementById('eye-icon');
      if (pass.type === 'password') {
        pass.type = 'text';
        icon.innerText = 'visibility_off';
      } else {
        pass.type = 'password';
        icon.innerText = 'visibility';
      }
    }

    function setCredentials(email, pass) {
      document.getElementById('email').value = email;
      document.getElementById('password').value = pass;
    }
  </script>
</body>
</html>
