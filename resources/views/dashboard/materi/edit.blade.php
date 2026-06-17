@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Edit Materi</h1></div>
        <div class="topbar__right">
            <a href="{{ route('materi.index', $materi->kelas_id) }}" class="btn-back">← Kembali</a>
        </div>
    </div>
    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif

    <div class="section-card">
        <form method="POST" action="{{ route('materi.update', $materi) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
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
            <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="2" class="form-input">{{ old('deskripsi', $materi->deskripsi) }}</textarea></div>
            <div class="form-group"><label>Konten</label><textarea name="konten" rows="5" class="form-input">{{ old('konten', $materi->konten) }}</textarea></div>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>.btn-back{padding:.5rem 1rem;background:#F1F5F9;color:#475569;border-radius:10px;text-decoration:none;font-size:.82rem;font-weight:600;}.section-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}.form-group label{font-size:.8rem;font-weight:600;color:#374151;}.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;}.btn-primary{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;}</style>
<script>
function toggleTipeFields(v){document.getElementById('fieldLink').style.display=v==='link'?'':'none';document.getElementById('fieldFile').style.display=v==='file'?'':'none';}
toggleTipeFields(document.getElementById('tipeSelect').value);
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger)hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
if(overlay)overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
</script>
@endsection
