# LearnFit — Frontend Integration Plan

> **Stack:** Laravel 12 + Blade + Tailwind CSS + Vite + Vanilla JS (with Axios for AJAX)
> **Backend:** ✅ Sudah lengkap (lihat `sumber.md` & audit report)
> **Target:** Enhanced Blade, dengan SPA-feel untuk Flashcard Review
> **Per Phase:** Halaman/partial + Backend dependencies + Tailwind extraction + JS modules needed

---

## Arsitektur Dasar

```
resources/
├── css/
│   └── app.css                 # Single Tailwind entry, @layer base/components/utilities
├── js/
│   ├── bootstrap.js            # Axios + CSRF + Echo (existing)
│   ├── app.js                  # Sidebar toggle, flash dismiss, IntersectionObserver
│   ├── composables/
│   │   ├── useFlash.js         # Toast/flash helper
│   │   ├── useApi.js           # Axios wrapper with CSRF + JSON Accept
│   │   └── useModal.js         # Bootstrap/Tailwind modal helper
│   ├── modules/
│   │   ├── pomodoro.js         # Timer state + Notification API
│   │   ├── flashcard-review.js # SPA-feel review (flip, keyboard, AJAX)
│   │   ├── materi-upload.js    # File preview + progress + validation
│   │   ├── kelas-modal.js      # Modal edit + AJAX submit
│   │   ├── jurnal-modal.js     # Modal edit + AJAX submit
│   │   └── quiz-chart.js       # Animated bar chart for quiz result
│   └── views/
│       ├── siswa.js
│       ├── pengajar.js
│       ├── auth.js
│       └── public.js
└── views/
    └── (existing — see phase breakdown)
```

**Backend touch points (JSON variants):**
Tambahkan `if ($request->wantsJson()) return response()->json(...)` di:
- `KelasController::edit, update, destroy`
- `MateriController::update, destroy, publish`
- `TugasController::update, destroy, publish`
- `JawabanTugasController::nilai`
- `JurnalBelajarController::update, destroy`
- `SesiBelajarController::complete, destroy, updateCatatan`

---

## Fondasi (Phase 0 — Refactor)

**Tujuan:** Hilangkan duplikasi CSS/JS/sidebar; pasang Tailwind; siapkan Axios.

| Task | File | Output |
|---|---|---|
| Install Tailwind | `package.json`, `tailwind.config.js`, `postcss.config.js` | `npm run dev` works |
| Extract sidebar ke partials | `_sidebar_siswa.blade.php`, `_sidebar_pengajar.blade.php` | Wajib `@include` di semua dashboard view |
| Hapus inline `<style>` & `<script>` duplikat | 12+ blade files | Pindah ke `resources/css/app.css` & `resources/js/app.js` |
| Setup CSRF meta tag | `layouts/app.blade.php` | `<meta name="csrf-token" content="...">` |
| Configure Axios defaults | `resources/js/bootstrap.js` | `X-CSRF-TOKEN`, `Accept: application/json` |
| View Composer untuk metadata | `app/View/Composers/DashboardComposer.php` | `$metodeInfo`, `$methodMap`, `$quizResult` tersedia global di dashboard |
| Helper `flash()` | `app/helpers.php` atau View Composer | Standardized flash rendering |

**Done when:** Halaman dashboard tampil identik dengan Tailwind class names, tidak ada inline `<style>` lagi, sidebar konsisten 100%.

---

## Phase 1 — Halaman Publik (Landing)

**Halaman:**
- `welcome.blade.php` — Landing page
- `about.blade.php` — Tentang
- `contact.blade.php` — Kontak
- `auth/welcome-after.blade.php` — Post-registration landing (CTA: Mulai Quiz / Lihat Dashboard)

**Routes:** `/`, `/tentang`, `/kontak`, `/welcome` (auth)

**Backend:** Tidak ada perubahan.

**Tailwind Components:**
- `navbar` (transparan → solid on scroll, existing JS hook)
- `hero` (gradient + CTA)
- `feature-card` (3 kolom)
- `footer`
- `cta-banner`

