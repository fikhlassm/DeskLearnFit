@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_siswa', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Tugas — {{ $kelas->nama_kelas }}</h1><p class="topbar__sub">Tugas dari kelas ini</p></div>
        <div class="topbar__right"><a href="{{ route('siswa.kelas.index') }}" class="btn-back">← Kelas Saya</a></div>
    </div>

    @if($tugasList->isEmpty())
    <div class="empty-state"><div class="empty-state__icon">📝</div><p class="empty-state__title">Belum ada tugas</p><p class="empty-state__sub">Pengajar belum mempublikasikan tugas.</p></div>
    @else
    <div class="list-card">
        @foreach($tugasList as $tugas)
        @php $statusJawaban = $jawabanStatus[$tugas->id] ?? null; @endphp
        <a href="{{ route('siswa.tugas.show', $tugas) }}" class="list-item list-item--link">
            <div class="list-item__left">
                <p class="list-item__judul">{{ $tugas->judul }}</p>
                <p class="list-item__sub">{{ $tugas->deadline ? 'Deadline: ' . $tugas->deadline->format('d M Y H:i') : 'Tanpa deadline' }}</p>
            </div>
            <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">
                @if($statusJawaban)
                    <span class="badge-jawaban badge-jawaban--{{ $statusJawaban }}">
                        {{ $statusJawaban === 'dinilai' ? '✓ Dinilai' : ($statusJawaban === 'terlambat' ? '⚠ Terlambat' : '✓ Dikumpulkan') }}
                    </span>
                @else
                    <span class="badge-jawaban badge-jawaban--belum">Belum Dikumpulkan</span>
                @endif
                @if($tugas->deadline && $tugas->deadline->isPast() && !$statusJawaban)
                    <span class="badge-jawaban badge-jawaban--terlambat">⚠ Lewat Deadline</span>
                @endif
            </div>
        </a>
        @endforeach
    </div>
    <div style="margin-top:.75rem">{{ $tugasList->links() }}</div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{padding:.5rem 1rem;background:#F1F5F9;color:#475569;border-radius:10px;text-decoration:none;font-size:.82rem;font-weight:600;}
.list-card{display:flex;flex-direction:column;gap:.65rem;}
.list-item{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;transition:box-shadow .2s,transform .2s;}
.list-item--link{text-decoration:none;color:inherit;cursor:pointer;}
.list-item--link:hover{box-shadow:0 4px 14px rgba(15,23,42,.08);transform:translateY(-1px);}
.list-item__left{flex:1;}
.list-item__judul{font-size:.9rem;font-weight:600;color:#0F172A;}
.list-item__sub{font-size:.75rem;color:#94A3B8;margin-top:.2rem;}
.badge-jawaban{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;white-space:nowrap;}
.badge-jawaban--terkirim{background:#DBEAFE;color:#1D4ED8;}
.badge-jawaban--dinilai{background:#DCFCE7;color:#15803D;}
.badge-jawaban--terlambat{background:#FEE2E2;color:#DC2626;}
.badge-jawaban--belum{background:#F1F5F9;color:#64748B;}
</style>
<script>
const s=document.querySelector('.sidebar'),o=document.getElementById('sidebarOverlay'),h=document.getElementById('hamburgerBtn');
if(h)h.addEventListener('click',()=>{s.classList.add('sidebar--open');o.classList.add('overlay--show');});
if(o)o.addEventListener('click',()=>{s.classList.remove('sidebar--open');o.classList.remove('overlay--show');});
</script>
@endsection
