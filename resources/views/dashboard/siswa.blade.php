@extends('layouts.app')

@section('content')

@php
$methodMap = [
    'pomodoro'     => ['label'=>'Pomodoro',     'color'=>'#2563EB', 'bg'=>'#EFF6FF', 'icon'=>'⌚'],
    'active_recall'=> ['label'=>'Active Recall', 'color'=>'#7C3AED', 'bg'=>'#F5F3FF', 'icon'=>'🧠'],
    'blurting'     => ['label'=>'Blurting',      'color'=>'#059669', 'bg'=>'#ECFDF5', 'icon'=>'✍️'],
    'feynman'      => ['label'=>'Feynman',        'color'=>'#D97706', 'bg'=>'#FFFBEB', 'icon'=>'🏫'],
];
$result   = Auth::user()->quiz_result;
$method   = $result ? ($methodMap[$result] ?? null) : null;
$userName = Auth::user()->name;
@endphp

<div class="dash-page">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar__brand">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                <rect width="28" height="28" rx="8" fill="#2563EB"/>
                <path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <div>
                <p class="sidebar__brand-name">LearnFit</p>
                <p class="sidebar__brand-sub">Platform Belajar Anda</p>
            </div>
        </div>

        <nav class="sidebar__nav">
            <a href="{{ route('dashboard.siswa') }}" class="sidebar__link sidebar__link--active">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" fill="currentColor"/><rect x="11" y="2" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/><rect x="2" y="11" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/><rect x="11" y="11" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/></svg>
                Beranda
            </a>
            <a href="{{ route('catatan.index') }}" class="sidebar__link">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M6 8h8M6 11h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Catatan Belajar
            </a>
            <a href="{{ route('sesi.index') }}" class="sidebar__link">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.6"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Sesi Belajar
            </a>
            <a href="{{ route('profil.show') }}" class="sidebar__link">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.6"/><path d="M3 18c0-3.31 3.13-6 7-6s7 2.69 7 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                Profil
            </a>
        </nav>

        <div class="sidebar__user">
            <div class="sidebar__avatar">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="3.5" stroke="#64748b" stroke-width="1.5"/><path d="M3 18c0-3.31 3.13-6 7-6s7 2.69 7 6" stroke="#64748b" stroke-width="1.5" stroke-linecap="round"/></svg>
            </div>
            <div>
                <p class="sidebar__user-name">{{ $userName }}</p>
                <p class="sidebar__user-role">Siswa</p>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="dash-main">

        {{-- TOP BAR --}}
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Buka Menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
            <div>
                <h1 class="topbar__title">Beranda</h1>
                <p class="topbar__sub">Semangat belajar, {{ explode(' ', $userName)[0] }}! 🎯</p>
            </div>
            <div class="topbar__right">
                <div class="search-box" id="searchBox">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="6.5" cy="6.5" r="5" stroke="#94a3b8" stroke-width="1.5"/><path d="M10.5 10.5l3 3" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="text" placeholder="Cari materi, sesi..." id="searchInput">
                    <button class="search-box__close" id="searchClose" aria-label="Tutup pencarian">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 2l10 10M12 2L2 12" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"/></svg>
                    </button>
                </div>
                <button class="topbar__icon-btn search-toggle" id="searchToggle" aria-label="Cari">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="8.5" cy="8.5" r="5.5" stroke="#475569" stroke-width="1.5"/><path d="M13 13l3.5 3.5" stroke="#475569" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <button class="topbar__icon-btn" aria-label="Notifikasi" style="position:relative;">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 2a6 6 0 00-6 6v2.586l-1.707 1.707A1 1 0 003 14h14a1 1 0 00.707-1.707L16 10.586V8a6 6 0 00-6-6z" stroke="#475569" stroke-width="1.5"/><path d="M8 14a2 2 0 004 0" stroke="#475569" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <span class="notif-dot"></span>
                </button>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="topbar__icon-btn" title="Logout">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- HERO: Kemajuan Mingguan --}}
        <div class="hero-card">
            <div class="hero-card__left">
                <p class="hero-card__eyebrow">Kemajuan Mingguan</p>
                <p class="hero-card__title">Ringkasan Belajar Mingguan</p>
                <p class="hero-card__big">12.5 jam <span class="hero-card__target">/ 15 jam target</span></p>
                <div class="hero-progress">
                    <div class="hero-progress__fill" style="width:83%"></div>
                </div>
                <p class="hero-card__note">Hebat! Kamu tinggal 2.5 jam lagi mencapai target mingguanmu.</p>
            </div>
            <div class="hero-card__right">
                <span class="hero-badge">+11% Mingguan</span>
                <a href="#" class="hero-btn">Lihat Statistik Detail →</a>
            </div>
        </div>

        {{-- METODE BELAJAR (hanya jika sudah quiz) --}}
        @if($method)
        <section class="section">
            <div class="section__head">
                <h2 class="section__title">Metode Belajarmu</h2>
                @if($result)
                <a href="{{ route('quiz.retake') }}" class="section__link">Ikut Quiz Ulang</a>
                @endif
            </div>
            <div class="method-card" style="--mc:#{{ ltrim($method['color'],'#') }}; --mcbg:{{ $method['bg'] }};">
                <div class="method-card__icon">{{ $method['icon'] }}</div>
                <div class="method-card__body">
                    <span class="method-card__label">Cocok Untukmu</span>
                    <p class="method-card__name">{{ $method['label'] }} Method</p>
                    <p class="method-card__desc">Metode ini dipilih berdasarkan hasil quiz gaya belajarmu. Terapkan secara konsisten untuk hasil terbaik.</p>
                </div>
                <a href="{{ route('quiz.result') }}" class="method-card__btn">Lihat Detail Hasil</a>
            </div>
        </section>
        @else
        <section class="section">
            <div class="quiz-banner">
                <div>
                    <p class="quiz-banner__title">Temukan Metode Belajarmu! 🎯</p>
                    <p class="quiz-banner__desc">Ikuti quiz singkat untuk mendapatkan rekomendasi metode belajar yang paling cocok untukmu.</p>
                </div>
                <a href="{{ route('quiz') }}" class="quiz-banner__btn">Mulai Quiz Sekarang →</a>
            </div>
        </section>
        @endif

        {{-- RIWAYAT BELAJAR --}}
        <section class="section">
            <div class="section__head">
                <h2 class="section__title">Riwayat Belajar</h2>
                <div style="display:flex;gap:.5rem;align-items:center;">
                    <button class="filter-btn">
                        <svg width="13" height="13" viewBox="0 0 14 14" fill="none"><path d="M1 3h12M3 7h8M5 11h4" stroke="#475569" stroke-width="1.4" stroke-linecap="round"/></svg>
                        Filter
                    </button>
                    <a href="#" class="section__link">Lihat Semua</a>
                </div>
            </div>
            <div class="history-list">

                <div class="history-item">
                    <div class="history-icon" style="background:#EFF6FF;">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M4 10h12M10 4l6 6-6 6" stroke="#2563EB" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="history-item__body">
                        <p class="history-item__name">Fungsi</p>
                        <p class="history-item__sub">Kalkulus • 60m</p>
                    </div>
                    <div class="history-item__right">
                        <span class="method-badge" style="background:#EFF6FF;color:#2563EB;">Pomodoro</span>
                        <div class="history-item__time">
                            <span class="history-item__date">Today</span>
                            <span class="history-item__clock">14:20 WIB</span>
                        </div>
                    </div>
                </div>

                <div class="history-item">
                    <div class="history-icon" style="background:#ECFDF5;">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="7" cy="10" r="3" stroke="#059669" stroke-width="1.6"/><circle cx="13" cy="7" r="2" stroke="#059669" stroke-width="1.5"/><circle cx="13" cy="13" r="2" stroke="#059669" stroke-width="1.5"/><path d="M9.8 8.8l1.5-1M9.8 11.2l1.5 1" stroke="#059669" stroke-width="1.3"/></svg>
                    </div>
                    <div class="history-item__body">
                        <p class="history-item__name">Clustering</p>
                        <p class="history-item__sub">Statistika Deskriptif • 45m</p>
                    </div>
                    <div class="history-item__right">
                        <span class="method-badge" style="background:#ECFDF5;color:#059669;">Mind Mapping</span>
                        <div class="history-item__time">
                            <span class="history-item__date">Kemarin</span>
                            <span class="history-item__clock">09:15 WIB</span>
                        </div>
                    </div>
                </div>

                <div class="history-item">
                    <div class="history-icon" style="background:#FFFBEB;">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 3v4M10 13v4M3 10h4M13 10h4" stroke="#D97706" stroke-width="1.6" stroke-linecap="round"/><circle cx="10" cy="10" r="2.5" stroke="#D97706" stroke-width="1.5"/></svg>
                    </div>
                    <div class="history-item__body">
                        <p class="history-item__name">Gaya</p>
                        <p class="history-item__sub">Fisika • 30m</p>
                    </div>
                    <div class="history-item__right">
                        <span class="method-badge" style="background:#FFFBEB;color:#D97706;">Feynman</span>
                        <div class="history-item__time">
                            <span class="history-item__date">24 Okt 2023</span>
                            <span class="history-item__clock">16:45 WIB</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- FAB --}}
    <button class="fab" title="Tambah Sesi Belajar">
        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M11 4v14M4 11h14" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