**JS Modules:** `public.js` (scroll animation, hamburger)

**Refactor:** Extract inline styles, replace SVG icons dengan Heroicons via `<x-icon>` Blade component.

**Estimasi:** 1.5 hari

---

## Phase 2 — Auth (Login & Register)

**Halaman:**
- `auth/login.blade.php` — GET/POST `/login`
- `auth/register.blade.php` — GET/POST `/register`
- `auth/passwords/email.blade.php` — GET/POST `/lupa-password`
- `auth/passwords/reset.blade.php` — GET/POST `/reset-password`
- `auth/verify.blade.php` — GET `/email/verify`, POST `/email/verification-notification`, GET `/email/verify/{id}/{hash}`

**Backend:** Tidak ada perubahan (sudah lengkap dengan Google OAuth + dev-mode email preview).

**Tailwind Components:**
- `auth-card` (centered, max-w-md, glass effect)
- `form-input` (label + input + error)
- `password-toggle` (show/hide)
- `social-button` (Google Sign-In)
- `alert-error` / `alert-success` (inline, dismissible)
- `role-radio` (Siswa / Pengajar selector)

**JS Modules:** `auth.js`
- Toggle role → update form fields
- Password show/hide
- Real-time email format validation
- Real-time password strength meter (min 8, mixed char)

**Estimasi:** 1.5 hari

---

## Phase 3 — Siswa: Dashboard, Quiz, Profil

### 3A. Dashboard Siswa
- **Halaman:** `dashboard/siswa.blade.php`
- **Route:** `GET /dashboard/siswa`
- **Data:** `DashboardController::siswa` (lihat audit)
- **Tailwind:** `stat-card`, `tugas-pending-list`, `quiz-banner`, `method-card`
- **JS:** Sidebar toggle, search box (filter tugas/notebook client-side), FAB → `/dashboard/sesi-belajar?metode=pomodoro`, notification icon (terhubung ke `tugasBelumKumpul` badge real-time via `route('siswa.kelas.index')`)

### 3B. Quiz Gaya Belajar
- **Halaman:** `quiz/quiz.blade.php`, `quiz/result.blade.php`
- **Routes:** `GET/POST /quiz`, `GET /quiz/hasil`, `GET /quiz/ulang`
- **Data:** `QuizController` (7 soal, scoreMap sudah final)
- **Tailwind:** `quiz-progress` (1/7 … 7/7), `quiz-option` (radio-card), `quiz-result-card` (winner), `score-bar` (animated)
- **JS:** `quiz-chart.js` — render horizontal bar chart 4 metode pakai SVG/CSS, animate on load
- **Refactor:** Render 1 soal per screen dengan tombol Next/Prev, simpan state di JS, submit sekali di akhir (UX lebih baik)

### 3C. Profil
- **Halaman:** `dashboard/profil.blade.php`
- **Routes:** `GET/PUT /dashboard/profil`
- **Tailwind:** `profile-form`, `info-row`
- **JS:** Auto-save indicator, no_hp formatter

**Estimasi Phase 3:** 2.5 hari

---

## Phase 4 — Siswa: Notebook Multi-Metode (CORE FEATURE)

### 4A. Sesi Belajar (entry point)
- **Halaman:** `dashboard/sesi-belajar.blade.php`
- **Routes:** `GET/POST /dashboard/sesi-belajar`, `PATCH .../{sesi}/complete`, `DELETE .../{sesi}`
- **Backend:** `SesiBelajarController` (sudah ada)
- **Tailwind:** `metode-picker` (4-card radio), `form-card`, `tool-card` (kontainer dinamis)
- **JS:** Dynamic form (show timer fields hanya jika Pomodoro); AJAX submit untuk `complete` & `destroy` (optimistic update)

