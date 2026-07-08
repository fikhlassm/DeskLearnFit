@extends('layouts.app')
@section('content')

@php
$metodeMap = [
    'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB','bg'=>'#EFF6FF','icon'=>'⌚','desc'=>'25 menit fokus + 5 menit istirahat'],
    'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED','bg'=>'#F5F3FF','icon'=>'🧠','desc'=>'Uji dirimu sendiri tanpa melihat catatan'],
    'blurting'     => ['label'=>'Blurting',       'color'=>'#059669','bg'=>'#ECFDF5','icon'=>'✍️','desc'=>'Tulis semua yang kamu ingat di kertas kosong'],
    'feynman'      => ['label'=>'Feynman',         'color'=>'#D97706','bg'=>'#FFFBEB','icon'=>'🏫','desc'=>'Jelaskan konsep seolah mengajar orang lain'],
];
@endphp

<div class="dash-page">
    @include('dashboard._sidebar_siswa', ['active' => 'sesi-belajar'])

    <main class="dash-main">
        <header class="dash-header">
            <div>
                <h1 class="dash-header__title">Sesi Belajar</h1>
                <p class="dash-header__sub">Pilih metode belajar dan mulai fokus!</p>
            </div>
        </header>

        @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert-error" style="margin-bottom:1rem;">
                <ul style="margin-left:1.5rem">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="method-selector">
            @foreach($metodeMap as $m => $info)
                <a href="?metode={{ $m }}" class="method-tab {{ $selectedMetode === $m ? 'method-tab--active' : '' }}" style="border-bottom-color:{{ $selectedMetode === $m ? $info['color'] : 'transparent' }}">
                    <span class="method-icon" style="background:{{ $selectedMetode === $m ? $info['bg'] : '#F1F5F9' }}; color:{{ $info['color'] }}">{{ $info['icon'] }}</span>
                    <div>
                        <div class="method-name" style="color:{{ $selectedMetode === $m ? $info['color'] : '#475569' }}">{{ $info['label'] }}</div>
                        <div class="method-desc">{{ $info['desc'] }}</div>
                    </div>
                </a>
            @endforeach
        </div>

        <section class="setup-section" style="background:{{ $metodeMap[$selectedMetode]['bg'] }}; border-color:{{ $metodeMap[$selectedMetode]['color'] }}40">
            <div class="setup-header">
                <div style="font-size:2.5rem;">{{ $metodeMap[$selectedMetode]['icon'] }}</div>
                <div>
                    <h2 class="setup-title" style="color:{{ $metodeMap[$selectedMetode]['color'] }}">Mulai Sesi {{ $metodeMap[$selectedMetode]['label'] }}</h2>
                    <p class="setup-desc">Siapkan materi dan target belajarmu.</p>
                </div>
            </div>

            <form action="{{ route('sesi.store') }}" method="POST" class="setup-form">
                @csrf
                <input type="hidden" name="metode" value="{{ $selectedMetode }}">
                
                <div class="form-group">
                    <label>Judul Sesi (Opsional)</label>
                    <input type="text" name="judul" class="form-input" placeholder="Misal: Belajar Bab 1 Biologi">
                </div>

                @if($selectedMetode === 'pomodoro')
                    <div class="form-row">
                        <div class="form-group">
                            <label>Durasi Fokus (menit)</label>
                            <input type="number" name="durasi_fokus_menit" class="form-input" value="25" min="1" max="120">
                        </div>
                        <div class="form-group">
                            <label>Istirahat (menit)</label>
                            <input type="number" name="durasi_istirahat_menit" class="form-input" value="5" min="1" max="60">
                        </div>
                        <div class="form-group">
                            <label>Siklus</label>
                            <input type="number" name="jumlah_siklus" class="form-input" value="1" min="1" max="10">
                        </div>
                    </div>
                @endif
                <button type="submit" class="btn-primary" style="background:{{ $metodeMap[$selectedMetode]['color'] }}; align-self:flex-start;">Mulai Sesi Sekarang</button>
            </form>
        </section>

        @if($riwayat && count($riwayat) > 0)
        <section class="history-section">
            <h3 class="history-title">Sesi Aktif & Terakhir</h3>
            <div class="history-grid">
                @foreach($riwayat as $sesi)
                    @php $infoList = $metodeMap[$sesi->metode] ?? $metodeMap['pomodoro']; @endphp
                    <div class="history-card" style="border-left: 4px solid {{ $infoList['color'] }}">
                        <div class="history-card__head">
                            <span class="history-card__icon">{{ $infoList['icon'] }}</span>
                            <span class="history-card__status {{ $sesi->status === 'aktif' ? 'status-aktif' : 'status-selesai' }}">
                                {{ $sesi->status === 'aktif' ? 'Sedang Berjalan' : 'Selesai' }}
                            </span>
                        </div>
                        <h4 class="history-card__title">{{ $sesi->judul ?: 'Sesi ' . $infoList['label'] }}</h4>
                        <div class="history-card__meta">
                            <span>{{ $sesi->created_at->format('d M, H:i') }}</span>
                            @if($sesi->status === 'aktif')
                                <a href="{{ route('sesi.show', $sesi->id) }}" class="btn-lanjutkan" style="background:{{ $infoList['color'] }}">Lanjutkan</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </main>
