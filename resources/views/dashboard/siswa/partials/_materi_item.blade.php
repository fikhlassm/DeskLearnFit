<div class="item-row" onclick="openMateriModal({{ $materi->id ?? 0 }})" style="cursor:pointer;">
    <div class="item-icon materi">
        @if($materi->tipe === 'file')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
        @elseif($materi->tipe === 'link')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
        @else
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
        @endif
    </div>
    <div class="item-info">
        <h3 class="item-title">{{ $materi->judul }}</h3>
        <div class="item-meta">Materi &middot; {{ ucfirst($materi->tipe) }}</div>
    </div>
</div>

<div id="materiModal-{{ $materi->id ?? 0 }}" class="materi-modal-overlay" style="display:none;" onclick="closeMateriModal({{ $materi->id ?? 0 }})">
    <div class="materi-modal-content" onclick="event.stopPropagation();">
        <div class="materi-modal-header" style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
            <div>
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                    <span class="badge-tipe badge-tipe--{{ $materi->tipe }}">{{ strtoupper($materi->tipe) }}</span>
                    <span class="materi-modal-date">{{ $materi->published_at ? \Carbon\Carbon::parse($materi->published_at)->format('d M Y') : '' }}</span>
                </div>
                <h2 class="materi-modal-title">{{ $materi->judul }}</h2>
            </div>
            <button class="materi-modal-close" onclick="closeMateriModal({{ $materi->id ?? 0 }})">&times;</button>
        </div>
        <div class="materi-modal-body">
            @if($materi->deskripsi)
                <p class="materi-modal-desc">{{ $materi->deskripsi }}</p>
            @endif

            @if($materi->tipe === 'link' && $materi->link_url)
                <a href="{{ $materi->link_url }}" target="_blank" rel="noopener" class="btn-link-ext">🔗 Buka Link Materi</a>
            @elseif($materi->tipe === 'file' && $materi->file_path)
                <a href="{{ route('materi.download', $materi) }}" class="btn-link-ext">📎 Unduh / Lihat File</a>
            @endif

            @if($materi->konten)
                <div class="materi-modal-konten">{!! nl2br(e($materi->konten)) !!}</div>
            @endif
        </div>
    </div>
</div>
