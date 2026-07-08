@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Tugas — {{ $kelas->nama_kelas }}</h1><p class="topbar__sub">Kelola tugas kelas ini</p></div>
        <div class="topbar__right"><a href="{{ route('kelas.show', $kelas) }}" class="btn-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Kembali ke Kelas</a></div>
    </div>
    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif

    <div class="section-card">
        <p class="section-card__title">Tambah Tugas Baru</p>
        <form method="POST" action="{{ route('tugas.store', $kelas) }}" enctype="multipart/form-data">
            @csrf
            @if(request('topik') || old('topik_id'))
                <input type="hidden" name="topik_id" value="{{ old('topik_id', request('topik')) }}">
            @endif
            @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
            <div class="form-group"><label>Judul *</label><input type="text" name="judul" value="{{ old('judul') }}" required class="form-input"></div>
            
            <div class="form-group">
                <label>Tipe Pengumpulan *</label>
                <select name="tipe" id="tipeSelect" class="form-input" required onchange="toggleTugasFields()">
                    <option value="teks" {{ old('tipe') === 'teks' ? 'selected' : '' }}>Teks Saja</option>
                    <option value="link" {{ old('tipe') === 'link' ? 'selected' : '' }}>Link / Tautan</option>
                    <option value="file" {{ old('tipe') === 'file' ? 'selected' : '' }}>Upload File</option>
                </select>
            </div>

            <div class="form-group"><label>Deskripsi / Instruksi *</label><textarea name="deskripsi" rows="3" class="form-input">{{ old('deskripsi') }}</textarea></div>

            <div class="form-group" id="linkField" style="display:none;">
                <label>URL Link *</label>
                <input type="url" name="link_url" value="{{ old('link_url') }}" placeholder="https://..." class="form-input">
            </div>

            <div class="form-group" id="fileField" style="display:none;">
                <label>Upload File *</label>
                <input type="file" name="file_upload" class="form-input" style="padding: .4rem .85rem;">
                <small style="color:#64748B; font-size:.75rem; margin-top:.2rem;">Maks 10MB.</small>
            </div>

            <div class="form-group"><label>Deadline (opsional)</label><input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="form-input"></div>
            <button type="submit" class="btn-primary">Simpan Tugas</button>
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
    // Initialize state on load
    document.addEventListener('DOMContentLoaded', toggleTugasFields);
    </script>


</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:500;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);}
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





.btn-edit{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1.5px solid #BFDBFE;background:#EFF6FF;color:#1D4ED8;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .15s;font-family:inherit;text-decoration:none;}
.btn-edit:hover{background:#DBEAFE;border-color:#93C5FD;transform:translateY(-1px);}
.btn-hapus{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1.5px solid #FECACA;background:#FEF2F2;color:#DC2626;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .15s;font-family:inherit;}
.btn-hapus:hover{background:#FEE2E2;border-color:#FCA5A5;transform:translateY(-1px);}
.btn-publish{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1.5px solid #bbf7d0;background:#f0fdf4;color:#15803d;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .15s;font-family:inherit;}
.btn-publish:hover{background:#dcfce7;border-color:#86efac;transform:translateY(-1px);}
.btn-lihat{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#475569;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .15s;font-family:inherit;text-decoration:none;}
.btn-lihat:hover{background:#f1f5f9;border-color:#cbd5e1;transform:translateY(-1px);}

</style>
<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger)hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
if(overlay)overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
