@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Tugas — {{ $kelas->nama_kelas }}</h1><p class="topbar__sub">Kelola tugas kelas ini</p></div>
        <div class="topbar__right"><a href="{{ route('dashboard.kelas') }}" class="btn-back">← Kembali</a></div>
    </div>
    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif

    <div class="section-card">
        <p class="section-card__title">Tambah Tugas Baru</p>
        <form method="POST" action="{{ route('tugas.store', $kelas) }}">
            @csrf
            @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
            <div class="form-group"><label>Judul *</label><input type="text" name="judul" value="{{ old('judul') }}" required class="form-input"></div>
            <div class="form-group"><label>Deskripsi / Instruksi *</label><textarea name="deskripsi" rows="3" required class="form-input">{{ old('deskripsi') }}</textarea></div>
            <div class="form-group"><label>Deadline (opsional)</label><input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="form-input"></div>
            <button type="submit" class="btn-primary">Simpan sebagai Draf</button>
        </form>
    </div>

    @if($tugasList->isEmpty())
    <div class="empty-state"><div class="empty-state__icon">📝</div><p class="empty-state__title">Belum ada tugas</p></div>
    @else
    <div class="list-card">
        @foreach($tugasList as $tugas)
        <div class="list-item">
            <div class="list-item__left">
                <p class="list-item__judul">{{ $tugas->judul }}</p>
                <p class="list-item__sub">
                    {{ $tugas->deadline ? 'Deadline: ' . $tugas->deadline->format('d M Y H:i') : 'Tanpa deadline' }}
                    · {{ $tugas->jawaban_tugas_count }} jawaban
                </p>
            </div>
            <div class="list-item__right">
                <span class="badge-status badge-status--{{ $tugas->status }}">{{ ucfirst($tugas->status) }}</span>
                @if($tugas->status === 'draf')
                <form method="POST" action="{{ route('tugas.publish', $tugas) }}" style="display:inline">@csrf @method('PATCH')
                    <button class="btn-sm btn-publish">Publish</button></form>
                @endif
                <a href="{{ route('tugas.jawaban.index', $tugas) }}" class="btn-sm btn-lihat">Jawaban</a>
                <a href="{{ route('tugas.edit', $tugas) }}" class="btn-sm btn-edit">Edit</a>
                <form method="POST" action="{{ route('tugas.destroy', $tugas) }}" onsubmit="return confirm('Hapus tugas ini?')" style="display:inline">@csrf @method('DELETE')
                    <button class="btn-sm btn-hapus">Hapus</button></form>
            </div>
        </div>
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
.section-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.section-card__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:1rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;}
.form-input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.btn-primary{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;}
.list-card{display:flex;flex-direction:column;gap:.65rem;}
.list-item{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.list-item__left{flex:1;}
.list-item__judul{font-size:.9rem;font-weight:600;color:#0F172A;}
.list-item__sub{font-size:.75rem;color:#94A3B8;margin-top:.2rem;}
.list-item__right{display:flex;align-items:center;gap:.5rem;flex-shrink:0;flex-wrap:wrap;}
.badge-status{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;}
.badge-status--draf{background:#F1F5F9;color:#64748B;}
.badge-status--terbit{background:#DCFCE7;color:#15803D;}
.badge-status--ditutup{background:#FEE2E2;color:#DC2626;}
.btn-sm{padding:.3rem .75rem;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;border:none;font-family:inherit;}
.btn-publish{background:#DCFCE7;color:#15803D;}
.btn-edit{background:#EFF6FF;color:#2563EB;text-decoration:none;}
.btn-lihat{background:#F5F3FF;color:#7C3AED;text-decoration:none;}
.btn-hapus{background:#FEF2F2;color:#DC2626;}
</style>
<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger)hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
if(overlay)overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
