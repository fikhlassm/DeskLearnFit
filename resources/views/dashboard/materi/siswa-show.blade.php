@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_siswa', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Detail Materi</h1></div>
        <div class="topbar__right"><a href="{{ route('siswa.materi.index', $materi->kelas_id) }}" class="btn-back">← Kembali</a></div>
    </div>

    <div class="materi-card">
        <div class="materi-card__header">
            <span class="badge-tipe badge-tipe--{{ $materi->tipe }}">{{ strtoupper($materi->tipe) }}</span>
            <p class="materi-card__date">{{ $materi->published_at?->format('d M Y') }}</p>
        </div>
        <h2 class="materi-card__judul">{{ $materi->judul }}</h2>
        @if($materi->deskripsi)<p class="materi-card__desc">{{ $materi->deskripsi }}</p>@endif

        @if($materi->tipe === 'link' && $materi->link_url)
        <a href="{{ $materi->link_url }}" target="_blank" rel="noopener" class="btn-link-ext">🔗 Buka Link Materi →</a>
        @elseif($materi->tipe === 'file' && $materi->file_path)
        <a href="{{ route('materi.download', $materi) }}" class="btn-link-ext">📎 Unduh / Lihat File</a>
        @endif

        @if($materi->konten)
        <div class="materi-card__konten">{!! nl2br(e($materi->konten)) !!}</div>
        @endif
    </div>
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{padding:.5rem 1rem;background:#F1F5F9;color:#475569;border-radius:10px;text-decoration:none;font-size:.82rem;font-weight:600;}
.materi-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.75rem;}
.materi-card__header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;}
.materi-card__date{font-size:.78rem;color:#94A3B8;}
.materi-card__judul{font-size:1.3rem;font-weight:800;color:#0F172A;margin-bottom:.6rem;}
.materi-card__desc{font-size:.88rem;color:#64748B;margin-bottom:1rem;line-height:1.6;}
.btn-link-ext{display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.25rem;background:#EFF6FF;color:#2563EB;border-radius:10px;text-decoration:none;font-size:.88rem;font-weight:600;margin-bottom:1rem;transition:background .18s;}
.btn-link-ext:hover{background:#DBEAFE;}
.materi-card__konten{font-size:.88rem;color:#374151;line-height:1.8;border-top:1px solid #F1F5F9;padding-top:1rem;}
.badge-tipe{font-size:.65rem;font-weight:700;padding:.18rem .55rem;border-radius:6px;}
.badge-tipe--teks{background:#EFF6FF;color:#2563EB;}
.badge-tipe--link{background:#F0FDF4;color:#15803D;}
.badge-tipe--file{background:#FFFBEB;color:#D97706;}
</style>
<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger)hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
if(overlay)overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
</script>
@endsection
