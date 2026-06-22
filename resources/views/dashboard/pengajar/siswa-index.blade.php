@extends('layouts.app')
@section('content')

@php
    $metodeInfo = [
        'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB', 'bg'=>'#EFF6FF', 'icon'=>'⏱️'],
        'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED', 'bg'=>'#F5F3FF', 'icon'=>'🧠'],
        'blurting'     => ['label'=>'Blurting',       'color'=>'#059669', 'bg'=>'#ECFDF5', 'icon'=>'✍️'],
        'feynman'      => ['label'=>'Feynman',        'color'=>'#D97706', 'bg'=>'#FFFBEB', 'icon'=>'🏫'],
    ];
@endphp

<div class="dash-page">
    @include('dashboard._sidebar_pengajar', ['active' => 'siswa'])

    <main class="dash-main">
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
            <div>
                <h1 class="topbar__title">Daftar Siswa</h1>
                <p class="topbar__sub">Siswa yang mengikuti kelas Anda</p>
            </div>
            <div class="topbar__right">
                <form method="GET" action="{{ route('siswa.index') }}" class="topbar__search">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="topbar__search-input">
                    <button type="submit" class="topbar__search-btn">🔍</button>
                </form>
            </div>
        </div>

        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

        <div class="siswa-list">
            @forelse($siswa as $s)
            <a href="{{ route('siswa.show', $s) }}" class="siswa-card">
                <div class="siswa-card__avatar">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
                <div class="siswa-card__body">
                    <p class="siswa-card__name">{{ $s->name }}</p>
                    <p class="siswa-card__email">{{ $s->email }}</p>
                    <div class="siswa-card__kelas">
                        @foreach($s->kelas_diikuti as $k)
                            <span class="siswa-card__kelas-chip">{{ $k }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="siswa-card__stats">
                    @if($s->quiz_result && isset($metodeInfo[$s->quiz_result]))
                    <span class="siswa-card__metode" style="background:{{ $metodeInfo[$s->quiz_result]['bg'] }};color:{{ $metodeInfo[$s->quiz_result]['color'] }}">
                        {{ $metodeInfo[$s->quiz_result]['icon'] }} {{ $metodeInfo[$s->quiz_result]['label'] }}
                    </span>
                    @else
                    <span class="siswa-card__metode" style="background:#F1F5F9;color:#64748B">Belum Quiz</span>
                    @endif
                    <span class="siswa-card__stat">{{ $s->total_sesi_selesai }} sesi selesai</span>
                </div>
            </a>
            @empty
            <div class="empty-state">
                <p class="empty-state__title">Belum ada siswa</p>
                <p class="empty-state__sub">Siswa akan muncul di sini setelah mereka bergabung ke kelas Anda.</p>
            </div>
            @endforelse
        </div>

        <div class="pagination-wrap">{{ $siswa->links() }}</div>
    </main>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.25rem;overflow-x:hidden;}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}
.topbar__search{display:flex;align-items:center;gap:.4rem;background:#fff;border:1px solid #E2E8F0;border-radius:10px;padding:.3rem .5rem .3rem .85rem;}
.topbar__search-input{border:none;outline:none;font-size:.85rem;font-family:inherit;width:220px;background:transparent;color:#0F172A;}
.topbar__search-btn{background:none;border:none;cursor:pointer;font-size:1rem;padding:0 .25rem;}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.65rem 1rem;color:#991B1B;font-size:.83rem;}
.siswa-list{display:flex;flex-direction:column;gap:.75rem;}
.siswa-card{display:flex;align-items:center;gap:1rem;background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.25rem;text-decoration:none;color:inherit;transition:border-color .15s,box-shadow .15s;}
.siswa-card:hover{border-color:#2563EB;box-shadow:0 4px 16px rgba(37,99,235,.08);}
.siswa-card__avatar{width:48px;height:48px;border-radius:50%;background:#2563EB;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;flex-shrink:0;}
.siswa-card__body{flex:1;min-width:0;}
.siswa-card__name{font-size:.95rem;font-weight:700;color:#0F172A;}
.siswa-card__email{font-size:.78rem;color:#64748B;margin-top:.1rem;}
.siswa-card__kelas{display:flex;flex-wrap:wrap;gap:.3rem;margin-top:.4rem;}
.siswa-card__kelas-chip{font-size:.68rem;font-weight:600;background:#EFF6FF;color:#1D4ED8;padding:.15rem .5rem;border-radius:6px;}
.siswa-card__stats{display:flex;flex-direction:column;align-items:flex-end;gap:.4rem;flex-shrink:0;}
.siswa-card__metode{font-size:.7rem;font-weight:700;padding:.25rem .65rem;border-radius:6px;}
.siswa-card__stat{font-size:.7rem;color:#64748B;}
.empty-state{text-align:center;padding:2.5rem 1rem;background:#fff;border:1px dashed #E2E8F0;border-radius:16px;}
.empty-state__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:.3rem;}
.empty-state__sub{font-size:.8rem;color:#64748B;}
.pagination-wrap{margin-top:.5rem;}
@media(max-width:700px){.topbar__search-input{width:140px;}.siswa-card{flex-wrap:wrap;}.siswa-card__stats{width:100%;flex-direction:row;align-items:center;}}
</style>
@endsection
