# LearnFit — Phase Implementation Report

## PHASE 0 — Audit

**Status**: ✅ Selesai  
**Temuan**: 3 bug critical, 3 bug medium, 3 issue low  
**Lihat**: [01-audit-report.md](01-audit-report.md)

---

## PHASE 1 — Role Access & Middleware

**Status**: ✅ Selesai

### Yang dikerjakan:
- Buat `app/Http/Middleware/RoleMiddleware.php`
- Daftarkan alias `'role'` di `app/Http/Kernel.php`
- Restrukturisasi `routes/web.php`:
  - Guest-only group: `/register`, `/login`
  - Auth group: `/logout`, `/welcome`, `/dashboard/profil`
  - `auth + role:siswa`: dashboard siswa, quiz, catatan, sesi
  - `auth + role:pengajar`: dashboard pengajar, kelas CRUD
- Update `User::$fillable` untuk include `quiz_result`, `quiz_scores`, dan profil fields
- Tambah helper methods `isSiswa()` dan `isPengajar()` di User model
- Tambah relationships `jurnalBelajar()` dan `sesiBelajar()` di User model

### Acceptance Criteria:
- [x] Guest diarahkan ke login ketika akses dashboard
- [x] Siswa tidak bisa buka dashboard pengajar → redirect dashboard.siswa + flash error
- [x] Pengajar tidak bisa buka quiz/dashboard siswa → redirect dashboard.pengajar + flash error
- [x] Redirect after login/register sesuai role dan status quiz

---

## PHASE 2 — Quiz Validation & Refactor

**Status**: ✅ Selesai

### Yang dikerjakan:
- Tambah `$validOptions` array — 7 soal × 4 opsi tiap soal
- Tambah `$scoreMap` dan `$methodMap` sebagai private properties (tidak lagi inline)
- Validasi dinamis: setiap `answers.qN` wajib ada dan opsi harus valid
- Error message per soal dalam bahasa Indonesia
- Ekstrak `scoreMap` ke property private agar controller tidak terlalu gemuk

### Acceptance Criteria:
- [x] Submit kosong ditolak dengan validation error
- [x] Submit sebagian soal ditolak
- [x] Submit opsi tidak valid ditolak
- [x] Submit valid menyimpan `quiz_result` dan `quiz_scores`
- [x] Retake menghapus `quiz_result` dan `quiz_scores`

---

## PHASE 3 — Catatan Belajar / Jurnal Belajar

**Status**: ✅ Selesai

### Yang dikerjakan:
- Migration `jurnal_belajar` dengan index `(user_id, tanggal)`
- Model `JurnalBelajar` dengan casts, fillable, dan relationship
- `JurnalBelajarController` dengan CRUD lengkap + ownership check
- Routes siswa-only dengan route model binding
- View `catatan-belajar.blade.php` dengan:
  - Stats row (total catatan, catatan terakhir)
  - Filter berdasarkan metode
  - Daftar catatan dengan badge metode + rating bintang
  - Modal tambah dan edit
  - Empty state
  - Pagination

### Acceptance Criteria:
- [x] Siswa bisa tambah, lihat, edit, hapus catatan miliknya
- [x] Siswa tidak bisa akses catatan user lain (403)
- [x] Tanggal tidak boleh masa depan
- [x] Isi jurnal wajib diisi

---

## PHASE 4 — Sesi Belajar / Pomodoro

**Status**: ✅ Selesai

### Yang dikerjakan:
- Migration `sesi_belajar` dengan index `(user_id, status)` dan `(user_id, created_at)`
- Model `SesiBelajar` dengan casts, fillable, dan relationship
- `SesiBelajarController` dengan store, start, complete, destroy + ownership check
- Routes siswa-only: index, store, start, complete, destroy
- View `sesi-belajar.blade.php` dengan:
  - Rekomendasi metode dari quiz_result
  - Pomodoro timer client-side (Vanilla JS, no package)
  - Form buat sesi baru
  - Sesi aktif card dengan tombol "Tandai Selesai"
  - Riwayat sesi dengan pagination
  - Notifikasi browser saat timer selesai

### Acceptance Criteria:
- [x] Siswa bisa buat, start, complete, hapus sesi
- [x] Siswa tidak bisa akses sesi user lain (403)
- [x] Timer client-side tanpa websocket/package
- [x] Validasi metode, durasi, jumlah siklus

---

## PHASE 5 — Profil

**Status**: ✅ Selesai

### Yang dikerjakan:
- Migration `add_profil_fields_to_users_table` (bio, tujuan_belajar, jenjang, no_hp)
- `ProfilController` dengan show dan update
- `Rule::unique('users')->ignore($user->id)` untuk email update
- View `profil.blade.php` dengan sidebar dinamis (siswa/pengajar)
- Role tidak bisa diubah melalui form (field hanya readonly/display)
- Field khusus siswa: jenjang, tujuan_belajar

### Acceptance Criteria:
- [x] User bisa lihat dan update profil
- [x] Role tidak bisa dimanipulasi dari form
- [x] Email unique mengabaikan user sendiri
- [x] Profil bisa diakses siswa maupun pengajar

---

## PHASE 6 — Kelas CRUD Cleanup

**Status**: ✅ Selesai

### Yang dikerjakan:
- Ganti `$request->all()` dengan `$validated` di `store()` dan `update()`
- Gunakan route model binding `Kelas $kelas` (hapus `findOrFail($id)` manual)
- `edit()` dikembalikan sebagai JSON untuk modal
- Semua route kelas pindah ke group `auth + role:pengajar`
- Tambah `HasFactory` ke model Kelas
- Buat `KelasFactory`

### Acceptance Criteria:
- [x] Pengajar bisa CRUD kelas
- [x] Siswa tidak bisa CRUD kelas (redirect + flash)
- [x] Validasi aman, tidak ada mass assignment

---

## PHASE 7 — UI Sync

**Status**: ✅ Selesai

### Yang dikerjakan:
- Dashboard siswa: Semua link sidebar diperbaiki:
  - "Catatan Belajar" → `route('catatan.index')`
  - "Sesi Belajar" → `route('sesi.index')`
  - "Profil" → `route('profil.show')`
  - "Mentoring" dihapus (belum ada fiturnya)
- Dashboard siswa: tambah logout button di topbar
- Dashboard pengajar: "Profil" → `route('profil.show')`
- Kelas-saya: "Profil" → `route('profil.show')`

---

## PHASE 8 — Testing

**Status**: ✅ Selesai (perlu `php artisan test` untuk verifikasi)

### Test files yang dibuat:

| File | Test Count | Coverage |
|------|-----------|----------|
| `AuthFlowTest.php` | 8 | Register, login, logout flows |
| `RoleAccessTest.php` | 11 | Guest, siswa, pengajar access control |
| `QuizFlowTest.php` | 9 | Quiz show, submit, result, retake |
| `KelasCrudTest.php` | 7 | Kelas CRUD + authorization |
| `JurnalBelajarTest.php` | 8 | Jurnal CRUD + ownership |
| `SesiBelajarTest.php` | 7 | Sesi CRUD + ownership |
| `ProfileTest.php` | 7 | Profil show, update, role protection |

**Total**: 57 test cases

---

## TODO / Belum Dikerjakan

- [ ] Fitur Materi dan Tugas (Prioritas 5 — di luar scope Phase 1-8)
- [ ] Isolasi kelas per pengajar (tambah `pengajar_id` ke tabel `kelas`)
- [ ] Dashboard siswa: riwayat belajar yang sesungguhnya (saat ini dummy)
- [ ] Dashboard pengajar: statistik real dari database
- [ ] Email verification
- [ ] Password reset
