@extends('layouts.app')
@section('content')
<div class="dash-page">
@include('dashboard._sidebar_siswa', ['active' => 'kelas'])
<main class="dash-main">
    <div class="topbar">
        <button class="hamburger" id="hamburgerBtn"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg></button>
        <div><h1 class="topbar__title">Detail Tugas</h1></div>
        <div class="topbar__right"><a href="{{ route('siswa.kelas.show', $tugas->kelas_id) }}" class="btn-back"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Detail Kelas</a></div>
    </div>

    @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif

    {{-- Info Tugas --}}
    <div class="detail-card">
        <div class="detail-card__header">
            <span class="badge-status badge-status--{{ $tugas->status }}">{{ ucfirst($tugas->status) }}</span>
            <span class="badge-status badge-status--terbit" style="background:#EFF6FF;color:#2563EB;">Tipe: {{ ucfirst($tugas->tipe) }}</span>
            @if($tugas->deadline)
                @if($tugas->deadline->isPast())
                <span class="badge-deadline badge-deadline--lewat">⚠ Deadline lewat {{ $tugas->deadline->diffForHumans(['parts' => 3]) }}</span>
                @else
                <span class="badge-deadline">Deadline {{ $tugas->deadline->format('d M Y H:i') }}</span>
                @endif
            @endif
        </div>
        <h2 class="detail-card__judul">{{ $tugas->judul }}</h2>
        <div class="detail-card__desc">{!! nl2br(e($tugas->deskripsi)) !!}</div>

        @if($tugas->tipe === 'link' && $tugas->link_url)
            <a href="{{ $tugas->link_url }}" target="_blank" rel="noopener" class="btn-link-ext" style="margin-top:1.25rem;">🔗 Buka Link Tugas</a>
        @elseif($tugas->tipe === 'file' && $tugas->lampiran_path)
            <a href="{{ Storage::url($tugas->lampiran_path) }}" target="_blank" class="btn-link-ext" style="margin-top:1.25rem;">📎 Unduh / Lihat Lampiran</a>
        @endif
    </div>

    @php
        $isClosed = $tugas->status === 'ditutup' || ($tugas->deadline && $tugas->deadline->isPast());
    @endphp

    {{-- Jawaban siswa (jika sudah ada) --}}
    @if($jawaban)
    <div class="jawaban-card">
        <div class="jawaban-card__header">
            <p class="jawaban-card__title">Jawaban Kamu</p>
            <span class="badge-jawaban badge-jawaban--{{ $jawaban->status }}">{{ ucfirst($jawaban->status) }}</span>
        </div>
        <div class="jawaban-card__teks">
            @if($jawaban->tipe === 'teks')
                {!! nl2br(e($jawaban->jawaban_text)) !!}
            @elseif($jawaban->tipe === 'link')
                <a href="{{ $jawaban->link_url }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">🔗 {{ $jawaban->link_url }}</a>
                @if($jawaban->jawaban_text)<div style="margin-top:.5rem">{!! nl2br(e($jawaban->jawaban_text)) !!}</div>@endif
            @elseif($jawaban->tipe === 'file')
                <a href="{{ Storage::url($jawaban->file_path) }}" target="_blank" style="color:#2563EB; font-weight:600; text-decoration:none;">📎 Unduh / Lihat File Jawaban</a>
                @if($jawaban->jawaban_text)<div style="margin-top:.5rem">{!! nl2br(e($jawaban->jawaban_text)) !!}</div>@endif
            @endif
        </div>
        @if($jawaban->nilai !== null)
        <div class="nilai-box">
            <span class="nilai-box__val">{{ $jawaban->nilai }}/100</span>
            @if($jawaban->feedback)<p class="nilai-box__feedback">Feedback: {{ $jawaban->feedback }}</p>@endif
        </div>
        @endif

        {{-- Update jawaban jika belum ditutup --}}

        @if($isClosed)
        <div class="alert-error" style="margin-top:1rem;">Tugas ini sudah melewati batas waktu atau ditutup. Anda tidak dapat mengirimkan atau mengubah jawaban.</div>
        @else
        <details style="margin-top:.75rem">
            <summary class="btn-update-toggle">✏ Perbarui Jawaban</summary>
            <form method="POST" action="{{ route('siswa.tugas.update-submit', $jawaban) }}" style="margin-top:.75rem" enctype="multipart/form-data">
                @csrf @method('PUT')
                
                <div class="form-group">
                    <label>Tipe Pengumpulan *</label>
                    <select name="tipe" id="tipeUpdateSelect" class="form-input" required onchange="toggleUpdateFields()">
                        <option value="teks" {{ $jawaban->tipe === 'teks' ? 'selected' : '' }}>Teks Saja</option>
                        <option value="link" {{ $jawaban->tipe === 'link' ? 'selected' : '' }}>Link / Tautan</option>
                        <option value="file" {{ $jawaban->tipe === 'file' ? 'selected' : '' }}>Upload File</option>
                    </select>
                </div>

                <div class="form-group" id="updateLinkField" style="display:none;">
                    <label>URL Link *</label>
                    <input type="url" name="link_url" value="{{ old('link_url', $jawaban->link_url) }}" placeholder="https://..." class="form-input">
                </div>

                <div class="form-group" id="updateFileField" style="display:none;">
                    <label>Upload File *</label>
                    <input type="file" name="file_upload" class="form-input" style="padding: .4rem .85rem;">
                </div>

                <div class="form-group">
                    <label>Catatan / Pembahasan (opsional)</label>
                    <textarea name="jawaban_text" rows="4" class="form-input" maxlength="10000">{{ old('jawaban_text', $jawaban->jawaban_text) }}</textarea>
                </div>

                <button type="submit" class="btn-submit" style="margin-top:.5rem">Perbarui Jawaban</button>
            </form>
        </details>
        @endif
    </div>
    @elseif(!$isClosed)
    {{-- Form submit jawaban --}}
    <div class="submit-card">
        <p class="submit-card__title">Kumpulkan Jawaban</p>
        @if($errors->any())<div class="alert-error" style="margin-bottom:.75rem">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('siswa.tugas.submit', $tugas) }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Tipe Pengumpulan *</label>
                <select name="tipe" id="tipeSubmitSelect" class="form-input" required onchange="toggleSubmitFields()">
                    <option value="teks" {{ old('tipe') === 'teks' ? 'selected' : '' }}>Teks Saja</option>
                    <option value="link" {{ old('tipe') === 'link' ? 'selected' : '' }}>Link / Tautan</option>
                    <option value="file" {{ old('tipe') === 'file' ? 'selected' : '' }}>Upload File</option>
                </select>
            </div>

            <div class="form-group" id="submitLinkField" style="display:none;">
                <label>URL Link *</label>
                <input type="url" name="link_url" value="{{ old('link_url') }}" placeholder="https://..." class="form-input">
            </div>

            <div class="form-group" id="submitFileField" style="display:none;">
                <label>Upload File *</label>
                <input type="file" name="file_upload" class="form-input" style="padding: .4rem .85rem;">
                <small style="color:#64748B; font-size:.75rem; margin-top:.2rem;">Maks 10MB.</small>
            </div>

            <div class="form-group">
                <label>Catatan / Pembahasan (opsional)</label>
                <textarea name="jawaban_text" rows="5" maxlength="10000" class="form-input" placeholder="Tambahkan catatan jika perlu...">{{ old('jawaban_text') }}</textarea>
            </div>

            <button type="submit" class="btn-submit">Kumpulkan Jawaban</button>
        </form>
    </div>
    @else
    <div class="empty-state" style="padding:1.5rem"><p style="color:#DC2626;font-weight:700">Tugas sudah melewati batas waktu atau ditutup</p></div>
    @endif
