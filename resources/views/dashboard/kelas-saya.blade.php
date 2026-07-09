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

        {{-- DAFTAR KELAS (Grid) --}}
        @if($kelasList->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon" style="display:flex;justify-content:center;color:#94A3B8;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            </div>
            <p class="empty-state__title">Belum ada kelas</p>
            <p class="empty-state__sub">Buat kelas pertama Anda dengan mengklik "Tambah Kelas".</p>
        </div>
        @else
        <div class="kelas-grid">
            @foreach($kelasList as $kelas)
            <div class="kelas-card">
                @if($kelas->cover_image)
                    <div class="kelas-card__cover" style="background-image: url('{{ asset($kelas->cover_image) }}')">
                        <span class="badge-status badge-{{ $kelas->status }}" style="position:absolute;top:1rem;right:1rem;box-shadow:0 2px 4px rgba(0,0,0,0.1);">{{ ucfirst($kelas->status) }}</span>
                    </div>
                @else
                    <div class="kelas-card__cover" style="background-color: {{ $kelas->theme_color ?? '#4F46E5' }}">
                        <span class="badge-status badge-{{ $kelas->status }}" style="position:absolute;top:1rem;right:1rem;box-shadow:0 2px 4px rgba(0,0,0,0.1);">{{ ucfirst($kelas->status) }}</span>
                    </div>
                @endif
                <div class="kelas-card__body">
                    <p class="kelas-card__nama">{{ $kelas->nama_kelas }}</p>
                    <p class="kelas-card__kode">{{ $kelas->mata_pelajaran }}</p>
                    <p class="kelas-card__kode" style="margin-top:.25rem">Kode: <code>{{ $kelas->kode_kelas }}</code> &middot; {{ $kelas->kapasitas }} siswa</p>
                    
                    <div class="kelas-card__actions">
                        <a href="{{ route('kelas.show', $kelas->id) }}" class="btn-masuk-kelas">
                            Masuk Kelas
                        </a>
                        <div style="display:flex;gap:.5rem;width:100%;">
                            @php
                                $jadwal = $kelas->jadwals->first();
                                $hari = $jadwal->hari ?? 'Senin';
                                $jm = $jadwal ? substr($jadwal->jam_mulai, 0, 5) : '08:00';
                                $js = $jadwal ? substr($jadwal->jam_selesai, 0, 5) : '10:00';
                                $ruang = addslashes($jadwal->ruang ?? '');
                            @endphp
                            <button class="btn-edit-card" onclick="openEdit({{ $kelas->id }},'{{ addslashes($kelas->nama_kelas) }}','{{ addslashes($kelas->mata_pelajaran) }}','{{ addslashes($kelas->deskripsi) }}','{{ $kelas->status }}','{{ $kelas->theme_color }}','{{ $hari }}','{{ $jm }}','{{ $js }}','{{ $ruang }}', {{ $kelas->kapasitas }})" title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                            </button>
                            <a href="{{ route('kelas.peserta', $kelas->id) }}" class="btn-edit-card" title="Peserta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </a>
                            <form method="POST" action="{{ route('kelas.destroy', $kelas->id) }}" style="display:contents" onsubmit="return confirm('Hapus kelas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-hapus-card" title="Hapus">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

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
        <form method="POST" action="{{ route('kelas.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal__body">
                <div class="form-group">
                    <label>Gambar Sampul <span style="color:#94a3b8;font-weight:400">(Ukuran disarankan 600x300 px / Rasio 2:1)</span></label>
                    <input type="file" name="cover_image" accept="image/png, image/jpeg, image/jpg">
                </div>
                <div class="form-group">
                    <label>Warna Tema <span style="color:#94a3b8;font-weight:400">(Jika tidak ada gambar sampul)</span></label>
                    <input type="color" name="theme_color" value="#4F46E5" style="width:100%;height:40px;padding:0;cursor:pointer;">
                </div>
                <div class="form-group">
                    <label>Nama Kelas <span class="req">*</span></label>
                    <input type="text" name="nama_kelas" required placeholder="cth: Matematika Dasar A">
                </div>
                <div class="form-group">
                    <label>Mata Pelajaran <span class="req">*</span></label>
                    <input type="text" name="mata_pelajaran" required placeholder="cth: Matematika">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Jadwal Hari Rutin <span class="req">*</span></label>
                        <select name="hari" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ruang / Lokasi <span class="req">*</span></label>
                        <input type="text" name="ruang" required placeholder="cth: Lab Komputer 1">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Jam Mulai <span class="req">*</span></label>
                        <input type="time" name="jam_mulai" required value="08:00">
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai <span class="req">*</span></label>
                        <input type="time" name="jam_selesai" required value="10:00">
                    </div>
                </div>
                <div class="form-group">
                    <label>Kapasitas Siswa <span class="req">*</span></label>
                    <input type="number" name="kapasitas" required min="1" max="100" value="30">
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
        <form method="POST" id="formEdit" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal__body">
                <div class="form-group">
                    <label>Gambar Sampul <span style="color:#94a3b8;font-weight:400">(Ukuran disarankan 600x300 px / Rasio 2:1)</span></label>
                    <input type="file" name="cover_image" accept="image/png, image/jpeg, image/jpg">
                </div>
                <div class="form-group">
                    <label>Warna Tema <span style="color:#94a3b8;font-weight:400">(Jika tidak ada gambar sampul)</span></label>
                    <input type="color" name="theme_color" id="e_theme" value="#4F46E5" style="width:100%;height:40px;padding:0;cursor:pointer;">
                </div>
                <div class="form-group">
                    <label>Nama Kelas <span class="req">*</span></label>
                    <input type="text" name="nama_kelas" id="e_nama" required>
                </div>
                <div class="form-group">
                    <label>Mata Pelajaran <span class="req">*</span></label>
                    <input type="text" name="mata_pelajaran" id="e_mapel" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Jadwal Hari Rutin <span class="req">*</span></label>
                        <select name="hari" id="e_hari" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ruang / Lokasi <span class="req">*</span></label>
                        <input type="text" name="ruang" id="e_ruang" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Jam Mulai <span class="req">*</span></label>
                        <input type="time" name="jam_mulai" id="e_jam_mulai" required>
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai <span class="req">*</span></label>
                        <input type="time" name="jam_selesai" id="e_jam_selesai" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status <span class="req">*</span></label>
                    <select name="status" id="e_status">
                        <option value="aktif">Aktif</option>
                        <option value="draf">Draf</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kapasitas Siswa <span class="req">*</span></label>
                    <input type="number" name="kapasitas" id="e_kap" required min="1" max="100">
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
}
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
    justify-content:flex-start;
    padding:1.5rem 2rem;gap:.65rem;
    overflow-x:hidden;
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

