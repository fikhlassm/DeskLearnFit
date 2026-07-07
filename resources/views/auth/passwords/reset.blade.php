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
    </div>

    <div class="auth-card">
        <div class="auth-card__header">
            <h1 class="auth-card__title">Password Baru</h1>
            <p class="auth-card__sub">Masukkan password baru untuk akun <strong>{{ $email }}</strong>.</p>
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

        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label class="form-label" for="password">Password Baru</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'form-input--error':'' }}" placeholder="Minimal 8 karakter" required autofocus minlength="8" autocomplete="new-password">
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

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                <div class="input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Ulangi password baru" required minlength="8" autocomplete="new-password">
                    <button type="button" class="input-icon input-icon--btn" id="togglePasswordConfirm" aria-label="Lihat password">
                        <svg id="eyeShowConfirm" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M1 9s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6z" stroke="#94a3b8" stroke-width="1.5"/>
                            <circle cx="9" cy="9" r="2.5" stroke="#94a3b8" stroke-width="1.5"/>
                        </svg>
                        <svg id="eyeHideConfirm" width="18" height="18" viewBox="0 0 18 18" fill="none" style="display:none">
                            <path d="M1 9s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6z" stroke="#94a3b8" stroke-width="1.5"/>
                            <line x1="2" y1="2" x2="16" y2="16" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Reset Password
            </button>
        </form>
    </div>
</div>

<script>
function setupToggle(btnId, inputId, showId, hideId) {
    const btn = document.getElementById(btnId);
    const input = document.getElementById(inputId);
    const show = document.getElementById(showId);
    const hide = document.getElementById(hideId);
    if(btn) {
        btn.addEventListener('click', () => {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            show.style.display = isHidden ? 'none' : 'block';
            hide.style.display = isHidden ? 'block' : 'none';
        });
    }
}
setupToggle('togglePassword', 'password', 'eyeShow', 'eyeHide');
setupToggle('togglePasswordConfirm', 'password_confirmation', 'eyeShowConfirm', 'eyeHideConfirm');
</script>

@endsection
