@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Edit Tugas</h1></div>
        <div class="topbar__right"><a href="{{ route('tugas.index', $tugas->kelas_id) }}" class="btn-back">← Kembali</a></div>
    </div>
    <div class="section-card">
        <form method="POST" action="{{ route('tugas.update', $tugas) }}">
            @csrf @method('PUT')
            @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
            <div class="form-group"><label>Judul *</label><input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}" required class="form-input"></div>
            <div class="form-group"><label>Deskripsi *</label><textarea name="deskripsi" rows="4" required class="form-input">{{ old('deskripsi', $tugas->deskripsi) }}</textarea></div>
            <div class="form-group"><label>Deadline</label><input type="datetime-local" name="deadline" value="{{ old('deadline', $tugas->deadline?->format('Y-m-d\TH:i')) }}" class="form-input"></div>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>.btn-back{padding:.5rem 1rem;background:#F1F5F9;color:#475569;border-radius:10px;text-decoration:none;font-size:.82rem;font-weight:600;}.section-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}.form-group label{font-size:.8rem;font-weight:600;color:#374151;}.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;}.btn-primary{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;}</style>
<script>
const s=document.querySelector('.sidebar'),o=document.getElementById('sidebarOverlay'),h=document.getElementById('hamburgerBtn');
if(h)h.addEventListener('click',()=>{s.classList.add('sidebar--open');o.classList.add('overlay--show');});
if(o)o.addEventListener('click',()=>{s.classList.remove('sidebar--open');o.classList.remove('overlay--show');});
</script>
@endsection