### 4B. Partial: Tool Pomodoro
- **Partial:** `dashboard/partials/tool-pomodoro.blade.php`
- **Route:** (no new route — state in JS, sync via `PATCH .../catatan` & `PATCH .../complete`)
- **JS Module:** `pomodoro.js`
  - `class PomodoroTimer { start(), pause(), reset(), getState() }`
  - State: `totalSeconds`, `remaining`, `running`, `cycleIndex`, `phase` ('focus'|'break')
  - Notification API + Audio cue (bell.mp3)
  - Visibility API — pause saat tab hidden
  - LocalStorage backup (resume after refresh)
  - PATCH catatan debounced 1500ms

### 4C. Partial: Tool Flashcard
- **Partial:** `dashboard/partials/tool-flashcard.blade.php`
- **Routes:** `POST .../{sesi}/flashcards`, `PUT /dashboard/flashcards/{flashcard}`, `DELETE ...`
- **Backend:** `FlashcardController` (sudah ada)
- **Tailwind:** `flashcard-form`, `flashcard-deck` (collapsible)
- **JS:** Inline edit (modal/toggle textarea), inline delete dengan konfirmasi

### 4D. Partial: Tool Notebook (Feynman/Blurting)
- **Partial:** `dashboard/partials/tool-notebook.blade.php`
- **Routes:** `POST .../{sesi}/notebook`, `DELETE /dashboard/notebook/{entri}`
- **Backend:** `NotebookController` + `HeuristicAnalyzer`
- **Tailwind:** `notebook-form`, `entri-list`, `analisis-card`, `score-badge`, `keyword-chip`
- **JS:** Word counter, optional live preview of analyzer (call backend via Axios, debounced 800ms), optimistically show "Menganalisis..." state

### 4E. Halaman Flashcard Review (SPA-feel)
- **Halaman:** `dashboard/flashcard-review.blade.php`
- **Routes:** `GET .../review`, `POST .../review/answer`, `GET .../review/stats` (JSON)
- **JS Module:** `flashcard-review.js` — **CORE DELIVERABLE**
  - State: `cards[]`, `currentIndex`, `flipped`, `stats{}`
  - Render: 1 card full-viewport, flip on click/Space, jawaban tersembunyi sampai flip
  - Keyboard: `Space` (flip), `→` (next, jika sudah dijawab), `←` (prev), `1` (salah), `2` (benar)
  - AJAX submit jawaban → update `stats` dari `/review/stats` → re-render
  - Progress bar: `currentIndex / cards.length`
  - End screen: akurasi total, daftar kartu dengan badge benar/salah
  - Animations: Tailwind `transition-transform`, `rotate-y-180` untuk flip

### 4F. Notebook Index
- **Halaman:** `dashboard/notebook-index.blade.php`
- **Route:** `GET /dashboard/notebook`
- **Data:** `SesiBelajar` grouped by `metode`
- **Tailwind:** `notebook-group` (collapsible section per metode)
- **JS:** Filter by metode, sort by tanggal

### 4G. Catatan Belajar (Jurnal)
- **Halaman:** `dashboard/catatan-belajar.blade.php`
- **Routes:** `GET/POST /dashboard/catatan-belajar`, `GET/PUT/DELETE .../{jurnal}`
- **Backend:** `JurnalBelajarController` (sudah ada, `edit` returns JSON)
- **Tailwind:** `jurnal-list`, `jurnal-card`, `filter-tabs`
- **JS Module:** `jurnal-modal.js`
  - Modal edit: fetch JSON dari `GET .../{jurnal}/edit`, populate form, submit via Axios
  - Filter by `metode_yang_digunakan` (link-based, existing)
  - Rating stars (1-5)

**Estimasi Phase 4:** 4 hari

---

## Phase 5 — Siswa: Kelas & Tugas

### 5A. Kelas yang Diikuti
- **Halaman:** `dashboard/kelas-diikuti.blade.php`
- **Routes:** `GET /dashboard/kelas-diikuti`, `POST .../join`, `DELETE .../{kelas}/leave`
- **Tailwind:** `kelas-grid` (card per kelas), `join-form` (input kode + button)
- **JS:** Copy kode kelas ke clipboard, leave confirmation

