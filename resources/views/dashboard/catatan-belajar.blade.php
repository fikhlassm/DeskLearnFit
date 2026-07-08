@extends('layouts.app')
@section('content')

@php
$metodeLabel = [
    'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB','bg'=>'#EFF6FF','icon'=>'⌚'],
    'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED','bg'=>'#F5F3FF','icon'=>'🧠'],
    'blurting'     => ['label'=>'Blurting',       'color'=>'#059669','bg'=>'#ECFDF5','icon'=>'✍️'],
    'feynman'      => ['label'=>'Feynman',         'color'=>'#D97706','bg'=>'#FFFBEB','icon'=>'🏫'],
    'lainnya'      => ['label'=>'Lainnya',         'color'=>'#475569','bg'=>'#F1F5F9','icon'=>'📝'],
];
@endphp

<div class="dash-page">
    @include('dashboard._sidebar_siswa', ['active' => 'catatan'])

{{-- MAIN --}}
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn" aria-label="Buka Menu">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
        <div>
            <h1 class="topbar__title">Catatan Belajar</h1>
            <p class="topbar__sub">Catat dan evaluasi proses belajarmu setiap hari</p>
        </div>
        <div class="topbar__right">
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success" id="flashMsg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-error" id="flashMsg">{{ session('error') }}</div>
    @endif

    {{-- STATS ROW --}}
    <div class="stats-row">
        <div class="stat-mini"><span class="stat-mini__num">{{ $totalJurnal }}</span><span class="stat-mini__label">Total Catatan</span></div>
        @if($jurnalTerbaru)
        <div class="stat-mini"><span class="stat-mini__num">{{ $jurnalTerbaru->tanggal->format('d M') }}</span><span class="stat-mini__label">Catatan Terakhir</span></div>
        @endif
        <button class="btn-tambah-catatan" onclick="openModal('modalTambah')">+ Catatan Baru</button>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('catatan.index') }}" class="filter-bar">
        <select name="metode" onchange="this.form.submit()" class="filter-select">
            <option value="">Semua Metode</option>
            @foreach($metodeLabel as $key => $m)
            <option value="{{ $key }}" {{ request('metode') === $key ? 'selected' : '' }}>{{ $m['icon'] }} {{ $m['label'] }}</option>
            @endforeach
        </select>
        @if(request('metode'))
        <a href="{{ route('catatan.index') }}" class="btn-reset-filter">Reset</a>
        @endif
    </form>

    {{-- DAFTAR CATATAN --}}
    @if($jurnalList->isEmpty())
    <div class="empty-state">
        <div class="empty-state__icon" style="display:flex;justify-content:center;color:#94A3B8;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></div>
        <p class="empty-state__title">Belum ada catatan belajar</p>
        <p class="empty-state__sub">Mulai catat proses belajarmu hari ini untuk melacak perkembangan.</p>
        <button class="btn-primary" onclick="openModal('modalTambah')">+ Tambah Catatan Pertama</button>
    </div>
    @else
    <div class="catatan-list">
        @foreach($jurnalList as $jurnal)
        @php $m = $metodeLabel[$jurnal->metode_yang_digunakan] ?? null; @endphp
        <div class="catatan-card">
            <div class="catatan-card__header">
                <div class="catatan-card__date">
                    <span class="catatan-card__day">{{ $jurnal->tanggal->format('d') }}</span>
                    <span class="catatan-card__month">{{ $jurnal->tanggal->format('M Y') }}</span>
                </div>
                <div class="catatan-card__meta">
                    @if($m)
                    <span class="badge-metode" style="background:{{ $m['bg'] }};color:{{ $m['color'] }}">{{ $m['icon'] }} {{ $m['label'] }}</span>
                    @endif
                    @if($jurnal->rating_efektivitas)
                    <span class="badge-rating">
                        @for($i=1;$i<=5;$i++)<span style="color:{{ $i<=$jurnal->rating_efektivitas ? '#FBBF24' : '#CBD5E1' }}">★</span>@endfor
                    </span>
                    @endif
                    @if($jurnal->durasi_menit)
                    <span class="badge-durasi">{{ $jurnal->durasi_menit }} mnt</span>
                    @endif
                </div>
            </div>
            @if($jurnal->judul)
            <p class="catatan-card__judul">{{ $jurnal->judul }}</p>
            @endif
            <p class="catatan-card__isi">{{ Str::limit($jurnal->isi_jurnal, 200) }}</p>
            <div class="catatan-card__actions">
                <button class="btn-edit-catatan" onclick="openEditModal({{ $jurnal->id }})">Edit</button>
                <form method="POST" action="{{ route('catatan.destroy', $jurnal) }}" style="display:inline" onsubmit="return confirm('Hapus catatan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-hapus-catatan">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="pagination-wrap">{{ $jurnalList->links() }}</div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah">
