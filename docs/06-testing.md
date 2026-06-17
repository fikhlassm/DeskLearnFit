# LearnFit — Testing Guide

## Setup

Test menggunakan `RefreshDatabase` trait yang me-reset database setiap test.  
Database testing menggunakan koneksi yang dikonfigurasi di `.env` (atau SQLite jika diaktifkan di `phpunit.xml`).

### Aktifkan SQLite untuk test lebih cepat (opsional)

Edit `phpunit.xml`, uncomment:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## Menjalankan Test

```bash
# Semua test
php artisan test --compact

# Per file
php artisan test --filter=AuthFlowTest
php artisan test --filter=RoleAccessTest
php artisan test --filter=QuizFlowTest
php artisan test --filter=KelasCrudTest
php artisan test --filter=JurnalBelajarTest
php artisan test --filter=SesiBelajarTest
php artisan test --filter=ProfileTest

# Per method
php artisan test --filter="test_siswa_bisa_buat_catatan_belajar"
```

## Test Coverage

### AuthFlowTest (`tests/Feature/AuthFlowTest.php`)

| Test | Skenario |
|------|----------|
| `test_register_siswa_redirects_to_welcome` | Register siswa → redirect welcome |
| `test_register_pengajar_redirects_to_dashboard_pengajar` | Register pengajar → redirect dashboard pengajar |
| `test_register_requires_valid_role` | Role harus siswa atau pengajar |
| `test_register_requires_terms_accepted` | Terms wajib dicentang |
| `test_login_siswa_tanpa_quiz_redirects_to_welcome` | Login siswa belum quiz → welcome |
| `test_login_siswa_dengan_quiz_redirects_to_dashboard_siswa` | Login siswa sudah quiz → dashboard siswa |
| `test_login_pengajar_redirects_to_dashboard_pengajar` | Login pengajar → dashboard pengajar |
| `test_login_dengan_kredensial_salah_gagal` | Kredensial salah → session error |
| `test_logout_redirect_ke_home` | Logout → home |

### RoleAccessTest (`tests/Feature/RoleAccessTest.php`)

| Test | Skenario |
|------|----------|
| `test_guest_tidak_bisa_akses_dashboard_siswa` | Guest → login |
| `test_guest_tidak_bisa_akses_dashboard_pengajar` | Guest → login |
| `test_guest_tidak_bisa_akses_quiz` | Guest → login |
| `test_siswa_tidak_bisa_akses_dashboard_pengajar` | Siswa → dashboard siswa |
| `test_siswa_tidak_bisa_akses_kelas_crud` | Siswa → dashboard siswa |
| `test_pengajar_tidak_bisa_akses_dashboard_siswa` | Pengajar → dashboard pengajar |
| `test_pengajar_tidak_bisa_akses_quiz` | Pengajar → dashboard pengajar |
| `test_profil_bisa_diakses_siswa` | Siswa bisa akses profil |
| `test_profil_bisa_diakses_pengajar` | Pengajar bisa akses profil |

### QuizFlowTest (`tests/Feature/QuizFlowTest.php`)

| Test | Skenario |
|------|----------|
| `test_siswa_bisa_lihat_halaman_quiz` | Show quiz 200 |
| `test_siswa_yang_sudah_quiz_redirect_ke_hasil` | Sudah quiz → hasil |
| `test_submit_valid_menyimpan_quiz_result_dan_scores` | 7 jawaban valid → simpan ke DB |
| `test_submit_kosong_ditolak_dengan_validation_error` | Kosong → error |
| `test_submit_jawaban_tidak_lengkap_ditolak` | Kurang soal → error |
| `test_submit_opsi_tidak_valid_ditolak` | Opsi invalid → error |
| `test_hasil_quiz_tampil_jika_sudah_quiz` | Result page 200 |
| `test_hasil_quiz_redirect_ke_quiz_jika_belum_quiz` | Belum quiz → quiz |
| `test_retake_menghapus_quiz_result` | Retake → null di DB |

## Code Style

Setelah mengubah file PHP, jalankan Pint:

```bash
vendor/bin/pint --dirty --format agent
```
