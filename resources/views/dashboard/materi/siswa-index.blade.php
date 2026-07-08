@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_siswa', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Materi — {{ $kelas->nama_kelas }}</h1><p class="topbar__sub">Materi yang tersedia di kelas ini</p></div>
        <div class="topbar__right"><a href="{{ route('siswa.kelas.index') }}" class="btn-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Kelas Saya</a></div>
    </div>

    @if($materiList->isEmpty())
    <div class="empty-state"><div class="empty-state__icon" style="display:flex;justify-content:center;color:#94A3B8;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></div><p class="empty-state__title">Belum ada materi</p><p class="empty-state__sub">Pengajar belum mempublikasikan materi.</p></div>
    @else
    <div class="list-card">
        @foreach($materiList as $materi)
        <div class="list-item">
            <div class="list-item__icon badge-tipe--{{ $materi->tipe }}">{{ $materi->tipe === 'link' ? '🔗' : ($materi->tipe === 'file' ? '📎' : '📄') }}</div>
            <div class="list-item__left">
                <p class="list-item__judul"><a href="{{ route('siswa.materi.show', $materi) }}">{{ $materi->judul }}</a></p>
                <p class="list-item__sub">{{ $materi->published_at?->format('d M Y') }}</p>
            </div>
            <span class="badge-tipe badge-tipe--{{ $materi->tipe }}">{{ strtoupper($materi->tipe) }}</span>
            @if($materi->tipe === 'file' && $materi->file_path)
            <a href="{{ route('materi.download', $materi) }}" class="btn-dl" title="Unduh">⬇</a>
            @elseif($materi->tipe === 'link' && $materi->link_url)
            <a href="{{ $materi->link_url }}" target="_blank" rel="noopener" class="btn-dl" title="Buka link">↗</a>
            @endif
        </div>
        @endforeach
    </div>
    <div style="margin-top:.75rem">{{ $materiList->links() }}</div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:500;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);}
.list-card{display:flex;flex-direction:column;gap:.65rem;}
.list-item{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;transition:box-shadow .2s,transform .2s;}
.list-item--link{text-decoration:none;color:inherit;cursor:pointer;}
.list-item--link:hover{box-shadow:0 4px 14px rgba(15,23,42,.08);transform:translateY(-1px);}
.list-item__icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;background:#F1F5F9;}
.list-item__left{flex:1;}
.list-item__judul{font-size:.9rem;font-weight:600;color:#0F172A;}
.list-item__sub{font-size:.75rem;color:#94A3B8;margin-top:.1rem;}
.badge-tipe{font-size:.65rem;font-weight:700;padding:.18rem .55rem;border-radius:6px;}
.badge-tipe--teks{background:#EFF6FF;color:#2563EB;}
.badge-tipe--link{background:#F0FDF4;color:#15803D;}
.badge-tipe--file{background:#FFFBEB;color:#D97706;}
.btn-dl{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;background:#EFF6FF;color:#2563EB;text-decoration:none;font-size:1rem;font-weight:700;transition:background .15s;}
.btn-dl:hover{background:#DBEAFE;}
.list-item__judul a{color:#0F172A;text-decoration:none;font-weight:600;}
.list-item__judul a:hover{color:#2563EB;text-decoration:underline;}
</style>
<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger)hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
if(overlay)overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
</script>
@endsection
