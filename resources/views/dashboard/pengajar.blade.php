@extends('layouts.app')

@section('content')

<div class="dash-page">

    {{-- SIDEBAR --}}
    @include('dashboard._sidebar_pengajar', ['active' => 'beranda'])

    {{-- MAIN --}}
    <main class="dash-main">

        {{-- TOP BAR --}}
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Buka Menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
            <div>
                <h1 class="topbar__title">Halo, {{ Auth::user()->name }}!</h1>
                <p class="topbar__sub">Semoga harimu menyenangkan.</p>
            </div>
            <div class="topbar__right">
            </div>
        </div>

        {{-- RINGKASAN --}}
        <section class="dash-section">
            <h2 class="section__title">Ringkasan Pengajaran</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon" style="background:#F3E8FF;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" stroke="#9333EA" stroke-width="1.8" stroke-linecap="round"/><circle cx="9" cy="7" r="4" stroke="#9333EA" stroke-width="1.8"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke="#9333EA" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </div>
                    </div>
                    <p class="stat-card__label">Total Siswa</p>
                    <p class="stat-card__value">{{ $totalSiswa ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon" style="background:#ECFDF5;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect x="2" y="3" width="20" height="14" rx="2" stroke="#10B981" stroke-width="1.8"/><path d="M8 21h8M12 17v4" stroke="#10B981" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </div>
                    </div>
                    <p class="stat-card__label">Kelas Aktif</p>
                    <p class="stat-card__value">{{ $totalKelas ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card__top">
                        <div class="stat-card__icon" style="background:#FFFBEB;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#F59E0B" stroke-width="1.8"/><path d="M12 6v6l4 2" stroke="#F59E0B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        @if(($totalJawabanBelumDinilai ?? 0) > 0)
                        <span class="stat-card__badge stat-card__badge--red">{{ $totalJawabanBelumDinilai }} belum dinilai</span>
                        @endif
                    </div>
                    <p class="stat-card__label">Tugas Terbit</p>
                    <p class="stat-card__value">{{ $totalTugasTerbit ?? 0 }}</p>
                </div>
            </div>
        </section>

        {{-- JADWAL + TUGAS --}}
        <div class="bottom-grid">

            {{-- Jadwal --}}
            <section class="dash-section" style="display:flex; flex-direction:column;">
                <div class="section__head">
                    <h2 class="section__title">Jadwal Mengajar Hari Ini</h2>
                    <!-- <a href="#" class="section__link">Lihat Semua</a> -->
                </div>
                <div class="schedule-list" style="flex:1; display:flex; flex-direction:column;">
                    @if($jadwalHariIni->isEmpty())
                        <div style="flex:1; background:#fff;border:2px dashed #E2E8F0;border-radius:14px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.75rem;min-height:250px;text-align:center;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" opacity="0.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="16" y1="2" x2="16" y2="6" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="8" y1="2" x2="8" y2="6" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="3" y1="10" x2="21" y2="10" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div>
                                <p style="color:#334155;font-weight:600;font-size:0.9rem;">Waktu Bebas!</p>
                                <p style="color:#64748B;font-size:0.85rem;">Tidak ada jadwal mengajar untuk hari ini.</p>
                            </div>
                        </div>
                    @else
                        @foreach($jadwalHariIni as $jadwal)
                            @php
                                $now = \Carbon\Carbon::now();
                                $mulai = \Carbon\Carbon::parse($jadwal->jam_mulai);
                                $selesai = \Carbon\Carbon::parse($jadwal->jam_selesai);
                                $isActive = $now->between($mulai, $selesai);
                                $theme = $jadwal->kelas->theme_color ?? '#4F46E5';
                            @endphp
                            <div class="sched-card {{ $isActive ? 'sched-card--active' : '' }}" style="--theme: {{ $theme }};">
                                <div class="sched-card__time">
                                    <span class="sched-card__hour" style="color: var(--theme);">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</span>
                                    <span class="sched-card__end">{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                                </div>
                                <div class="sched-card__body">
                                    @if($isActive)
                                        <span class="sched-badge sched-badge--live">Sedang Berlangsung</span>
                                    @endif
                                    <p class="sched-card__name">{{ $jadwal->kelas->nama_kelas ?? 'Kelas' }}</p>
                                    <div class="sched-card__meta">
                                        <span>
                                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none"><path d="M7 1a5 5 0 100 10A5 5 0 007 1zM7 3v4l2.5 1.5" stroke="#94a3b8" stroke-width="1.3" stroke-linecap="round"/></svg>
                                            {{ $jadwal->ruang ?: 'Online' }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('materi.index', $jadwal->kelas_id) }}" class="sched-btn {{ $isActive ? 'sched-btn--primary' : 'sched-btn--ghost' }}">Masuk Kelas</a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>

            {{-- Tugas --}}
            <section class="dash-section" style="display:flex; flex-direction:column;">
                <h2 class="section__title">Tugas Menunggu Dinilai</h2>
                <div class="task-card" style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                    @if($tugasTerbaru->isEmpty())
                        <div style="text-align:center; display:flex; flex-direction:column; align-items:center; gap:0.75rem; padding: 2rem 1rem;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" opacity="0.5"><path d="M12 20h9M9 20v-2a3 3 0 013-3h.5m-6.5 5H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v7.5M9 8h6m-6 4h6" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <div>
                                <p style="color:#334155;font-weight:600;font-size:0.9rem;">Semua Tugas Selesai</p>
                                <p style="color:#64748B;font-size:0.85rem;">Belum ada tugas baru yang perlu dinilai.</p>
                            </div>
                        </div>
                    @else
                        @foreach($tugasTerbaru as $tugas)
                            @php
                                $totalSiswa = $tugas->kelas->siswa()->count();
                                $dikumpulkan = $tugas->jawaban_tugas_count;
                                $pct = $totalSiswa > 0 ? round(($dikumpulkan / $totalSiswa) * 100) : 0;
                                $theme = $tugas->kelas->theme_color ?? '#4F46E5';
                            @endphp
                            <a href="{{ route('tugas.jawaban.index', $tugas->id) }}" class="task-item task-item-link" style="--theme: {{ $theme }}; text-decoration: none;">
                                <div class="task-item__head">
                                    <p class="task-item__name">{{ $tugas->judul }}</p>
                                    <span class="task-badge" style="background: {{ $theme }}1a; color: {{ $theme }}; border: 1px solid {{ $theme }}33;">{{ $dikumpulkan }}/{{ $totalSiswa }}</span>
                                </div>
                                <p class="task-item__class" style="color: {{ $theme }}; opacity: 0.8; font-weight: 600;">{{ $tugas->kelas->nama_kelas }}</p>
                                <div class="task-bar"><div class="task-bar__fill" style="width:{{ $pct }}%; background: {{ $theme }};"></div></div>
                                <div class="task-item__foot">
                                    <span class="task-item__deadline" data-deadline="{{ \Carbon\Carbon::parse($tugas->deadline)->toIso8601String() }}">Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->diffForHumans(['parts' => 3]) }}</span>
                                    <span class="task-item__pct">{{ $pct }}% Dikumpulkan</span>
                                </div>
                            </a>
                        @endforeach
                        <!-- <a href="#" class="btn-all-tasks">Lihat Semua Tugas</a> -->
                    @endif
                </div>
            </section>

        </div>

    </main>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>

    </main>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

.dash-page{
    display:flex;
    min-height:100vh;
    font-family:'Plus Jakarta Sans',sans-serif;
    background:#F1F5F9;
    color:#0F172A;
}

/* ── SIDEBAR ── */
.sidebar{
    width:240px;
    flex-shrink:0;
    background:#fff;
    border-right:1px solid #E2E8F0;
    display:flex;
    flex-direction:column;
    padding:1.25rem 0;
    position:sticky;
    top:0;
    height:100vh;
    overflow-y:auto;
}
.sidebar__brand{
    display:flex;
    align-items:center;
    gap:.7rem;
    padding:.25rem 1.25rem 1.25rem;
    border-bottom:1px solid #F1F5F9;
    margin-bottom:.5rem;
}
.sidebar__brand-name{font-size:.95rem;font-weight:700;color:#0F172A;line-height:1.2;}
.sidebar__brand-sub{font-size:.68rem;color:#94A3B8;}

.sidebar__nav{
    flex:1;
    display:flex;
    flex-direction:column;
    gap:.15rem;
    padding:.5rem .75rem;
}
.sidebar__link{
    display:flex;
    align-items:center;
    gap:.7rem;
    padding:.65rem .85rem;
    border-radius:10px;
    text-decoration:none;
    font-size:.85rem;
    font-weight:500;
    color:#475569;
    transition:background .18s,color .18s;
}
.sidebar__link:hover{background:#F8FAFC;color:#2563EB;}
.sidebar__link--active{background:#EFF6FF;color:#2563EB;font-weight:600;}

.sidebar__user{
    display:flex;
    align-items:center;
    gap:.7rem;
    padding:1rem 1.25rem;
    border-top:1px solid #F1F5F9;
    margin-top:auto;
}
.sidebar__avatar{
    width:36px;height:36px;
    border-radius:50%;
    background:#E2E8F0;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}
.sidebar__user-name{font-size:.82rem;font-weight:600;color:#0F172A;}
.sidebar__user-role{font-size:.68rem;color:#94A3B8;}

/* ── MAIN ── */
.dash-main{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:flex-start;
    padding:1.5rem 2rem;
    gap:.65rem;
    overflow-x:hidden;
}

/* ── TOPBAR ── */
.topbar{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:1rem;
}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}

.search-box{
    display:flex;align-items:center;gap:.5rem;
    background:#fff;border:1px solid #E2E8F0;
    border-radius:10px;padding:.5rem .9rem;
    width:260px;
}
.search-box input{
    border:none;outline:none;
    font-size:.83rem;color:#0F172A;
    font-family:inherit;width:100%;
    background:transparent;
}
.search-box input::placeholder{color:#94A3B8;}
.search-box__close{display:none;background:none;border:none;cursor:pointer;padding:0;line-height:0;flex-shrink:0;}
.search-toggle{display:none;}

.topbar__icon-btn{
    width:38px;height:38px;
    border:1px solid #E2E8F0;
    background:#fff;border-radius:10px;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:background .18s;
}
.topbar__icon-btn:hover{background:#F1F5F9;}

/* ── SECTION ── */
.dash-section{display:flex;flex-direction:column;gap:.85rem;}
.section__head{display:flex;align-items:center;justify-content:space-between;}
.section__title{font-size:1rem;font-weight:700;color:#0F172A;}
.section__link{font-size:.82rem;font-weight:600;color:#2563EB;text-decoration:none;}
.section__link:hover{text-decoration:underline;}

/* ── STATS ── */
.stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
.stat-card{
    background:#fff;border:1px solid #E2E8F0;
    border-radius:16px;padding:1.25rem;
    display:flex;flex-direction:column;gap:.5rem;
}
.stat-card__top{display:flex;align-items:center;justify-content:space-between;margin-bottom:.25rem;}
.stat-card__icon{
    width:42px;height:42px;border-radius:10px;
    display:flex;align-items:center;justify-content:center;
}
.stat-card__badge{font-size:.68rem;font-weight:700;padding:.2rem .55rem;border-radius:99px;}
.stat-card__badge--green{background:#DCFCE7;color:#15803D;}
.stat-card__badge--gray{background:#F1F5F9;color:#64748B;}
.stat-card__label{font-size:.78rem;color:#64748B;}
.stat-card__value{font-size:2rem;font-weight:800;color:#0F172A;letter-spacing:-.04em;line-height:1;}

/* ── BOTTOM GRID ── */
.bottom-grid{display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:stretch;}

/* ── SCHEDULE ── */
.schedule-list{display:flex;flex-direction:column;gap:.75rem;}
.sched-card{
    background:#fff;border:1px solid #E2E8F0;
    border-radius:14px;padding:1rem 1.1rem;
    display:flex;align-items:center;gap:1rem;
    border-left:3px solid transparent;
}
.sched-card--active{border-left-color:#2563EB;}
.sched-card__time{
    display:flex;flex-direction:column;
    align-items:flex-end;flex-shrink:0;
    min-width:44px;
}
.sched-card__hour{font-size:.92rem;font-weight:700;color:#0F172A;}
.sched-card__end{font-size:.72rem;color:#94A3B8;}
.sched-card__body{flex:1;display:flex;flex-direction:column;gap:.25rem;}
.sched-badge{display:inline-flex;width:fit-content;}
.sched-badge--live{
    font-size:.65rem;font-weight:700;
    background:#DCFCE7;color:#15803D;
    padding:.18rem .55rem;border-radius:99px;
}
.sched-card__name{font-size:.9rem;font-weight:600;color:#0F172A;}
.sched-card__meta{display:flex;gap:.9rem;}
.sched-card__meta span{
    display:flex;align-items:center;gap:.28rem;
    font-size:.72rem;color:#94A3B8;
}
.sched-btn{
    flex-shrink:0;
    font-size:.78rem;font-weight:600;
    border-radius:9px;padding:.5rem 1rem;
    border:none;cursor:pointer;
    font-family:inherit;
    transition:all .18s;
    white-space:nowrap;
    text-decoration:none;
    display:inline-block;
    text-align:center;
}
.sched-btn--primary{background:var(--theme);color:#fff;border:1.5px solid var(--theme);}
.sched-btn--primary:hover{opacity:0.85;transform:translateY(-1px);}
.sched-btn--ghost{background:#fff;color:var(--theme);border:1.5px solid var(--theme);}
.sched-btn--ghost:hover{background:var(--theme);color:#fff;transform:translateY(-1px);box-shadow:0 2px 8px color-mix(in srgb, var(--theme) 30%, transparent);}


/* ── TUGAS ── */
.task-card{
    background:#fff;border:1px solid #E2E8F0;
    border-radius:16px;padding:1.1rem;
    display:flex;flex-direction:column;gap:1rem;
}
.task-item{display:flex;flex-direction:column;gap:.35rem;}
.task-item__head{display:flex;align-items:center;justify-content:space-between;}
.task-item__name{font-size:.88rem;font-weight:600;color:#0F172A;}
.task-badge{font-size:.7rem;font-weight:700;padding:.18rem .55rem;border-radius:6px;}
.task-badge--blue{background:#DBEAFE;color:#1D4ED8;}
.task-badge--red{background:#FEE2E2;color:#DC2626;}
.task-badge--green{background:#DCFCE7;color:#15803D;}
.task-item__class{font-size:.72rem;color:#64748B;}
.task-bar{height:6px;background:#F1F5F9;border-radius:99px;overflow:hidden;}
.task-bar__fill{height:100%;border-radius:99px;transition:width .4s;}
.task-bar__fill--blue{background:#2563EB;}
.task-bar__fill--red{background:#EF4444;}
.task-bar__fill--green{background:#22C55E;}
.task-item__foot{display:flex;flex-direction:column;gap:.35rem;}
.task-item__deadline{font-size:.7rem;color:#64748B;font-variant-numeric:tabular-nums;}
.task-item__deadline--urgent{color:#EF4444;font-weight:600;}
.task-item__pct{font-size:.7rem;color:#94A3B8;}

.btn-all-tasks{
    display:flex;align-items:center;justify-content:center;
    padding:.65rem;border-radius:10px;
    border:1.5px solid #E2E8F0;background:#fff;
    font-size:.82rem;font-weight:600;color:#475569;
    text-decoration:none;transition:color .2s,border-color .2s,box-shadow .2s,transform .15s;
    font-family:inherit;
}
.btn-all-tasks:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);transform:translateY(-1px);}

/* ── HOVER & ACTIVE STATES ── */

/* Stat cards */
.stat-card{transition:box-shadow .2s,transform .2s;}
.stat-card:hover{box-shadow:0 6px 24px rgba(37,99,235,.10);transform:translateY(-2px);}

/* Schedule cards */
.sched-card{--theme:#4F46E5; border-left: 4px solid var(--theme); transition:box-shadow .2s,transform .2s,border-color .2s;}
.sched-card:hover{box-shadow:0 4px 18px rgba(15,23,42,.08);transform:translateY(-1px);}
.sched-card--active{border-color:var(--theme);box-shadow:0 4px 12px color-mix(in srgb, var(--theme) 15%, transparent);}
.sched-card--active:hover{border-left-color:var(--theme);box-shadow:0 6px 18px color-mix(in srgb, var(--theme) 25%, transparent);}
.sched-badge--live{background:color-mix(in srgb, var(--theme) 15%, transparent);color:var(--theme);}

/* Schedule buttons active (klik) */
.sched-btn:active{transform:scale(.96);}
.sched-btn--primary:active{background:#1e40af;}
.sched-btn--ghost:active{background:#CBD5E1;}

/* Task items */
.task-item{--theme:#4F46E5; border-left: 4px solid var(--theme); padding:.8rem 1rem; border-radius:10px; transition:background .18s, border-color .2s, box-shadow .2s; border-top:1px solid #E2E8F0; border-right:1px solid #E2E8F0; border-bottom:1px solid #E2E8F0;}
.task-item:hover{background:#F8FAFC; border-color:var(--theme); box-shadow:0 4px 12px color-mix(in srgb, var(--theme) 10%, transparent);}
.task-item-link:hover .task-item__name{color:var(--theme);}

/* Lihat Semua Tugas button */
.btn-all-tasks:active{background:#E2E8F0;transform:scale(.98);}

/* Sidebar links active */
.sidebar__link:active{background:#DBEAFE;}

.sidebar__user{border-radius:10px;margin:.5rem .75rem 0;padding:.85rem 1.25rem;}

/* Topbar icon buttons active */
.topbar__icon-btn:active{background:#E2E8F0;transform:scale(.93);}

/* Search box focus */
.search-box{transition:border-color .18s,box-shadow .18s;}
.search-box:focus-within{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}

/* Section link */
.section__link{transition:color .18s;}
.section__link:active{color:#1d4ed8;}

.hamburger{
    display:none;align-items:center;justify-content:center;
    width:38px;height:38px;border-radius:10px;
    border:1px solid #E2E8F0;background:#fff;
    cursor:pointer;flex-shrink:0;
    transition:background .18s;
}
.hamburger:hover{background:#F1F5F9;}
.sidebar-overlay{display:none;}

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
    .bottom-grid{grid-template-columns:1fr;}
    .stats-grid{grid-template-columns:1fr 1fr;}
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
    .stats-grid{grid-template-columns:1fr;}
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

// Real-time Countdown
function updateDeadlines() {
    document.querySelectorAll('[data-deadline]').forEach(el => {
        const deadline = new Date(el.getAttribute('data-deadline'));
        const now = new Date();
        const diff = deadline - now;
        
        if (diff <= 0) {
            el.textContent = 'Tenggat: Waktu habis';
            el.classList.add('task-item__deadline--urgent');
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        let text = 'Tenggat: ';
        if(days > 0) text += days + ' hari ';
        if(hours > 0 || days > 0) text += hours + ' jam ';
        if(minutes > 0 || hours > 0 || days > 0) text += minutes + ' menit ';
        text += seconds + ' detik dari sekarang';
        
        el.textContent = text;
        
        // Add urgent styling if less than 24 hours
        if (days < 1 && !el.classList.contains('task-item__deadline--urgent')) {
            el.classList.add('task-item__deadline--urgent');
        }
    });
}
setInterval(updateDeadlines, 1000);
updateDeadlines();
</script>
@endsection
