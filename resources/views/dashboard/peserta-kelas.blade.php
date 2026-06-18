@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Peserta — {{ $kelas->nama_kelas }}</h1><p class="topbar__sub">{{ $peserta->count() }} siswa terdaftar</p></div>
        <div class="topbar__right"><a href="{{ route('dashboard.kelas') }}" class="btn-back">← Kembali</a></div>
    </div>

    @if($peserta->isEmpty())
    <div class="empty-state"><div class="empty-state__icon">👥</div><p class="empty-state__title">Belum ada peserta</p><p class="empty-state__sub">Siswa belum ada yang bergabung ke kelas ini.</p><p style="font-size:.82rem;color:#64748B;margin-top:.5rem">Kode kelas: <code style="background:#F1F5F9;padding:.2rem .5rem;border-radius:5px;font-size:.8rem">{{ $kelas->kode_kelas }}</code></p></div>
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
                    <td>{{ $anggota->siswa->name }}</td>
                    <td>{{ $anggota->siswa->email }}</td>
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
.btn-back{padding:.5rem 1rem;background:#F1F5F9;color:#475569;border-radius:10px;text-decoration:none;font-size:.82rem;font-weight:600;}
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
