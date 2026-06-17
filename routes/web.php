<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\AnggotaKelasController;
use App\Http\Controllers\JurnalBelajarController;
use App\Http\Controllers\SesiBelajarController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\JawabanTugasController;

// ─── Public ──────────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/tentang', function () { return view('about'); })->name('about');
Route::get('/kontak', function () { return view('contact'); })->name('contact');

// ─── Guest-only ───────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ─── Any authenticated user ───────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/welcome', function () {
        return view('auth.welcome-after');
    })->name('welcome');

    // Profil — bisa diakses siswa & pengajar
    Route::get('/dashboard/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::put('/dashboard/profil', [ProfilController::class, 'update'])->name('profil.update');
});

// ─── Siswa only ───────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard/siswa', [DashboardController::class, 'siswa'])->name('dashboard.siswa');

    // Quiz
    Route::get('/quiz', [QuizController::class, 'show'])->name('quiz');
    Route::post('/quiz', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/hasil', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/quiz/ulang', [QuizController::class, 'retake'])->name('quiz.retake');

    // Kelas yang diikuti siswa
    Route::get('/dashboard/kelas-diikuti', [AnggotaKelasController::class, 'index'])->name('siswa.kelas.index');
    Route::post('/dashboard/kelas-diikuti/join', [AnggotaKelasController::class, 'join'])->name('siswa.kelas.join');
    Route::delete('/dashboard/kelas-diikuti/{kelas}/leave', [AnggotaKelasController::class, 'leave'])->name('siswa.kelas.leave');

    // Materi siswa
    Route::get('/dashboard/siswa/kelas/{kelas}/materi', [MateriController::class, 'indexSiswa'])->name('siswa.materi.index');
    Route::get('/dashboard/siswa/materi/{materi}', [MateriController::class, 'showSiswa'])->name('siswa.materi.show');

    // Tugas siswa
    Route::get('/dashboard/siswa/kelas/{kelas}/tugas', [TugasController::class, 'indexSiswa'])->name('siswa.tugas.index');
    Route::get('/dashboard/siswa/tugas/{tugas}', [TugasController::class, 'showSiswa'])->name('siswa.tugas.show');
    Route::post('/dashboard/siswa/tugas/{tugas}/jawaban', [JawabanTugasController::class, 'submit'])->name('siswa.tugas.submit');
    Route::put('/dashboard/siswa/jawaban-tugas/{jawaban}', [JawabanTugasController::class, 'updateSubmit'])->name('siswa.tugas.update-submit');

    // Catatan Belajar
    Route::get('/dashboard/catatan-belajar', [JurnalBelajarController::class, 'index'])->name('catatan.index');
    Route::post('/dashboard/catatan-belajar', [JurnalBelajarController::class, 'store'])->name('catatan.store');
    Route::get('/dashboard/catatan-belajar/{jurnal}/edit', [JurnalBelajarController::class, 'edit'])->name('catatan.edit');
    Route::put('/dashboard/catatan-belajar/{jurnal}', [JurnalBelajarController::class, 'update'])->name('catatan.update');
    Route::delete('/dashboard/catatan-belajar/{jurnal}', [JurnalBelajarController::class, 'destroy'])->name('catatan.destroy');

    // Sesi Belajar
    Route::get('/dashboard/sesi-belajar', [SesiBelajarController::class, 'index'])->name('sesi.index');
    Route::post('/dashboard/sesi-belajar', [SesiBelajarController::class, 'store'])->name('sesi.store');
    Route::patch('/dashboard/sesi-belajar/{sesi}/start', [SesiBelajarController::class, 'start'])->name('sesi.start');
    Route::patch('/dashboard/sesi-belajar/{sesi}/complete', [SesiBelajarController::class, 'complete'])->name('sesi.complete');
    Route::delete('/dashboard/sesi-belajar/{sesi}', [SesiBelajarController::class, 'destroy'])->name('sesi.destroy');
});

// ─── Pengajar only ────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:pengajar'])->group(function () {
    Route::get('/dashboard/pengajar', [DashboardController::class, 'pengajar'])->name('dashboard.pengajar');

    // Kelas CRUD
    Route::get('/dashboard/kelas', [KelasController::class, 'index'])->name('dashboard.kelas');
    Route::post('/dashboard/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('/dashboard/kelas/{kelas}', [KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/dashboard/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/dashboard/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    Route::get('/dashboard/kelas/{kelas}/peserta', [AnggotaKelasController::class, 'peserta'])->name('kelas.peserta');

    // Materi
    Route::get('/dashboard/kelas/{kelas}/materi', [MateriController::class, 'index'])->name('materi.index');
    Route::post('/dashboard/kelas/{kelas}/materi', [MateriController::class, 'store'])->name('materi.store');
    Route::get('/dashboard/materi/{materi}/edit', [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/dashboard/materi/{materi}', [MateriController::class, 'update'])->name('materi.update');
    Route::delete('/dashboard/materi/{materi}', [MateriController::class, 'destroy'])->name('materi.destroy');
    Route::patch('/dashboard/materi/{materi}/publish', [MateriController::class, 'publish'])->name('materi.publish');

    // Tugas
    Route::get('/dashboard/kelas/{kelas}/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::post('/dashboard/kelas/{kelas}/tugas', [TugasController::class, 'store'])->name('tugas.store');
    Route::get('/dashboard/tugas/{tugas}/edit', [TugasController::class, 'edit'])->name('tugas.edit');
    Route::put('/dashboard/tugas/{tugas}', [TugasController::class, 'update'])->name('tugas.update');
    Route::delete('/dashboard/tugas/{tugas}', [TugasController::class, 'destroy'])->name('tugas.destroy');
    Route::patch('/dashboard/tugas/{tugas}/publish', [TugasController::class, 'publish'])->name('tugas.publish');
    Route::get('/dashboard/tugas/{tugas}/jawaban', [JawabanTugasController::class, 'index'])->name('tugas.jawaban.index');
    Route::put('/dashboard/jawaban-tugas/{jawaban}/nilai', [JawabanTugasController::class, 'nilai'])->name('jawaban.nilai');
});
