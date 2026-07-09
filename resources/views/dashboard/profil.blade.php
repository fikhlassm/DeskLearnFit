@extends('layouts.app')
@section('content')

@php
$methodMap = ['pomodoro'=>'Pomodoro','active_recall'=>'Active Recall','blurting'=>'Blurting','feynman'=>'Feynman Technique'];
$isSiswa   = $user->role === 'siswa';
@endphp

<div class="dash-page">
    @if($isSiswa)
        @include('dashboard._sidebar_siswa', ['active' => 'profil'])
    @else
        @include('dashboard._sidebar_pengajar', ['active' => 'profil'])
    @endif

<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Profil</h1><p class="topbar__sub">Kelola informasi akun kamu</p></div>
        <div class="topbar__right">
            <form method="POST" action="{{ route('logout') }}" style="margin:0">@csrf
                <button type="submit" class="topbar__icon-btn"><svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
            </form>
        </div>
    </div>

    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif

    <div class="profil-grid">
        {{-- KARTU INFO --}}
        <div class="profil-card">
            <div class="profil-avatar-wrap">
                <div class="profil-avatar" style="overflow:hidden;">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        {{ strtoupper(collect(explode(' ', trim($user->name)))->map(fn($w) => mb_substr($w,0,1))->take(2)->join('')) }}
                    @endif
                </div>
            </div>
            <p class="profil-nama">{{ $user->name }}</p>
            <p class="profil-email">{{ $user->email }}</p>
            <span class="profil-role-badge">{{ ucfirst($user->role) }}</span>
            @if($isSiswa && $user->quiz_result)
            <div class="profil-metode">
                <span class="profil-metode__label">Metode Belajar</span>
                <span class="profil-metode__val">{{ $methodMap[$user->quiz_result] ?? $user->quiz_result }}</span>
            </div>
            @endif
        </div>

        {{-- FORM EDIT --}}
        <div class="form-section">
            <p class="form-section__title">Edit Profil</p>
            <form method="POST" action="{{ route('profil.update') }}">
                @csrf @method('PUT')
                @if($errors->any())
                <div class="alert-error" style="margin-bottom:1rem">
                    <ul style="margin:0;padding-left:1rem">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                    </div>
                    <div class="form-group">
                        <label>Email <span class="req">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255">
                    </div>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <input type="text" value="{{ ucfirst($user->role) }}" disabled class="input-disabled">
                    <span class="form-hint">Role tidak dapat diubah melalui halaman ini.</span>
                </div>

                @if($isSiswa)
                <div class="form-row-2">
                    <div class="form-group">
                        <label>Jenjang Pendidikan</label>
                        <input type="text" name="jenjang" value="{{ old('jenjang', $user->jenjang) }}" placeholder="cth: SMA, Kuliah, S1" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="cth: 0812xxxxxxxx" maxlength="20">
                    </div>
                </div>
                <div class="form-group">
                    <label>Tujuan Belajar</label>
                    <input type="text" name="tujuan_belajar" value="{{ old('tujuan_belajar', $user->tujuan_belajar) }}" placeholder="cth: Lulus SNBT, Meningkatkan IPK" maxlength="500">
                </div>
                @else
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="cth: 0812xxxxxxxx" maxlength="20">
                </div>
                @endif

                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" rows="3" placeholder="Ceritakan sedikit tentang dirimu..." maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-simpan-profil">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
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
.topbar__icon-btn{width:38px;height:38px;border:1px solid #E2E8F0;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .18s, transform .15s;}
.topbar__icon-btn:hover{background:#F1F5F9;}
.topbar__icon-btn:active{background:#E2E8F0;transform:scale(.93);}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;transition:background .18s, transform .15s;}
.hamburger:hover{background:#F1F5F9;}
.hamburger:active{background:#E2E8F0;transform:scale(.93);}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.65rem 1rem;color:#991B1B;font-size:.83rem;}
.profil-grid{display:grid;grid-template-columns:260px 1fr;gap:1.5rem;align-items:start;}
.profil-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.75rem;display:flex;flex-direction:column;align-items:center;gap:.65rem;text-align:center;}
.profil-avatar-wrap{margin-bottom:.5rem;}
.profil-avatar{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#60A5FA);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:#fff;}
.profil-nama{font-size:1rem;font-weight:700;color:#0F172A;}
.profil-email{font-size:.8rem;color:#64748B;}
.profil-role-badge{font-size:.72rem;font-weight:700;background:#EFF6FF;color:#2563EB;padding:.25rem .75rem;border-radius:99px;}
.profil-metode{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:.6rem 1rem;width:100%;text-align:left;}
.profil-metode__label{display:block;font-size:.7rem;color:#94A3B8;margin-bottom:.2rem;}
.profil-metode__val{font-size:.85rem;font-weight:700;color:#2563EB;}
.form-section{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.75rem;}
.form-section__title{font-size:1rem;font-weight:700;color:#0F172A;margin-bottom:1.25rem;}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.req{color:#EF4444;}
.form-group input,.form-group textarea{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;color:#0F172A;outline:none;font-family:inherit;transition:border-color .18s;}
.form-group input:focus,.form-group textarea:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}
.input-disabled{background:#F8FAFC;color:#94A3B8;cursor:not-allowed;}
.form-hint{font-size:.72rem;color:#94A3B8;margin-top:.15rem;}
.form-actions{display:flex;justify-content:flex-end;margin-top:.5rem;}
.btn-simpan-profil{padding:.65rem 1.75rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .18s, transform .18s, box-shadow .18s;}
.btn-simpan-profil:hover{background:#1d4ed8;transform:translateY(-1px);box-shadow:0 4px 14px rgba(37,99,235,.25);}
.sidebar-overlay{display:none;}
@media(max-width:900px){
    .hamburger{display:flex;}
    .sidebar{position:fixed;top:0;left:-260px;z-index:200;height:100vh;width:240px;transition:left .28s;}
    .sidebar.sidebar--open{left:0;box-shadow:4px 0 24px rgba(15,23,42,.15);}
    .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.35);z-index:199;transition:opacity .28s;opacity:0;}
    .sidebar-overlay.overlay--show{display:block;opacity:1;}
    .profil-grid{grid-template-columns:1fr;}
    .dash-main{padding:1rem;}
    .form-row-2{grid-template-columns:1fr;}
}
</style>
<script>
const sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('sidebarOverlay'),hamburger=document.getElementById('hamburgerBtn');
hamburger.addEventListener('click',()=>{sidebar.classList.add('sidebar--open');overlay.classList.add('overlay--show');});
overlay.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');});
document.querySelectorAll('.sidebar__link').forEach(l=>l.addEventListener('click',()=>{sidebar.classList.remove('sidebar--open');overlay.classList.remove('overlay--show');}));
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);
</script>
@endsection
