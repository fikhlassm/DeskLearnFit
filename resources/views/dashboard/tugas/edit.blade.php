@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Edit Tugas</h1></div>
        <div class="topbar__right"><a href="{{ route('kelas.show', $tugas->kelas_id) }}" class="btn-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Batal</a></div>
    </div>
    <div class="section-card">
        <form method="POST" action="{{ route('tugas.update', $tugas) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="topik_id" value="{{ $tugas->topik_id }}">
            @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
            <div class="form-group"><label>Judul *</label><input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}" required class="form-input"></div>
            
            <div class="form-group">
                <label>Tipe Pengumpulan *</label>
                <select name="tipe" id="tipeSelect" class="form-input" required onchange="toggleTugasFields()">
                    <option value="teks" {{ old('tipe', $tugas->tipe) === 'teks' ? 'selected' : '' }}>Teks Saja</option>
                    <option value="link" {{ old('tipe', $tugas->tipe) === 'link' ? 'selected' : '' }}>Link / Tautan</option>
                    <option value="file" {{ old('tipe', $tugas->tipe) === 'file' ? 'selected' : '' }}>Upload File</option>
                </select>
            </div>

            <div class="form-group"><label>Deskripsi *</label><textarea name="deskripsi" rows="4" class="form-input">{{ old('deskripsi', $tugas->deskripsi) }}</textarea></div>
            
            <div class="form-group" id="linkField" style="display:none;">
                <label>URL Link *</label>
                <input type="url" name="link_url" value="{{ old('link_url', $tugas->link_url) }}" placeholder="https://..." class="form-input">
            </div>

            <div class="form-group" id="fileField" style="display:none;">
                <label>Upload File *</label>
                <input type="file" name="file_upload" class="form-input" style="padding: .4rem .85rem;">
                @if($tugas->tipe === 'file' && $tugas->lampiran_path)
                    <small style="color:#64748B; font-size:.75rem; margin-top:.2rem;">File saat ini: Ada. Upload baru untuk mengganti.</small>
                @endif
            </div>

            <div class="form-group"><label>Deadline</label><input type="datetime-local" name="deadline" value="{{ old('deadline', $tugas->deadline?->format('Y-m-d\TH:i')) }}" class="form-input"></div>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
    <script>
    function toggleTugasFields() {
        const tipe = document.getElementById('tipeSelect').value;
        const linkField = document.getElementById('linkField');
        const fileField = document.getElementById('fileField');
        if (tipe === 'link') {
            linkField.style.display = 'flex';
            fileField.style.display = 'none';
        } else if (tipe === 'file') {
            linkField.style.display = 'none';
            fileField.style.display = 'flex';
        } else {
            linkField.style.display = 'none';
            fileField.style.display = 'none';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleTugasFields);
    </script>
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>.btn-back{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:500;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);}.section-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}.form-group label{font-size:.8rem;font-weight:600;color:#374151;}.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;}.btn-primary{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;}</style>
<script>
const s=document.querySelector('.sidebar'),o=document.getElementById('sidebarOverlay'),h=document.getElementById('hamburgerBtn');
if(h)h.addEventListener('click',()=>{s.classList.add('sidebar--open');o.classList.add('overlay--show');});
if(o)o.addEventListener('click',()=>{s.classList.remove('sidebar--open');o.classList.remove('overlay--show');});
</script>
@endsection
