@extends('layouts.app')

@section('content')

<div class="dash-page">

    {{-- SIDEBAR --}}
    @include('dashboard._sidebar_pengajar', ['active' => 'kelas'])

    {{-- MAIN --}}
    <main class="dash-main">

        {{-- TOP BAR --}}
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Buka Menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
            <div>
                <h1 class="topbar__title">Kelas Saya</h1>
                <p class="topbar__sub">Kelola semua kelas yang Anda ampu</p>
            </div>
            <div class="topbar__right">
                <button class="topbar__icon-btn" aria-label="Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 2a6 6 0 00-6 6v2.586l-1.707 1.707A1 1 0 003 14h14a1 1 0 00.707-1.707L16 10.586V8a6 6 0 00-6-6z" stroke="#475569" stroke-width="1.5"/><path d="M8 14a2 2 0 004 0" stroke="#475569" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <button class="topbar__icon-btn" aria-label="Pengaturan">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="2.5" stroke="#475569" stroke-width="1.5"/><path d="M10 2v2M10 16v2M2 10h2M16 10h2M4.22 4.22l1.42 1.42M14.36 14.36l1.42 1.42M4.22 15.78l1.42-1.42M14.36 5.64l1.42-1.42" stroke="#475569" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="topbar__icon-btn" title="Logout">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M7 3H4a1 1 0 00-1 1v12a1 1 0 001 1h3M13 14l3-4-3-4M16 10H7" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
        <div class="alert-success" id="flashMsg">{{ session('success') }}</div>
        @endif

        {{-- ACTION BAR --}}
        <div class="action-bar">
            <div>
                <h2 class="section__title">Daftar Kelas</h2>
            </div>
            <div style="display:flex;align-items:center;gap:.75rem;">
                <div class="search-box-kelas">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><circle cx="6.5" cy="6.5" r="5" stroke="#94a3b8" stroke-width="1.5"/><path d="M10.5 10.5l3 3" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="text" id="searchKelas" placeholder="Cari kelas...">
                </div>
                <button class="btn-tambah-kelas" onclick="openModal('modalTambah')">+ Tambah Kelas</button>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="table-wrap">
            <table class="kelas-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Kode Kelas</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="kelasBody">
                    @foreach($kelasList as $index => $kelas)
                    <tr class="kelas-row">
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $kelas->nama_kelas }}</strong></td>
                        <td>{{ $kelas->mata_pelajaran }}</td>
                        <td><span class="badge-kode">{{ $kelas->kode_kelas }}</span></td>
                        <td>{{ $kelas->kapasitas }} siswa</td>
                        <td><span class="badge-status badge-{{ $kelas->status }}">{{ ucfirst($kelas->status) }}</span></td>
                        <td class="aksi-col">
                            <button class="btn-edit" onclick="openEdit({{ $kelas->id }},'{{ addslashes($kelas->nama_kelas) }}','{{ addslashes($kelas->mata_pelajaran) }}','{{ $kelas->kode_kelas }}','{{ addslashes($kelas->deskripsi) }}',{{ $kelas->kapasitas }},'{{ $kelas->status }}')">✏ Edit</button>
                            <a href="{{ route('kelas.peserta', $kelas->id) }}" class="btn-edit" style="text-decoration:none;">👥 Peserta</a>
                            <a href="{{ route('materi.index', $kelas->id) }}" class="btn-edit" style="text-decoration:none;background:#F5F3FF;border-color:#DDD6FE;color:#7C3AED;">📚 Materi</a>
                            <a href="{{ route('tugas.index', $kelas->id) }}" class="btn-edit" style="text-decoration:none;background:#FFFBEB;border-color:#FDE68A;color:#D97706;">📝 Tugas</a>
                            <form method="POST" action="{{ route('kelas.destroy', $kelas->id) }}" style="display:inline" onsubmit="return confirm('Hapus kelas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-hapus">🗑 Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal">
        <div class="modal__header">
            <h2 class="modal__title">Tambah Kelas Baru</h2>
            <button class="modal__close" onclick="closeModal('modalTambah')">✕</button>
        </div>
        <form method="POST" action="{{ route('kelas.store') }}">
            @csrf
            <div class="modal__body">
                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" required placeholder="cth: Matematika Dasar A">
                </div>
                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" required placeholder="cth: Matematika">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kode Kelas</label>
                        <input type="text" name="kode_kelas" required placeholder="cth: MTK-A-01">
                    </div>
                    <div class="form-group">
                        <label>Kapasitas</label>
                        <input type="number" name="kapasitas" required min="1" max="100" value="30">
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="aktif">Aktif</option>
                        <option value="draf">Draf</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi <span style="color:#94a3b8">(opsional)</span></label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat kelas..."></textarea>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn-batal" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn-simpan">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal">
        <div class="modal__header">
            <h2 class="modal__title">Edit Kelas</h2>
            <button class="modal__close" onclick="closeModal('modalEdit')">✕</button>
        </div>
        <form method="POST" id="formEdit" action="">
            @csrf
            @method('PUT')
            <div class="modal__body">
                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="e_nama" required>
                </div>
                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" id="e_mapel" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Kode Kelas</label>
                        <input type="text" name="kode_kelas" id="e_kode" required>
                    </div>
                    <div class="form-group">
                        <label>Kapasitas</label>
                        <input type="number" name="kapasitas" id="e_kap" required min="1" max="100">
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="e_status">
                        <option value="aktif">Aktif</option>
                        <option value="draf">Draf</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Deskripsi <span style="color:#94a3b8">(opsional)</span></label>
                    <textarea name="deskripsi" id="e_desk" rows="3"></textarea>
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

