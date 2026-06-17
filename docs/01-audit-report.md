# LearnFit — Audit Report (Phase 0)

**Tanggal Audit**: 17 Juni 2026  
**Auditor**: Kiro AI Engineer

---

## 1. Fitur yang Sudah Selesai (Pre-Development)

| Fitur | Status | Catatan |
|-------|--------|---------|
| Landing page (welcome, about, contact) | ✅ Selesai | UI lengkap |
| Register (siswa + pengajar) | ✅ Selesai | Validasi baik, redirect sesuai role |
| Login dengan redirect by role | ✅ Selesai | Cek quiz_result untuk siswa |
| Logout | ✅ Selesai | Session invalidate |
| Quiz gaya belajar (show, submit, result, retake) | ✅ Selesai | Ada bug validasi |
| Dashboard pengajar (view) | ✅ Selesai | Data masih statis/dummy |
| Dashboard siswa (view) | ✅ Selesai | Membaca quiz_result |
| Kelas CRUD (controller + routes) | ⚠️ Partial | Bug mass assignment, tidak ada isolasi pengajar |
| Welcome-after | ✅ Selesai | UI lengkap |

---

## 2. Bug & Risiko yang Ditemukan

### CRITICAL

| # | Bug | File | Dampak |
|---|-----|------|--------|
| 1 | **Tidak ada role middleware** — siapapun bisa akses route manapun | `routes/web.php` | Siswa bisa akses `/dashboard/kelas`, pengajar bisa ikut quiz |
| 2 | **`$request->all()` di KelasController** | `KelasController.php` | Mass assignment vulnerability |
| 3 | **Quiz bisa disubmit kosong** — tidak ada validasi jumlah soal | `QuizController.php` | Submit kosong tetap menghasilkan pemenang default |

### MEDIUM

| # | Bug | File | Dampak |
|---|-----|------|--------|
| 4 | `User::$fillable` tidak include `quiz_result` dan `quiz_scores` | `User.php` | `save()` bypass fillable (bekerja tapi inconsistent) |
| 5 | Tidak ada `pengajar_id` di tabel `kelas` | Migration | Semua pengajar lihat semua kelas, tidak ada isolasi |
| 6 | Sidebar siswa semua link masih `href="#"` | `siswa.blade.php` | Link tidak berfungsi |

### LOW

| # | Issue | File |
|---|-------|------|
| 7 | Tidak ada logout button di dashboard siswa | `siswa.blade.php` |
| 8 | phpunit.xml menggunakan DB asli bukan SQLite test | `phpunit.xml` |
| 9 | Tidak ada factory untuk Kelas, JurnalBelajar, SesiBelajar | - |

---

## 3. Fitur yang Belum Ada

- Catatan Belajar / Jurnal Belajar
- Sesi Pomodoro / Sesi Belajar
- Halaman Profil user
- Materi & Tugas (di luar scope Phase 1-8)

---

## 4. File yang Akan Diubah/Dibuat

### Diubah
- `app/Http/Kernel.php` — tambah alias `role`
- `app/Models/User.php` — tambah fillable, casts, relationships, factory states
- `app/Models/Kelas.php` — tambah HasFactory
- `app/Http/Controllers/QuizController.php` — tambah validasi
- `app/Http/Controllers/KelasController.php` — ganti `$request->all()` dengan `$validated`
- `routes/web.php` — regroup dengan role middleware
- `resources/views/dashboard/siswa.blade.php` — perbaiki sidebar links
- `resources/views/dashboard/pengajar.blade.php` — perbaiki sidebar links
- `resources/views/dashboard/kelas-saya.blade.php` — perbaiki sidebar links
- `database/factories/UserFactory.php` — tambah states siswa/pengajar
- `phpunit.xml` — enable SQLite untuk testing

### Dibuat Baru
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Models/JurnalBelajar.php`
- `app/Models/SesiBelajar.php`
- `app/Http/Controllers/JurnalBelajarController.php`
- `app/Http/Controllers/SesiBelajarController.php`
- `app/Http/Controllers/ProfilController.php`
- `database/migrations/2026_06_17_000001_create_jurnal_belajar_table.php`
- `database/migrations/2026_06_17_000002_add_profil_fields_to_users_table.php`
- `database/migrations/2026_06_17_000003_create_sesi_belajar_table.php`
- `database/factories/KelasFactory.php`
- `database/factories/JurnalBelajarFactory.php`
- `database/factories/SesiBelajarFactory.php`
- `resources/views/dashboard/catatan-belajar.blade.php`
- `resources/views/dashboard/sesi-belajar.blade.php`
- `resources/views/dashboard/profil.blade.php`
- `tests/Feature/AuthFlowTest.php`
- `tests/Feature/RoleAccessTest.php`
- `tests/Feature/QuizFlowTest.php`
- `tests/Feature/KelasCrudTest.php`
- `tests/Feature/JurnalBelajarTest.php`
- `tests/Feature/SesiBelajarTest.php`
- `tests/Feature/ProfileTest.php`
