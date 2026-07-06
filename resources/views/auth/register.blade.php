@extends('layouts.app')

@section('content')

<div class="auth-page">
    <div class="auth-page__blob auth-page__blob--1"></div>
    <div class="auth-page__blob auth-page__blob--2"></div>

    {{-- Navbar mini --}}
    <div class="auth-nav container">
        <a href="/" class="navbar__brand flex items-center gap-2">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" class="shrink-0">
                <rect width="28" height="28" rx="8" fill="#2563EB"/>
                <path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="font-display text-[22px] font-bold tracking-tight text-ink-900">LearnFit</span>
        </a>
        <a href="/" class="auth-nav__back">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M10 12L6 8l4-4" stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali ke Beranda
        </a>
    </div>

    {{-- Card --}}
    <div class="auth-card">
        <div class="auth-card__header">
            <h1 class="auth-card__title">Buat Akun</h1>
            <p class="auth-card__sub">Mulai perjalanan belajar teratur.</p>
        </div>

        @if($errors->any())
        <div class="alert alert--error">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <circle cx="9" cy="9" r="8" stroke="#ef4444" stroke-width="1.5"/>
                <path d="M9 5v4M9 12v.5" stroke="#ef4444" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="auth-form">
            @csrf

            {{-- Role --}}
            <div class="form-group">
                <label class="form-label">Daftar Sebagai</label>
                <div class="role-selector">
                    <label class="role-card {{ old('role','siswa')==='siswa' ? 'role-card--active' : '' }}">
                        <input type="radio" name="role" value="siswa" {{ old('role','siswa')==='siswa' ? 'checked' : '' }} hidden>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M12 3L2 8l10 5 10-5-10-5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                        <span class="role-card__name">Siswa</span>
                        <span class="role-card__desc">Ingin belajar materi baru</span>
                    </label>
                    <label class="role-card {{ old('role')==='pengajar' ? 'role-card--active' : '' }}">
                        <input type="radio" name="role" value="pengajar" {{ old('role')==='pengajar' ? 'checked' : '' }} hidden>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M8 21h8M12 17v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <span class="role-card__name">Pengajar</span>
                        <span class="role-card__desc">Ingin berbagi pengetahuan</span>
                    </label>
                </div>
            </div>

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap</label>
                <div class="input-wrapper">
                    <input type="text" id="name" name="name" class="form-input {{ $errors->has('name') ? 'form-input--error':'' }}" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required autocomplete="name">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <circle cx="9" cy="6" r="3.5" stroke="#94a3b8" stroke-width="1.5"/>
                        <path d="M2.5 16c0-3.31 2.91-6 6.5-6s6.5 2.69 6.5 6" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'form-input--error':'' }}" placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="email">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <rect x="1.5" y="3.5" width="15" height="11" rx="2" stroke="#94a3b8" stroke-width="1.5"/>
                        <path d="M1.5 6.5l7.5 5 7.5-5" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'form-input--error':'' }}" placeholder="Min. 8 karakter" required autocomplete="new-password">
                    <button type="button" class="input-icon input-icon--btn" id="togglePassword" aria-label="Lihat password">
                        <svg id="eyeShow" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M1 9s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6z" stroke="#94a3b8" stroke-width="1.5"/>
                            <circle cx="9" cy="9" r="2.5" stroke="#94a3b8" stroke-width="1.5"/>
                        </svg>
                        <svg id="eyeHide" width="18" height="18" viewBox="0 0 18 18" fill="none" style="display:none">
                            <path d="M1 9s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6z" stroke="#94a3b8" stroke-width="1.5"/>
                            <line x1="2" y1="2" x2="16" y2="16" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi" required autocomplete="new-password">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <rect x="4" y="8" width="10" height="8" rx="1.5" stroke="#94a3b8" stroke-width="1.5"/>
                        <path d="M6 8V5.5a3 3 0 016 0V8" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            {{-- Terms --}}
            <label class="checkbox-label">
                <input type="checkbox" name="terms" id="terms" class="checkbox-input" required {{ old('terms') ? 'checked':'' }}>
                <span class="checkbox-box"></span>
                <span class="checkbox-text">Saya setuju dengan <a href="#" class="form-link">syarat & ketentuan</a> serta <a href="#" class="form-link">Privasi LearnFit</a>.</span>
            </label>

            <button type="submit" class="btn-submit">
                Buat Akun
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M4 9h10M10 5l4 4-4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </form>

        <p class="auth-card__footer">Sudah punya akun? <a href="{{ route('login') }}" class="form-link form-link--bold">Masuk</a></p>
    </div>
</div>

<script>
// Role card toggle
document.querySelectorAll('.role-card').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('role-card--active'));
        card.classList.add('role-card--active');
    });
});

// Password toggle
const toggleBtn = document.getElementById('togglePassword');
const pwInput   = document.getElementById('password');
const eyeShow   = document.getElementById('eyeShow');
const eyeHide   = document.getElementById('eyeHide');
if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        const isHidden = pwInput.type === 'password';
        pwInput.type   = isHidden ? 'text' : 'password';
        eyeShow.style.display = isHidden ? 'none'  : 'block';
        eyeHide.style.display = isHidden ? 'block' : 'none';
    });
}
</script>

@endsection