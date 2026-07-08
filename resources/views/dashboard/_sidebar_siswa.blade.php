<aside class="sidebar" id="sidebar">
    <div class="sidebar__brand" style="text-decoration:none;cursor:default;">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" class="shrink-0">
            <rect width="28" height="28" rx="8" fill="#2563EB"/>
            <path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <span class="font-display text-[22px] font-bold tracking-tight text-ink-900">LearnFit</span>
    </div>
    <nav class="sidebar__nav">
        <a href="{{ route('dashboard.siswa') }}" class="sidebar__link {{ ($active ?? '') === 'beranda' ? 'sidebar__link--active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="2" width="7" height="7" rx="1.5" fill="currentColor"/><rect x="11" y="2" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/><rect x="2" y="11" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/><rect x="11" y="11" width="7" height="7" rx="1.5" fill="currentColor" opacity=".4"/></svg>
            Beranda
        </a>

        <a href="{{ route('siswa.kelas.index') }}" class="sidebar__link {{ ($active ?? '') === 'kelas' ? 'sidebar__link--active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><rect x="2" y="3" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M7 3v14M2 8h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Kelas Saya
        </a>
        <a href="{{ route('sesi.index') }}" class="sidebar__link {{ ($active ?? '') === 'sesi' ? 'sidebar__link--active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.6"/><path d="M10 6v4l2.5 2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Sesi Belajar
        </a>
        <a href="{{ route('notebook.index') }}" class="sidebar__link {{ ($active ?? '') === 'notebook' ? 'sidebar__link--active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M5 4h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5"/><path d="M6 8h8M6 11h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Catatan Belajar
        </a>
        <a href="{{ route('profil.show') }}" class="sidebar__link {{ ($active ?? '') === 'profil' ? 'sidebar__link--active' : '' }}">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="3.5" stroke="currentColor" stroke-width="1.6"/><path d="M3 18c0-3.31 3.13-6 7-6s7 2.69 7 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            Profil
        </a>
    </nav>
    <div class="sidebar__user">
        <div class="sidebar__avatar"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="7" r="3.5" stroke="#64748b" stroke-width="1.5"/><path d="M3 18c0-3.31 3.13-6 7-6s7 2.69 7 6" stroke="#64748b" stroke-width="1.5" stroke-linecap="round"/></svg></div>
        <div><p class="sidebar__user-name">{{ Auth::user()->name }}</p><p class="sidebar__user-role">Siswa</p></div>
    </div>
</aside>

<style>
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
</style>