### 5B. Materi (Siswa view)
- **Halaman:** `dashboard/materi/siswa-index.blade.php`, `dashboard/materi/siswa-show.blade.php`
- **Routes:** `GET /dashboard/siswa/kelas/{kelas}/materi`, `GET .../materi/{materi}`, `GET /dashboard/materi/{materi}/download`
- **Tailwind:** `materi-list`, `materi-card` (icon by tipe: teks/link/file), `link-preview`
- **JS:** File preview untuk `tipe=file` (icon by extension), link unfur untuk `tipe=link`

### 5C. Tugas (Siswa view)
- **Halaman:** `dashboard/tugas/siswa-index.blade.php`, `dashboard/tugas/siswa-show.blade.php`
- **Routes:** `GET /dashboard/siswa/kelas/{kelas}/tugas`, `GET .../tugas/{tugas}`, `POST .../tugas/{tugas}/jawaban`, `PUT .../jawaban-tugas/{jawaban}`
- **Tailwind:** `tugas-list`, `tugas-card` (deadline countdown, status badge), `jawaban-form`
- **JS:** Real-time countdown ke deadline, status badge (terkumpul/dinilai/terlambat), word counter untuk jawaban

**Estimasi Phase 5:** 1.5 hari

---

## Phase 6 — Pengajar: Dashboard & Manajemen Kelas

### 6A. Dashboard Pengajar
- **Halaman:** `dashboard/pengajar.blade.php`
- **Route:** `GET /dashboard/pengajar`
- **Data:** `DashboardController::pengajar`
- **Tailwind:** Sama dengan siswa dashboard, beda konten (stat cards: kelas, siswa, materi, tugas, belum-dinilai)
- **JS:** Same sidebar/navigation, no extra

### 6B. Manajemen Kelas
- **Halaman:** `dashboard/kelas-saya.blade.php`
- **Routes:** `GET /dashboard/kelas`, `POST /dashboard/kelas`, `GET/PUT/DELETE .../{kelas}` (`edit` returns JSON)
- **Backend:** `KelasController` (sudah ada)
- **Tailwind:** `kelas-grid` (admin view), `kelas-card`, `kelas-form-modal` (Tailwind dialog, bukan Bootstrap)
- **JS Module:** `kelas-modal.js`
  - Open modal: Tambah (form kosong) / Edit (fetch JSON dari `/dashboard/kelas/{kelas}`, populate)
  - Submit via Axios, optimistic update atau full reload
  - Delete dengan double-confirm

### 6C. Peserta Kelas
- **Halaman:** `dashboard/peserta-kelas.blade.php`
- **Route:** `GET /dashboard/kelas/{kelas}/peserta`
- **Tailwind:** `peserta-table`
- **JS:** Sort by nama/tanggal join, search

**Estimasi Phase 6:** 2 hari

---

## Phase 7 — Pengajar: Materi

### 7A. Daftar Materi
- **Halaman:** `dashboard/materi/index.blade.php`
- **Routes:** `GET /dashboard/kelas/{kelas}/materi`, `POST /dashboard/kelas/{kelas}/materi`, `PATCH /dashboard/materi/{materi}/publish`, `DELETE /dashboard/materi/{materi}`
- **Tailwind:** `materi-list` (admin view), `upload-form`, `tipe-selector` (tabs: teks|link|file)
- **JS Module:** `materi-upload.js`
  - Tab switcher untuk `tipe` (tampilkan field sesuai)
  - File preview (image thumbnail, PDF icon, doc icon, video icon — **fix plan gap: tambah mimes video**)
  - Client-side validation: size ≤ 10 MB, extension whitelist
  - Upload progress bar (XHR via Axios)
  - Validation feedback real-time

### 7B. Edit Materi
- **Halaman:** `dashboard/materi/edit.blade.php`
- **Routes:** `GET/PUT /dashboard/materi/{materi}/edit`
- **Tailwind:** Sama dengan index, form pre-filled
- **JS:** Reuse `materi-upload.js`

