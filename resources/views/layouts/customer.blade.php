<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>TambahBang Hub - Unit PS 03 Customer Console</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;500;600;700;900&amp;family=Space+Grotesk:wght@500;600;700;800&amp;family=Space+Mono:wght@400;700&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-fixed-dim": "#b4c5ff",
                        "surface-container-high": "#e2e7ff",
                        "outline": "#737686",
                        "tertiary-fixed-dim": "#4edea3",
                        "primary": "#004ac6",
                        "inverse-primary": "#b4c5ff",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-surface-variant": "#434655",
                        "on-secondary-fixed": "#341100",
                        "on-secondary-container": "#5c2400",
                        "on-error-container": "#93000a",
                        "on-background": "#131b2e",
                        "on-tertiary-fixed": "#002113",
                        "background": "#faf8ff",
                        "surface-container-low": "#f2f3ff",
                        "secondary": "#9d4300",
                        "tertiary-container": "#007d55",
                        "tertiary": "#006242",
                        "inverse-on-surface": "#eef0ff",
                        "on-tertiary": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-tertiary-container": "#bdffdb",
                        "on-primary-fixed-variant": "#003ea8",
                        "surface-variant": "#dae2fd",
                        "primary-container": "#2563eb",
                        "secondary-fixed-dim": "#ffb690",
                        "on-error": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-container": "#eeefff",
                        "surface-bright": "#faf8ff",
                        "secondary-fixed": "#ffdbca",
                        "inverse-surface": "#283044",
                        "error": "#ba1a1a",
                        "surface-dim": "#d2d9f4",
                        "on-primary-fixed": "#00174b",
                        "surface-container": "#eaedff",
                        "tertiary-fixed": "#6ffbbe",
                        "surface": "#faf8ff",
                        "on-secondary-fixed-variant": "#783200",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c3c6d7",
                        "surface-container-highest": "#dae2fd",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#0053db",
                        "on-surface": "#131b2e",
                        "secondary-container": "#fd761a",
                        "primary-fixed": "#dbe1ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "body-md": ["Chivo", "sans-serif"],
                        "label-lg": ["Space Mono", "monospace"],
                        "headline-md": ["Space Grotesk", "sans-serif"],
                        "headline-sm": ["Space Grotesk", "sans-serif"],
                        "headline-lg": ["Space Grotesk", "sans-serif"],
                        "label-md": ["Space Mono", "monospace"],
                        "timer-display": ["Space Mono", "monospace"],
                        "label-sm": ["Space Mono", "monospace"],
                        "body-sm": ["Chivo", "sans-serif"]
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
            line-height: 1;
        }

        .neo-border-3 {
            border: 3px solid #0F172A;
        }

        .neo-border-2 {
            border: 2px solid #0F172A;
        }

        .neo-shadow-sm {
            box-shadow: 2.5px 2.5px 0px #0F172A;
        }

        .neo-shadow-md {
            box-shadow: 4px 4px 0px #0F172A;
        }

        .neo-btn {
            transition: transform 0.08s ease, box-shadow 0.08s ease;
            touch-action: manipulation;
        }

        .neo-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px #0F172A;
        }

        @keyframes pulse-dot-anim {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.35);
                opacity: 0.55;
            }
        }

        .pulse-dot {
            animation: pulse-dot-anim 1.8s infinite ease-in-out;
        }
    </style>
</head>