.dash-page{
    display:flex;
    min-height:100vh;
    font-family:'Plus Jakarta Sans',sans-serif;
    background:#F1F5F9;
    color:#0F172A;
}

/* ── SIDEBAR ── */
.sidebar{
    width:240px;flex-shrink:0;background:#fff;
    border-right:1px solid #E2E8F0;
    display:flex;flex-direction:column;
    padding:1.25rem 0;
    position:sticky;top:0;height:100vh;overflow-y:auto;
}
.sidebar__brand{
    display:flex;align-items:center;gap:.7rem;
    padding:.25rem 1.25rem 1.25rem;
    border-bottom:1px solid #F1F5F9;
    margin-bottom:.5rem;
}
.sidebar__brand-name{font-size:.95rem;font-weight:700;color:#0F172A;line-height:1.2;}
.sidebar__brand-sub{font-size:.68rem;color:#94A3B8;}
.sidebar__nav{
    flex:1;display:flex;flex-direction:column;
    gap:.15rem;padding:.5rem .75rem;
}
.sidebar__link{
    display:flex;align-items:center;gap:.7rem;
    padding:.65rem .85rem;border-radius:10px;
    text-decoration:none;font-size:.85rem;font-weight:500;
    color:#475569;transition:background .18s,color .18s;
}
.sidebar__link:hover{background:#F8FAFC;color:#2563EB;}
.sidebar__link--active{background:#EFF6FF;color:#2563EB;font-weight:600;}
.sidebar__link:active{background:#DBEAFE;}
.sidebar__user{
    display:flex;align-items:center;gap:.7rem;
    padding:.85rem 1.25rem;
    border-top:1px solid #F1F5F9;
    margin:.5rem .75rem 0;
    border-radius:10px;
    transition:background .18s;cursor:pointer;
}
.sidebar__user:hover{background:#F8FAFC;}
.sidebar__avatar{
    width:36px;height:36px;border-radius:50%;
    background:#E2E8F0;display:flex;
    align-items:center;justify-content:center;flex-shrink:0;
}
.sidebar__user-name{font-size:.82rem;font-weight:600;color:#0F172A;}
.sidebar__user-role{font-size:.68rem;color:#94A3B8;}

/* ── MAIN ── */
.dash-main{
    flex:1;display:flex;flex-direction:column;
    padding:1.5rem 2rem;gap:1.5rem;overflow-x:hidden;
}

/* ── TOPBAR ── */
.topbar{
    display:flex;align-items:center;
    justify-content:space-between;gap:1rem;
}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}
.topbar__icon-btn{
    width:38px;height:38px;
    border:1px solid #E2E8F0;background:#fff;
    border-radius:10px;display:flex;
    align-items:center;justify-content:center;
    cursor:pointer;transition:background .18s;
}
.topbar__icon-btn:hover{background:#F1F5F9;}
.topbar__icon-btn:active{background:#E2E8F0;transform:scale(.93);}
.hamburger{
    display:none;align-items:center;justify-content:center;
    width:38px;height:38px;border-radius:10px;
    border:1px solid #E2E8F0;background:#fff;
    cursor:pointer;flex-shrink:0;transition:background .18s;
}
.hamburger:hover{background:#F1F5F9;}

/* ── ALERT ── */
.alert-success{
    background:#ECFDF5;border:1px solid #6EE7B7;
    border-radius:10px;padding:.65rem 1rem;
    color:#065F46;font-size:.83rem;
}

/* ── ACTION BAR ── */
.action-bar{
    display:flex;align-items:center;
    justify-content:space-between;gap:1rem;
}
.section__title{font-size:1rem;font-weight:700;color:#0F172A;}
.search-box-kelas{
    display:flex;align-items:center;gap:.5rem;
    background:#fff;border:1px solid #E2E8F0;
    border-radius:10px;padding:.5rem .9rem;
    width:220px;transition:border-color .18s,box-shadow .18s;
}
.search-box-kelas:focus-within{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);}
.search-box-kelas input{
    border:none;outline:none;
    font-size:.83rem;color:#0F172A;
    font-family:inherit;width:100%;background:transparent;
}
.search-box-kelas input::placeholder{color:#94A3B8;}
.btn-tambah-kelas{
    padding:.55rem 1.1rem;background:#2563EB;color:#fff;
    border:none;border-radius:10px;font-size:.83rem;
    font-weight:600;cursor:pointer;font-family:inherit;
    transition:background .18s,transform .15s,box-shadow .18s;
    white-space:nowrap;
}
.btn-tambah-kelas:hover{background:#1d4ed8;box-shadow:0 4px 14px rgba(37,99,235,.3);transform:translateY(-1px);}
.btn-tambah-kelas:active{transform:scale(.96);background:#1e40af;}

/* ── TABLE ── */
.table-wrap{overflow-x:auto;}
.kelas-table{
    width:100%;border-collapse:collapse;
    font-size:.85rem;background:#fff;
    border-radius:16px;overflow:hidden;
    border:1px solid #E2E8F0;
}
.kelas-table thead tr{background:#F8FAFC;border-bottom:1px solid #E2E8F0;}
.kelas-table th{
    padding:.75rem 1rem;text-align:left;
    font-weight:600;color:#475569;
    font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;
}
.kelas-table td{
    padding:.85rem 1rem;color:#0F172A;
    border-bottom:1px solid #F1F5F9;vertical-align:middle;
}
.kelas-table tbody tr:last-child td{border-bottom:none;}
.kelas-table tbody tr{transition:background .18s;}
.kelas-table tbody tr:hover{background:#F8FAFC;}
.badge-kode{
    background:#EFF6FF;color:#1D4ED8;
    padding:.2rem .65rem;border-radius:99px;
    font-size:.72rem;font-weight:600;font-family:monospace;
}
.badge-status{padding:.2rem .65rem;border-radius:99px;font-size:.72rem;font-weight:600;}
.badge-aktif{background:#DCFCE7;color:#15803D;}
.badge-draf{background:#FFF7ED;color:#92400E;}
.badge-selesai{background:#F1F5F9;color:#475569;}
.aksi-col{white-space:nowrap;}
.btn-edit{
    padding:.35rem .85rem;border:1.5px solid #BFDBFE;
    background:#EFF6FF;color:#1D4ED8;border-radius:8px;
    font-size:.75rem;font-weight:600;cursor:pointer;
    margin-right:.35rem;transition:all .15s;font-family:inherit;
}
.btn-edit:hover{background:#DBEAFE;border-color:#93C5FD;transform:translateY(-1px);}
.btn-edit:active{transform:scale(.96);}
.btn-hapus{
    padding:.35rem .85rem;border:1.5px solid #FECACA;
    background:#FEF2F2;color:#DC2626;border-radius:8px;
    font-size:.75rem;font-weight:600;cursor:pointer;
    transition:all .15s;font-family:inherit;
}
.btn-hapus:hover{background:#FEE2E2;border-color:#FCA5A5;transform:translateY(-1px);}
.btn-hapus:active{transform:scale(.96);}

/* ── MODAL ── */
.modal-overlay{
    display:none;position:fixed;inset:0;
    background:rgba(15,23,42,.45);z-index:100;
    align-items:center;justify-content:center;padding:1.25rem;
}
.modal-overlay.open{display:flex;}
.modal{
    background:#fff;border-radius:16px;
    width:100%;max-width:520px;
    box-shadow:0 8px 32px rgba(0,0,0,.16);
    animation:slideUp .2s ease;
}
@keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.modal__header{
    display:flex;align-items:center;
    justify-content:space-between;
    padding:1.25rem 1.5rem 0;
}
.modal__title{font-size:1rem;font-weight:700;color:#0F172A;}
.modal__close{
    background:none;border:none;font-size:1rem;
    cursor:pointer;color:#94A3B8;padding:.25rem;
    border-radius:6px;transition:color .15s;
}
.modal__close:hover{color:#0F172A;}
.modal__body{padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:.9rem;}
.modal__footer{
    padding:0 1.5rem 1.25rem;
    display:flex;justify-content:flex-end;gap:.6rem;
}
.form-group{display:flex;flex-direction:column;gap:.3rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.form-group input,.form-group select,.form-group textarea{
    padding:.55rem .85rem;border:1px solid #E2E8F0;
    border-radius:9px;font-size:.85rem;color:#0F172A;
    outline:none;font-family:inherit;
    transition:border-color .18s,box-shadow .18s;
}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{
    border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.10);
}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.btn-batal{
    padding:.55rem 1.1rem;border:1.5px solid #E2E8F0;
    background:#fff;color:#475569;border-radius:9px;
    font-size:.83rem;font-weight:600;cursor:pointer;
    font-family:inherit;transition:all .15s;
}
.btn-batal:hover{background:#F8FAFC;border-color:#CBD5E1;}
.btn-simpan{
    padding:.55rem 1.1rem;background:#2563EB;
    color:#fff;border:none;border-radius:9px;
    font-size:.83rem;font-weight:600;cursor:pointer;
    font-family:inherit;transition:background .18s;
}
.btn-simpan:hover{background:#1d4ed8;}

/* ── SIDEBAR OVERLAY ── */
.sidebar-overlay{display:none;}

@media(max-width:900px){
    .hamburger{display:flex;}
    .sidebar{
        position:fixed;top:0;left:-260px;z-index:200;
        height:100vh;width:240px;
        transition:left .28s cubic-bezier(.4,0,.2,1);
        box-shadow:none;
    }
    .sidebar.sidebar--open{
        left:0;
        box-shadow:4px 0 24px rgba(15,23,42,.15);
    }
    .sidebar-overlay{
        display:none;position:fixed;inset:0;
        background:rgba(15,23,42,.35);z-index:199;
        backdrop-filter:blur(2px);
        transition:opacity .28s;opacity:0;
    }
    .sidebar-overlay.overlay--show{display:block;opacity:1;}
    .dash-main{padding:1rem;}
    .action-bar{flex-direction:column;align-items:flex-start;}
}
@media(max-width:560px){
    .dash-main{padding:1rem;}
}
</style>

<script>
// ── SIDEBAR / HAMBURGER ──
const sidebar  = document.querySelector('.sidebar');
const overlay  = document.getElementById('sidebarOverlay');
const hamburger= document.getElementById('hamburgerBtn');
hamburger.addEventListener('click', () => {
    sidebar.classList.add('sidebar--open');
    overlay.classList.add('overlay--show');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('sidebar--open');
    overlay.classList.remove('overlay--show');
});
document.querySelectorAll('.sidebar__link').forEach(link => {
    link.addEventListener('click', () => {
        sidebar.classList.remove('sidebar--open');
        overlay.classList.remove('overlay--show');
    });
});

// ── MODAL ──
function openModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});

// ── EDIT ──
function openEdit(id, nama, mapel, kode, desk, kap, status) {
    document.getElementById('formEdit').action = '/dashboard/kelas/' + id;
    document.getElementById('e_nama').value   = nama;
    document.getElementById('e_mapel').value  = mapel;
    document.getElementById('e_kode').value   = kode;
    document.getElementById('e_desk').value   = desk;
    document.getElementById('e_kap').value    = kap;
    document.getElementById('e_status').value = status;
    openModal('modalEdit');
}

// ── SEARCH ──
document.getElementById('searchKelas').addEventListener('input', function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll('.kelas-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// ── FLASH AUTO HIDE ──
setTimeout(() => {
    const f = document.getElementById('flashMsg');
    if(f) f.style.transition='opacity .5s', f.style.opacity='0', setTimeout(()=>f.remove(),500);
}, 3000);
</script>

@endsection