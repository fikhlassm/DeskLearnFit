@extends('layouts.app')
@section('content')

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__icon">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <rect x="6" y="10" width="36" height="28" rx="4" stroke="#2563EB" stroke-width="2"/>
                <path d="M6 14l18 12 18-12" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="auth-card__title">Verifikasi Email</h1>
        <p class="auth-card__desc">
            Kami sudah mengirim link verifikasi ke <strong>{{ auth()->user()->email }}</strong>.
            Klik link tersebut untuk mengaktifkan akun.
        </p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <p class="auth-card__sub-desc">Belum menerima email?</p>

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-primary">Kirim Ulang Email Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="auth-card__logout">
            @csrf
            <button type="submit" class="btn-link">Logout</button>
        </form>
    </div>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.auth-page{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#EFF6FF,#F1F5F9);font-family:'Plus Jakarta Sans',sans-serif;padding:1.5rem;}
.auth-card{background:#fff;border-radius:18px;box-shadow:0 10px 40px rgba(15,23,42,.08);padding:2.5rem;max-width:480px;width:100%;text-align:center;}
.auth-card__icon{margin-bottom:1rem;display:flex;justify-content:center;}
.auth-card__title{font-size:1.5rem;font-weight:800;color:#0F172A;margin-bottom:.5rem;letter-spacing:-.02em;}
.auth-card__desc{font-size:.88rem;color:#64748B;line-height:1.6;margin-bottom:1.5rem;}
.auth-card__sub-desc{font-size:.82rem;color:#94A3B8;margin:1rem 0 .5rem;}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;margin-bottom:1rem;text-align:left;}
.btn-primary{width:100%;padding:.85rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .18s;}
.btn-primary:hover{background:#1d4ed8;}
.auth-card__logout{margin-top:1rem;}
.btn-link{background:none;border:none;color:#64748B;font-size:.85rem;cursor:pointer;text-decoration:underline;font-family:inherit;}
</style>
@endsection
