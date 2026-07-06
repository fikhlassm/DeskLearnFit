@extends('layouts.app')

@section('content')

<div class="auth-page">
    <div class="auth-page__blob auth-page__blob--1"></div>
    <div class="auth-page__blob auth-page__blob--2"></div>

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

    <div class="auth-card">
        <div class="auth-card__header">
            <h1 class="auth-card__title">Selamat Datang</h1>
            <p class="auth-card__sub">Masuk ke akun LearnFit kamu.</p>
        </div>

        @if(session('success'))
        <div class="alert alert--success">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <circle cx="9" cy="9" r="8" stroke="#16a34a" stroke-width="1.5"/>
                <path d="M5.5 9l2.5 2.5 4.5-4.5" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert--error">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <circle cx="9" cy="9" r="8" stroke="#ef4444" stroke-width="1.5"/>
                <path d="M9 5v4M9 12v.5" stroke="#ef4444" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'form-input--error':'' }}" placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="email">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <rect x="1.5" y="3.5" width="15" height="11" rx="2" stroke="#94a3b8" stroke-width="1.5"/>
                        <path d="M1.5 6.5l7.5 5 7.5-5" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            <div class="form-group">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" class="form-link" style="font-size:.78rem">Lupa kata sandi?</a>
                </div>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'form-input--error':'' }}" placeholder="Masukkan kata sandi" required autocomplete="current-password">
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
            </div>

            <label class="checkbox-label">
                <input type="checkbox" name="remember" id="remember" class="checkbox-input">
                <span class="checkbox-box"></span>
                <span class="checkbox-text">Ingat saya di perangkat ini</span>
            </label>

            <button type="submit" class="btn-submit">
                Masuk
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M4 9h10M10 5l4 4-4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
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
            Masuk dengan Google
        </a>

        <p class="auth-card__footer">Belum punya akun? <a href="{{ route('register') }}" class="form-link form-link--bold">Daftar Sekarang</a></p>
    </div>
</div>

<style>
.auth-card__divider{display:flex;align-items:center;gap:.75rem;margin:1.25rem 0;color:#94A3B8;font-size:.78rem;}
.auth-card__divider::before,.auth-card__divider::after{content:"";flex:1;height:1px;background:#E2E8F0;}
.btn-google{display:flex;align-items:center;justify-content:center;gap:.65rem;width:100%;padding:.8rem;background:#fff;color:#0F172A;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;transition:background .18s,border-color .18s;}
.btn-google:hover{background:#F8FAFC;border-color:#94A3B8;}
</style>

<script>
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