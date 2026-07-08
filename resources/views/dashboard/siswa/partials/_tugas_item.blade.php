<a href="{{ route('siswa.tugas.show', $tugas->id ?? 0) }}" class="item-row">
    <div class="item-icon tugas">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
    </div>
    <div class="item-info">
        <h3 class="item-title">{{ $tugas->judul }}</h3>
        <div class="item-meta">
            Tugas
            @if($tugas->deadline)
                &middot; Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y H:i') }}
            @endif
        </div>
    </div>
</a>
