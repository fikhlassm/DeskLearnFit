@extends('layouts.app')

@section('content')
<div class="dash-page" x-data="{ editMode: localStorage.getItem('editMode_{{ $kelas->id }}') === 'true' }" x-init="$watch('editMode', val => localStorage.setItem('editMode_{{ $kelas->id }}', val))">
    @include('dashboard._sidebar_pengajar', ['active' => 'kelas'])

    <main class="dash-main">
        {{-- TOP BAR --}}
        <div class="topbar">
            <button class="hamburger" id="hamburgerBtn" aria-label="Buka Menu">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
            <div style="display:flex;align-items:center;gap:.75rem">
                <a href="{{ route('dashboard.kelas') }}" class="btn-back" style="padding:.4rem .6rem;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></a>
                <div>
                    <div style="display:flex;align-items:center;gap:.5rem">
                        <h1 class="topbar__title">{{ $kelas->nama_kelas }}</h1>
                        <span class="badge-kode">{{ $kelas->kode_kelas }}</span>
                    </div>
                    <p class="topbar__sub">{{ $kelas->mata_pelajaran }} &middot; Kapasitas: {{ $kelas->kapasitas }} siswa</p>
                </div>
            </div>
            
            {{-- Edit Mode Toggle --}}
            <div class="topbar__right" style="display:flex;align-items:center;gap:1rem;">
                <label class="toggle-switch">
                    <input type="checkbox" x-model="editMode">
                    <span class="slider"></span>
                </label>
                <span style="font-size:0.9rem;font-weight:600;color:#374151" x-text="editMode ? 'Mode Edit Aktif' : 'Mode Edit Mati'"></span>
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
                        <div style="display:flex;align-items:center;gap:1rem;">
                            <div x-show="editMode" class="drag-handle" style="cursor:grab;color:#94a3b8"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></div>
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
                        <div x-show="editMode" class="topik-actions">
                            <button class="btn-sm btn-edit" onclick="openEditTopik({{ $topik->id }}, '{{ addslashes($topik->judul) }}', '{{ addslashes($topik->deskripsi) }}', '{{ $topik->tanggal }}', '{{ $topik->jam_mulai ? substr($topik->jam_mulai,0,5) : '' }}', '{{ $topik->jam_selesai ? substr($topik->jam_selesai,0,5) : '' }}')">Edit Topik</button>
                            <form action="{{ route('topik.destroy', $topik->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus topik beserta isinya?')">
                                @csrf @method('DELETE')
                                <button class="btn-sm btn-hapus">Hapus</button>
                            </form>
                        </div>
                    </div>
                    <div class="topik-body">
                        @if($topik->deskripsi)
                        <div class="topik-desc">{{ $topik->deskripsi }}</div>
                        @endif

                        <div class="topik-items">
                            @foreach($topik->materi as $materi)
                                @include('dashboard.pengajar.partials._materi_item', ['materi' => $materi])
                            @endforeach
                            @foreach($topik->tugas as $tugas)
                                @include('dashboard.pengajar.partials._tugas_item', ['tugas' => $tugas])
                            @endforeach
                        </div>

                        {{-- Action buttons to add content inside Topik --}}
                        <div x-show="editMode" class="topik-add-actions">
                            <a href="{{ route('materi.index', $kelas->id) }}?topik={{ $topik->id }}" class="btn-add-item">+ Tambah Materi</a>
                            <a href="{{ route('tugas.index', $kelas->id) }}?topik={{ $topik->id }}" class="btn-add-item">+ Tambah Tugas</a>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>

            <div x-show="editMode" style="text-align:center; padding: 2rem 0;">
                <button class="btn-tambah-topik" onclick="openModal('modalTambahTopik')">+ Tambah Topik / Minggu Baru</button>
            </div>

        </div>
    </main>
</div>

