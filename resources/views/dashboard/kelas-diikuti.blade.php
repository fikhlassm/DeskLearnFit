@extends('layouts.app')
@section('content')

<div class="dash-page">
@include('dashboard._sidebar_siswa', ['active' => 'kelas'])

<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Kelas Saya</h1><p class="topbar__sub">Kelas yang kamu ikuti</p></div>
        <div class="topbar__right">
            <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
                <button type="submit" class="topbar__icon-btn" title="Logout"><svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif

    {{-- Form Join --}}
    <div class="join-card">
        <p class="join-card__title">Bergabung ke Kelas Baru</p>
        <form method="POST" action="{{ route('siswa.kelas.join') }}" class="join-form">
            @csrf
            <div class="join-form__field">
                <input type="text" name="kode_kelas" placeholder="Masukkan kode kelas..." value="{{ old('kode_kelas') }}"
                    class="join-input {{ $errors->has('kode_kelas') ? 'input-error' : '' }}" maxlength="20">
                <button type="submit" class="btn-join">Bergabung</button>
            </div>
            @error('kode_kelas')<p class="form-error">{{ $message }}</p>@enderror
        </form>
    </div>

    {{-- Daftar Kelas --}}
    @if($kelasDiikuti->isEmpty())
    <div class="empty-state">
        <div class="empty-state__icon">🎓</div>
        <p class="empty-state__title">Belum ada kelas</p>
        <p class="empty-state__sub">Masukkan kode kelas di atas untuk bergabung.</p>
    </div>
    @else
    <div class="kelas-grid">
        @foreach($kelasDiikuti as $kelas)
        <div class="kelas-card">
            <div class="kelas-card__header">
                <span class="kelas-badge">{{ $kelas->mata_pelajaran }}</span>
                <span class="kelas-anggota">{{ $kelas->siswa_count }} siswa</span>
            </div>
            <p class="kelas-card__nama">{{ $kelas->nama_kelas }}</p>
            <p class="kelas-card__pengajar">Pengajar: {{ $kelas->pengajar->name ?? '-' }}</p>
            <p class="kelas-card__kode">Kode: <code>{{ $kelas->kode_kelas }}</code></p>
            <div class="kelas-card__actions">
                <a href="{{ route('siswa.materi.index', $kelas) }}" class="btn-lihat-materi">📚 Materi</a>
                <a href="{{ route('siswa.tugas.index', $kelas) }}" class="btn-lihat-tugas">📝 Tugas</a>
                <form method="POST" action="{{ route('siswa.kelas.leave', $kelas) }}" onsubmit="return confirm('Keluar dari kelas {{ $kelas->nama_kelas }}?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-leave">Keluar</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.25rem;}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}
.topbar__icon-btn{width:38px;height:38px;border:1px solid #E2E8F0;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.65rem 1rem;color:#991B1B;font-size:.83rem;}
.join-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.25rem 1.5rem;}
.join-card__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:.85rem;}
.join-form{display:flex;flex-direction:column;gap:.4rem;}
.join-form__field{display:flex;gap:.5rem;}
.join-input{flex:1;padding:.6rem 1rem;border:1px solid #E2E8F0;border-radius:10px;font-size:.88rem;outline:none;font-family:inherit;transition:border-color .18s;}
.join-input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.input-error{border-color:#EF4444;}
.btn-join{padding:.6rem 1.25rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;white-space:nowrap;font-family:inherit;transition:background .18s;}
.btn-join:hover{background:#1d4ed8;}
.form-error{font-size:.78rem;color:#EF4444;}
.kelas-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;}
.kelas-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.25rem;display:flex;flex-direction:column;gap:.6rem;transition:box-shadow .2s,transform .2s;}
.kelas-card:hover{box-shadow:0 4px 18px rgba(15,23,42,.08);transform:translateY(-2px);}
.kelas-card__header{display:flex;align-items:center;justify-content:space-between;}
.kelas-badge{font-size:.72rem;font-weight:700;background:#EFF6FF;color:#2563EB;padding:.22rem .65rem;border-radius:99px;}
.kelas-anggota{font-size:.72rem;color:#94A3B8;}
.kelas-card__nama{font-size:1rem;font-weight:700;color:#0F172A;}
.kelas-card__pengajar{font-size:.8rem;color:#64748B;}
.kelas-card__kode{font-size:.78rem;color:#94A3B8;}code{background:#F1F5F9;padding:.1rem .4rem;border-radius:5px;font-size:.75rem;}
.kelas-card__actions{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.35rem;}
.btn-lihat-materi,.btn-lihat-tugas{padding:.38rem .75rem;border-radius:8px;font-size:.75rem;font-weight:600;text-decoration:none;transition:background .18s;}
.btn-lihat-materi{background:#EFF6FF;color:#2563EB;}
.btn-lihat-tugas{background:#F5F3FF;color:#7C3AED;}
.btn-leave{padding:.38rem .75rem;background:#FEF2F2;color:#DC2626;border:1px solid #FECACA;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;font-family:inherit;}
.empty-state{text-align:center;padding:3rem 1rem;background:#fff;border:1px dashed #E2E8F0;border-radius:16px;}
.empty-state__icon{font-size:2.5rem;margin-bottom:.65rem;}
.empty-state__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:.3rem;}
.empty-state__sub{font-size:.8rem;color:#64748B;}
.sidebar-overlay{display:none;}
@media(max-width:900px){.hamburger{display:flex;}.sidebar{position:fixed;top:0;left:-260px;z-index:200;height:100vh;width:240px;transition:left .28s;}.sidebar.sidebar--open{left:0;box-shadow:4px 0 24px rgba(15,23,42,.15);}.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.35);z-index:199;transition:opacity .28s;opacity:0;}.sidebar-overlay.overlay--show{display:block;opacity:1;}.dash-main{padding:1rem;}}
</style>
<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger){hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});}
if(overlay){overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});}
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
