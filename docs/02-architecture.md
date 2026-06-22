# LearnFit — Architecture

## Struktur Folder (Laravel 10 style, dipertahankan)

```
app/
├── Console/Kernel.php
├── Exceptions/Handler.php
├── Http/
│   ├── Controllers/
│   │   ├── auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── Controller.php
│   │   ├── JurnalBelajarController.php   ← NEW
│   │   ├── KelasController.php           ← REFACTORED
│   │   ├── ProfilController.php          ← NEW
│   │   ├── QuizController.php            ← FIXED
│   │   └── SesiBelajarController.php     ← NEW
│   ├── Kernel.php                        ← tambah alias 'role'
│   └── Middleware/
│       ├── RoleMiddleware.php            ← NEW
│       └── ... (existing)
├── Models/
│   ├── JurnalBelajar.php                 ← NEW
│   ├── Kelas.php                         ← tambah HasFactory
│   ├── SesiBelajar.php                   ← NEW
│   └── User.php                          ← UPDATED
└── Providers/...
```

## Flow Authentication & Authorization

```
Request
  │
  ▼
middleware: auth (cek session)
  │
  ├─ NOT AUTH → redirect /login
  │
  ▼
middleware: role:siswa|pengajar (RoleMiddleware)
  │
  ├─ ROLE MISMATCH → redirect ke dashboard role asli
  │
  ▼
Controller → Business Logic → View
```

## Flow Quiz

```
GET /quiz
  │
  ├─ sudah punya quiz_result? → redirect /quiz/hasil
  └─ belum → tampilkan quiz.blade.php

POST /quiz (7 jawaban wajib)
  │
  ├─ validasi: setiap soal wajib dijawab, opsi harus valid
  │
  ├─ hitung skor: P/A/B/F per jawaban
  ├─ arsort → ambil pemenang
  ├─ simpan quiz_result + quiz_scores ke users
  └─ redirect /quiz/hasil

GET /quiz/ulang
  │
  ├─ reset quiz_result = null
  ├─ reset quiz_scores = null
  └─ redirect /quiz
```

## Role-Based Route Groups

```
guest       → /register, /login
auth        → /logout, /welcome, /dashboard/profil
auth+siswa  → /dashboard/siswa, /quiz/*, /dashboard/catatan-belajar/*, /dashboard/sesi-belajar/*
auth+pengajar → /dashboard/pengajar, /dashboard/kelas/*
```

## Per-Method Tool Flow (PHASE 9)

```
Siswa buka /dashboard/sesi-belajar?metode={m}
  │
  ▼
SesiBelajarController@index
  ├─ muat sesiAktif (jika ada)
  ├─ muat selectedMetode dari query (default: quiz_result / pomodoro)
  └─ render view
        │
        ▼
  Blade: kondisional render tool
    ├─ pomodoro    → partial tool-pomodoro (timer client-side)
    ├─ active_recall → partial tool-flashcard (form + deck list)
    └─ blurting/feynman → partial tool-notebook
                          │
                          ▼ submit
                   NotebookController@store
                          │
                          ▼
                   HeuristicAnalyzer::analyze(topik, konten, tipe)
                          │
                          ├─ tokenisasi + stopwords ID
                          ├─ pencocokan kata kunci
                          ├─ bonus panjang + marker Feynman
                          └─ return {analisis, skor, kata_kunci_cocok}
                          │
                          ▼ simpan
                   entri_notebook
```

## Data Ownership

- `jurnal_belajar.user_id` → hanya pemilik yang bisa read/write
- `sesi_belajar.user_id` → hanya pemilik yang bisa read/write
- `kelas` → semua pengajar bisa akses (no per-pengajar isolation di phase ini)
