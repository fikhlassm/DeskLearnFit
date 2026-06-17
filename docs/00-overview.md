# LearnFit — Project Documentation Overview

> Platform pendidikan untuk personalisasi metode belajar dan jurnal efektivitas studi.

## Daftar Dokumen

| File | Deskripsi |
|------|-----------|
| [01-audit-report.md](01-audit-report.md) | Hasil audit kode sebelum development dimulai |
| [02-architecture.md](02-architecture.md) | Arsitektur aplikasi, struktur folder, dan flow data |
| [03-database-schema.md](03-database-schema.md) | Skema database lengkap & ERD textual |
| [04-phase-report.md](04-phase-report.md) | Laporan implementasi per phase |
| [05-routes.md](05-routes.md) | Daftar semua route beserta middleware |
| [06-testing.md](06-testing.md) | Panduan testing dan hasil test per fase |
| [07-changelog.md](07-changelog.md) | Changelog semua perubahan file |

## Tech Stack

- **Framework**: Laravel 12 (struktur Laravel 10)
- **Language**: PHP 8.2+
- **Frontend**: Blade + Vanilla JS (Plus Jakarta Sans)
- **Database**: MySQL (SQLite untuk testing)
- **Auth**: Custom manual auth (tanpa Breeze/Fortify)
- **Testing**: PHPUnit 11
- **Code Style**: Laravel Pint

## User Roles

| Role | Akses |
|------|-------|
| `siswa` | Quiz, Dashboard Siswa, Catatan Belajar, Sesi Pomodoro, Profil |
| `pengajar` | Dashboard Pengajar, Kelas CRUD, Profil |
