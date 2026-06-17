@extends('layouts.app')
@section('content')

@php
$metodeInfo = [
    'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB','bg'=>'#EFF6FF','icon'=>'⏱️','desc'=>'25 menit fokus + 5 menit istirahat'],
    'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED','bg'=>'#F5F3FF','icon'=>'🧠','desc'=>'Uji dirimu sendiri tanpa melihat catatan'],
    'blurting'     => ['label'=>'Blurting',       'color'=>'#059669','bg'=>'#ECFDF5','icon'=>'✍️','desc'=>'Tulis semua yang kamu ingat di kertas kosong'],
    'feynman'      => ['label'=>'Feynman',         'color'=>'#D97706','bg'=>'#FFFBEB','icon'=>'🏫','desc'=>'Jelaskan konsep seolah mengajar orang lain'],
];
$quizResult = Auth::user()->quiz_result;
$rekomendasiMetode = $quizResult ? ($metodeInfo[$quizResult] ?? null) : null;
@endphp

<div class="dash-page">
<aside class="sidebar" id="sidebar">
    <div class="sidebar__brand">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none"><rect width="28" height="28" rx="8" fill="#2563EB"/><path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
        <div><p class="sidebar__brand-name">LearnFit</p><p class="sidebar__brand-sub">Platform Belajar Anda</p></div>
    </div>
    <nav class="sidebar__nav">
        <a href="{{ route('dashboard.siswa') }}" class="sidebar__link">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" fill="currentColor"/><rect x="11" y="2" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/><rect x="2" y="11" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/><rect x="11" y="11" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/></svg>
            Beranda
        </a>
        <a href="{{ route('catatan.index') }}" class="sidebar__link">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M6 8h8M6 11h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Catatan Belajar
        </a>
        <a href="{{ route('sesi.index') }}" class="sidebar__link sidebar__link--active">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.6"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Sesi Belajar
        </a>
        <a href="{{ route('profil.show') }}" class="sidebar__link">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.6"/><path d="M3 18c0-3.31 3.13-6 7-6s7 2.69 7 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            Profil
        </a>
    </nav>
    <div class="sidebar__user">
        <div class="sidebar__avatar"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="3.5" stroke="#64748b" stroke-width="1.5"/><path d="M3 18c0-3.31 3.13-6 7-6s7 2.69 7 6" stroke="#64748b" stroke-width="1.5" stroke-linecap="round"/></svg></div>
        <div><p class="sidebar__user-name">{{ Auth::user()->name }}</p><p class="sidebar__user-role">Siswa</p></div>
    </div>
</aside>

