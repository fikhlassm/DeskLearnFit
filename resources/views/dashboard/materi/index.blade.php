@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div>
            <h1 class="topbar__title">Materi — {{ $kelas->nama_kelas }}</h1>
            <p class="topbar__sub">Kelola materi kelas ini</p>
        </div>
        <div class="topbar__right">
            <a href="{{ route('dashboard.kelas') }}" class="btn-back">← Kembali</a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
                <button type="submit" class="topbar__icon-btn"><svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            </form>
        </div>
    </div>
    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

    {{-- Tambah Materi --}}
    <div class="section-card">
        <p class="section-card__title">Tambah Materi Baru</p>
        <form method="POST" action="{{ route('materi.store', $kelas) }}" enctype="multipart/form-data">
            @csrf
            @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
            <div class="form-group"><label>Judul <span class="req">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="255" class="form-input"></div>
            <div class="form-group"><label>Tipe <span class="req">*</span></label>
                <select name="tipe" class="form-input" id="tipeSelect" onchange="toggleTipeFields(this.value)">
                    <option value="teks" {{ old('tipe')=='teks'?'selected':'' }}>Teks</option>
                    <option value="link" {{ old('tipe')=='link'?'selected':'' }}>Link Eksternal</option>
                    <option value="file" {{ old('tipe')=='file'?'selected':'' }}>Upload File</option>
                </select></div>
            <div class="form-group" id="fieldLink" style="display:none"><label>URL Link</label>
                <input type="url" name="link_url" value="{{ old('link_url') }}" class="form-input" placeholder="https://..."></div>
            <div class="form-group" id="fieldFile" style="display:none"><label>Upload File (maks 10MB)</label>
                <input type="file" name="file" class="form-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.zip"></div>
            <div class="form-group"><label>Deskripsi</label>
                <textarea name="deskripsi" rows="2" class="form-input" maxlength="2000">{{ old('deskripsi') }}</textarea></div>
            <div class="form-group"><label>Konten</label>
                <textarea name="konten" rows="4" class="form-input">{{ old('konten') }}</textarea></div>
            <button type="submit" class="btn-primary">Simpan sebagai Draf</button>
        </form>
    </div>

    {{-- Daftar Materi --}}
    @if($materiList->isEmpty())
    <div class="empty-state"><div class="empty-state__icon">📚</div><p class="empty-state__title">Belum ada materi</p><p class="empty-state__sub">Tambahkan materi pertama di atas.</p></div>
    @else
    <div class="list-card">
        @foreach($materiList as $materi)
        <div class="list-item">
            <div class="list-item__left">
                <span class="badge-tipe badge-tipe--{{ $materi->tipe }}">{{ strtoupper($materi->tipe) }}</span>
                <p class="list-item__judul">{{ $materi->judul }}</p>
                <p class="list-item__sub">{{ $materi->created_at->format('d M Y') }}</p>
            </div>
            <div class="list-item__right">
                <span class="badge-status badge-status--{{ $materi->status }}">{{ ucfirst($materi->status) }}</span>
                @if($materi->status === 'draf')
                <form method="POST" action="{{ route('materi.publish', $materi) }}" style="display:inline">@csrf @method('PATCH')
                    <button class="btn-sm btn-publish">Publish</button>
                </form>
                @endif
                <a href="{{ route('materi.edit', $materi) }}" class="btn-sm btn-edit">Edit</a>
                <form method="POST" action="{{ route('materi.destroy', $materi) }}" onsubmit="return confirm('Hapus materi ini?')" style="display:inline">@csrf @method('DELETE')
                    <button class="btn-sm btn-hapus">Hapus</button>
                </form>
            </div>
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
.btn-back{padding:.5rem 1rem;background:#F1F5F9;color:#475569;border-radius:10px;text-decoration:none;font-size:.82rem;font-weight:600;}
.section-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.section-card__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:1rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}.req{color:#EF4444;}
.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;}
.form-input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.btn-primary{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;}
.list-card{display:flex;flex-direction:column;gap:.65rem;}
.list-item{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.list-item__left{flex:1;}
.list-item__judul{font-size:.9rem;font-weight:600;color:#0F172A;margin:.2rem 0;}
.list-item__sub{font-size:.75rem;color:#94A3B8;}
.list-item__right{display:flex;align-items:center;gap:.5rem;flex-shrink:0;flex-wrap:wrap;}
.badge-tipe{font-size:.65rem;font-weight:700;padding:.18rem .55rem;border-radius:6px;}
.badge-tipe--teks{background:#EFF6FF;color:#2563EB;}
.badge-tipe--link{background:#F0FDF4;color:#15803D;}
.badge-tipe--file{background:#FFFBEB;color:#D97706;}
.badge-status{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;}
.badge-status--draf{background:#F1F5F9;color:#64748B;}
.badge-status--terbit{background:#DCFCE7;color:#15803D;}
.btn-sm{padding:.3rem .75rem;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;border:none;font-family:inherit;}
.btn-publish{background:#DCFCE7;color:#15803D;}
.btn-edit{background:#EFF6FF;color:#2563EB;text-decoration:none;}
.btn-hapus{background:#FEF2F2;color:#DC2626;}
</style>
<script>
function toggleTipeFields(v){
    document.getElementById('fieldLink').style.display=v==='link'?'':'none';
    document.getElementById('fieldFile').style.display=v==='file'?'':'none';
}
toggleTipeFields(document.getElementById('tipeSelect').value);
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger){hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});}
if(overlay){overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});}
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
