@extends('layouts.app')
@section('content')

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__brand">
            <svg width="32" height="32" viewBox="0 0 28 28" fill="none"><rect width="28" height="28" rx="8" fill="#2563EB"/><path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
            <div>
                <p class="auth-card__name">LearnFit</p>
                <p class="auth-card__sub">Buat Password Baru</p>
            </div>
        </div>

        <h1 class="auth-card__title">Password Baru</h1>
        <p class="auth-card__desc">Masukkan password baru untuk akun <strong>{{ $email }}</strong>.</p>

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" required autofocus minlength="8" placeholder="Minimal 8 karakter">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required minlength="8" placeholder="Ulangi password baru">
            </div>
            <button type="submit" class="btn-primary">Reset Password</button>
        </form>

        <p class="auth-card__foot">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </p>
    </div>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.auth-page{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#EFF6FF,#F1F5F9);font-family:'Plus Jakarta Sans',sans-serif;padding:1.5rem;}
.auth-card{background:#fff;border-radius:18px;box-shadow:0 10px 40px rgba(15,23,42,.08);padding:2.5rem;max-width:440px;width:100%;}
.auth-card__brand{display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;}
.auth-card__name{font-size:1.1rem;font-weight:700;color:#0F172A;}
.auth-card__sub{font-size:.7rem;color:#94A3B8;}
.auth-card__title{font-size:1.6rem;font-weight:800;color:#0F172A;margin-bottom:.4rem;letter-spacing:-.02em;}
.auth-card__desc{font-size:.85rem;color:#64748B;margin-bottom:1.5rem;line-height:1.5;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.65rem 1rem;color:#991B1B;font-size:.83rem;margin-bottom:1rem;}
.auth-form{display:flex;flex-direction:column;gap:1rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.form-group input{padding:.7rem .9rem;border:1.5px solid #E2E8F0;border-radius:10px;font-size:.9rem;outline:none;font-family:inherit;background:#fff;}
.form-group input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}
.btn-primary{width:100%;padding:.85rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .18s;}
.btn-primary:hover{background:#1d4ed8;}
.auth-card__foot{text-align:center;margin-top:1.25rem;font-size:.85rem;color:#64748B;}
.auth-card__foot a{color:#2563EB;font-weight:600;text-decoration:none;}
.auth-card__foot a:hover{text-decoration:underline;}
</style>
@endsection