.dash-page{
    display:flex;min-height:100vh;
    font-family:'Plus Jakarta Sans',sans-serif;
    background:#F1F5F9;color:#0F172A;
}

/* ── SIDEBAR (identik dgn pengajar) ── */
.sidebar{
    width:240px;flex-shrink:0;
    background:#fff;border-right:1px solid #E2E8F0;
    display:flex;flex-direction:column;
    padding:1.25rem 0;
    position:sticky;top:0;height:100vh;overflow-y:auto;
}
.sidebar__brand{
    display:flex;align-items:center;gap:.7rem;
    padding:.25rem 1.25rem 1.25rem;
    border-bottom:1px solid #F1F5F9;margin-bottom:.5rem;
}
.sidebar__brand-name{font-size:.95rem;font-weight:700;color:#0F172A;line-height:1.2;}
.sidebar__brand-sub{font-size:.68rem;color:#94A3B8;}
.sidebar__nav{flex:1;display:flex;flex-direction:column;gap:.15rem;padding:.5rem .75rem;}
.sidebar__link{
    display:flex;align-items:center;gap:.7rem;
    padding:.65rem .85rem;border-radius:10px;
    text-decoration:none;font-size:.85rem;font-weight:500;color:#475569;
    transition:background .18s,color .18s;
}
.sidebar__link:hover{background:#F8FAFC;color:#2563EB;}
.sidebar__link--active{background:#EFF6FF;color:#2563EB;font-weight:600;}
.sidebar__link:active{background:#DBEAFE;}
.sidebar__user{
    display:flex;align-items:center;gap:.7rem;
    padding:.85rem 1.25rem;border-top:1px solid #F1F5F9;
    border-radius:10px;margin:.5rem .75rem 0;
    transition:background .18s;cursor:pointer;
}
.sidebar__user:hover{background:#F8FAFC;}
.sidebar__avatar{
    width:36px;height:36px;border-radius:50%;
    background:#E2E8F0;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.sidebar__user-name{font-size:.82rem;font-weight:600;color:#0F172A;}
.sidebar__user-role{font-size:.68rem;color:#94A3B8;}

/* ── MAIN ── */
.dash-main{
    flex:1;display:flex;flex-direction:column;
    padding:1.5rem 2rem;gap:1.5rem;overflow-x:hidden;
}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}
.topbar__icon-btn{
    width:38px;height:38px;border:1px solid #E2E8F0;
    background:#fff;border-radius:10px;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:background .18s,transform .15s;
}
.topbar__icon-btn:hover{background:#F1F5F9;}
.topbar__icon-btn:active{background:#E2E8F0;transform:scale(.93);}
.notif-dot{
    position:absolute;top:7px;right:7px;
    width:8px;height:8px;border-radius:50%;
    background:#EF4444;border:1.5px solid #fff;
}
.search-box{
    display:flex;align-items:center;gap:.5rem;
    background:#fff;border:1px solid #E2E8F0;
    border-radius:10px;padding:.5rem .9rem;
    width:260px;transition:border-color .18s,box-shadow .18s;
}
.search-box:focus-within{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}
.search-box input{border:none;outline:none;font-size:.83rem;color:#0F172A;font-family:inherit;width:100%;background:transparent;}
.search-box input::placeholder{color:#94A3B8;}
.search-box__close{display:none;background:none;border:none;cursor:pointer;padding:0;line-height:0;flex-shrink:0;}
.search-toggle{display:none;}

/* ── HERO CARD ── */
.hero-card{
    background:linear-gradient(135deg,#2563EB 0%,#3B82F6 60%,#60A5FA 100%);
    border-radius:20px;padding:1.75rem 2rem;
    display:flex;align-items:flex-end;justify-content:space-between;gap:1.5rem;
    color:#fff;
    animation:fadeInUp .35s ease both;
    transition:box-shadow .2s,transform .2s;
}
.hero-card:hover{box-shadow:0 10px 32px rgba(37,99,235,.35);transform:translateY(-2px);}
.hero-card__left{flex:1;}
.hero-card__eyebrow{font-size:.75rem;font-weight:600;opacity:.8;margin-bottom:.3rem;letter-spacing:.04em;}
.hero-card__title{font-size:1.1rem;font-weight:700;margin-bottom:.6rem;}
.hero-card__big{font-size:2.8rem;font-weight:800;letter-spacing:-.04em;line-height:1;}
.hero-card__target{font-size:1rem;font-weight:500;opacity:.7;letter-spacing:0;}
.hero-progress{
    height:8px;background:rgba(255,255,255,.25);
    border-radius:99px;overflow:hidden;
    margin:1rem 0 .6rem;max-width:520px;
}
.hero-progress__fill{
    height:100%;background:#fff;border-radius:99px;
    transition:width 1s cubic-bezier(.4,0,.2,1);
}
.hero-card__note{font-size:.8rem;opacity:.85;}
.hero-card__right{display:flex;flex-direction:column;align-items:flex-end;gap:1rem;flex-shrink:0;}
.hero-badge{
    font-size:.72rem;font-weight:700;
    background:rgba(255,255,255,.2);
    border:1px solid rgba(255,255,255,.3);
    padding:.3rem .75rem;border-radius:99px;
    backdrop-filter:blur(4px);
}
.hero-btn{
    display:inline-flex;align-items:center;gap:.4rem;
    background:#fff;color:#2563EB;
    font-size:.82rem;font-weight:700;
    padding:.6rem 1.2rem;border-radius:12px;
    text-decoration:none;white-space:nowrap;
    transition:box-shadow .18s,transform .15s;
}
.hero-btn:hover{box-shadow:0 4px 14px rgba(0,0,0,.15);transform:translateY(-1px);}
.hero-btn:active{transform:scale(.97);}

/* ── SECTION ── */
.section{display:flex;flex-direction:column;gap:.85rem;animation:fadeInUp .35s ease both;}
.section__head{display:flex;align-items:center;justify-content:space-between;}
.section__title{font-size:1rem;font-weight:700;color:#0F172A;}
.section__link{font-size:.82rem;font-weight:600;color:#2563EB;text-decoration:none;transition:color .18s;}
.section__link:hover{text-decoration:underline;}

/* ── METHOD CARD ── */
.method-card{
    background:#fff;border:1px solid #E2E8F0;
    border-radius:16px;padding:1.25rem 1.5rem;
    display:flex;align-items:center;gap:1.25rem;
    border-left:4px solid var(--mc);
    transition:box-shadow .2s,transform .2s;
}
.method-card:hover{box-shadow:0 6px 24px rgba(37,99,235,.1);transform:translateY(-2px);}
.method-card__icon{
    font-size:2rem;width:56px;height:56px;
    border-radius:14px;background:var(--mcbg);
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.method-card__body{flex:1;}
.method-card__label{
    font-size:.68rem;font-weight:700;letter-spacing:.05em;
    color:var(--mc);background:var(--mcbg);
    padding:.18rem .55rem;border-radius:6px;
    display:inline-block;margin-bottom:.35rem;
}
.method-card__name{font-size:1.05rem;font-weight:700;color:#0F172A;margin-bottom:.25rem;}
.method-card__desc{font-size:.78rem;color:#64748B;line-height:1.5;}
.method-card__btn{
    font-size:.8rem;font-weight:600;color:#2563EB;
    padding:.55rem 1.1rem;border-radius:10px;
    border:1.5px solid #DBEAFE;background:#EFF6FF;
    text-decoration:none;white-space:nowrap;flex-shrink:0;
    transition:background .18s,border-color .18s,transform .15s;
}
.method-card__btn:hover{background:#DBEAFE;border-color:#93C5FD;transform:translateY(-1px);}
.method-card__btn:active{transform:scale(.96);}

/* ── QUIZ BANNER ── */
.quiz-banner{
    background:linear-gradient(135deg,#F5F3FF,#EDE9FE);
    border:1px solid #DDD6FE;
    border-radius:16px;padding:1.5rem;
    display:flex;align-items:center;justify-content:space-between;gap:1rem;
    transition:box-shadow .2s,transform .2s;
}
.quiz-banner:hover{box-shadow:0 6px 24px rgba(124,58,237,.12);transform:translateY(-2px);}
.quiz-banner__title{font-size:1rem;font-weight:700;color:#4C1D95;margin-bottom:.3rem;}
.quiz-banner__desc{font-size:.8rem;color:#5B21B6;line-height:1.5;}
.quiz-banner__btn{
    display:inline-flex;align-items:center;
    background:#7C3AED;color:#fff;
    font-size:.82rem;font-weight:700;
    padding:.65rem 1.3rem;border-radius:12px;
    text-decoration:none;white-space:nowrap;flex-shrink:0;
    transition:background .18s,transform .15s,box-shadow .18s;
}
.quiz-banner__btn:hover{background:#6D28D9;transform:translateY(-1px);box-shadow:0 4px 14px rgba(124,58,237,.3);}
.quiz-banner__btn:active{transform:scale(.97);}

/* ── RIWAYAT ── */
.history-list{display:flex;flex-direction:column;gap:.65rem;}
.history-item{
    background:#fff;border:1px solid #E2E8F0;
    border-radius:14px;padding:1rem 1.25rem;
    display:flex;align-items:center;gap:1rem;
    transition:box-shadow .2s,transform .2s,border-color .2s;
}
.history-item:hover{box-shadow:0 4px 16px rgba(15,23,42,.08);transform:translateY(-1px);border-color:#DBEAFE;}
.history-icon{
    width:44px;height:44px;border-radius:12px;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.history-item__body{flex:1;}
.history-item__name{font-size:.9rem;font-weight:600;color:#0F172A;}
.history-item__sub{font-size:.75rem;color:#94A3B8;margin-top:.1rem;}
.history-item__right{display:flex;align-items:center;gap:1rem;flex-shrink:0;}
.method-badge{font-size:.72rem;font-weight:700;padding:.22rem .65rem;border-radius:8px;}
.history-item__time{text-align:right;}
.history-item__date{display:block;font-size:.8rem;font-weight:600;color:#0F172A;}
.history-item__clock{display:block;font-size:.72rem;color:#94A3B8;}

/* ── FILTER BTN ── */
.filter-btn{
    display:flex;align-items:center;gap:.35rem;
    font-size:.78rem;font-weight:600;color:#475569;
    padding:.38rem .8rem;border-radius:8px;
    border:1.5px solid #E2E8F0;background:#fff;
    cursor:pointer;font-family:inherit;
    transition:color .18s,border-color .18s;
}
.filter-btn:hover{color:#2563EB;border-color:#2563EB;}

/* ── FAB ── */
.fab{
    position:fixed;bottom:2rem;right:2rem;
    width:52px;height:52px;border-radius:50%;
    background:#2563EB;border:none;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;box-shadow:0 4px 18px rgba(37,99,235,.4);
    transition:background .18s,transform .18s,box-shadow .18s;
}
.fab:hover{background:#1d4ed8;transform:scale(1.08);box-shadow:0 6px 24px rgba(37,99,235,.5);}
.fab:active{transform:scale(.95);}

/* ── ANIMATIONS ── */
@keyframes fadeInUp{
    from{opacity:0;transform:translateY(16px);}
    to{opacity:1;transform:translateY(0);}
}
.hero-card{animation-delay:.05s;}
.section:nth-child(3){animation-delay:.12s;}
.section:nth-child(4){animation-delay:.19s;}

.hamburger{
    display:none;align-items:center;justify-content:center;
    width:38px;height:38px;border-radius:10px;
    border:1px solid #E2E8F0;background:#fff;
    cursor:pointer;flex-shrink:0;
    transition:background .18s;
}
.hamburger:hover{background:#F1F5F9;}
.sidebar-overlay{display:none;}

/* ── RESPONSIVE ── */
@media(max-width:900px){
    .hamburger{display:flex;}
    .sidebar{
        position:fixed;top:0;left:-260px;z-index:200;
        height:100vh;width:240px;
        transition:left .28s cubic-bezier(.4,0,.2,1);
        box-shadow:none;
    }
    .sidebar.sidebar--open{
        left:0;
        box-shadow:4px 0 24px rgba(15,23,42,.15);
    }
    .sidebar-overlay{
        display:none;position:fixed;inset:0;
        background:rgba(15,23,42,.35);z-index:199;
        backdrop-filter:blur(2px);
        transition:opacity .28s;opacity:0;
    }
    .sidebar-overlay.overlay--show{display:block;opacity:1;}
    .hero-card{flex-direction:column;align-items:flex-start;}
    .hero-card__right{flex-direction:row;align-items:center;}
    .search-box{display:none;}
    .search-box.search-box--open{
        display:flex;
        position:fixed;top:12px;left:50%;transform:translateX(-50%);
        width:calc(100vw - 2rem);max-width:420px;
        z-index:300;box-shadow:0 4px 20px rgba(15,23,42,.15);
    }
    .search-box.search-box--open .search-box__close{display:flex;}
    .search-toggle{display:flex;}
}
@media(max-width:560px){
    .dash-main{padding:1rem;}
    .hero-card__big{font-size:2rem;}
    .method-card{flex-direction:column;align-items:flex-start;}
    .quiz-banner{flex-direction:column;align-items:flex-start;}
    .history-item__right{flex-direction:column;align-items:flex-end;gap:.4rem;}
}
</style>

<script>
const sidebar = document.querySelector('.sidebar');
const overlay = document.getElementById('sidebarOverlay');
const hamburger = document.getElementById('hamburgerBtn');

hamburger.addEventListener('click', () => {
    sidebar.classList.add('sidebar--open');
    overlay.classList.add('overlay--show');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('sidebar--open');
    overlay.classList.remove('overlay--show');
});
// Tutup drawer saat klik link sidebar
document.querySelectorAll('.sidebar__link').forEach(link => {
    link.addEventListener('click', () => {
        sidebar.classList.remove('sidebar--open');
        overlay.classList.remove('overlay--show');
    });
});

const searchBox = document.getElementById('searchBox');
const searchToggle = document.getElementById('searchToggle');
const searchClose = document.getElementById('searchClose');
const searchInput = document.getElementById('searchInput');

searchToggle.addEventListener('click', () => {
    searchBox.classList.add('search-box--open');
    searchInput.focus();
});
searchClose.addEventListener('click', () => {
    searchBox.classList.remove('search-box--open');
    searchInput.value = '';
});
</script>
@endsection