{{-- MODAL TAMBAH TOPIK --}}
<div class="modal-overlay" id="modalTambahTopik">
    <div class="modal">
        <div class="modal__header">
            <h2 class="modal__title">Tambah Topik Baru</h2>
            <button class="modal__close" onclick="closeModal('modalTambahTopik')">✕</button>
        </div>
        <form method="POST" action="{{ route('topik.store', $kelas->id) }}">
            @csrf
            <div class="modal__body">
                <div class="form-group">
                    <label>Judul Topik <span class="req">*</span></label>
                    <input type="text" name="judul" class="form-input" required placeholder="cth: Pertemuan 1 - Kontrak Kuliah">
                </div>
                <div class="form-group">
                    <label>Deskripsi / Info Kelas <span style="color:#94a3b8">(opsional)</span></label>
                    <textarea name="deskripsi" class="form-input" rows="3" placeholder="Informasi singkat untuk topik ini..."></textarea>
                </div>
                <div class="form-row" style="display:flex;gap:1rem;">
                    <div class="form-group" style="flex:1">
                        <label>Tanggal <span style="color:#94a3b8">(opsional)</span></label>
                        <input type="date" name="tanggal" class="form-input">
                    </div>
                    <div class="form-group" style="flex:1">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-input">
                    </div>
                    <div class="form-group" style="flex:1">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-input">
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn-batal" onclick="closeModal('modalTambahTopik')">Batal</button>
                <button type="submit" class="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT TOPIK --}}