<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Sesi Belajar</h1><p class="topbar__sub">Timer pomodoro & riwayat sesi belajarmu</p></div>
        <div class="topbar__right">
            <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
                <button type="submit" class="topbar__icon-btn"><svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif

    {{-- REKOMENDASI METODE --}}
    @if($rekomendasiMetode)
    <div class="rekomendasi-card" style="border-left:4px solid {{ $rekomendasiMetode['color'] }}">
        <span class="rekomendasi-badge" style="background:{{ $rekomendasiMetode['bg'] }};color:{{ $rekomendasiMetode['color'] }}">✦ Rekomendasi untukmu</span>
        <p class="rekomendasi-nama">{{ $rekomendasiMetode['icon'] }} {{ $rekomendasiMetode['label'] }}</p>
        <p class="rekomendasi-desc">{{ $rekomendasiMetode['desc'] }}</p>
    </div>
    @endif

    <div class="sesi-grid">
        {{-- TIMER POMODORO --}}
        <div class="timer-card">
            <p class="timer-card__label">Timer Fokus</p>
            <div class="timer-display" id="timerDisplay">25:00</div>
            <div class="timer-progress-wrap"><div class="timer-progress-bar" id="timerBar" style="width:100%"></div></div>
            <div class="timer-controls">
                <button class="timer-btn timer-btn--start" id="btnTimerStart" onclick="startTimer()">▶ Mulai</button>
                <button class="timer-btn timer-btn--pause" id="btnTimerPause" onclick="pauseTimer()" style="display:none">⏸ Jeda</button>
                <button class="timer-btn timer-btn--reset" onclick="resetTimer()">↺ Reset</button>
            </div>
            <p class="timer-hint" id="timerHint">Siap untuk fokus belajar?</p>
        </div>

        {{-- FORM SESI BARU --}}
        <div class="form-card">
            <p class="form-card__title">Mulai Sesi Baru</p>
            <form method="POST" action="{{ route('sesi.store') }}">
                @csrf
                @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
                <div class="form-group">
                    <label>Metode <span class="req">*</span></label>
                    <select name="metode" required>
                        @foreach($metodeInfo as $key => $info)
                        <option value="{{ $key }}" {{ (old('metode', $quizResult) === $key) ? 'selected' : '' }}>{{ $info['icon'] }} {{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Judul / Topik</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" placeholder="cth: Belajar Turunan Fungsi" maxlength="200">
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label>Fokus (menit)</label>
                        <input type="number" name="durasi_fokus_menit" value="{{ old('durasi_fokus_menit', 25) }}" min="1" max="120" id="inputFokus">
                    </div>
                    <div class="form-group">
                        <label>Istirahat (menit)</label>
                        <input type="number" name="durasi_istirahat_menit" value="{{ old('durasi_istirahat_menit', 5) }}" min="1" max="60">
                    </div>
                    <div class="form-group">
                        <label>Siklus</label>
                        <input type="number" name="jumlah_siklus" value="{{ old('jumlah_siklus', 4) }}" min="1" max="10">
                    </div>
                </div>
                <button type="submit" class="btn-mulai-sesi">🚀 Catat & Mulai Sesi</button>
            </form>
        </div>
    </div>

    {{-- SESI AKTIF --}}
    @if($sesiAktif)
    <div class="sesi-aktif-card">
        <div class="sesi-aktif-card__left">
            <span class="badge-aktif">🟢 Sedang Berlangsung</span>
            <p class="sesi-aktif-card__judul">{{ $sesiAktif->judul ?: $sesiAktif->metode }}</p>
            <p class="sesi-aktif-card__meta">Dimulai {{ $sesiAktif->started_at->diffForHumans() }} · {{ $sesiAktif->durasi_fokus_menit }} menit fokus</p>
        </div>
        <form method="POST" action="{{ route('sesi.complete', $sesiAktif) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn-selesai-sesi">✓ Tandai Selesai</button>
        </form>
    </div>
    @endif

    {{-- RIWAYAT --}}
    <div class="section">
        <div class="section__head">
            <h2 class="section__title">Riwayat Sesi <span class="badge-count">{{ $totalSelesai }} selesai</span></h2>
        </div>
        @if($riwayat->isEmpty())
        <div class="empty-state"><div class="empty-state__icon">⏱️</div><p class="empty-state__title">Belum ada riwayat sesi</p><p class="empty-state__sub">Mulai sesi belajar pertamamu di atas.</p></div>
        @else
        <div class="riwayat-list">
            @foreach($riwayat as $sesi)
            @php $info = $metodeInfo[$sesi->metode] ?? $metodeInfo['pomodoro']; @endphp
            <div class="riwayat-item">
                <div class="riwayat-icon" style="background:{{ $info['bg'] }}"><span style="font-size:1.2rem">{{ $info['icon'] }}</span></div>
                <div class="riwayat-body">
                    <p class="riwayat-judul">{{ $sesi->judul ?: $info['label'] }}</p>
                    <p class="riwayat-meta">{{ $info['label'] }} · {{ $sesi->durasi_fokus_menit }}m fokus · {{ $sesi->jumlah_siklus }} siklus · {{ $sesi->created_at->format('d M Y') }}</p>
                </div>
                <div class="riwayat-right">
                    @if($sesi->status === 'selesai')
                    <span class="badge-selesai">✓ Selesai</span>
                    @elseif($sesi->status === 'aktif')
                    <span class="badge-berjalan">▶ Aktif</span>
                    @else
                    <span class="badge-batal">✕ Batal</span>
                    @endif
                    <form method="POST" action="{{ route('sesi.destroy', $sesi) }}" onsubmit="return confirm('Hapus sesi ini?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-hapus-sesi">🗑</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination-wrap">{{ $riwayat->links() }}</div>
        @endif
    </div>
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.sidebar{width:240px;flex-shrink:0;background:#fff;border-right:1px solid #E2E8F0;display:flex;flex-direction:column;padding:1.25rem 0;position:sticky;top:0;height:100vh;overflow-y:auto;}
.sidebar__brand{display:flex;align-items:center;gap:.7rem;padding:.25rem 1.25rem 1.25rem;border-bottom:1px solid #F1F5F9;margin-bottom:.5rem;}
.sidebar__brand-name{font-size:.95rem;font-weight:700;color:#0F172A;line-height:1.2;}
.sidebar__brand-sub{font-size:.68rem;color:#94A3B8;}
.sidebar__nav{flex:1;display:flex;flex-direction:column;gap:.15rem;padding:.5rem .75rem;}
.sidebar__link{display:flex;align-items:center;gap:.7rem;padding:.65rem .85rem;border-radius:10px;text-decoration:none;font-size:.85rem;font-weight:500;color:#475569;transition:background .18s,color .18s;}
.sidebar__link:hover{background:#F8FAFC;color:#2563EB;}
.sidebar__link--active{background:#EFF6FF;color:#2563EB;font-weight:600;}
.sidebar__user{display:flex;align-items:center;gap:.7rem;padding:.85rem 1.25rem;border-top:1px solid #F1F5F9;margin:.5rem .75rem 0;border-radius:10px;}
.sidebar__avatar{width:36px;height:36px;border-radius:50%;background:#E2E8F0;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sidebar__user-name{font-size:.82rem;font-weight:600;color:#0F172A;}
.sidebar__user-role{font-size:.68rem;color:#94A3B8;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.25rem;overflow-x:hidden;}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}
.topbar__icon-btn{width:38px;height:38px;border:1px solid #E2E8F0;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.65rem 1rem;color:#991B1B;font-size:.83rem;}
.rekomendasi-card{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.3rem;}
.rekomendasi-badge{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;width:fit-content;}
.rekomendasi-nama{font-size:1rem;font-weight:700;color:#0F172A;}
.rekomendasi-desc{font-size:.8rem;color:#64748B;}
.sesi-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;align-items:start;}
.timer-card{background:#0F172A;border-radius:20px;padding:2rem;display:flex;flex-direction:column;align-items:center;gap:1rem;color:#fff;}
.timer-card__label{font-size:.75rem;font-weight:700;letter-spacing:.06em;color:#64748B;text-transform:uppercase;}
.timer-display{font-size:4rem;font-weight:800;letter-spacing:-.04em;font-variant-numeric:tabular-nums;color:#fff;}
.timer-progress-wrap{width:100%;height:6px;background:rgba(255,255,255,.1);border-radius:99px;overflow:hidden;}
.timer-progress-bar{height:100%;background:linear-gradient(90deg,#2563EB,#60A5FA);border-radius:99px;transition:width .5s linear;}
.timer-controls{display:flex;gap:.65rem;}
.timer-btn{padding:.6rem 1.2rem;border-radius:10px;border:none;cursor:pointer;font-size:.83rem;font-weight:600;font-family:inherit;transition:opacity .18s,transform .15s;}
.timer-btn--start{background:#2563EB;color:#fff;}
.timer-btn--pause{background:#F59E0B;color:#fff;}
.timer-btn--reset{background:rgba(255,255,255,.1);color:#94A3B8;}
.timer-btn:hover{opacity:.88;transform:translateY(-1px);}
.timer-hint{font-size:.75rem;color:#64748B;}
.form-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.form-card__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:1rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.75rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.req{color:#EF4444;}
.form-group input,.form-group select{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;color:#0F172A;outline:none;font-family:inherit;}
.form-group input:focus,.form-group select:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}
.form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:.65rem;}
.btn-mulai-sesi{width:100%;padding:.75rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:inherit;margin-top:.25rem;transition:background .18s;}
.btn-mulai-sesi:hover{background:#1d4ed8;}
.sesi-aktif-card{background:linear-gradient(135deg,#DCFCE7,#ECFDF5);border:1px solid #6EE7B7;border-radius:16px;padding:1.25rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.badge-aktif{font-size:.72rem;font-weight:700;display:block;margin-bottom:.3rem;color:#15803D;}
.sesi-aktif-card__judul{font-size:1rem;font-weight:700;color:#0F172A;margin-bottom:.2rem;}
.sesi-aktif-card__meta{font-size:.78rem;color:#64748B;}
.btn-selesai-sesi{padding:.65rem 1.3rem;background:#15803D;color:#fff;border:none;border-radius:10px;font-size:.85rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .18s;}
.btn-selesai-sesi:hover{background:#166534;}
.section{display:flex;flex-direction:column;gap:.85rem;}
.section__head{display:flex;align-items:center;justify-content:space-between;}
.section__title{font-size:1rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:.5rem;}
.badge-count{font-size:.72rem;font-weight:700;background:#DCFCE7;color:#15803D;padding:.2rem .55rem;border-radius:99px;}
.riwayat-list{display:flex;flex-direction:column;gap:.65rem;}
.riwayat-item{background:#fff;border:1px solid #E2E8F0;border-radius:14px;padding:.9rem 1.1rem;display:flex;align-items:center;gap:1rem;}
.riwayat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.riwayat-body{flex:1;}
.riwayat-judul{font-size:.88rem;font-weight:600;color:#0F172A;}
.riwayat-meta{font-size:.75rem;color:#94A3B8;margin-top:.15rem;}
.riwayat-right{display:flex;align-items:center;gap:.6rem;flex-shrink:0;}
.badge-selesai{font-size:.7rem;font-weight:700;background:#DCFCE7;color:#15803D;padding:.2rem .55rem;border-radius:6px;}
.badge-berjalan{font-size:.7rem;font-weight:700;background:#DBEAFE;color:#1D4ED8;padding:.2rem .55rem;border-radius:6px;}
.badge-batal{font-size:.7rem;font-weight:700;background:#F1F5F9;color:#64748B;padding:.2rem .55rem;border-radius:6px;}
.btn-hapus-sesi{background:none;border:none;cursor:pointer;font-size:.85rem;opacity:.5;transition:opacity .18s;}
.btn-hapus-sesi:hover{opacity:1;}
.empty-state{text-align:center;padding:2.5rem 1rem;background:#fff;border:1px dashed #E2E8F0;border-radius:16px;}
.empty-state__icon{font-size:2.5rem;margin-bottom:.65rem;}
.empty-state__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:.3rem;}
.empty-state__sub{font-size:.8rem;color:#64748B;}
.pagination-wrap{margin-top:.5rem;}
.sidebar-overlay{display:none;}
@media(max-width:900px){
    .hamburger{display:flex;}
    .sidebar{position:fixed;top:0;left:-260px;z-index:200;height:100vh;width:240px;transition:left .28s cubic-bezier(.4,0,.2,1);}
    .sidebar.sidebar--open{left:0;box-shadow:4px 0 24px rgba(15,23,42,.15);}
    .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.35);z-index:199;transition:opacity .28s;opacity:0;}
    .sidebar-overlay.overlay--show{display:block;opacity:1;}
    .dash-main{padding:1rem;}
    .sesi-grid{grid-template-columns:1fr;}
    .form-row-3{grid-template-columns:1fr 1fr;}
}
</style>

<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
document.querySelectorAll('.sidebar__link').forEach(l=>l.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');}));
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);

// --- POMODORO TIMER ---
let totalSeconds = parseInt(document.getElementById('inputFokus')?.value || 25) * 60;
let remaining = totalSeconds;
let running = false;
let interval = null;

function pad(n){return String(n).padStart(2,'0');}
function updateDisplay(){
    const m=Math.floor(remaining/60), s=remaining%60;
    document.getElementById('timerDisplay').textContent=pad(m)+':'+pad(s);
    document.getElementById('timerBar').style.width=(remaining/totalSeconds*100)+'%';
    document.getElementById('timerHint').textContent=running?'Fokus! Jangan terganggu sekarang.':'Siap untuk fokus belajar?';
}

document.getElementById('inputFokus')?.addEventListener('change',function(){
    if(!running){totalSeconds=parseInt(this.value||25)*60;remaining=totalSeconds;updateDisplay();}
});

function startTimer(){
    if(running)return;
    running=true;
    document.getElementById('btnTimerStart').style.display='none';
    document.getElementById('btnTimerPause').style.display='';
    interval=setInterval(()=>{
        remaining--;
        updateDisplay();
        if(remaining<=0){
            clearInterval(interval);running=false;
            document.getElementById('btnTimerPause').style.display='none';
            document.getElementById('btnTimerStart').style.display='';
            document.getElementById('timerHint').textContent='🎉 Sesi fokus selesai! Saatnya istirahat.';
            document.getElementById('timerDisplay').textContent='00:00';
            if(window.Notification&&Notification.permission==='granted')new Notification('LearnFit',{body:'Sesi fokus selesai! Istirahat sebentar.'});
        }
    },1000);
    if(window.Notification&&Notification.permission==='default')Notification.requestPermission();
}

function pauseTimer(){
    if(!running)return;
    clearInterval(interval);running=false;
    document.getElementById('btnTimerPause').style.display='none';
    document.getElementById('btnTimerStart').style.display='';
    document.getElementById('timerHint').textContent='Dijeda. Klik Mulai untuk lanjutkan.';
}

function resetTimer(){
    clearInterval(interval);running=false;remaining=totalSeconds;
    document.getElementById('btnTimerPause').style.display='none';
    document.getElementById('btnTimerStart').style.display='';
    updateDisplay();
}

updateDisplay();
</script>
@endsection
