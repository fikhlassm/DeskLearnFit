/**
 * Bootstrap entry — load Axios with sane defaults + CSRF + JSON header.
 * App-wide helpers will be registered here as we add modules (auth.js, etc.).
 */

import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

/**
 * Tiny global helper used by server-side flash banners and AJAX responses.
 * Usage: window.showFlash('success', 'Profil diperbarui')
 *        window.showFlash('error',   'Gagal menyimpan')
 */
window.showFlash = function (type, message, { timeout = 4000 } = {}) {
    const palette = {
        success: { bg: 'bg-emerald-50', border: 'border-emerald-200', text: 'text-emerald-800' },
        error:   { bg: 'bg-rose-50',    border: 'border-rose-200',    text: 'text-rose-800' },
        warning: { bg: 'bg-amber-50',   border: 'border-amber-200',   text: 'text-amber-800' },
        info:    { bg: 'bg-sky-50',     border: 'border-sky-200',     text: 'text-sky-800' },
    };
    const colors = palette[type] || palette.info;
    const node = document.createElement('div');
    node.setAttribute('role', 'alert');
    node.className = `fixed left-1/2 top-4 z-[1000] -translate-x-1/2 rounded-2xl border ${colors.border} ${colors.bg} ${colors.text} px-4 py-3 shadow-[0_8px_24px_rgba(15,23,42,.08)] max-w-[560px] text-sm leading-relaxed flex items-start gap-3`;
    node.innerHTML = `<span>${message}</span><button type="button" class="ml-2 -mr-1 opacity-70 hover:opacity-100" aria-label="Tutup">&times;</button>`;
    node.querySelector('button').addEventListener('click', () => node.remove());
    document.body.appendChild(node);
    if (timeout > 0) {
        setTimeout(() => node.remove(), timeout);
    }
};

// Auto-dismiss server-rendered flash banner after 5s.
const serverFlash = document.getElementById('flash-banner');
if (serverFlash) {
    setTimeout(() => serverFlash.remove(), 5000);
}