<body
    class="bg-[#F0F2FA] text-on-surface font-body-md min-h-screen antialiased flex justify-center selection:bg-secondary-container selection:text-surface-container-lowest">
    @yield('content')


    <script>
        let totalSeconds = (1 * 3600) + (24 * 60) + 28;
        const timerElement = document.getElementById('countdown-timer');

        function updateTimer() {
            if (totalSeconds <= 0) {
                timerElement.textContent = "00:00:00";
                timerElement.classList.add("text-error");
                return;
            }
            totalSeconds--;
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            const formatted =
                String(hours).padStart(2, '0') + ":" +
                String(minutes).padStart(2, '0') + ":" +
                String(seconds).padStart(2, '0');

            timerElement.textContent = formatted;
        }

        setInterval(updateTimer, 1000);

        function handleAction(type) {
            if (type === 'extend') {
                document.getElementById('modal-extend').classList.remove('hidden');
                document.getElementById('modal-extend').classList.add('flex');
            } else if (type === 'menu') {
                showToast("Membuka katalog F&B lengkap...");
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function submitExtend(duration, price) {
            closeModal('modal-extend');
            showToast(`Request ${duration} (Rp ${price.toLocaleString('id-ID')}) dikirim ke kasir!`);
        }

        function orderQuick(item, price) {
            showToast(`Pesanan 1x ${item} berhasil dikirim!`);
        }

        function callCashier() {
            showToast("Buzzer Meja PS 03 berbunyi di kasir. Mohon tunggu!");
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const msg = document.getElementById('toast-msg');
            msg.textContent = message;
            toast.classList.remove('hidden');
            toast.classList.add('flex');
            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 3000);
        }
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>TambahBang Hub - Unit PS 03 Customer Console</title>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;500;600;700;900&amp;family=Space+Grotesk:wght@500;600;700;800&amp;family=Space+Mono:wght@400;700&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-fixed-dim": "#b4c5ff",
                        "surface-container-high": "#e2e7ff",
                        "outline": "#737686",
                        "tertiary-fixed-dim": "#4edea3",
                        "primary": "#004ac6",
                        "inverse-primary": "#b4c5ff",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-surface-variant": "#434655",
                        "on-secondary-fixed": "#341100",
                        "on-secondary-container": "#5c2400",
                        "on-error-container": "#93000a",
                        "on-background": "#131b2e",
                        "on-tertiary-fixed": "#002113",
                        "background": "#faf8ff",
                        "surface-container-low": "#f2f3ff",
                        "secondary": "#9d4300",
                        "tertiary-container": "#007d55",
                        "tertiary": "#006242",
                        "inverse-on-surface": "#eef0ff",
                        "on-tertiary": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-tertiary-container": "#bdffdb",
                        "on-primary-fixed-variant": "#003ea8",
                        "surface-variant": "#dae2fd",
                        "primary-container": "#2563eb",
                        "secondary-fixed-dim": "#ffb690",
                        "on-error": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-container": "#eeefff",
                        "surface-bright": "#faf8ff",
                        "secondary-fixed": "#ffdbca",
                        "inverse-surface": "#283044",
                        "error": "#ba1a1a",
                        "surface-dim": "#d2d9f4",
                        "on-primary-fixed": "#00174b",
                        "surface-container": "#eaedff",
                        "tertiary-fixed": "#6ffbbe",
                        "surface": "#faf8ff",
                        "on-secondary-fixed-variant": "#783200",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c3c6d7",
                        "surface-container-highest": "#dae2fd",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#0053db",
                        "on-surface": "#131b2e",
                        "secondary-container": "#fd761a",
                        "primary-fixed": "#dbe1ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "body-md": ["Chivo", "sans-serif"],
                        "label-lg": ["Space Mono", "monospace"],
                        "headline-md": ["Space Grotesk", "sans-serif"],
                        "headline-sm": ["Space Grotesk", "sans-serif"],
                        "headline-lg": ["Space Grotesk", "sans-serif"],
                        "label-md": ["Space Mono", "monospace"],
                        "timer-display": ["Space Mono", "monospace"],
                        "label-sm": ["Space Mono", "monospace"],
                        "body-sm": ["Chivo", "sans-serif"]
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
            line-height: 1;
        }

        .neo-border-3 {
            border: 3px solid #0F172A;
        }

        .neo-border-2 {
            border: 2px solid #0F172A;
        }

        .neo-shadow-sm {
            box-shadow: 2.5px 2.5px 0px #0F172A;
        }

        .neo-shadow-md {
            box-shadow: 4px 4px 0px #0F172A;
        }

        .neo-btn {
            transition: transform 0.08s ease, box-shadow 0.08s ease;
            touch-action: manipulation;
        }

        .neo-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px #0F172A;
        }

        @keyframes pulse-dot-anim {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.35);
                opacity: 0.55;
            }
        }

        .pulse-dot {
            animation: pulse-dot-anim 1.8s infinite ease-in-out;
        }
    </style>
</head>

<body
    class="bg-[#F0F2FA] text-on-surface font-body-md min-h-screen antialiased flex justify-center selection:bg-secondary-container selection:text-surface-container-lowest">
    @yield('content')


    <script>
        function updateTimer() {
            if (totalSeconds <= 0) {
                timerElement.textContent = "00:00:00";
                timerElement.classList.add("text-error");
                return;
            }
            totalSeconds--;
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            const formatted =
                String(hours).padStart(2, '0') + ":" +
                String(minutes).padStart(2, '0') + ":" +
                String(seconds).padStart(2, '0');

            timerElement.textContent = formatted;
        }

        setInterval(updateTimer, 1000);

        function handleAction(type) {
            if (type === 'extend') {
                document.getElementById('modal-extend').classList.remove('hidden');
                document.getElementById('modal-extend').classList.add('flex');
            } else if (type === 'menu') {
                showToast("Membuka katalog F&B lengkap...");
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function submitExtend(duration, price) {
            closeModal('modal-extend');
            showToast(`Request ${duration} (Rp ${price.toLocaleString('id-ID')}) dikirim ke kasir!`);
        }

        function orderQuick(item, price) {
            showToast(`Pesanan 1x ${item} berhasil dikirim!`);
        }

        function callCashier() {
            showToast("Buzzer Meja PS 03 berbunyi di kasir. Mohon tunggu!");
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const msg = document.getElementById('toast-msg');
            msg.textContent = message;
            toast.classList.remove('hidden');
            toast.classList.add('flex');
            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 3000);
        }
    </script>
</body>

</html>
