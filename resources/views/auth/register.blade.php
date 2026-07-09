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
                <span class="checkbox-text">Saya setuju dengan <a href="{{ route('terms') }}" target="_blank" class="form-link">syarat & ketentuan</a> serta <a href="{{ route('privacy') }}" target="_blank" class="form-link">Privasi LearnFit</a>.</span>
            </label>

            <button type="submit" class="btn-submit">
                Buat Akun
            </button>
        </form>

        <div class="auth-card__divider">
            <span>atau</span>
        </div>

        <a href="{{ route('google.redirect') }}" class="btn-google">
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none">
                <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"/>
                <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
            </svg>
            Daftar dengan Google
        </a>

        <p class="auth-card__footer">Sudah punya akun? <a href="{{ route('login') }}" class="form-link form-link--bold">Masuk</a></p>
    </div>
</div>

<style>
.auth-card__divider{display:flex;align-items:center;gap:.75rem;margin:1.25rem 0;color:#94A3B8;font-size:.78rem;}
.auth-card__divider::before,.auth-card__divider::after{content:"";flex:1;height:1px;background:#E2E8F0;}
.btn-google{display:flex;align-items:center;justify-content:center;gap:.65rem;width:100%;padding:.8rem;background:#fff;color:#0F172A;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;transition:background .18s,border-color .18s, transform .18s, box-shadow .18s;}
.btn-google:hover{background:#F8FAFC;border-color:#94A3B8;transform:translateY(-1px);box-shadow:0 4px 12px rgba(148,163,184,.15);}
</style>

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