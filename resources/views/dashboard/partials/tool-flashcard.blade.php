@php
    /** @var \App\Models\SesiBelajar $sesi */
    $cards = $sesi->flashcards()->orderBy('urutan')->get();
    $totalReviews = $sesi->flashcardReviews()->count();
    $benarCount   = $sesi->flashcardReviews()->where('benar', true)->count();
    $akurasi      = $totalReviews > 0 ? (int) round(($benarCount / $totalReviews) * 100) : 0;
@endphp
<div class="tool-flashcard" data-sesi-id="{{ $sesi->id }}">
    <p class="tool-flashcard__title">🃏 Deck Kartu Flash</p>
    <p class="tool-flashcard__sub">Total: <strong>{{ $cards->count() }}</strong> kartu
        @if($totalReviews > 0)
            · {{ $totalReviews }} review · {{ $akurasi }}% akurasi
        @endif
    </p>

    @if($cards->count() > 0)
    <a href="{{ route('flashcard.review', $sesi) }}" style="display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.7rem;background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#fff;text-decoration:none;border-radius:10px;font-size:.88rem;font-weight:700;transition:transform .15s,box-shadow .15s;box-shadow:0 4px 12px rgba(124,58,237,.25);">
        ▶ Mulai Review ({{ $cards->count() }} kartu)
    </a>
    @endif

    <form method="POST" action="{{ route('flashcard.store', $sesi) }}" class="tool-flashcard__form">
        @csrf
        <div class="tool-flashcard__field">
            <label>Pertanyaan</label>
            <textarea name="pertanyaan" rows="2" required maxlength="1000" placeholder="cth: Apa rumus turunan pertama dari x²?">{{ old('pertanyaan') }}</textarea>
        </div>
        <div class="tool-flashcard__field">
            <label>Jawaban</label>
            <textarea name="jawaban" rows="2" required maxlength="2000" placeholder="cth: 2x — turunan pangkat dikurangi pangkatnya">{{ old('jawaban') }}</textarea>
        </div>
        <button type="submit" class="tool-flashcard__add">+ Tambah Kartu</button>
    </form>

    @if($cards->isEmpty())
        <div class="tool-flashcard__empty">
            <p>🃏</p>
            <p>Belum ada kartu. Tambahkan kartu pertama di atas.</p>
        </div>
    @else
        <div class="tool-flashcard__deck">
            @foreach($cards as $i => $card)
            <details class="tool-flashcard__card">
                <summary>
                    <span class="tool-flashcard__num">#{{ $i + 1 }}</span>
                    <span class="tool-flashcard__q">{{ \Illuminate\Support\Str::limit($card->pertanyaan, 80) }}</span>
                </summary>
                <div class="tool-flashcard__answer">
                    <p class="tool-flashcard__a-label">Jawaban:</p>
                    <p class="tool-flashcard__a-text">{{ $card->jawaban }}</p>
                </div>
                <div class="tool-flashcard__actions">
                    <button type="button" class="tool-flashcard__edit" data-card-id="{{ $card->id }}">✎ Edit</button>
                    <form method="POST" action="{{ route('flashcard.destroy', $card) }}" onsubmit="return confirm('Hapus kartu ini?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="tool-flashcard__delete">🗑 Hapus</button>
                    </form>
                </div>
            </details>
            @endforeach
        </div>
    @endif
</div>
