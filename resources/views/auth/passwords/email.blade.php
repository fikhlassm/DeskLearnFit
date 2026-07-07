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
        <a href="{{ route('login') }}" class="auth-nav__back">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M10 12L6 8l4-4" stroke="#64748b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali ke Login
        </a>
    </div>

    <div class="auth-card">
        <div class="auth-card__header">
            <h1 class="auth-card__title">Lupa Password?</h1>
            <p class="auth-card__sub">Masukkan email Anda. Kami akan kirim link untuk membuat password baru.</p>
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

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'form-input--error':'' }}" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus autocomplete="email">
                    <svg class="input-icon" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <rect x="1.5" y="3.5" width="15" height="11" rx="2" stroke="#94a3b8" stroke-width="1.5"/>
                        <path d="M1.5 6.5l7.5 5 7.5-5" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Kirim Link Reset
            </button>
        </form>

        <div class="mt-7 flex flex-col items-center gap-2 border-t border-slate-200 pt-5 text-center">
            <p class="text-[0.85rem] text-slate-500">Ingat kata sandi Anda? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">Masuk ke akun</a></p>
            <p class="text-[0.85rem] text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">Daftar Sekarang</a></p>
        </div>
    </div>
</div>

@endsection
