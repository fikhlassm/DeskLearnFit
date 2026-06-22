@extends('layouts.app')
@section('content')

@php
    $metodeInfo = [
        'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB', 'bg'=>'#EFF6FF', 'icon'=>'⏱️', 'desc'=>'25 menit fokus + 5 menit istirahat'],
        'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED', 'bg'=>'#F5F3FF', 'icon'=>'🧠', 'desc'=>'Uji dirimu dengan kartu flash'],
        'blurting'     => ['label'=>'Blurting',       'color'=>'#059669', 'bg'=>'#ECFDF5', 'icon'=>'✍️', 'desc'=>'Tulis semua yang kamu ingat'],
        'feynman'      => ['label'=>'Feynman',        'color'=>'#D97706', 'bg'=>'#FFFBEB', 'icon'=>'🏫', 'desc'=>'Jelaskan ke orang lain dengan bahasamu'],
    ];
@endphp

<div class="dash-page">
    @include('dashboard._sidebar_siswa', ['active' => 'notebook'])

    <main class="dash-main">
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
            <div>
                <h1 class="topbar__title">📓 Notebook Saya</h1>
                <p class="topbar__sub">Semua sesi belajar yang pernah kamu buat, dikelompokkan per metode</p>
            </div>
            <div class="topbar__right">
                <a href="{{ route('sesi.index') }}" class="btn-mulai">+ Sesi Baru</a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
                    <button type="submit" class="topbar__icon-btn"><svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                </form>
            </div>
        </div>

        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif

        <div class="notebook-grid">
            @foreach($metodeInfo as $key => $info)
            @php
                $metodeSesi = $sesiByMetode[$key] ?? collect();
                $totalSesi = $metodeSesi->count();
                $totalSelesai = $metodeSesi->where('status', 'selesai')->count();
                $latest = $metodeSesi->sortByDesc('created_at')->first();
            @endphp
            <div class="notebook-section" style="border-top:3px solid {{ $info['color'] }}">
                <div class="notebook-section__head" style="background:{{ $info['bg'] }}">
                    <div class="notebook-section__icon" style="color:{{ $info['color'] }}">{{ $info['icon'] }}</div>
                    <div class="notebook-section__body">
                        <h2 class="notebook-section__title">{{ $info['label'] }}</h2>
                        <p class="notebook-section__desc">{{ $info['desc'] }}</p>
                    </div>
                    <div class="notebook-section__count">
                        <span class="notebook-count-num">{{ $totalSesi }}</span>
                        <span class="notebook-count-label">sesi</span>
                    </div>
                </div>

                @if($metodeSesi->isEmpty())
                <div class="notebook-empty">
                    <p>Belum ada sesi {{ $info['label'] }}.</p>
                    <a href="{{ route('sesi.index', ['metode' => $key]) }}" class="notebook-empty__btn">+ Buat Sesi Pertama</a>
                </div>
                @else
                <div class="notebook-list">
                    @foreach($metodeSesi->take(5) as $sesi)
                    <a href="{{ route('sesi.index', ['metode' => $key]) }}" class="notebook-item">
                        <div class="notebook-item__left">
                            <p class="notebook-item__judul">{{ $sesi->judul ?: 'Tanpa judul' }}</p>
                            <p class="notebook-item__sub">
                                @if($key === 'pomodoro')
                                    {{ $sesi->durasi_fokus_menit }}m fokus · {{ $sesi->jumlah_siklus }} siklus
                                @elseif($key === 'active_recall')
                                    {{ $sesi->flashcards()->count() }} kartu · {{ $sesi->flashcardReviews()->count() }} review
                                @else
                                    {{ $sesi->entriNotebook()->count() }} entri
                                @endif
                            </p>
                        </div>
                        <div class="notebook-item__right">
                            @if($sesi->status === 'selesai')
                            <span class="notebook-status" data-tone="ok">✓ Selesai</span>
                            @elseif($sesi->status === 'aktif')
                            <span class="notebook-status" data-tone="primary">▶ Aktif</span>
                            @else
                            <span class="notebook-status" data-tone="neutral">✕ Batal</span>
                            @endif
                            <span class="notebook-item__date">{{ $sesi->created_at->format('d M') }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @if($totalSesi > 5)
                <div class="notebook-more">
                    <a href="{{ route('sesi.index', ['metode' => $key]) }}">Lihat semua ({{ $totalSesi }} sesi) →</a>
                </div>
                @endif
                @endif
            </div>
            @endforeach
        </div>
    </main>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.25rem;overflow-x:hidden;}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}