<div class="modal">
    <div class="modal__header">
        <h2 class="modal__title">Catatan Belajar Baru</h2>
        <button class="modal__close" onclick="closeModal('modalTambah')">✕</button>
    </div>
    <form method="POST" action="{{ route('catatan.store') }}">
        @csrf
        <div class="modal__body">
            @if($errors->any())
            <div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>
            @endif
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal <span class="req">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>Durasi (menit)</label>
                    <input type="number" name="durasi_menit" value="{{ old('durasi_menit') }}" min="1" max="1440" placeholder="cth: 45">
                </div>
            </div>
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" placeholder="cth: Belajar Turunan Fungsi" maxlength="200">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Metode Belajar</label>
                    <select name="metode_yang_digunakan">
                        <option value="">-- Pilih Metode --</option>
                        @foreach($metodeLabel as $key => $m)
                        <option value="{{ $key }}" {{ old('metode_yang_digunakan') === $key ? 'selected' : '' }}>{{ $m['icon'] }} {{ $m['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Rating Efektivitas</label>
                    <div class="star-input" id="starInput">
                        @for($i=1;$i<=5;$i++)
                        <input type="radio" name="rating_efektivitas" value="{{ $i }}" id="star{{ $i }}" {{ old('rating_efektivitas')==$i ? 'checked' : '' }} style="display:none">
                        <label for="star{{ $i }}" class="star-label" data-val="{{ $i }}">★</label>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Isi Catatan <span class="req">*</span></label>
                <textarea name="isi_jurnal" rows="4" required maxlength="5000" placeholder="Tulis apa yang kamu pelajari hari ini...">{{ old('isi_jurnal') }}</textarea>
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn-batal" onclick="closeModal('modalTambah')">Batal</button>
            <button type="submit" class="btn-simpan">Simpan Catatan</button>
        </div>
    </form>
</div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay" id="modalEdit">
<div class="modal">
    <div class="modal__header">
        <h2 class="modal__title">Edit Catatan Belajar</h2>
        <button class="modal__close" onclick="closeModal('modalEdit')">✕</button>
    </div>
    <form method="POST" id="formEdit" action="">
        @csrf @method('PUT')
        <div class="modal__body">
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal <span class="req">*</span></label>
                    <input type="date" name="tanggal" id="e_tanggal" max="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>Durasi (menit)</label>
                    <input type="number" name="durasi_menit" id="e_durasi" min="1" max="1440">
                </div>
            </div>
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" id="e_judul" maxlength="200">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Metode Belajar</label>
                    <select name="metode_yang_digunakan" id="e_metode">
                        <option value="">-- Pilih Metode --</option>
                        @foreach($metodeLabel as $key => $m)
                        <option value="{{ $key }}">{{ $m['icon'] }} {{ $m['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Rating Efektivitas</label>
                    <input type="number" name="rating_efektivitas" id="e_rating" min="1" max="5" placeholder="1–5">
                </div>
            </div>
            <div class="form-group">
                <label>Isi Catatan <span class="req">*</span></label>
                <textarea name="isi_jurnal" id="e_isi" rows="4" required maxlength="5000"></textarea>
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn-batal" onclick="closeModal('modalEdit')">Batal</button>
            <button type="submit" class="btn-simpan">Simpan Perubahan</button>
        </div>
    </form>
</div>
</div>

    </main>
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
.topbar__icon-btn{width:38px;height:38px;border:1px solid #E2E8F0;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .18s;}
.topbar__icon-btn:hover{background:#F1F5F9;}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;transition:background .18s, transform .15s;}
.hamburger:hover{background:#F1F5F9;}
.hamburger:active{background:#E2E8F0;transform:scale(.93);}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.65rem 1rem;color:#991B1B;font-size:.83rem;}
.stats-row{display:flex;align-items:center;gap:1rem;flex-wrap:wrap;}
.stat-mini{background:#fff;border:1px solid #E2E8F0;border-radius:12px;padding:.75rem 1.25rem;display:flex;flex-direction:column;align-items:center;gap:.15rem;min-width:100px;}
.stat-mini__num{font-size:1.5rem;font-weight:800;color:#0F172A;}
.stat-mini__label{font-size:.72rem;color:#64748B;}
.btn-tambah-catatan{margin-left:auto;padding:.55rem 1.1rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.83rem;font-weight:600;cursor:pointer;font-family:inherit;transition:background .18s;}
.btn-tambah-catatan:hover{background:#1d4ed8;}
.filter-bar{display:flex;align-items:center;gap:.75rem;}
.filter-select{padding:.45rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.83rem;color:#0F172A;font-family:inherit;outline:none;background:#fff;}
.btn-reset-filter{font-size:.78rem;color:#EF4444;text-decoration:none;padding:.35rem .65rem;border-radius:7px;border:1px solid #FECACA;background:#FEF2F2;}
.catatan-list{display:flex;flex-direction:column;gap:.85rem;}
.catatan-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.1rem 1.3rem;transition:box-shadow .2s,transform .2s;}
.catatan-card:hover{box-shadow:0 4px 18px rgba(15,23,42,.08);transform:translateY(-1px);}
.catatan-card__header{display:flex;align-items:center;gap:1rem;margin-bottom:.6rem;}
.catatan-card__date{display:flex;flex-direction:column;align-items:center;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:.4rem .7rem;min-width:50px;}
.catatan-card__day{font-size:1.3rem;font-weight:800;color:#0F172A;line-height:1;}
.catatan-card__month{font-size:.65rem;color:#94A3B8;font-weight:500;}
.catatan-card__meta{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;}
.badge-metode{font-size:.72rem;font-weight:700;padding:.22rem .65rem;border-radius:8px;}
.badge-rating{font-size:.88rem;}
.badge-durasi{font-size:.72rem;color:#64748B;background:#F1F5F9;padding:.2rem .55rem;border-radius:6px;}
.catatan-card__judul{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:.3rem;}
.catatan-card__isi{font-size:.83rem;color:#475569;line-height:1.6;margin-bottom:.75rem;}
.catatan-card__actions{display:flex;gap:.5rem;}
.btn-edit-catatan{padding:.35rem .85rem;border:1.5px solid #BFDBFE;background:#EFF6FF;color:#1D4ED8;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;font-family:inherit;}
.btn-hapus-catatan{padding:.35rem .85rem;border:1.5px solid #FECACA;background:#FEF2F2;color:#DC2626;border-radius:8px;font-size:.75rem;font-weight:600;cursor:pointer;font-family:inherit;}
.empty-state{text-align:center;padding:3rem 1rem;background:#fff;border:1px dashed #E2E8F0;border-radius:16px;}
.empty-state__icon{font-size:3rem;margin-bottom:.75rem;}
.empty-state__title{font-size:1rem;font-weight:700;color:#0F172A;margin-bottom:.35rem;}
.empty-state__sub{font-size:.83rem;color:#64748B;margin-bottom:1.25rem;}
.btn-primary{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.85rem;font-weight:600;cursor:pointer;font-family:inherit;}
.pagination-wrap{margin-top:.5rem;}
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:100;align-items:center;justify-content:center;padding:1.25rem;}
.modal-overlay.open{display:flex;}
.modal{background:#fff;border-radius:16px;width:100%;max-width:520px;box-shadow:0 8px 32px rgba(0,0,0,.16);animation:slideUp .2s ease;max-height:90vh;overflow-y:auto;}
@keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.modal__header{display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem 0;}
.modal__title{font-size:1rem;font-weight:700;color:#0F172A;}
.modal__close{background:none;border:none;font-size:1rem;cursor:pointer;color:#94A3B8;padding:.25rem;border-radius:6px;}
.modal__body{padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:.9rem;}
.modal__footer{padding:0 1.5rem 1.25rem;display:flex;justify-content:flex-end;gap:.6rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.req{color:#EF4444;}
.form-group input,.form-group select,.form-group textarea{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;color:#0F172A;outline:none;font-family:inherit;transition:border-color .18s;}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.btn-batal{padding:.55rem 1.1rem;border:1.5px solid #E2E8F0;background:#fff;color:#475569;border-radius:9px;font-size:.83rem;font-weight:600;cursor:pointer;font-family:inherit;}
.btn-simpan{padding:.55rem 1.1rem;background:#2563EB;color:#fff;border:none;border-radius:9px;font-size:.83rem;font-weight:600;cursor:pointer;font-family:inherit;}
.star-input{display:flex;gap:.25rem;}
.star-label{font-size:1.5rem;color:#CBD5E1;cursor:pointer;transition:color .15s;}
.star-label:hover,.star-label.active{color:#FBBF24;}
.sidebar-overlay{display:none;}
@media(max-width:900px){
    .hamburger{display:flex;}
    .sidebar{position:fixed;top:0;left:-260px;z-index:200;height:100vh;width:240px;transition:left .28s cubic-bezier(.4,0,.2,1);}
    .sidebar.sidebar--open{left:0;box-shadow:4px 0 24px rgba(15,23,42,.15);}
    .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.35);z-index:199;transition:opacity .28s;opacity:0;}
    .sidebar-overlay.overlay--show{display:block;opacity:1;}
    .dash-main{padding:1rem;}
    .stats-row{flex-direction:column;align-items:flex-start;}
    .btn-tambah-catatan{margin-left:0;}
}
</style>

<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
document.querySelectorAll('.sidebar__link').forEach(l=>l.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');}));

function openModal(id){document.getElementById(id).classList.add('open');}
function closeModal(id){document.getElementById(id).classList.remove('open');}
document.querySelectorAll('.modal-overlay').forEach(el=>el.addEventListener('click',e=>{if(e.target===el)closeModal(el.id);}));

function openEditModal(id){
    fetch('/dashboard/catatan-belajar/'+id+'/edit', {headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
    .then(r=>r.json()).then(d=>{
        document.getElementById('formEdit').action='/dashboard/catatan-belajar/'+id;
        document.getElementById('e_tanggal').value=d.tanggal||'';
        document.getElementById('e_judul').value=d.judul||'';
        document.getElementById('e_isi').value=d.isi_jurnal||'';
        document.getElementById('e_metode').value=d.metode_yang_digunakan||'';
        document.getElementById('e_rating').value=d.rating_efektivitas||'';
        document.getElementById('e_durasi').value=d.durasi_menit||'';
        openModal('modalEdit');
    });
}

// Star rating interaction
document.querySelectorAll('.star-label').forEach((label,i,all)=>{
    label.addEventListener('mouseover',()=>all.forEach((l,j)=>l.classList.toggle('active',j<=i)));
    label.addEventListener('mouseout',()=>{
        const checked=document.querySelector('input[name="rating_efektivitas"]:checked');
        const val=checked?parseInt(checked.value):0;
        all.forEach((l,j)=>l.classList.toggle('active',j<val));
    });
    label.addEventListener('click',()=>{
        const val=parseInt(label.dataset.val);
        all.forEach((l,j)=>l.classList.toggle('active',j<val));
    });
});

setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);

@if($errors->any())
document.addEventListener('DOMContentLoaded',()=>openModal('modalTambah'));
@endif
</script>
@endsection