/* ── GRID KELAS ── */
.kelas-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;}
.kelas-card{background:#fff;border:1px solid #E2E8F0;border-radius:12px;display:flex;flex-direction:column;overflow:hidden;transition:box-shadow .2s,transform .2s;}
.kelas-card:hover{box-shadow:0 6px 20px rgba(15,23,42,.08);transform:translateY(-3px);}
.kelas-card__cover{height:140px;background-size:cover;background-position:center;position:relative;}
.kelas-card__body{padding:1.25rem;display:flex;flex-direction:column;flex:1;}
.kelas-badge{font-size:.72rem;font-weight:700;background:#EFF6FF;color:#2563EB;padding:.22rem .65rem;border-radius:99px;}
.kelas-card__nama{font-size:1.05rem;font-weight:700;color:#0F172A;line-height:1.4;margin-bottom:.3rem;}
.kelas-card__kode{font-size:.8rem;color:#64748B;}
.badge-status{font-size:.7rem;font-weight:700;padding:.25rem .65rem;border-radius:99px;}
.badge-aktif{background:#DCFCE7;color:#15803D;}
.badge-tutup{background:#FEE2E2;color:#B91C1C;}
.kelas-card__actions{display:flex;flex-direction:column;gap:.5rem;margin-top:.5rem;}
.btn-lihat-materi{padding:.5rem .75rem;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;transition:background .18s;display:flex;align-items:center;justify-content:center;gap:.4rem;}
.btn-lihat-materi:hover{background:#7C3AED!important;}
.btn-masuk-kelas{padding:.5rem .75rem;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;transition:background .18s;display:flex;align-items:center;justify-content:center;gap:.4rem;background:#8B5CF6;color:#fff;flex:1;text-align:center;}
.btn-masuk-kelas:hover{background:#7C3AED;}
.btn-edit-card,.btn-hapus-card{flex:1;display:flex;align-items:center;justify-content:center;padding:.4rem;border-radius:8px;border:1px solid #E2E8F0;background:#fff;color:#64748B;cursor:pointer;transition:all .18s;text-decoration:none;}
.btn-edit-card:hover{background:#EFF6FF;color:#2563EB;border-color:#BFDBFE;}
.btn-hapus-card:hover{background:#FEF2F2;color:#DC2626;border-color:#FECACA;}
.empty-state{text-align:center;padding:3rem 1rem;background:#fff;border:1px dashed #E2E8F0;border-radius:16px;margin-top:1rem;}
.empty-state__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-top:.5rem;}
.empty-state__sub{font-size:.8rem;color:#64748B;}

/* ── MODAL ── */
.modal-overlay{
    display:none;position:fixed;inset:0;
    background:rgba(15,23,42,.45);z-index:100;
    align-items:center;justify-content:center;padding:1.25rem;
    backdrop-filter:blur(5px);
}
.modal-overlay.open{display:flex;}
.modal{
    background:#fff;border-radius:16px;
    width:100%;max-width:520px;
    max-height:90vh;display:flex;flex-direction:column;
    box-shadow:0 8px 32px rgba(0,0,0,.16);
    animation:slideUp .2s ease;
    overflow:hidden;
}
.modal form{display:flex;flex-direction:column;overflow:hidden;}
@keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.modal__header{
    display:flex;align-items:center;
    justify-content:space-between;
    padding:1.25rem 1.5rem;
    border-bottom:1px solid #E2E8F0;
}
.modal__title{font-size:1rem;font-weight:700;color:#0F172A;}
.modal__close{
    background:none;border:none;font-size:1rem;
    cursor:pointer;color:#94A3B8;padding:.25rem;
    border-radius:6px;transition:color .15s;
}
.modal__close:hover{color:#0F172A;}
.modal__body{
    padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:.9rem;
    overflow-y:auto;
}
.modal__footer{
    padding:1.25rem 1.5rem;
    border-top:1px solid #E2E8F0;
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
function openEdit(id, nama, mapel, desk, status, theme, hari, jm, js, ruang, kap) {
    document.getElementById('formEdit').action = '/dashboard/kelas/' + id;
    document.getElementById('e_nama').value   = nama;
    document.getElementById('e_mapel').value  = mapel;
    document.getElementById('e_desk').value   = desk;
    document.getElementById('e_status').value = status;
    document.getElementById('e_kap').value    = kap;
    if (theme) document.getElementById('e_theme').value = theme;
    
    document.getElementById('e_hari').value = hari;
    document.getElementById('e_jam_mulai').value = jm;
    document.getElementById('e_jam_selesai').value = js;
    document.getElementById('e_ruang').value = ruang;
    
    openModal('modalEdit');
}

// ── SEARCH ──
document.getElementById('searchKelas').addEventListener('input', function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll('.kelas-card').forEach(row => {
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