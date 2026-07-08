@extends('layouts.app')
@section('content')

@php
    $metodeInfo = [
        'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB', 'bg'=>'#EFF6FF', 'icon'=>'⏱️'],
        'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED', 'bg'=>'#F5F3FF', 'icon'=>'🧠'],
        'blurting'     => ['label'=>'Blurting',       'color'=>'#059669', 'bg'=>'#ECFDF5', 'icon'=>'✍️'],
        'feynman'      => ['label'=>'Feynman',        'color'=>'#D97706', 'bg'=>'#FFFBEB', 'icon'=>'🏫'],
    ];
    $metode = $siswa->quiz_result ? ($metodeInfo[$siswa->quiz_result] ?? null) : null;
@endphp

<div class="dash-page">
    @include('dashboard._sidebar_pengajar', ['active' => 'siswa'])

    <main class="dash-main">
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
            <div>
                <a href="{{ route('siswa.index') }}" style="color:#64748B;text-decoration:none;font-size:.78rem;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Kembali ke daftar siswa</a>
                <h1 class="topbar__title">Profil Siswa</h1>
            </div>
            <div class="topbar__right"></div>
        </div>

        <div class="profile-card">
            <div class="profile-card__avatar">{{ strtoupper(collect(explode(' ', trim($siswa->name)))->map(fn($w) => mb_substr($w,0,1))->take(2)->join('')) }}</div>
            <div class="profile-card__body">
                <h2 class="profile-card__name">{{ $siswa->name }}</h2>
                <p class="profile-card__email">{{ $siswa->email }}</p>
                <p class="profile-card__join">Bergabung {{ $siswa->created_at->format('d M Y') }}</p>
                @if($metode)
                <span class="profile-card__metode" style="background:{{ $metode['bg'] }};color:{{ $metode['color'] }}">
                    ✦ Metode utama: {{ $metode['icon'] }} {{ $metode['label'] }}
                </span>
                @else
                <span class="profile-card__metode" style="background:#FEF3C7;color:#92400E">⚠ Belum mengerjakan quiz</span>
                @endif
            </div>
        </div>

        <div class="stat-grid">
            <div class="stat-box"><span class="stat-box__num">{{ $totalSesi }}</span><span class="stat-box__label">Total Sesi</span></div>
            <div class="stat-box" data-tone="ok"><span class="stat-box__num">{{ $totalSelesai }}</span><span class="stat-box__label">Sesi Selesai</span></div>
            <div class="stat-box" data-tone="primary"><span class="stat-box__num">{{ $totalDurasi }}</span><span class="stat-box__label">Menit Belajar</span></div>
            <div class="stat-box"><span class="stat-box__num">{{ $totalJurnal }}</span><span class="stat-box__label">Jurnal</span></div>
            <div class="stat-box" data-tone="primary"><span class="stat-box__num">{{ $totalKelas }}</span><span class="stat-box__label">Kelas Anda</span></div>
        </div>

        @if($sesiByMetode->count() > 0)
        <section class="dash-section">
            <h2 class="section__title">Distribusi Metode Belajar</h2>
            <div class="metode-list">
                @foreach($metodeInfo as $key => $info)
                    @php $row = $sesiByMetode->firstWhere('metode', $key); @endphp
                    <div class="metode-row">
                        <div class="metode-row__icon" style="background:{{ $info['bg'] }};color:{{ $info['color'] }}">{{ $info['icon'] }}</div>
                        <div class="metode-row__body">
                            <p class="metode-row__name">{{ $info['label'] }}</p>
                            <p class="metode-row__sub">{{ $row?->total ?? 0 }} sesi selesai · {{ $row?->total_durasi ?? 0 }} menit</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <section class="dash-section">
            <h2 class="section__title">5 Sesi Terbaru</h2>
            @if($sesiTerbaru->isEmpty())
            <div class="empty-state"><p class="empty-state__sub">Belum ada sesi belajar.</p></div>
            @else
            <div class="sesi-list">
                @foreach($sesiTerbaru as $sesi)
                @php $info = $metodeInfo[$sesi->metode] ?? $metodeInfo['pomodoro']; @endphp
                <div class="sesi-row">
                    <div class="sesi-row__icon" style="background:{{ $info['bg'] }}">{{ $info['icon'] }}</div>
                    <div class="sesi-row__body">
                        <p class="sesi-row__name">{{ $sesi->judul ?: $info['label'] }}</p>
                        <p class="sesi-row__sub">{{ $info['label'] }} · {{ $sesi->durasi_fokus_menit }}m · {{ $sesi->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="sesi-row__status sesi-row__status--{{ $sesi->status }}">{{ ucfirst($sesi->status) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </section>
    </main>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.25rem;overflow-x:hidden;}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;transition:background .18s, transform .15s;}
.hamburger:hover{background:#F1F5F9;}
.hamburger:active{background:#E2E8F0;transform:scale(.93);}
.profile-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;display:flex;align-items:center;gap:1.25rem;}
.profile-card__avatar{width:72px;height:72px;border-radius:50%;background:#2563EB;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;flex-shrink:0;}
.profile-card__name{font-size:1.25rem;font-weight:800;color:#0F172A;}
.profile-card__email{font-size:.85rem;color:#64748B;margin-top:.1rem;}
.profile-card__join{font-size:.75rem;color:#94A3B8;margin-top:.25rem;}
.profile-card__metode{display:inline-block;margin-top:.65rem;font-size:.78rem;font-weight:700;padding:.3rem .75rem;border-radius:6px;}
.stat-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:.75rem;}
.stat-box{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:1rem;text-align:center;display:flex;flex-direction:column;gap:.25rem;}
.stat-box[data-tone="ok"]{background:#F0FDF4;border-color:#86EFAC;}
.stat-box[data-tone="primary"]{background:#EFF6FF;border-color:#93C5FD;}
.stat-box__num{font-size:1.4rem;font-weight:800;color:#0F172A;}
.stat-box__label{font-size:.7rem;color:#64748B;font-weight:600;}
.dash-section{display:flex;flex-direction:column;gap:.85rem;}
.section__title{font-size:1rem;font-weight:700;color:#0F172A;}
.metode-list,.sesi-list{display:flex;flex-direction:column;gap:.5rem;}
.metode-row,.sesi-row{background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:.85rem 1.1rem;display:flex;align-items:center;gap:1rem;}
.metode-row__icon,.sesi-row__icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.metode-row__body,.sesi-row__body{flex:1;}
.metode-row__name,.sesi-row__name{font-size:.88rem;font-weight:700;color:#0F172A;}
.metode-row__sub,.sesi-row__sub{font-size:.75rem;color:#64748B;margin-top:.1rem;}
.sesi-row__status{font-size:.7rem;font-weight:700;padding:.25rem .65rem;border-radius:6px;}
.sesi-row__status--aktif{background:#DBEAFE;color:#1D4ED8;}
.sesi-row__status--selesai{background:#DCFCE7;color:#15803D;}
.sesi-row__status--batal{background:#F1F5F9;color:#64748B;}
.empty-state{text-align:center;padding:1.5rem;background:#fff;border:1px dashed #E2E8F0;border-radius:12px;}
.empty-state__sub{font-size:.82rem;color:#64748B;}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr);}}
</style>
@endsection