.topbar__icon-btn{width:38px;height:38px;border:1px solid #E2E8F0;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;}
.btn-mulai{padding:.6rem 1.2rem;background:#2563EB;color:#fff;text-decoration:none;border-radius:10px;font-size:.85rem;font-weight:600;transition:background .15s;}
.btn-mulai:hover{background:#1d4ed8;}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;}
.notebook-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;align-items:start;}
.notebook-section{background:#fff;border:1px solid #E2E8F0;border-radius:14px;overflow:hidden;}
.notebook-section__head{padding:1.1rem 1.25rem;display:flex;align-items:center;gap:1rem;}
.notebook-section__icon{font-size:1.75rem;line-height:1;flex-shrink:0;}
.notebook-section__body{flex:1;min-width:0;}
.notebook-section__title{font-size:1.05rem;font-weight:800;color:#0F172A;}
.notebook-section__desc{font-size:.75rem;color:#64748B;margin-top:.1rem;}
.notebook-section__count{text-align:center;flex-shrink:0;}
.notebook-count-num{display:block;font-size:1.4rem;font-weight:800;color:#0F172A;line-height:1;}
.notebook-count-label{font-size:.68rem;color:#64748B;font-weight:600;}
.notebook-empty{padding:1.5rem 1.25rem;text-align:center;color:#94A3B8;}
.notebook-empty p{font-size:.85rem;margin-bottom:.75rem;}
.notebook-empty__btn{display:inline-block;padding:.5rem 1rem;background:#F1F5F9;color:#475569;text-decoration:none;border-radius:8px;font-size:.78rem;font-weight:600;}
.notebook-empty__btn:hover{background:#E2E8F0;}
.notebook-list{display:flex;flex-direction:column;padding:.25rem;}
.notebook-item{display:flex;align-items:center;justify-content:space-between;padding:.75rem 1.25rem;text-decoration:none;color:inherit;border-bottom:1px solid #F1F5F9;transition:background .15s;gap:.75rem;}
.notebook-item:last-child{border-bottom:none;}
.notebook-item:hover{background:#F8FAFC;}
.notebook-item__left{flex:1;min-width:0;}
.notebook-item__judul{font-size:.88rem;font-weight:600;color:#0F172A;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.notebook-item__sub{font-size:.72rem;color:#94A3B8;margin-top:.15rem;}
.notebook-item__right{display:flex;align-items:center;gap:.5rem;flex-shrink:0;}
.notebook-item__date{font-size:.7rem;color:#94A3B8;}
.notebook-status{font-size:.65rem;font-weight:700;padding:.18rem .55rem;border-radius:6px;}
.notebook-status[data-tone="ok"]{background:#DCFCE7;color:#15803D;}
.notebook-status[data-tone="primary"]{background:#DBEAFE;color:#1D4ED8;}
.notebook-status[data-tone="neutral"]{background:#F1F5F9;color:#64748B;}
.notebook-more{padding:.75rem 1.25rem;background:#FAFBFC;border-top:1px solid #F1F5F9;text-align:center;}
.notebook-more a{color:#2563EB;text-decoration:none;font-size:.82rem;font-weight:600;}
.notebook-more a:hover{text-decoration:underline;}
@media(max-width:900px){.notebook-grid{grid-template-columns:1fr;}.hamburger{display:flex;}}
</style>

<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
if(hamburger)hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
if(overlay)overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
</script>
@endsection
