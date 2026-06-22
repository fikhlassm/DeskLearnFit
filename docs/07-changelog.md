# LearnFit — Changelog

## v0.3.0 — 18 Juni 2026

### Fitur Baru
- **Sesi Belajar — Per-Method Tools**: Setiap metode belajar sekarang punya tool yang berbeda
  - **Pomodoro** → Timer fokus + istirahat client-side (vanilla JS, no package)
  - **Active Recall** → Deck kartu flash (Q/A) per sesi, CRUD lengkap
  - **Blurting** → Notebook dengan textarea + analisis heuristik (skor 0-100, kata kunci cocok)
  - **Feynman** → Notebook dengan bonus marker penjelasan (`karena`, `misalnya`, `jadi`, `artinya`)
- **HeuristicAnalyzer Service** (`app/Services/HeuristicAnalyzer.php`)
  - Tokenisasi judul topik + stopwords Indonesia
  - Skor berbasis pencocokan kata kunci + panjang tulisan + marker
  - Tanpa dependency LLM API (deterministic, gratis)

### Perubahan
- `SesiBelajar::store()` — validasi kondisional: timer field hanya wajib untuk metode Pomodoro
- `sesi.index` view — redesigned dengan layout 2-kolom (form kiri + tool kanan)
- Metode dropdown — auto-toggle field timer & update hint deskripsi metode
- `sesi.store` dan `sesi.destroy` redirect — tambah query `?metode=...` untuk preserve state

### File Baru

#### App
- `app/Models/Flashcard.php`
- `app/Models/EntriNotebook.php`
- `app/Http/Controllers/FlashcardController.php`
- `app/Http/Controllers/NotebookController.php`
- `app/Services/HeuristicAnalyzer.php`

#### Database
- `database/migrations/2026_06_18_000001_create_flashcards_table.php`
- `database/migrations/2026_06_18_000002_create_entri_notebook_table.php`
- `database/factories/FlashcardFactory.php`
- `database/factories/EntriNotebookFactory.php`

#### Views
- `resources/views/dashboard/partials/tool-pomodoro.blade.php`
- `resources/views/dashboard/partials/tool-flashcard.blade.php`
- `resources/views/dashboard/partials/tool-notebook.blade.php`

#### Tests
- `tests/Feature/HeuristicAnalyzerTest.php` (8 test)
- `tests/Feature/FlashcardControllerTest.php` (10 test)
- `tests/Feature/NotebookControllerTest.php` (10 test)

## v0.2.0 — 17 Juni 2026

### Fitur Baru
- **Catatan Belajar** — CRUD catatan/jurnal belajar siswa dengan filter metode, rating bintang, badge metode
- **Sesi Belajar / Pomodoro** — Timer pomodoro client-side (Vanilla JS), riwayat sesi, rekomendasi berdasarkan quiz
- **Halaman Profil** — Edit nama, email, bio, tujuan belajar, jenjang, no HP untuk siswa & pengajar
- **Role Middleware** — Akses berbasis role: siswa & pengajar dipisah dengan tegas

### Perubahan
- `routes/web.php` — Restrukturisasi total dengan role middleware groups
- `QuizController.php` — Validasi 7 soal wajib dijawab, opsi harus valid, private properties untuk scoreMap
- `KelasController.php` — Ganti `$request->all()` dengan `$validated`, route model binding
- `User.php` — Fillable diperluas, casts diperbaiki, relationships ditambah, helper methods `isSiswa()`/`isPengajar()`
- `Kelas.php` — Tambah `HasFactory`
- `UserFactory.php` — Tambah states: `siswa()`, `siswaWithQuiz()`, `pengajar()`
- `dashboard/siswa.blade.php` — Semua link sidebar aktif, tambah logout button
- `dashboard/pengajar.blade.php` — Link Profil aktif
- `dashboard/kelas-saya.blade.php` — Link Profil aktif
- `app/Http/Kernel.php` — Tambah alias `'role'`

### File Baru

#### App
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Models/JurnalBelajar.php`
- `app/Models/SesiBelajar.php`
- `app/Http/Controllers/JurnalBelajarController.php`
- `app/Http/Controllers/SesiBelajarController.php`
- `app/Http/Controllers/ProfilController.php`

#### Database
- `database/migrations/2026_06_17_000001_create_jurnal_belajar_table.php`
- `database/migrations/2026_06_17_000002_add_profil_fields_to_users_table.php`
- `database/migrations/2026_06_17_000003_create_sesi_belajar_table.php`
- `database/factories/KelasFactory.php`
- `database/factories/JurnalBelajarFactory.php`
- `database/factories/SesiBelajarFactory.php`

#### Views
- `resources/views/dashboard/catatan-belajar.blade.php`
- `resources/views/dashboard/sesi-belajar.blade.php`
- `resources/views/dashboard/profil.blade.php`

#### Tests
- `tests/Feature/AuthFlowTest.php`
- `tests/Feature/RoleAccessTest.php`
- `tests/Feature/QuizFlowTest.php`
- `tests/Feature/KelasCrudTest.php`
- `tests/Feature/JurnalBelajarTest.php`
- `tests/Feature/SesiBelajarTest.php`
- `tests/Feature/ProfileTest.php`

#### Docs
- `docs/00-overview.md`
- `docs/01-audit-report.md`
- `docs/02-architecture.md`
- `docs/03-database-schema.md`
- `docs/04-phase-report.md`
- `docs/05-routes.md`
- `docs/06-testing.md`
- `docs/07-changelog.md`

---

## v0.1.0 — Pre-development (existing)

- Auth manual (register, login, logout)
- Quiz gaya belajar (7 soal, 4 metode)
- Dashboard siswa & pengajar (UI dasar)
- Kelas CRUD (partial)
- Landing, about, contact pages
