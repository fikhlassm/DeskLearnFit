@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Peserta — {{ $kelas->nama_kelas }}</h1><p class="topbar__sub">{{ $peserta->count() }} siswa terdaftar</p></div>
        <div class="topbar__right"><a href="{{ route('dashboard.kelas') }}" class="btn-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Kembali</a></div>
    </div>

    @if($peserta->isEmpty())
    <div class="empty-state"><div class="empty-state__icon" style="display:flex;justify-content:center;color:#94A3B8;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div><p class="empty-state__title">Belum ada peserta</p><p class="empty-state__sub">Siswa belum ada yang bergabung ke kelas ini.</p><p style="font-size:.82rem;color:#64748B;margin-top:.5rem">Kode kelas: <code style="background:#F1F5F9;padding:.2rem .5rem;border-radius:5px;font-size:.8rem">{{ $kelas->kode_kelas }}</code></p></div>
    @else
    <div class="peserta-card">
        <table class="peserta-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peserta as $i => $anggota)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $anggota->siswa?->name ?? 'Akun Terhapus' }}</td>
                    <td>{{ $anggota->siswa?->email ?? '-' }}</td>
                    <td>{{ $anggota->joined_at?->format('d M Y') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:500;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);}
.peserta-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;overflow:hidden;}
.peserta-table{width:100%;border-collapse:collapse;font-size:.85rem;}
.peserta-table thead tr{background:#F8FAFC;border-bottom:1px solid #E2E8F0;}
.peserta-table th{padding:.75rem 1rem;text-align:left;font-weight:600;color:#475569;font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;}
.peserta-table td{padding:.85rem 1rem;color:#0F172A;border-bottom:1px solid #F1F5F9;}
.peserta-table tbody tr:last-child td{border-bottom:none;}
.peserta-table tbody tr:hover{background:#F8FAFC;}
</style>
<script>
const s=document.querySelector('.sidebar'),o=document.getElementById('sidebarOverlay'),h=document.getElementById('hamburgerBtn');
if(h)h.addEventListener('click',()=>{s.classList.add('sidebar--open');o.classList.add('overlay--show');});
if(o)o.addEventListener('click',()=>{s.classList.remove('sidebar--open');o.classList.remove('overlay--show');});
</script>
@endsection
