# LearnFit — Database Schema

## ERD (Textual)

```
users
  ├── id (PK)
  ├── name
  ├── email (unique)
  ├── password
  ├── role (enum: siswa, pengajar)
  ├── quiz_result (nullable: pomodoro|active_recall|blurting|feynman)
  ├── quiz_scores (JSON nullable)
  ├── bio (nullable)          ← NEW migration 2026_06_17_000002
  ├── tujuan_belajar (nullable) ← NEW
  ├── jenjang (nullable)     ← NEW
  ├── no_hp (nullable)       ← NEW
  └── timestamps

jurnal_belajar               ← NEW migration 2026_06_17_000001
  ├── id (PK)
  ├── user_id (FK → users)
  ├── tanggal (date)
  ├── judul (nullable)
  ├── isi_jurnal (text)
  ├── metode_yang_digunakan (nullable)
  ├── rating_efektivitas (tinyint 1-5, nullable)
  ├── durasi_menit (smallint, nullable)
  └── timestamps
  INDEX: (user_id, tanggal)

sesi_belajar                 ← NEW migration 2026_06_17_000003
  ├── id (PK)
  ├── user_id (FK → users)
  ├── metode (default: pomodoro)
  ├── judul (nullable)
  ├── durasi_fokus_menit (smallint, default: 25)
  ├── durasi_istirahat_menit (smallint, default: 5)
  ├── jumlah_siklus (smallint, default: 1)
  ├── status (enum: aktif|selesai|batal)
  ├── started_at (nullable timestamp)
  ├── completed_at (nullable timestamp)
  ├── catatan (text, nullable)
  └── timestamps
  INDEX: (user_id, status), (user_id, created_at)

kelas
  ├── id (PK)
  ├── nama_kelas
  ├── mata_pelajaran
  ├── kode_kelas (unique)
  ├── deskripsi (nullable)
  ├── kapasitas (int, default: 30)
  ├── status (enum: aktif|draf|selesai)
  ├── pengajar_id (FK → users)    ← ADD 2026_06_17_000004
  └── timestamps

flashcards                       ← NEW migration 2026_06_18_000001
  ├── id (PK)
  ├── sesi_id (FK → sesi_belajar, cascade)
  ├── pertanyaan (text)
  ├── jawaban (text)
  ├── urutan (smallint, default: 0)
  └── timestamps
  INDEX: (sesi_id, urutan)

entri_notebook                   ← NEW migration 2026_06_18_000002
  ├── id (PK)
  ├── sesi_id (FK → sesi_belajar, cascade)
  ├── tipe (enum: blurting|feynman)
  ├── konten (text)
  ├── analisis_sistem (text, nullable)
  ├── skor_keyakinan (smallint 0-100, nullable)
  ├── kata_kunci_cocok (JSON, nullable)
  └── timestamps
  INDEX: (sesi_id, tipe)
```

## Migrations Order

| File | Action |
|------|--------|
| 2014_10_12_000000_create_users_table | CREATE users |
| 2026_04_25_000000_add_quiz_result_to_users_table | ADD quiz_result |
| 2026_04_28_000000_create_kelas_table | CREATE kelas |
| 2026_06_14_105529_add_quiz_scores_to_users_table | ADD quiz_scores |
| **2026_06_17_000001_create_jurnal_belajar_table** | CREATE jurnal_belajar |
| **2026_06_17_000002_add_profil_fields_to_users_table** | ADD bio, tujuan_belajar, jenjang, no_hp |
| **2026_06_17_000003_create_sesi_belajar_table** | CREATE sesi_belajar |
| 2026_06_17_000004_add_pengajar_id_to_kelas_table | ADD pengajar_id to kelas |
| 2026_06_17_000005_create_anggota_kelas_table | CREATE anggota_kelas |
| 2026_06_17_000006_create_materi_table | CREATE materi |
| 2026_06_17_000007_create_tugas_table | CREATE tugas |
| 2026_06_17_000008_create_jawaban_tugas_table | CREATE jawaban_tugas |
| **2026_06_18_000001_create_flashcards_table** | CREATE flashcards |
| **2026_06_18_000002_create_entri_notebook_table** | CREATE entri_notebook |

## Jalankan Migration

```bash
php artisan migrate
```
