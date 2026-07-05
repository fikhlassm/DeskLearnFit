<?php

use App\Http\Controllers\AnggotaKelasController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\JawabanTugasController;
use App\Http\Controllers\JurnalBelajarController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SesiBelajarController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TugasController;
use Illuminate\Support\Facades\Route;

// ─── Public ──────────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/tentang', function () {
    return view('about');
})->name('about');
Route::get('/kontak', function () {
    return view('contact');
})->name('contact');

// ─── Guest-only ───────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Reset Password
    Route::get('/lupa-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// ─── Any authenticated user ───────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/welcome', function () {
        return view('auth.welcome-after');
    })->name('welcome');

    // Email verification
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

    // Profil — bisa diakses siswa & pengajar
    Route::get('/dashboard/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::put('/dashboard/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Materi download (siswa yang join kelas + pengajar pemilik)
    Route::get('/dashboard/materi/{materi}/download', [MateriController::class, 'download'])->name('materi.download');
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

    // Notebook Saya (list semua sesi per metode)
    Route::get('/dashboard/notebook', [NotebookController::class, 'index'])->name('notebook.index');

    // Materi siswa
    Route::get('/dashboard/siswa/kelas/{kelas}/materi', [MateriController::class, 'indexSiswa'])->name('siswa.materi.index');
    Route::get('/dashboard/siswa/materi/{materi}', [MateriController::class, 'showSiswa'])->name('siswa.materi.show');

    // Tugas siswa
    Route::get('/dashboard/siswa/kelas/{kelas}/tugas', [TugasController::class, 'indexSiswa'])->name('siswa.tugas.index');
    Route::get('/dashboard/siswa/tugas/{tugas}', [TugasController::class, 'showSiswa'])->name('siswa.tugas.show');
    Route::post('/dashboard/siswa/tugas/{tugas}/jawaban', [JawabanTugasController::class, 'submit'])->name('siswa.tugas.submit');
    Route::put('/dashboard/siswa/jawaban-tugas/{jawaban}', [JawabanTugasController::class, 'updateSubmit'])->name('siswa.tugas.update-submit');

    // Jurnal / Catatan Belajar
    Route::get('/dashboard/catatan-belajar', [JurnalBelajarController::class, 'index'])->name('catatan.index');
    Route::post('/dashboard/catatan-belajar', [JurnalBelajarController::class, 'store'])->name('catatan.store');
    Route::put('/dashboard/catatan-belajar/{jurnal}', [JurnalBelajarController::class, 'update'])->name('catatan.update');
    Route::delete('/dashboard/catatan-belajar/{jurnal}', [JurnalBelajarController::class, 'destroy'])->name('catatan.destroy');

    // Sesi Belajar
    Route::get('/dashboard/sesi-belajar', [SesiBelajarController::class, 'index'])->name('sesi.index');
    Route::post('/dashboard/sesi-belajar', [SesiBelajarController::class, 'store'])->name('sesi.store');
    Route::get('/dashboard/sesi-belajar/{sesi}', [SesiBelajarController::class, 'show'])->name('sesi.show');
    Route::patch('/dashboard/sesi-belajar/{sesi}/start', [SesiBelajarController::class, 'start'])->name('sesi.start');
    Route::patch('/dashboard/sesi-belajar/{sesi}/complete', [SesiBelajarController::class, 'complete'])->name('sesi.complete');
    Route::patch('/dashboard/sesi-belajar/{sesi}/catatan', [SesiBelajarController::class, 'updateCatatan'])->name('sesi.catatan');
    Route::delete('/dashboard/sesi-belajar/{sesi}', [SesiBelajarController::class, 'destroy'])->name('sesi.destroy');

    // Flashcards (tool Active Recall)
    Route::post('/dashboard/sesi-belajar/{sesi}/flashcards', [FlashcardController::class, 'store'])->name('flashcard.store');
    Route::put('/dashboard/flashcards/{flashcard}', [FlashcardController::class, 'update'])->name('flashcard.update');
    Route::delete('/dashboard/flashcards/{flashcard}', [FlashcardController::class, 'destroy'])->name('flashcard.destroy');
    Route::get('/dashboard/sesi-belajar/{sesi}/review', [FlashcardController::class, 'review'])->name('flashcard.review');
    Route::post('/dashboard/sesi-belajar/{sesi}/review/answer', [FlashcardController::class, 'answer'])->name('flashcard.answer');
    Route::get('/dashboard/sesi-belajar/{sesi}/review/stats', [FlashcardController::class, 'stats'])->name('flashcard.stats');

    // Notebook (tool Blurting & Feynman)
    Route::post('/dashboard/sesi-belajar/{sesi}/notebook', [NotebookController::class, 'store'])->name('notebook.store');
    Route::delete('/dashboard/notebook/{entri}', [NotebookController::class, 'destroy'])->name('notebook.destroy');
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

    // Daftar siswa (profil siswa oleh pengajar)
    Route::get('/dashboard/daftar-siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/dashboard/daftar-siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
});