**Backend touch:** Fix gap — tambahkan `mp4,mov,avi,webm` ke mimes di `MateriController` (lihat audit plan gap #1).

**Estimasi Phase 7:** 2 hari

---

## Phase 8 — Pengajar: Tugas & Penilaian

### 8A. Daftar Tugas
- **Halaman:** `dashboard/tugas/index.blade.php`
- **Routes:** `GET/POST /dashboard/kelas/{kelas}/tugas`, `PATCH /dashboard/tugas/{tugas}/publish`, `DELETE /dashboard/tugas/{tugas}`
- **Tailwind:** `tugas-list` (admin), `tugas-form-modal`
- **JS:** Modal CRUD (sama pola dengan kelas-modal.js)

### 8B. Edit Tugas
- **Halaman:** `dashboard/tugas/edit.blade.php`
- **Routes:** `GET/PUT /dashboard/tugas/{tugas}/edit`
- **Tailwind:** Reuse form
- **JS:** Inline form

### 8C. Penilaian Jawaban
- **Halaman:** `dashboard/tugas/jawaban-index.blade.php`
- **Routes:** `GET /dashboard/tugas/{tugas}/jawaban`, `PUT /dashboard/jawaban-tugas/{jawaban}/nilai`
- **Tailwind:** `jawaban-table`, `nilai-form` (inline), `nilai-badge`
- **JS Module:** `grading.js`
  - Inline edit nilai (klik cell → form input)
  - Submit via Axios
  - Update badge (belum-dinilai → dinilai) tanpa reload
  - Sort by status (tampilkan belum-dinilai di atas)

**Estimasi Phase 8:** 1.5 hari

---

## Phase 9 — Pengajar: Lihat Profil Siswa

### 9A. Daftar Siswa
- **Halaman:** `dashboard/pengajar/siswa-index.blade.php`
- **Route:** `GET /dashboard/daftar-siswa` (supports `?search=`)
- **Tailwind:** `siswa-table` atau `siswa-grid`
- **JS:** Debounced search box (300ms), pagination links

### 9B. Detail Profil Siswa
- **Halaman:** `dashboard/pengajar/siswa-show.blade.php`
- **Route:** `GET /dashboard/daftar-siswa/{siswa}`
- **Data:** Quiz result, sesiByMetode, totalDurasi, totalJurnal
- **Tailwind:** `profile-detail`, `metode-stats-card`
- **JS:** Chart sesi per metode (mini bar chart)

**Estimasi Phase 9:** 1 hari

---

## Cross-Cutting Concerns

### 1. Konsistensi Sidebar
- `resources/views/dashboard/_sidebar_siswa.blade.php` ← WAJIB `@include` di: siswa, catatan-belajar, sesi-belajar, notebook-index, kelas-diikuti, materi/siswa-*, tugas/siswa-*, flashcard-review
- `resources/views/dashboard/_sidebar_pengajar.blade.php` ← WAJIB `@include` di: pengajar, kelas-saya, peserta-kelas, materi/*, tugas/*, pengajar/siswa-*

### 2. View Composer Global
```php
// app/View/Composers/DashboardComposer.php
class DashboardComposer {
    public function compose(View $view) {
        $view->with('metodeInfo', [...])
             ->with('methodMap', [...])
             ->with('flashTypes', ['success', 'error', 'warning', 'info']);
    }
}
```

### 3. Flash Message Standard
- Server: `with('success', '...')` / `with('error', '...')`
- AJAX response: `{ flash: { type: 'success', message: '...' } }`
- Client: `window.showFlash(type, message)` di `app.js`

### 4. Form Validation Pattern
- Backend: Form Request classes (sudah dipakai sebagian) atau inline validation
- Client: Real-time validation per field (email, min length, regex)
- Error display: `@error('field')` di bawah input, Tailwind `text-red-600 text-xs`

### 5. A11y (Accessibility)
- Semantic HTML (`<main>`, `<nav>`, `<section>`)
- ARIA labels pada icon-only buttons
- Focus management pada modal (trap focus, ESC to close)
- Color contrast WCAG AA

### 6. Performance
- Tailwind JIT → minimal CSS bundle
- Lazy-load images (materi previews)
- Vite chunking untuk `flashcard-review.js` (SPA-feel, dimuat hanya di halaman review)
- Pagination di semua list view (sudah dipakai)

---

## Tabel Ringkasan

| Phase | Halaman | Backend Touch | Tailwind Effort | JS Modules | Estimasi |
|---|---|---|---|---|---|
| 0 | Refactor fondasi | Setup CSRF meta | Install + config | `app.js` consolidated | 1.5 hari |
| 1 | Landing, About, Contact, Welcome-after | None | `navbar`, `hero`, `feature-card` | `public.js` | 1.5 hari |
| 2 | Auth (Login, Register, Forgot/Reset, Verify) | None | `auth-card`, `form-input` | `auth.js` | 1.5 hari |
| 3 | Dashboard Siswa, Quiz, Profil | None | `stat-card`, `quiz-card`, `score-bar` | `quiz-chart.js` | 2.5 hari |
| 4 | Notebook multi-metode + Flashcard Review | Optional JSON variants | `metode-picker`, `flashcard-flip`, `tool-card` | `pomodoro.js`, `flashcard-review.js`, `notebook-live.js`, `jurnal-modal.js` | 4 hari |
| 5 | Siswa Kelas + Materi + Tugas | None | `kelas-grid`, `materi-card` | Inline JS | 1.5 hari |
| 6 | Pengajar Dashboard + Kelas | JSON for kelas CRUD | `kelas-card`, `kelas-form-modal` | `kelas-modal.js` | 2 hari |
| 7 | Pengajar Materi | **Fix video mimes** | `upload-form`, `tipe-tabs` | `materi-upload.js` | 2 hari |
| 8 | Pengajar Tugas + Penilaian | JSON for nilai | `tugas-list`, `nilai-form` | `grading.js` | 1.5 hari |
| 9 | Pengajar Profil Siswa | None | `siswa-grid`, `metode-stats` | Mini chart | 1 hari |
| 10 | Test pass + bug fix | None | — | — | 1 hari |
| | | | | **TOTAL** | **~19 hari** |

---

## Definition of Done (per phase)

- [ ] Tailwind classes (no inline `<style>`)
- [ ] `@include` sidebar partial (no duplicated HTML)
- [ ] Mobile-responsive (tested at 360px, 768px, 1280px)
- [ ] Empty state, loading state, error state each designed
- [ ] Form validation: client-side real-time + server-side redirect
- [ ] Flash message rendered consistently
- [ ] Authorization respected (caller verified against role middleware)
- [ ] Browser tested: Chrome, Firefox, Safari
- [ ] Keyboard navigation works (tab order, focus visible, ESC closes modals)
- [ ] All existing PHPUnit feature tests still pass
- [ ] No new console errors / 404s in browser log

---

## Recommended Iteration Order

User sudah memilih: **Foundation (Phase 0) + Phase 4 (Notebook Multi-Metode) lebih dulu.**

Urutan eksekusi:
1. **Phase 0** — Fondasi (Tailwind, refactor sidebar, Axios setup)
2. **Phase 4A–4G** — Notebook multi-metode penuh (Pomodoro + Flashcard Review + Notebook + Jurnal)
3. **Phase 1** — Landing (least complex, builds confidence)
4. **Phase 2** — Auth
5. **Phase 3** — Dashboard Siswa + Quiz + Profil
6. **Phase 5** — Siswa Kelas/Tugas
7. **Phase 6** — Pengajar Dashboard + Kelas
8. **Phase 7** — Pengajar Materi
9. **Phase 8** — Pengajar Tugas + Penilaian
10. **Phase 9** — Pengajar Profil Siswa
11. **Phase 10** — Final test pass
