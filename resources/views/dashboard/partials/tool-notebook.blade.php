@php
    /** @var \App\Models\SesiBelajar $sesi */
    $entries = $sesi->entriNotebook()->orderByDesc('created_at')->get();
    $tipe    = $sesi->metode;
    $isFeynman = $tipe === 'feynman';
    $label   = $isFeynman ? 'Feynman' : 'Blurting';
    $icon    = $isFeynman ? '🏫' : '✍️';
    $guide   = $isFeynman
        ? 'Jelaskan konsep seolah-olah mengajar orang lain. Gunakan kata: karena, misalnya, jadi, artinya…'
        : 'Tulis semua yang kamu ingat tentang topik tanpa melihat catatan.';
@endphp
<div class="tool-notebook" data-sesi-id="{{ $sesi->id }}" data-tipe="{{ $tipe }}">
    <p class="tool-notebook__title">{{ $icon }} Notebook {{ $label }}</p>
    <p class="tool-notebook__sub">{{ $guide }}</p>

    <form method="POST" action="{{ route('notebook.store', $sesi) }}" class="tool-notebook__form">
        @csrf
        <textarea name="konten" rows="6" required minlength="5" maxlength="5000"
            placeholder="Mulai menulis di sini…">{{ old('konten') }}</textarea>
        <button type="submit" class="tool-notebook__submit">📤 Kirim & Analisis</button>
    </form>

    @if($entries->isEmpty())
        <div class="tool-notebook__empty">
            <p>{{ $icon }}</p>
            <p>Belum ada entri. Tulis jawaban pertamamu di atas.</p>
        </div>
    @else
        <p class="tool-notebook__section-label">Riwayat Entri ({{ $entries->count() }})</p>
        <div class="tool-notebook__list">
            @foreach($entries as $entri)
            <div class="tool-notebook__entry">
                <div class="tool-notebook__entry-head">
                    <span class="tool-notebook__time">{{ $entri->created_at->format('d M H:i') }}</span>
                    <span class="tool-notebook__score" data-skor="{{ $entri->skor_keyakinan }}">{{ $entri->skor_keyakinan }}/100</span>
                </div>
                <p class="tool-notebook__konten">{{ $entri->konten }}</p>
                <div class="tool-notebook__analisis">
                    <p class="tool-notebook__a-title">📊 Analisis Sistem</p>
                    <p class="tool-notebook__a-text">{{ $entri->analisis_sistem }}</p>
                    @if($entri->kata_kunci_cocok && count($entri->kata_kunci_cocok) > 0)
                    <div class="tool-notebook__keywords">
                        @foreach($entri->kata_kunci_cocok as $kw)
                            <span class="tool-notebook__kw">{{ $kw }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('notebook.destroy', $entri) }}" onsubmit="return confirm('Hapus entri ini?')" class="tool-notebook__entry-actions">
                    @csrf @method('DELETE')
                    <button type="submit">🗑 Hapus</button>
                </form>
            </div>
            @endforeach
        </div>
    @endif
</div>
