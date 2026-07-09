@php
    /** @var \App\Models\SesiBelajar $sesi */
    $cards = $sesi->flashcards()->orderBy('urutan')->get();
    $totalReviews = $sesi->flashcardReviews()->count();
    $benarCount   = $sesi->flashcardReviews()->where('benar', true)->count();
    $akurasi      = $totalReviews > 0 ? (int) round(($benarCount / $totalReviews) * 100) : 0;
@endphp
<div class="p-6 md:p-8 flex flex-col gap-6" data-sesi-id="{{ $sesi->id }}">
    <div>
        <h3 class="text-lg font-bold text-slate-900">Deck Kartu Flash</h3>
        <p class="text-sm text-slate-500 mt-1">Total: <strong class="text-slate-700">{{ $cards->count() }}</strong> kartu
            @if($totalReviews > 0)
                <span class="mx-1.5 text-slate-300">•</span> {{ $totalReviews }} review <span class="mx-1.5 text-slate-300">•</span> {{ $akurasi }}% akurasi
            @endif
        </p>
    </div>

    @if($cards->count() > 0)
    <a href="{{ route('flashcard.review', $sesi) }}" class="flex items-center justify-center gap-2 p-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white rounded-xl font-bold transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
        Mulai Review ({{ $cards->count() }} kartu)
    </a>
    @endif

    @if($sesi->status !== 'selesai')
    <form method="POST" action="{{ route('flashcard.store', $sesi) }}" class="flex flex-col gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-200">
        @csrf
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-slate-600">Pertanyaan</label>
            <textarea name="pertanyaan" rows="2" required maxlength="1000" placeholder="cth: Apa rumus turunan pertama dari x²?"
                      class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-shadow resize-y">{{ old('pertanyaan') }}</textarea>
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-slate-600">Jawaban</label>
            <textarea name="jawaban" rows="2" required maxlength="2000" placeholder="cth: 2x — turunan pangkat dikurangi pangkatnya"
                      class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-shadow resize-y">{{ old('jawaban') }}</textarea>
        </div>
        <button type="submit" class="mt-1 py-2.5 {{ $metodeInfo['btn'] ?? 'bg-slate-900 hover:bg-slate-800' }} text-white font-semibold rounded-xl text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shadow-sm w-full md:w-auto self-start px-6">
            + Tambah Kartu
        </button>
    </form>
    @endif

    @if($cards->isEmpty())
        <div class="py-12 px-6 text-center bg-slate-50 border border-slate-200 border-dashed rounded-2xl">
            <p class="text-sm text-slate-500 font-medium">Belum ada kartu. Tambahkan kartu pertama di atas.</p>
        </div>
    @else
        <div class="flex flex-col gap-3">
            @foreach($cards as $i => $card)
            <details class="group bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm [&_summary::-webkit-details-marker]:hidden" id="card-details-{{ $card->id }}">
                <summary id="card-summary-{{ $card->id }}" class="flex items-start gap-3 p-4 cursor-pointer hover:bg-slate-50 transition-colors select-none">
                    <span class="text-[10px] font-extrabold bg-violet-100 text-violet-700 px-2 py-0.5 rounded-md mt-0.5 shrink-0">#{{ $i + 1 }}</span>
                    <span class="text-sm font-semibold text-slate-800 flex-1 leading-snug">{{ \Illuminate\Support\Str::limit($card->pertanyaan, 80) }}</span>
                    <svg class="w-4 h-4 text-slate-400 transform group-open:rotate-180 transition-transform mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </summary>
                
                <div id="card-view-{{ $card->id }}" class="px-4 pb-4 pt-1 border-t border-slate-100">
                    <div class="mt-3 p-4 bg-violet-50/50 border-l-4 border-violet-500 rounded-r-lg">
                        <p class="text-[10px] font-bold text-violet-600 uppercase tracking-wider mb-1">Jawaban:</p>
                        <p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $card->jawaban }}</p>
                    </div>
                    
                    @if($sesi->status !== 'selesai')
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" onclick="editFlashcard({{ $card->id }})" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold transition-colors">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('flashcard.destroy', $card) }}" onsubmit="return confirm('Hapus kartu ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 rounded-lg text-xs font-semibold transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                @if($sesi->status !== 'selesai')
                <form method="POST" action="{{ route('flashcard.update', $card) }}" id="editForm-{{ $card->id }}" class="hidden flex-col gap-4 p-4 border-t border-slate-100 bg-slate-50">
                    @csrf @method('PUT')
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-600">Pertanyaan</label>
                        <textarea name="pertanyaan" rows="2" required maxlength="1000" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-shadow resize-y">{{ $card->pertanyaan }}</textarea>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-600">Jawaban</label>
                        <textarea name="jawaban" rows="2" required maxlength="2000" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-shadow resize-y">{{ $card->jawaban }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" onclick="cancelEdit({{ $card->id }})" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 {{ $metodeInfo['btn'] ?? 'bg-slate-900 hover:bg-slate-800' }} text-white rounded-lg text-xs font-semibold transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
                @endif
            </details>
            @endforeach
        </div>
    @endif
</div>

<script>
function editFlashcard(id) {
    document.getElementById('card-view-' + id).classList.add('hidden');
    document.getElementById('editForm-' + id).classList.remove('hidden');
    document.getElementById('editForm-' + id).classList.add('flex');
}
function cancelEdit(id) {
    document.getElementById('card-view-' + id).classList.remove('hidden');
    document.getElementById('editForm-' + id).classList.remove('flex');
    document.getElementById('editForm-' + id).classList.add('hidden');
}
</script>
