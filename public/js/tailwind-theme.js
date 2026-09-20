/* ============================================================
 * TambahBang — Tailwind CDN theme (external)
 * Dipakai oleh: layouts admin/kasir/customer + login
 * Cara pakai: muat SETELAH <script src="https://cdn.tailwindcss.com...">
 *   <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
 *   <script src="{{ asset('js/tailwind-theme.js') }}"></script>
 * Gabungan terlengkap dari semua layout agar kelas warna &
 * font tetap tersedia di semua halaman.
 * ============================================================ */

tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "primary": "#004ac6",
        "primary-container": "#2563eb",
        "on-primary": "#ffffff",
        "on-primary-container": "#eeefff",
        "primary-fixed": "#dbe1ff",
        "secondary": "#9d4300",
        "secondary-container": "#fd761a",
        "on-secondary": "#ffffff",
        "on-secondary-container": "#5c2400",
        "secondary-fixed": "#ffdbca",
        "tertiary": "#006242",
        "tertiary-container": "#007d55",
        "on-tertiary": "#ffffff",
        "tertiary-fixed": "#6ffbbe",
        "on-tertiary-fixed": "#002113",
        "error": "#ba1a1a",
        "error-container": "#ffdad6",
        "background": "#faf8ff",
        "surface": "#faf8ff",
        "surface-dim": "#d2d9f4",
        "surface-container-lowest": "#ffffff",
        "surface-container-low": "#f2f3ff",
        "surface-container": "#eaedff",
        "surface-container-high": "#e2e7ff",
        "surface-container-highest": "#dae2fd",
        "on-surface": "#131b2e",
        "on-surface-variant": "#434655",
        "outline": "#737686",
      },
      fontFamily: {
        "body-md": ["Chivo", "sans-serif"],
        "body-sm": ["Chivo", "sans-serif"],
        "headline-lg": ["Space Grotesk", "sans-serif"],
        "headline-md": ["Space Grotesk", "sans-serif"],
        "headline-sm": ["Space Grotesk", "sans-serif"],
        "headline-xl": ["Space Grotesk", "sans-serif"],
        "timer-display": ["Space Mono", "monospace"],
        "label-lg": ["Space Mono", "monospace"],
        "label-md": ["Space Mono", "monospace"],
        "label-sm": ["Space Mono", "monospace"],
      }
    }
  }
};
