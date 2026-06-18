@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_siswa', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Detail Tugas</h1></div>
        <div class="topbar__right"><a href="{{ route('siswa.tugas.index', $tugas->kelas_id) }}" class="btn-back">← Daftar Tugas</a></div>
    </div>

    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif

    {{-- Info Tugas --}}
    <div class="detail-card">
        <div class="detail-card__header">
            <span class="badge-status badge-status--{{ $tugas->status }}">{{ ucfirst($tugas->status) }}</span>
            @if($tugas->deadline)
                @if($tugas->deadline->isPast())
                <span class="badge-deadline badge-deadline--lewat">⚠ Deadline lewat {{ $tugas->deadline->diffForHumans() }}</span>
                @else
                <span class="badge-deadline">⏰ Deadline {{ $tugas->deadline->format('d M Y H:i') }}</span>
                @endif
            @endif
        </div>
        <h2 class="detail-card__judul">{{ $tugas->judul }}</h2>
        <div class="detail-card__desc">{!! nl2br(e($tugas->deskripsi)) !!}</div>
    </div>

    {{-- Jawaban siswa (jika sudah ada) --}}
    @if($jawaban)
    <div class="jawaban-card">
        <div class="jawaban-card__header">
            <p class="jawaban-card__title">Jawaban Kamu</p>
            <span class="badge-jawaban badge-jawaban--{{ $jawaban->status }}">{{ ucfirst($jawaban->status) }}</span>
        </div>
        <div class="jawaban-card__teks">{{ $jawaban->jawaban_text }}</div>
        @if($jawaban->nilai !== null)
        <div class="nilai-box">
            <span class="nilai-box__val">{{ $jawaban->nilai }}/100</span>
            @if($jawaban->feedback)<p class="nilai-box__feedback">Feedback: {{ $jawaban->feedback }}</p>@endif
        </div>
        @endif

        {{-- Update jawaban jika belum ditutup --}}
        @if($tugas->status !== 'ditutup')
        <details style="margin-top:.75rem">
            <summary class="btn-update-toggle">✏ Perbarui Jawaban</summary>
            <form method="POST" action="{{ route('siswa.tugas.update-submit', $jawaban) }}" style="margin-top:.75rem">
                @csrf @method('PUT')
                <textarea name="jawaban_text" rows="4" class="form-input" required maxlength="10000">{{ old('jawaban_text', $jawaban->jawaban_text) }}</textarea>
                <button type="submit" class="btn-submit" style="margin-top:.5rem">Perbarui Jawaban</button>
            </form>
        </details>
        @endif
    </div>
    @elseif($tugas->status !== 'ditutup')
    {{-- Form submit jawaban --}}
    <div class="submit-card">
        <p class="submit-card__title">Kumpulkan Jawaban</p>
        @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('siswa.tugas.submit', $tugas) }}">
            @csrf
            <div class="form-group">
                <label>Jawaban / Pembahasan <span class="req">*</span></label>
                <textarea name="jawaban_text" rows="5" required maxlength="10000" class="form-input" placeholder="Tulis jawaban kamu di sini...">{{ old('jawaban_text') }}</textarea>
            </div>
            <button type="submit" class="btn-submit">Kumpulkan Jawaban</button>
        </form>
    </div>
    @else
    <div class="empty-state" style="padding:1.5rem"><p style="color:#DC2626;font-weight:700">Tugas sudah ditutup</p></div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{padding:.5rem 1rem;background:#F1F5F9;color:#475569;border-radius:10px;text-decoration:none;font-size:.82rem;font-weight:600;}
.detail-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.detail-card__header{display:flex;align-items:center;gap:.6rem;margin-bottom:.75rem;flex-wrap:wrap;}
.detail-card__judul{font-size:1.2rem;font-weight:800;color:#0F172A;margin-bottom:.75rem;}
.detail-card__desc{font-size:.88rem;color:#374151;line-height:1.7;white-space:pre-wrap;}
.badge-status{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;}
.badge-status--terbit{background:#DCFCE7;color:#15803D;}
.badge-status--ditutup{background:#FEE2E2;color:#DC2626;}
.badge-deadline{font-size:.72rem;font-weight:600;background:#FFFBEB;color:#D97706;padding:.2rem .65rem;border-radius:99px;}
.badge-deadline--lewat{background:#FEE2E2;color:#DC2626;}
.jawaban-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.25rem;}
.jawaban-card__header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;}
.jawaban-card__title{font-size:.92rem;font-weight:700;color:#0F172A;}
.jawaban-card__teks{font-size:.85rem;color:#374151;line-height:1.7;background:#F8FAFC;border-radius:10px;padding:.75rem 1rem;white-space:pre-wrap;}
.badge-jawaban{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;}
.badge-jawaban--terkirim{background:#DBEAFE;color:#1D4ED8;}
.badge-jawaban--dinilai{background:#DCFCE7;color:#15803D;}
.badge-jawaban--terlambat{background:#FEE2E2;color:#DC2626;}
.nilai-box{background:#DCFCE7;border-radius:10px;padding:.75rem 1rem;margin-top:.75rem;display:flex;align-items:center;gap:1rem;}
.nilai-box__val{font-size:1.4rem;font-weight:800;color:#15803D;}
.nilai-box__feedback{font-size:.82rem;color:#374151;margin-top:.2rem;}
.submit-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.submit-card__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:1rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}.req{color:#EF4444;}
.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;transition:border-color .18s;}
.form-input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.btn-submit{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;font-family:inherit;}
.btn-update-toggle{font-size:.82rem;font-weight:600;color:#2563EB;cursor:pointer;list-style:none;}
</style>
<script>
const s=document.querySelector('.sidebar'),o=document.getElementById('sidebarOverlay'),h=document.getElementById('hamburgerBtn');
if(h)h.addEventListener('click',()=>{s.classList.add('sidebar--open');o.classList.add('overlay--show');});
if(o)o.addEventListener('click',()=>{s.classList.remove('sidebar--open');o.classList.remove('overlay--show');});
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
