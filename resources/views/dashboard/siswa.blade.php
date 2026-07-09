@extends('layouts.app')

@section('content')

@php
$methodMap = [
    'pomodoro'     => ['label'=>'Pomodoro',     'color'=>'#2563EB', 'bg'=>'#EFF6FF', 'icon'=>'⌚'],
    'active_recall'=> ['label'=>'Active Recall', 'color'=>'#7C3AED', 'bg'=>'#F5F3FF', 'icon'=>'🧠'],
    'blurting'     => ['label'=>'Blurting',      'color'=>'#059669', 'bg'=>'#ECFDF5', 'icon'=>'✍️'],
    'feynman'      => ['label'=>'Feynman',        'color'=>'#D97706', 'bg'=>'#FFFBEB', 'icon'=>'🏫'],
];
// Data real dari DashboardController
$totalCatatan    = $totalCatatan ?? 0;
$totalSesiSelesai = $totalSesiSelesai ?? 0;
$totalDurasi     = $totalDurasi ?? 0;
$kelasDiikuti    = $kelasDiikuti ?? collect();
$tugasBelumKumpul = $tugasBelumKumpul ?? collect();
$tugasSudahKumpul = $tugasSudahKumpul ?? collect();
$result   = Auth::user()->quiz_result;
$method   = $result ? ($methodMap[$result] ?? null) : null;
$userName = Auth::user()->name;
@endphp

<div class="dash-page">

    {{-- SIDEBAR --}}
    @include('dashboard._sidebar_siswa', ['active' => 'beranda'])

    {{-- MAIN --}}
    <main class="dash-main">

        {{-- TOP BAR --}}
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Buka Menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
            <div>
                <h1 class="topbar__title">Beranda</h1>
                <p class="topbar__sub">Semangat belajar, {{ explode(' ', $userName)[0] }}!</p>
            </div>
        </div>

        {{-- HERO: Real Data --}}
        <div class="hero-card">
            <div class="hero-card__left">
                <p class="hero-card__title" style="font-size:1.5rem; margin-bottom:1rem;">Halo, {{ explode(' ', $userName)[0] }}!</p>
                <div class="hero-stats">
                    <div class="stat-box">
                        <span class="stat-box__val">{{ $totalCatatan }}</span>
                        <span class="stat-box__lbl">Catatan Belajar</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-box__val">{{ $totalSesiSelesai }}</span>
                        <span class="stat-box__lbl">Sesi Selesai</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-box__val">{{ $kelasDiikuti->count() }}</span>
                        <span class="stat-box__lbl">Kelas Diikuti</span>
                    </div>
                </div>
                @if($totalDurasi > 0)
                <p class="hero-card__note" style="margin-top:1rem;font-weight:500;">Total <span style="color:#FFF;">{{ $totalDurasi }} menit</span> belajar sejauh ini. Terus semangat!</p>
                @else
                <p class="hero-card__note" style="margin-top:1rem;font-weight:500;">Mulai sesi belajar untuk melacak waktu belajarmu.</p>
                @endif
            </div>
            <div class="hero-card__right" style="justify-content:center; align-items:center;">
                <div style="background:rgba(255,255,255,0.15); padding:1.25rem; border-radius:16px; border:1px solid rgba(255,255,255,0.25); text-align:center; backdrop-filter:blur(8px); width:100%; min-width:200px;">
                    @if($kelasDiikuti->count() == 0)
                    <p style="font-size:0.85rem; font-weight:600; margin-bottom:0.75rem;">Belum mengikuti kelas</p>
                    @elseif($tugasBelumKumpul->count() > 0)
                    <div style="background:rgba(239,68,68,0.4); border:1px solid rgba(239,68,68,0.6); border-radius:8px; padding:0.4rem 0.75rem; margin-bottom:0.75rem;">
                        <p style="font-size:0.85rem; font-weight:700; color:#FFFFFF; margin:0;">⚠ {{ $tugasBelumKumpul->count() }} tugas belum dikumpulkan</p>
                    </div>
                    @else
                    <p style="font-size:0.85rem; font-weight:600; margin-bottom:0.75rem;">✓ Semua tugas terkumpul</p>
                    @endif
                    <a href="{{ route('siswa.kelas.index') }}" class="hero-btn" style="width:100%; justify-content:center;">Kelas Saya</a>
                </div>
            </div>
        </div>

        {{-- METODE BELAJAR (hanya jika sudah quiz) --}}
        @if($method)
        <section class="dash-section">
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
        <section class="dash-section">
            <div class="quiz-banner">
                <div>
                    <p class="quiz-banner__title">Temukan Metode Belajarmu!</p>
                    <p class="quiz-banner__desc">Ikuti quiz singkat untuk mendapatkan rekomendasi metode belajar yang paling cocok untukmu.</p>
                </div>
                <a href="{{ route('quiz') }}" class="quiz-banner__btn">Mulai Quiz Sekarang →</a>
            </div>
        </section>
        @endif

        {{-- RIWAYAT BELAJAR --}}
        <section class="dash-section">
            <div class="section__head">
                <h2 class="section__title">Tugas Perlu Dikumpulkan</h2>
            </div>
            @if($kelasDiikuti->count() == 0)
            <div style="background:#F1F5F9;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.25rem;color:#475569;font-size:.85rem;font-weight:600;text-align:center;">
                Belum ada kelas yang diikuti.
            </div>
            @elseif($tugasBelumKumpul->isEmpty())
            <div style="background:#DCFCE7;border:1px solid #6EE7B7;border-radius:14px;padding:1rem 1.25rem;color:#065F46;font-size:.85rem;font-weight:600;">
                ✓ Semua tugas sudah dikumpulkan!
            </div>
            @else
            <div class="history-list">
                @foreach($tugasBelumKumpul->take(3) as $tugas)
                <a href="{{ route('siswa.tugas.show', $tugas) }}" style="text-decoration:none">
                <div class="history-item">
                    <div class="history-icon" style="background:#FEF2F2;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <div class="history-item__body">
                        <p class="history-item__name">{{ $tugas->judul }}</p>
                        <p class="history-item__sub">{{ $tugas->kelas->nama_kelas }}</p>
                    </div>
                    <div class="history-item__right">
                        <span class="method-badge" style="background:#FEE2E2;color:#DC2626;">Belum Kumpul</span>
                        <div class="history-item__time">
                            <span class="history-item__date">{{ $tugas->deadline ? $tugas->deadline->format('d M') : 'No deadline' }}</span>
                        </div>
                    </div>
                </div>
                </a>
                @endforeach
            </div>
            @endif
        </section>

        {{-- TESTIMONI FORM --}}
        <section class="dash-section" style="margin-top: 2rem;">
            <div class="section__head">
                <h2 class="section__title">Bagikan Pengalaman Belajarmu</h2>
            </div>
            
            @if(session('success'))
            <div style="background:#DCFCE7;border:1px solid #6EE7B7;border-radius:14px;padding:1rem 1.25rem;color:#065F46;font-size:.85rem;font-weight:600;margin-bottom:1rem;">
                ✓ {{ session('success') }}
            </div>
            @endif

            <div style="background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1.5rem;">
                <form action="{{ route('testimoni.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:.5rem;">Rating Bintang</label>
                        <select name="rating" required style="width:100%;padding:.75rem 1rem;border:1px solid #E2E8F0;border-radius:10px;font-family:inherit;font-size:.85rem;outline:none;background:#F8FAFC;">
                            <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                            <option value="4">⭐⭐⭐⭐ Puas</option>
                            <option value="3">⭐⭐⭐ Biasa Saja</option>
                            <option value="2">⭐⭐ Kurang Puas</option>
                            <option value="1">⭐ Sangat Buruk</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:.5rem;">Ceritakan Pengalamanmu</label>
                        <textarea name="komentar" rows="4" placeholder="Bagaimana LearnFit membantu cara belajarmu?" required style="width:100%;padding:.75rem 1rem;border:1px solid #E2E8F0;border-radius:10px;font-family:inherit;font-size:.85rem;outline:none;resize:vertical;background:#F8FAFC;"></textarea>
                    </div>
                    <button type="submit" class="quiz-banner__btn">Kirim Testimoni</button>
                </form>
            </div>
        </section>

    </main>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- FAB --}}
    <a href="{{ route('sesi.index') }}" class="fab" title="Tambah Sesi Belajar">
        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"><path d="M11 4v14M4 11h14" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
    </a>