</main>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
</div>
@include('dashboard._dash_styles')
<style>
.btn-back{display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .9rem;background:#fff;color:#64748B;border:1px solid #E2E8F0;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:500;transition:all .18s;}
.btn-back:hover{color:#2563EB;border-color:#2563EB;box-shadow:0 2px 8px rgba(37,99,235,.1);}
.detail-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.detail-card__header{display:flex;align-items:center;gap:.6rem;margin-bottom:.75rem;flex-wrap:wrap;}
.detail-card__judul{font-size:1.2rem;font-weight:800;color:#0F172A;margin-bottom:.75rem;}
.detail-card__desc{font-size:.88rem;color:#374151;line-height:1.7;white-space:pre-wrap;}
.badge-status{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;}
.badge-status--terbit{background:#DCFCE7;color:#15803D;}
.badge-status--ditutup{background:#FEE2E2;color:#DC2626;}
.badge-deadline{font-size:.72rem;font-weight:600;background:#FFFBEB;color:#D97706;padding:.2rem .65rem;border-radius:99px;}
.badge-deadline--lewat{background:#FEE2E2;color:#DC2626;}
.jawaban-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.25rem;}
.jawaban-card__header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;}
.jawaban-card__title{font-size:.92rem;font-weight:700;color:#0F172A;}
.jawaban-card__teks{font-size:.85rem;color:#374151;line-height:1.7;background:#F8FAFC;border-radius:10px;padding:.75rem 1rem;}
.badge-jawaban{font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:99px;}
.badge-jawaban--terkirim{background:#DBEAFE;color:#1D4ED8;}
.badge-jawaban--dinilai{background:#DCFCE7;color:#15803D;}
.badge-jawaban--terlambat{background:#FEE2E2;color:#DC2626;}
.nilai-box{background:#DCFCE7;border-radius:10px;padding:.75rem 1rem;margin-top:.75rem;display:flex;align-items:center;gap:1rem;}
.nilai-box__val{font-size:1.4rem;font-weight:800;color:#15803D;}
.nilai-box__feedback{font-size:.82rem;color:#374151;margin-top:.2rem;}
.submit-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:1.5rem;}
.submit-card__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:1rem;}
.form-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:.85rem;}
.form-group label{font-size:.8rem;font-weight:600;color:#374151;}.req{color:#EF4444;}
.form-input{padding:.55rem .85rem;border:1px solid #E2E8F0;border-radius:9px;font-size:.85rem;font-family:inherit;outline:none;transition:border-color .18s;}
.form-input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.btn-submit{padding:.65rem 1.4rem;background:#2563EB;color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:600;cursor:pointer;font-family:inherit;}
.btn-update-toggle{font-size:.82rem;font-weight:600;color:#2563EB;cursor:pointer;list-style:none;}
.btn-link-ext{display:inline-block;padding:.45rem 1rem;background:#EFF6FF;color:#2563EB;font-weight:600;font-size:.85rem;border-radius:8px;text-decoration:none;transition:all .2s ease;}
.btn-link-ext:hover{background:#DBEAFE;color:#1D4ED8;transform:translateY(-2px);box-shadow:0 4px 12px rgba(37,99,235,.15);}
.btn-link-ext:active{transform:translateY(0);box-shadow:0 2px 6px rgba(37,99,235,.1);}
</style>
<script>
const s=document.querySelector('.sidebar'),o=document.getElementById('sidebarOverlay'),h=document.getElementById('hamburgerBtn');
if(h)h.addEventListener('click',()=>{s.classList.add('sidebar--open');o.classList.add('overlay--show');});
if(o)o.addEventListener('click',()=>{s.classList.remove('sidebar--open');o.classList.remove('overlay--show');});
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);

function toggleSubmitFields() {
    const tipe = document.getElementById('tipeSubmitSelect')?.value;
    const linkField = document.getElementById('submitLinkField');
    const fileField = document.getElementById('submitFileField');
    if (tipe === 'link') { linkField.style.display = 'flex'; fileField.style.display = 'none'; }
    else if (tipe === 'file') { linkField.style.display = 'none'; fileField.style.display = 'flex'; }
    else if (tipe) { linkField.style.display = 'none'; fileField.style.display = 'none'; }
}

function toggleUpdateFields() {
    const tipe = document.getElementById('tipeUpdateSelect')?.value;
    const linkField = document.getElementById('updateLinkField');
    const fileField = document.getElementById('updateFileField');
    if (tipe === 'link') { linkField.style.display = 'flex'; fileField.style.display = 'none'; }
    else if (tipe === 'file') { linkField.style.display = 'none'; fileField.style.display = 'flex'; }
    else if (tipe) { linkField.style.display = 'none'; fileField.style.display = 'none'; }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleSubmitFields();
    toggleUpdateFields();
});
</script>
@endsection
