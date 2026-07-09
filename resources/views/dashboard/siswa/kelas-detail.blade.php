@extends('layouts.app')

@section('content')
<div class="dash-page">
    @include('dashboard._sidebar_siswa', ['active' => 'kelas'])

    <main class="dash-main">
        {{-- TOP BAR --}}
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Buka Menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
            <div style="display:flex;align-items:center;gap:.75rem">
                <a href="{{ route('siswa.kelas.index') }}" class="btn-back" style="padding:.4rem .6rem;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></a>
                <div>
                    <div style="display:flex;align-items:center;gap:.5rem">
                        <h1 class="topbar__title">{{ $kelas->nama_kelas }}</h1>
                        <span class="badge-kode">Pengajar: {{ $kelas->pengajar->name }}</span>
                    </div>
                    <p class="topbar__sub">{{ $kelas->mata_pelajaran }}</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-success" id="flashMsg">{{ session('success') }}</div>
        @endif

        {{-- CONTENT STREAM --}}
        <div class="stream-container" id="streamContainer">
            
            {{-- DESKRIPSI KELAS --}}
            @if($kelas->deskripsi)
            <div class="topik-card">
                <div class="topik-body" style="padding:1.25rem;">
                    <div class="topik-desc" style="margin-bottom:0;">{{ $kelas->deskripsi }}</div>
                </div>
            </div>
            @endif

            {{-- LIST TOPIK --}}
            <div id="topikList">
            @foreach($kelas->topiks as $topik)
                <div class="topik-card" data-id="{{ $topik->id }}">
                    <div class="topik-header">
                        <div>
                            <h2 class="topik-title">{{ $topik->judul }}</h2>
                            @if($topik->tanggal)
                            <div class="topik-meta">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                {{ \Carbon\Carbon::parse($topik->tanggal)->format('d M Y') }}
                                @if($topik->jam_mulai && $topik->jam_selesai)
                                    &middot; {{ \Carbon\Carbon::parse($topik->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($topik->jam_selesai)->format('H:i') }}
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="topik-body">
                        @if($topik->deskripsi)
                        <div class="topik-desc">{{ $topik->deskripsi }}</div>
                        @endif

                        <div class="topik-items">
                            @foreach($topik->materi as $materi)
                                @include('dashboard.siswa.partials._materi_item', ['materi' => $materi])
                            @endforeach
                            @foreach($topik->tugas as $tugas)
                                @include('dashboard.siswa.partials._tugas_item', ['tugas' => $tugas])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            </div>

        </div>
    </main>
</div>

@include('dashboard._dash_styles')
<style>
.btn-back{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:8px;text-decoration:none;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;background:#EFF6FF;}
.badge-kode{background:#F1F5F9;color:#475569;font-size:.75rem;font-weight:700;padding:.25rem .65rem;border-radius:6px;border:1px solid #E2E8F0;}

.materi-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15,23,42,0.45); z-index: 100;
    display: flex; align-items: center; justify-content: center; padding: 1.5rem;
    opacity: 0; visibility: hidden; transition: all 0.25s ease;
    backdrop-filter: blur(5px);
}
.materi-modal-overlay.open { opacity: 1; visibility: visible; }
.materi-modal-content {
    background: #fff; border-radius: 16px; width: 100%; max-width: 600px;
    max-height: 90vh; overflow-y: auto; padding: 1.75rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1); transform: translateY(20px); transition: transform 0.2s;
}
.materi-modal-overlay.open .materi-modal-content { transform: translateY(0); }
.materi-modal-close {
    background: transparent; border: none; font-size: 1.5rem; line-height: 1; color: #94A3B8;
    cursor: pointer; padding: 0.2rem; transition: color 0.2s;
}
.materi-modal-close:hover { color: #DC2626; }
.materi-modal-title { font-size: 1.3rem; font-weight: 800; color: #0F172A; }
.materi-modal-date { font-size: 0.78rem; color: #94A3B8; }
.materi-modal-desc { font-size: 0.88rem; color: #64748B; margin-bottom: 1.25rem; line-height: 1.6; }
.btn-link-ext {
    display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.65rem 1.25rem;
    background: #EFF6FF; color: #2563EB; border-radius: 10px; text-decoration: none;
    font-size: 0.88rem; font-weight: 600; margin-bottom: 1.25rem; transition: all 0.2s ease;
}
.btn-link-ext:hover { background: #DBEAFE; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(37,99,235,0.15); }
.btn-link-ext:active { transform: translateY(0); box-shadow: 0 2px 6px rgba(37,99,235,0.1); }
.materi-modal-konten { font-size: 0.88rem; color: #374151; line-height: 1.8; border-top: 1px solid #F1F5F9; padding-top: 1rem; }
.badge-tipe { font-size: 0.65rem; font-weight: 700; padding: 0.18rem 0.55rem; border-radius: 6px; }
.badge-tipe--teks { background: #EFF6FF; color: #2563EB; }
.badge-tipe--link { background: #F0FDF4; color: #15803D; }
.badge-tipe--file { background: #FFFBEB; color: #D97706; }

.stream-container { max-width: 800px; margin: 1.5rem auto; padding: 0 1.5rem; }

.topik-card {
    background: #fff; border: 1px solid #E2E8F0; border-radius: 12px;
    margin-bottom: 1.5rem; overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: box-shadow 0.2s;
}
.topik-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.topik-header {
    background: #F8FAFC; padding: 1rem 1.5rem; border-bottom: 1px solid #E2E8F0;
    display: flex; justify-content: space-between; align-items: center;
}
.topik-title { font-size: 1.1rem; font-weight: 700; color: #0F172A; }
.topik-meta {
    font-size: 0.8rem; color: #64748B; margin-top: 0.2rem;
    display:flex; align-items:center; gap: 0.4rem;
}
.topik-body { padding: 1.5rem; }
.topik-desc {
    font-size: 0.9rem; color: #334155; background: #F8FAFC; padding: 1rem;
    border-radius: 8px; margin-bottom: 1.5rem; border-left: 3px solid #3B82F6; line-height: 1.5;
}
.topik-items { display: flex; flex-direction: column; gap: 0.5rem; }

.item-row {
    display: flex; align-items: center; padding: 0.75rem 1rem;
    border: 1px solid #E2E8F0; border-radius: 8px;
    transition: background 0.15s; text-decoration: none; color: inherit;
}
.item-row:hover { background: #F8FAFC; border-color: #cbd5e1; }
.item-icon {
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    margin-right: 1rem; flex-shrink: 0;
}
.item-icon.materi { background: #EEF2FF; color: #4F46E5; }
.item-icon.tugas { background: #FEF2F2; color: #DC2626; }

.item-info { flex: 1; }
.item-title { font-size: 0.9rem; font-weight: 600; color: #0F172A; margin-bottom: 0.15rem; }
.item-meta { font-size: 0.75rem; color: #94A3B8; }
</style>

<script>
function openMateriModal(id) {
    const modal = document.getElementById('materiModal-' + id);
    if (modal) {
        modal.style.display = 'flex';
        // Allow reflow
        setTimeout(() => modal.classList.add('open'), 10);
    }
}
function closeMateriModal(id) {
    const modal = document.getElementById('materiModal-' + id);
    if (modal) {
        modal.classList.remove('open');
        setTimeout(() => modal.style.display = 'none', 200);
    }
}
setTimeout(() => {
    const f = document.getElementById('flashMsg');
    if(f) f.style.transition='opacity .5s', f.style.opacity='0', setTimeout(()=>f.remove(),500);
}, 3000);
</script>

@endsection