<div class="modal-overlay" id="modalEditTopik">
    <div class="modal">
        <div class="modal__header">
            <h2 class="modal__title">Edit Topik</h2>
            <button class="modal__close" onclick="closeModal('modalEditTopik')">✕</button>
        </div>
        <form method="POST" id="formEditTopik" action="">
            @csrf @method('PUT')
            <div class="modal__body">
                <div class="form-group">
                    <label>Judul Topik <span class="req">*</span></label>
                    <input type="text" name="judul" id="e_t_judul" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi / Info Kelas <span style="color:#94a3b8">(opsional)</span></label>
                    <textarea name="deskripsi" id="e_t_desk" class="form-input" rows="3"></textarea>
                </div>
                <div class="form-row" style="display:flex;gap:1rem;">
                    <div class="form-group" style="flex:1">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="e_t_tgl" class="form-input">
                    </div>
                    <div class="form-group" style="flex:1">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="e_t_m" class="form-input">
                    </div>
                    <div class="form-group" style="flex:1">
                        <label>Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="e_t_s" class="form-input">
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn-batal" onclick="closeModal('modalEditTopik')">Batal</button>
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@include('dashboard._dash_styles')
<style>
.btn-back{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:8px;text-decoration:none;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;background:#EFF6FF;}
.badge-kode{background:#F1F5F9;color:#475569;font-size:.75rem;font-weight:700;padding:.25rem .65rem;border-radius:6px;border:1px solid #E2E8F0;}

/* Toggle Switch */
.toggle-switch {position:relative;display:inline-block;width:44px;height:24px;}
.toggle-switch input {opacity:0;width:0;height:0;}
.slider {position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#cbd5e1;transition:.3s;border-radius:34px;}
.slider:before {position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background-color:white;transition:.3s;border-radius:50%;}
input:checked + .slider {background-color:#2563EB;}
input:checked + .slider:before {transform:translateX(20px);}

.stream-container {
    max-width: 800px;
    margin: 1.5rem auto;
    padding: 0 1.5rem;
}

.topik-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    transition: box-shadow 0.2s;
}
.topik-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.topik-header {
    background: #F8FAFC;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.topik-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0F172A;
}
.topik-meta {
    font-size: 0.8rem;
    color: #64748B;
    margin-top: 0.2rem;
    display:flex; align-items:center; gap: 0.4rem;
}
.topik-body {
    padding: 1.5rem;
}
.topik-desc {
    font-size: 0.9rem;
    color: #334155;
    background: #F8FAFC;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 3px solid #3B82F6;
    line-height: 1.5;
}
.topik-items {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.item-row {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    transition: background 0.15s;
    text-decoration: none;
    color: inherit;
}
.item-row:hover {
    background: #F8FAFC;
    border-color: #cbd5e1;
}
.item-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}
.item-icon.materi { background: #EEF2FF; color: #4F46E5; }
.item-icon.tugas { background: #FEF2F2; color: #DC2626; }

.item-info { flex: 1; }
.item-title { font-size: 0.9rem; font-weight: 600; color: #0F172A; margin-bottom: 0.15rem; }
.item-meta { font-size: 0.75rem; color: #94A3B8; }

.item-actions {
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.2s;
}
.action-btn-group { display: flex; gap: 0.5rem; }
.item-row:hover .item-actions { opacity: 1; }

.topik-add-actions {
    margin-top: 1.5rem;
    display: flex;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px dashed #E2E8F0;
}
.btn-add-item {
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    background: #F1F5F9;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-add-item:hover {
    background: #E2E8F0;
    color: #0F172A;
}
.btn-tambah-topik {
    background: #2563EB;
    color: #fff;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37,99,235,0.2);
    transition: all 0.2s;
}
.btn-tambah-topik:hover {
    background: #1D4ED8;
    transform: translateY(-2px);
}

/* Modals */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:100;align-items:center;justify-content:center;padding:1.25rem;backdrop-filter:blur(5px);}
.modal-overlay.open{display:flex;}
.modal{background:#fff;border-radius:16px;width:100%;max-width:520px;box-shadow:0 8px 32px rgba(0,0,0,.16);animation:slideUp .2s ease;}
@keyframes slideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.modal__header{display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem 0;}
.modal__title{font-size:1rem;font-weight:700;color:#0F172A;}
.modal__close{background:none;border:none;font-size:1rem;cursor:pointer;color:#94A3B8;padding:.25rem;border-radius:6px;transition:color .15s;}
.modal__close:hover{color:#0F172A;}
.modal__body{padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:.9rem;}
.modal__footer{padding:0 1.5rem 1.25rem;display:flex;justify-content:flex-end;gap:.6rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}
.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;}
.form-input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.btn-batal{padding:.55rem 1.1rem;background:#F1F5F9;color:#475569;border:none;border-radius:8px;font-weight:600;font-size:.85rem;cursor:pointer;transition:background .2s;}
.btn-batal:hover{background:#E2E8F0;}
.btn-simpan{padding:.55rem 1.1rem;background:#2563EB;color:#fff;border:none;border-radius:8px;font-weight:600;font-size:.85rem;cursor:pointer;transition:background .2s;}
.btn-simpan:hover{background:#1D4ED8;}

.btn-sm{padding:.3rem .6rem;font-size:.75rem;border-radius:4px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;font-weight:600;transition:all .2s; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; box-sizing:border-box; height: 28px;}
.btn-edit{color:#4F46E5;}
.btn-edit:hover{background:#EEF2FF;border-color:#C7D2FE;}
.btn-hapus{color:#DC2626;}
.btn-hapus:hover{background:#FEF2F2;border-color:#FECACA;}
.btn-lihat-jawaban{color:#059669;}
.btn-lihat-jawaban:hover{background:#D1FAE5;border-color:#34D399;}
</style>

<!-- Add SortableJS from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<!-- Add Alpine.js for x-data and x-show functionality -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
function openModal(id){ document.getElementById(id).classList.add('open'); }
function closeModal(id){ document.getElementById(id).classList.remove('open'); }

function openEditTopik(id, judul, deskripsi, tgl, mulai, selesai) {
    document.getElementById('formEditTopik').action = '/dashboard/topik/' + id;
    document.getElementById('e_t_judul').value = judul;
    document.getElementById('e_t_desk').value = deskripsi;
    document.getElementById('e_t_tgl').value = tgl;
    document.getElementById('e_t_m').value = mulai;
    document.getElementById('e_t_s').value = selesai;
    openModal('modalEditTopik');
}

// Flash hide
setTimeout(() => {
    const f = document.getElementById('flashMsg');
    if(f) f.style.transition='opacity .5s', f.style.opacity='0', setTimeout(()=>f.remove(),500);
}, 3000);

// Initialize SortableJS
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('topikList');
    if (el) {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function (evt) {
                var itemEl = evt.item;
                
                // Get new order
                var items = el.querySelectorAll('.topik-card');
                var order = [];
                items.forEach(function(item) {
                    order.push(item.getAttribute('data-id'));
                });

                // Send to backend
                fetch('{{ route('topik.reorder', $kelas->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                }).then(res => res.json())
                  .then(data => {
                      if(data.status !== 'success') {
                          alert('Gagal menyimpan urutan');
                      }
                  });
            },
        });
    }
});
</script>

@endsection