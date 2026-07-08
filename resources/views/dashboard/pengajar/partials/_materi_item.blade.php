<a href="{{ route('materi.edit', $materi->id ?? 0) }}" class="item-row">
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
    <div class="item-actions">
        <!-- Prevent the link click from triggering when clicking edit -->
        <object x-show="editMode"><a href="{{ route('materi.edit', $materi->id) }}" class="btn-sm btn-edit">Edit</a></object>
        <object x-show="editMode">
            <form method="POST" action="{{ route('materi.destroy', $materi->id) }}" onsubmit="return confirm('Hapus materi?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn-sm btn-hapus">Hapus</button>
            </form>
        </object>
    </div>
</a>