</div>

    </main>
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
}
.sidebar__avatar{
    width:36px;height:36px;border-radius:50%;
    background:#E2E8F0;
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
}
.sidebar__user-name{font-size:.82rem;font-weight:600;color:#0F172A;}
.sidebar__user-role{font-size:.68rem;color:#94A3B8;}

.dash-main{
    flex:1;display:flex;flex-direction:column;
    justify-content:flex-start;
    padding:1.5rem 2rem;gap:.65rem;overflow-x:hidden;
}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.hero-stats {
    display:flex; gap:1rem; flex-wrap:wrap; margin-top:0.5rem;
}
.stat-box {
    background:rgba(255,255,255,0.15);
    border:1px solid rgba(255,255,255,0.25);
    border-radius:12px; padding:0.8rem 1.2rem;
    display:flex; flex-direction:column; gap:0.2rem;
    min-width: 110px;
}
.stat-box__val {
    font-size:1.8rem; font-weight:800; line-height:1; color:#fff;
}
.stat-box__lbl {
    font-size:0.75rem; font-weight:600; opacity:0.85; text-transform:uppercase; letter-spacing:0.02em;
}

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
.dash-section{display:flex;flex-direction:column;gap:.85rem;animation:fadeInUp .35s ease both;}
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
    background:linear-gradient(135deg,#EFF6FF,#DBEAFE);
    border:1px solid #BFDBFE;
    border-radius:16px;padding:1.5rem;
    display:flex;align-items:center;justify-content:space-between;gap:1rem;
    transition:box-shadow .2s,transform .2s;
}
.quiz-banner:hover{box-shadow:0 6px 24px rgba(37,99,235,.12);transform:translateY(-2px);}
.quiz-banner__title{font-size:1rem;font-weight:700;color:#1E3A8A;margin-bottom:.3rem;}
.quiz-banner__desc{font-size:.8rem;color:#1D4ED8;line-height:1.5;}
.quiz-banner__btn{
    display:inline-flex;align-items:center;
    background:#2563EB;color:#fff;
    font-size:.82rem;font-weight:700;
    padding:.65rem 1.3rem;border-radius:12px;
    text-decoration:none;white-space:nowrap;flex-shrink:0;
    transition:background .18s,transform .15s,box-shadow .18s;
}
.quiz-banner__btn:hover{background:#1D4ED8;transform:translateY(-1px);box-shadow:0 4px 14px rgba(37,99,235,.3);}
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
    .hero-card__right{flex-direction:row;align-items:center;width:100%;}
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
</script>
@endsection