</div>

<style>
/* CSS styles are unchanged from original */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.5rem;overflow-x:hidden;}
.dash-header{margin-bottom:.5rem;}
.dash-header__title{font-size:1.6rem;font-weight:800;color:#0F172A;letter-spacing:-.02em;}
.dash-header__sub{font-size:.9rem;color:#64748B;margin-top:.2rem;}

.alert-success{background:#ECFDF5;border:1px solid #A7F3D0;color:#065F46;padding:1rem;border-radius:10px;font-size:.85rem;font-weight:500;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:1rem;border-radius:10px;font-size:.85rem;font-weight:500;}

.method-selector{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;}
.method-tab{display:flex;align-items:flex-start;gap:.75rem;background:#fff;border:1px solid #E2E8F0;border-bottom:3px solid transparent;padding:1rem;border-radius:12px;text-decoration:none;transition:all .2s;cursor:pointer;}
.method-tab:hover{background:#FAFBFC;border-color:#CBD5E1;transform:translateY(-2px);}
.method-tab--active{background:#fff;border-color:#E2E8F0;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);}
.method-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;}
.method-name{font-size:.9rem;font-weight:700;margin-bottom:.2rem;}
.method-desc{font-size:.72rem;color:#64748B;line-height:1.4;}

.setup-section{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;display:flex;flex-direction:column;gap:1.5rem;}
.setup-header{display:flex;align-items:center;gap:1rem;}
.setup-title{font-size:1.2rem;font-weight:800;letter-spacing:-.01em;}
.setup-desc{font-size:.85rem;color:#475569;margin-top:.15rem;}

.setup-form{display:flex;flex-direction:column;gap:1rem;}
.form-row{display:flex;gap:1rem;flex-wrap:wrap;}
.form-row .form-group{flex:1;min-width:150px;}
.form-group{display:flex;flex-direction:column;gap:.4rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#334155;}
.form-input{padding:.65rem 1rem;background:#fff;border:1px solid #CBD5E1;border-radius:10px;font-size:.9rem;font-family:inherit;color:#0F172A;outline:none;transition:border-color .15s;}
.form-input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.btn-primary{padding:.75rem 1.5rem;color:#fff;border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:inherit;transition:opacity .15s;}
.btn-primary:hover{opacity:.9;}

.history-section{margin-top:1rem;}
.history-title{font-size:1.1rem;font-weight:700;color:#1E293B;margin-bottom:1rem;}
.history-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;}
.history-card{background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:.75rem;}
.history-card__head{display:flex;justify-content:space-between;align-items:center;}
.history-card__icon{font-size:1.5rem;}
.history-card__status{font-size:.7rem;font-weight:700;padding:.2rem .5rem;border-radius:6px;text-transform:uppercase;letter-spacing:.04em;}
.status-aktif{background:#FEF2F2;color:#991B1B;}
.status-selesai{background:#F1F5F9;color:#475569;}
.history-card__title{font-size:.95rem;font-weight:700;color:#0F172A;}
.history-card__meta{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;color:#64748B;}
.btn-lanjutkan{padding:.35rem .75rem;color:#fff;border-radius:6px;font-size:.75rem;font-weight:600;text-decoration:none;transition:opacity .15s;}
.btn-lanjutkan:hover{opacity:.85;}
</style>

<script>
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
