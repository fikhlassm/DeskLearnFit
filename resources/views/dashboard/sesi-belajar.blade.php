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
$selectedMetode = $selectedMetode ?? ($sesiAktif?->metode ?? $quizResult ?? 'pomodoro');
if (!array_key_exists($selectedMetode, $metodeInfo)) $selectedMetode = 'pomodoro';
@endphp

<div class="dash-page">
    @include('dashboard._sidebar_siswa', ['active' => 'sesi'])

<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Sesi Belajar</h1><p class="topbar__sub">Pilih metode — tool yang sesuai akan muncul</p></div>
        <div class="topbar__right">
            <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
                <button type="submit" class="topbar__icon-btn"><svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif

    @if($rekomendasiMetode)
    <div class="rekomendasi-card" style="border-left:4px solid {{ $rekomendasiMetode['color'] }}">
        <span class="rekomendasi-badge" style="background:{{ $rekomendasiMetode['bg'] }};color:{{ $rekomendasiMetode['color'] }}">✦ Rekomendasi untukmu</span>
        <p class="rekomendasi-nama">{{ $rekomendasiMetode['icon'] }} {{ $rekomendasiMetode['label'] }}</p>
        <p class="rekomendasi-desc">{{ $rekomendasiMetode['desc'] }}</p>
    </div>
    @endif

    <div class="sesi-layout">
        {{-- FORM SISI KIRI: Mulai Sesi Baru --}}
        <div class="form-card">
            <p class="form-card__title">🚀 Mulai Sesi Baru</p>
            <form method="POST" action="{{ route('sesi.store') }}">
                @csrf
                @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
                <div class="form-group">
                    <label>Metode <span class="req">*</span></label>
                    <select name="metode" id="selectMetode" required>
                        @foreach($metodeInfo as $key => $info)
                        <option value="{{ $key }}" {{ $selectedMetode === $key ? 'selected' : '' }}>{{ $info['icon'] }} {{ $info['label'] }}</option>
                        @endforeach
                    </select>
                    <p class="form-hint" id="metodeHint">{{ $metodeInfo[$selectedMetode]['desc'] }}</p>
                </div>
                <div class="form-group">
                    <label>Judul / Topik</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" placeholder="cth: Belajar Turunan Fungsi" maxlength="200">
                </div>

                <div id="timerFields" style="{{ $selectedMetode === 'pomodoro' ? '' : 'display:none' }}">
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
                </div>

                <button type="submit" class="btn-mulai-sesi">🚀 Catat & Mulai Sesi</button>
            </form>
        </div>

        {{-- SISI KANAN: TOOL sesuai metode --}}
        <div class="tool-card">
            @if($sesiAktif)
                @php $info = $metodeInfo[$sesiAktif->metode] ?? $metodeInfo['pomodoro']; @endphp
                <div class="tool-card__head" style="background:{{ $info['bg'] }}">
                    <span class="tool-card__icon" style="color:{{ $info['color'] }}">{{ $info['icon'] }}</span>
                    <div>
                        <p class="tool-card__label" style="color:{{ $info['color'] }}">Tool Aktif: {{ $info['label'] }}</p>
                        <p class="tool-card__judul">{{ $sesiAktif->judul ?: 'Tanpa judul' }}</p>
                    </div>
                </div>

                @if($sesiAktif->metode === 'pomodoro')
                    @include('dashboard.partials.tool-pomodoro', ['sesi' => $sesiAktif])
                @elseif($sesiAktif->metode === 'active_recall')
                    @include('dashboard.partials.tool-flashcard', ['sesi' => $sesiAktif])
                @elseif(in_array($sesiAktif->metode, ['blurting', 'feynman']))
                    @include('dashboard.partials.tool-notebook', ['sesi' => $sesiAktif])
                @endif

                <div class="tool-card__footer">
                    <form method="POST" action="{{ route('sesi.complete', $sesiAktif) }}" onsubmit="return confirm('Tandai sesi ini selesai?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-selesai-sesi">✓ Tandai Selesai</button>
                    </form>
                    <form method="POST" action="{{ route('sesi.destroy', $sesiAktif) }}" onsubmit="return confirm('Hapus sesi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-hapus-tool">🗑 Hapus Sesi</button>
                    </form>
                </div>
            @else
                <div class="tool-card__empty">
                    <div class="tool-card__empty-icon">🛠️</div>
                    <p class="tool-card__empty-title">Pilih metode & buat sesi dulu</p>
                    <p class="tool-card__empty-sub">
                        @if($selectedMetode === 'pomodoro')
                            Timer fokus akan muncul di sini.
                        @elseif($selectedMetode === 'active_recall')
                            Deck kartu flash akan muncul di sini.
                        @else
                            Notebook dengan analisis sistem akan muncul di sini.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

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
                    <p class="riwayat-meta">{{ $info['label'] }} · {{ $sesi->created_at->format('d M Y H:i') }}</p>
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
.sesi-layout{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;align-items:start;}
.form-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.form-card__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:1rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.75rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.req{color:#EF4444;}
.form-hint{font-size:.72rem;color:#94A3B8;margin-top:.15rem;}
.form-group input,.form-group select,.form-group textarea{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;color:#0F172A;outline:none;font-family:inherit;background:#fff;}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}
.form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:.65rem;}
.btn-mulai-sesi{width:100%;padding:.75rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:inherit;margin-top:.25rem;transition:background .18s;}
.btn-mulai-sesi:hover{background:#1d4ed8;}
.tool-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:0;display:flex;flex-direction:column;overflow:hidden;}
.tool-card__head{padding:1rem 1.25rem;display:flex;align-items:center;gap:.75rem;border-bottom:1px solid #E2E8F0;}
.tool-card__icon{font-size:1.5rem;line-height:1;}
.tool-card__label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;}
.tool-card__judul{font-size:.95rem;font-weight:700;color:#0F172A;margin-top:.1rem;}
.tool-card__empty{padding:3rem 1.5rem;text-align:center;color:#94A3B8;}
.tool-card__empty-icon{font-size:3rem;margin-bottom:.5rem;}
.tool-card__empty-title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:.3rem;}
.tool-card__empty-sub{font-size:.82rem;color:#64748B;}
.tool-card__footer{padding:1rem 1.25rem;border-top:1px solid #E2E8F0;display:flex;gap:.65rem;background:#FAFBFC;}
.btn-selesai-sesi{flex:1;padding:.65rem 1.2rem;background:#15803D;color:#fff;border:none;border-radius:10px;font-size:.85rem;font-weight:700;cursor:pointer;font-family:inherit;}
.btn-selesai-sesi:hover{background:#166534;}
.btn-hapus-tool{padding:.65rem 1rem;background:#fff;color:#991B1B;border:1px solid #FECACA;border-radius:10px;font-size:.82rem;font-weight:600;cursor:pointer;font-family:inherit;}
.btn-hapus-tool:hover{background:#FEF2F2;}

/* ── Tool Pomodoro ── */
.tool-pomodoro{background:#0F172A;color:#fff;padding:1.75rem;display:flex;flex-direction:column;align-items:center;gap:1rem;}
.tool-pomodoro__label{font-size:.72rem;font-weight:700;letter-spacing:.06em;color:#64748B;text-transform:uppercase;}
.tool-pomodoro__display{font-size:3.5rem;font-weight:800;letter-spacing:-.04em;font-variant-numeric:tabular-nums;color:#fff;}
.tool-pomodoro__progress-wrap{width:100%;height:6px;background:rgba(255,255,255,.1);border-radius:99px;overflow:hidden;}
.tool-pomodoro__progress-bar{height:100%;background:linear-gradient(90deg,#2563EB,#60A5FA);border-radius:99px;transition:width .5s linear;}
.tool-pomodoro__meta{display:flex;gap:.8rem;font-size:.72rem;color:#94A3B8;}
.tool-pomodoro__controls{display:flex;gap:.65rem;}
.tool-pomodoro__btn{padding:.6rem 1.2rem;border-radius:10px;border:none;cursor:pointer;font-size:.83rem;font-weight:600;font-family:inherit;transition:opacity .18s,transform .15s;}
.tool-pomodoro__btn--start{background:#2563EB;color:#fff;}
.tool-pomodoro__btn--pause{background:#F59E0B;color:#fff;}
.tool-pomodoro__btn--reset{background:rgba(255,255,255,.1);color:#94A3B8;}
.tool-pomodoro__btn:hover{opacity:.88;transform:translateY(-1px);}
.tool-pomodoro__hint{font-size:.75rem;color:#64748B;}
.tool-pomodoro__catatan{margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,.1);display:flex;flex-direction:column;gap:.4rem;width:100%;}
.tool-pomodoro__catatan-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94A3B8;text-align:left;}
.tool-pomodoro__catatan textarea{padding:.6rem .75rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:8px;color:#fff;font-size:.82rem;font-family:inherit;resize:vertical;outline:none;}
.tool-pomodoro__catatan textarea:focus{background:rgba(255,255,255,.12);border-color:#60A5FA;}
.tool-pomodoro__catatan-save{padding:.5rem .9rem;background:#2563EB;color:#fff;border:none;border-radius:8px;font-size:.78rem;font-weight:600;cursor:pointer;font-family:inherit;align-self:flex-end;transition:background .15s;}
.tool-pomodoro__catatan-save:hover{background:#1d4ed8;}

/* ── Tool Flashcard ── */
.tool-flashcard{padding:1.25rem;display:flex;flex-direction:column;gap:1rem;}
.tool-flashcard__title{font-size:1rem;font-weight:700;color:#0F172A;}
.tool-flashcard__sub{font-size:.78rem;color:#64748B;}
.tool-flashcard__form{display:flex;flex-direction:column;gap:.65rem;background:#F8FAFC;padding:1rem;border-radius:12px;border:1px dashed #CBD5E1;}
.tool-flashcard__field{display:flex;flex-direction:column;gap:.25rem;}
.tool-flashcard__field label{font-size:.75rem;font-weight:600;color:#374151;}
.tool-flashcard__field textarea{padding:.55rem .75rem;border:1px solid #E2E8F0;border-radius:8px;font-size:.85rem;font-family:inherit;resize:vertical;outline:none;background:#fff;}
.tool-flashcard__field textarea:focus{border-color:#7C3AED;box-shadow:0 0 0 3px rgba(124,58,237,.10);}
.tool-flashcard__add{padding:.6rem;background:#7C3AED;color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;font-family:inherit;}
.tool-flashcard__add:hover{background:#6D28D9;}
.tool-flashcard__empty{padding:2rem 1rem;text-align:center;color:#94A3B8;font-size:.85rem;background:#FAFBFC;border-radius:12px;}
.tool-flashcard__empty p:first-child{font-size:2rem;margin-bottom:.4rem;}
.tool-flashcard__deck{display:flex;flex-direction:column;gap:.5rem;}
.tool-flashcard__card{background:#fff;border:1px solid #E2E8F0;border-radius:10px;padding:.65rem .9rem;}
.tool-flashcard__card[open]{background:#FAFBFC;}
.tool-flashcard__card summary{cursor:pointer;display:flex;align-items:center;gap:.6rem;list-style:none;}
.tool-flashcard__card summary::-webkit-details-marker{display:none;}
.tool-flashcard__num{font-size:.7rem;font-weight:700;background:#7C3AED;color:#fff;padding:.1rem .45rem;border-radius:6px;}
.tool-flashcard__q{font-size:.85rem;font-weight:500;color:#0F172A;flex:1;}
.tool-flashcard__answer{margin-top:.65rem;padding:.65rem;background:#F5F3FF;border-left:3px solid #7C3AED;border-radius:6px;}
.tool-flashcard__a-label{font-size:.7rem;font-weight:700;color:#7C3AED;text-transform:uppercase;letter-spacing:.04em;}
.tool-flashcard__a-text{font-size:.82rem;color:#1E293B;margin-top:.2rem;white-space:pre-wrap;}
.tool-flashcard__actions{display:flex;gap:.5rem;margin-top:.5rem;justify-content:flex-end;}
.tool-flashcard__edit,.tool-flashcard__delete{background:none;border:1px solid #E2E8F0;padding:.3rem .7rem;border-radius:6px;font-size:.75rem;cursor:pointer;font-family:inherit;}
.tool-flashcard__edit{color:#475569;}
.tool-flashcard__delete{color:#991B1B;border-color:#FECACA;}

/* ── Tool Notebook ── */
.tool-notebook{padding:1.25rem;display:flex;flex-direction:column;gap:1rem;}
.tool-notebook__title{font-size:1rem;font-weight:700;color:#0F172A;}
.tool-notebook__sub{font-size:.78rem;color:#64748B;font-style:italic;}
.tool-notebook__form{display:flex;flex-direction:column;gap:.65rem;background:#F8FAFC;padding:1rem;border-radius:12px;border:1px dashed #CBD5E1;}
.tool-notebook__form textarea{padding:.75rem;border:1px solid #E2E8F0;border-radius:8px;font-size:.85rem;font-family:inherit;resize:vertical;outline:none;background:#fff;line-height:1.5;}
.tool-notebook__form textarea:focus{border-color:#D97706;box-shadow:0 0 0 3px rgba(217,119,6,.10);}
.tool-notebook__submit{padding:.65rem;background:#D97706;color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;font-family:inherit;align-self:flex-end;padding-left:1.2rem;padding-right:1.2rem;}
.tool-notebook__submit:hover{background:#B45309;}
.tool-notebook__empty{padding:2rem 1rem;text-align:center;color:#94A3B8;font-size:.85rem;background:#FAFBFC;border-radius:12px;}
.tool-notebook__empty p:first-child{font-size:2rem;margin-bottom:.4rem;}
.tool-notebook__section-label{font-size:.8rem;font-weight:700;color:#0F172A;margin-top:.5rem;}
.tool-notebook__list{display:flex;flex-direction:column;gap:.75rem;}
.tool-notebook__entry{background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:1rem;display:flex;flex-direction:column;gap:.65rem;}
.tool-notebook__entry-head{display:flex;align-items:center;justify-content:space-between;}
.tool-notebook__time{font-size:.72rem;color:#94A3B8;}
.tool-notebook__score{font-size:.78rem;font-weight:700;padding:.2rem .55rem;border-radius:6px;}
.tool-notebook__score[data-skor="0"],.tool-notebook__score[data-skor="1"],.tool-notebook__score[data-skor="2"],.tool-notebook__score[data-skor="3"],.tool-notebook__score[data-skor="4"]{background:#FEF2F2;color:#991B1B;}
.tool-notebook__score[data-skor="5"],.tool-notebook__score[data-skor="6"]{background:#FEF3C7;color:#92400E;}
.tool-notebook__score[data-skor="7"],.tool-notebook__score[data-skor="8"]{background:#DCFCE7;color:#15803D;}
.tool-notebook__score[data-skor="9"],.tool-notebook__score[data-skor="100"]{background:#DCFCE7;color:#15803D;}
.tool-notebook__konten{font-size:.85rem;color:#1E293B;line-height:1.55;white-space:pre-wrap;background:#F8FAFC;padding:.65rem;border-radius:8px;max-height:120px;overflow-y:auto;}
.tool-notebook__analisis{background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:.75rem;}
.tool-notebook__a-title{font-size:.72rem;font-weight:700;color:#92400E;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.25rem;}
.tool-notebook__a-text{font-size:.8rem;color:#78350F;line-height:1.5;}
.tool-notebook__keywords{display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.5rem;}
.tool-notebook__kw{font-size:.7rem;font-weight:600;background:#D97706;color:#fff;padding:.15rem .5rem;border-radius:6px;}
.tool-notebook__entry-actions{display:flex;justify-content:flex-end;}
.tool-notebook__entry-actions button{background:none;border:1px solid #FECACA;color:#991B1B;padding:.3rem .65rem;border-radius:6px;font-size:.75rem;cursor:pointer;font-family:inherit;}

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
    .sesi-layout{grid-template-columns:1fr;}
    .form-row-3{grid-template-columns:1fr 1fr;}
}
</style>

<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
document.querySelectorAll('.sidebar__link').forEach(l=>l.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');}));
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);

// Toggle timer fields + metode hint when metode dropdown changes
const metodeInfoJs = @json($metodeInfo);
const selectMetode = document.getElementById('selectMetode');
const timerFields  = document.getElementById('timerFields');
const metodeHint   = document.getElementById('metodeHint');
selectMetode?.addEventListener('change', function() {
    const m = this.value;
    if (timerFields) timerFields.style.display = (m === 'pomodoro') ? '' : 'none';
    if (metodeHint && metodeInfoJs[m]) metodeHint.textContent = metodeInfoJs[m].desc;
});

@if($sesiAktif && $sesiAktif->metode === 'pomodoro')
// --- POMODORO TIMER ---
let totalSeconds = {{ (int) $sesiAktif->durasi_fokus_menit }} * 60;
let remaining = totalSeconds;
let running = false;
let interval = null;

function pad(n){return String(n).padStart(2,'0');}
function updateDisplay(){
    const m=Math.floor(remaining/60), s=remaining%60;
    const display=document.getElementById('timerDisplay');
    const bar=document.getElementById('timerBar');
    const hint=document.getElementById('timerHint');
    if(display)display.textContent=pad(m)+':'+pad(s);
    if(bar)bar.style.width=(remaining/totalSeconds*100)+'%';
    if(hint)hint.textContent=running?'Fokus! Jangan terganggu sekarang.':'Siap untuk fokus belajar?';
}

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
            document.getElementById('timerHint').textContent='Sesi fokus selesai! Istirahat sebentar.';
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

document.getElementById('btnTimerStart')?.addEventListener('click', startTimer);
document.getElementById('btnTimerPause')?.addEventListener('click', pauseTimer);
document.getElementById('btnTimerReset')?.addEventListener('click', resetTimer);

updateDisplay();
@endif
</script>
@endsection
