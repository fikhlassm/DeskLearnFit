@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_pengajar', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Jawaban — {{ $tugas->judul }}</h1><p class="topbar__sub">{{ $jawaban->total() }} jawaban terkumpul</p></div>
        <div class="topbar__right"><a href="{{ route('kelas.show', $tugas->kelas_id) }}" class="btn-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Kembali ke Kelas</a></div>
    </div>
    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif

    @if($jawaban->isEmpty())
    <div class="empty-state"><div class="empty-state__icon" style="display:flex;justify-content:center;color:#94A3B8;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg></div><p class="empty-state__title">Belum ada jawaban</p></div>
    @else
    @foreach($jawaban as $j)
    <div class="jawaban-card">
        <div class="jawaban-card__header">
            <div>
                <p class="jawaban-card__nama">{{ $j->siswa->name }}</p>
                <p class="jawaban-card__email">
                    {{ $j->siswa->email }} · {{ $j->submitted_at?->format('d M Y H:i') }}
                    @if($j->submitted_at && $j->created_at && abs($j->submitted_at->diffInSeconds($j->created_at)) > 10)
                        <span style="background:#FFF7ED; color:#EA580C; border:1px solid #FFEDD5; font-size:.65rem; padding:.15rem .45rem; border-radius:4px; font-weight:700; margin-left:.4rem;">DIUBAH</span>
                    @endif
                </p>
            </div>
            <span class="badge-status badge-status--{{ $j->status }}">{{ ucfirst($j->status) }}</span>
        </div>
        <div class="jawaban-card__teks">
            <div style="margin-bottom:.5rem;">
                <span class="badge-tipe badge-tipe--{{ $j->tipe ?? 'teks' }}">{{ strtoupper($j->tipe ?? 'teks') }}</span>
            </div>
            @if($j->tipe === 'link')
                <a href="{{ $j->link_url }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">🔗 {{ $j->link_url }}</a>
                @if($j->jawaban_text)<div style="margin-top:.5rem">{!! nl2br(e($j->jawaban_text)) !!}</div>@endif
            @elseif($j->tipe === 'file')
                <a href="{{ Storage::url($j->file_path) }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">📎 Unduh / Lihat File Jawaban</a>
                @if($j->jawaban_text)<div style="margin-top:.5rem">{!! nl2br(e($j->jawaban_text)) !!}</div>@endif
            @else
                {!! nl2br(e($j->jawaban_text)) !!}
            @endif
        </div>
        @if($j->nilai !== null)
        <div class="jawaban-card__nilai">Nilai: <strong>{{ $j->nilai }}/100</strong> — {{ $j->feedback }}</div>
        @endif
        <form method="POST" action="{{ route('jawaban.nilai', $j) }}" class="nilai-form">
            @csrf @method('PUT')
            <div class="nilai-row">
                <input type="number" name="nilai" value="{{ $j->nilai }}" min="0" max="100" placeholder="Nilai (0-100)" class="nilai-input">
                <input type="text" name="feedback" value="{{ $j->feedback }}" placeholder="Feedback (opsional)" class="feedback-input">
                <button type="submit" class="btn-nilai">Simpan Nilai</button>
            </div>
        </form>
    </div>
    @endforeach
    <div style="margin-top:.75rem">{{ $jawaban->links() }}</div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:500;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);}
.jawaban-card{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1.25rem;margin-bottom:.75rem;}
.jawaban-card__header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;}
.jawaban-card__nama{font-size:.92rem;font-weight:700;color:#0F172A;}
.jawaban-card__email{font-size:.75rem;color:#94A3B8;margin-top:.1rem;}
.jawaban-card__teks{font-size:.85rem;color:#374151;line-height:1.7;background:#F8FAFC;border-radius:10px;padding:.75rem 1rem;margin-bottom:.75rem;}
.jawaban-card__nilai{font-size:.82rem;color:#15803D;background:#DCFCE7;border-radius:8px;padding:.5rem .85rem;margin-bottom:.75rem;}
.nilai-form{display:flex;flex-direction:column;gap:.5rem;}
.nilai-row{display:flex;gap:.5rem;flex-wrap:wrap;}
.nilai-input{width:100px;padding:.45rem .75rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.83rem;font-family:inherit;outline:none;}
.feedback-input{flex:1;min-width:150px;padding:.45rem .75rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.83rem;font-family:inherit;outline:none;}
.btn-nilai{padding:.45rem 1rem;background:#2563EB;color:#fff;border:none;border-radius:9px;font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap;transition:background .18s;}
.btn-nilai:hover{background:#1d4ed8;box-shadow:0 4px 14px rgba(37,99,235,.25);transform:translateY(-1px);}
.badge-status{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;}
.badge-status--terkirim{background:#DBEAFE;color:#1D4ED8;}
.badge-status--dinilai{background:#DCFCE7;color:#15803D;}
.badge-status--terlambat{background:#FEE2E2;color:#DC2626;}
.badge-tipe { font-size: 0.65rem; font-weight: 700; padding: 0.18rem 0.55rem; border-radius: 6px; }
.badge-tipe--teks { background: #EFF6FF; color: #2563EB; }
.badge-tipe--link { background: #F0FDF4; color: #15803D; }
.badge-tipe--file { background: #FFFBEB; color: #D97706; }
</style>
<script>
const s=document.querySelector('.sidebar'),o=document.getElementById('sidebarOverlay'),h=document.getElementById('hamburgerBtn');
if(h)h.addEventListener('click',()=>{s.classList.add('sidebar--open');o.classList.add('overlay--show');});
if(o)o.addEventListener('click',()=>{s.classList.remove('sidebar--open');o.classList.remove('overlay--show');});
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
