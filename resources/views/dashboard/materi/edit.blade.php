@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Edit Materi</h1></div>
        <div class="topbar__right">
            <a href="{{ route('kelas.show', $materi->kelas_id) }}" class="btn-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Batal</a>
        </div>
    </div>
    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif

    <div class="section-card">
        <form method="POST" action="{{ route('materi.update', $materi) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="topik_id" value="{{ $materi->topik_id }}">
            @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
            <div class="form-group"><label>Judul *</label><input type="text" name="judul" value="{{ old('judul', $materi->judul) }}" required class="form-input"></div>
            <div class="form-group"><label>Tipe *</label>
                <select name="tipe" class="form-input" id="tipeSelect" onchange="toggleTipeFields(this.value)">
                    <option value="teks" {{ old('tipe',$materi->tipe)=='teks'?'selected':'' }}>Teks</option>
                    <option value="link" {{ old('tipe',$materi->tipe)=='link'?'selected':'' }}>Link</option>
                    <option value="file" {{ old('tipe',$materi->tipe)=='file'?'selected':'' }}>File</option>
                </select></div>
            <div class="form-group" id="fieldLink"><label>URL</label><input type="url" name="link_url" value="{{ old('link_url', $materi->link_url) }}" class="form-input"></div>
            <div class="form-group" id="fieldFile"><label>Upload File Baru (opsional)</label><input type="file" name="file" class="form-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                @if($materi->file_path)<p style="font-size:.75rem;color:#64748B;margin-top:.3rem">File saat ini: {{ basename($materi->file_path) }}</p>@endif</div>
            <div class="form-group"><label>Deskripsi <span style="color:#94a3b8; font-weight:400;">(Opsional)</span></label>
                <textarea name="deskripsi" rows="3" class="form-input" maxlength="2000" placeholder="Ketik informasi singkat tentang materi ini...">{{ old('deskripsi', $materi->deskripsi) }}</textarea></div>
            <button type="submit" class="btn-primary" style="margin-top:0.5rem">Simpan Perubahan</button>
        </form>
    </div>
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>.btn-back{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:500;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);}.section-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}.form-group label{font-size:.8rem;font-weight:600;color:#374151;}.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;}.btn-primary{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;}</style>
<script>
function toggleTipeFields(v){document.getElementById('fieldLink').style.display=v==='link'?'':'none';document.getElementById('fieldFile').style.display=v==='file'?'':'none';}
toggleTipeFields(document.getElementById('tipeSelect').value);
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger)hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
if(overlay)overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
</script>
@endsection
