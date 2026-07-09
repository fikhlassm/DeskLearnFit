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
<div class="p-6 md:p-8 flex flex-col gap-6" data-sesi-id="{{ $sesi->id }}" data-tipe="{{ $tipe }}">
    <div>
        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <span>{{ $icon }}</span> Notebook {{ $label }}
        </h3>
        <p class="text-sm text-slate-500 mt-1 italic">{{ $guide }}</p>
    </div>

    @if($sesi->status !== 'selesai')
    <form method="POST" action="{{ route('notebook.store', $sesi) }}" class="flex flex-col gap-3">
        @csrf
        <div class="relative">
            <textarea name="konten" rows="6" required minlength="5" maxlength="5000"
                placeholder="Mulai menulis di sini…" 
                class="w-full p-4 md:p-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm md:text-base focus:outline-none focus:ring-2 {{ $metodeInfo['ring'] ?? 'focus:ring-amber-500' }} focus:border-transparent transition-shadow resize-y leading-relaxed text-slate-800">{{ old('konten') }}</textarea>
        </div>
        <button type="submit" class="self-end px-6 py-3 {{ $metodeInfo['btn'] ?? 'bg-amber-600 hover:bg-amber-700' }} text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-md hover:-translate-y-1 hover:shadow-lg">
            Kirim & Analisis
        </button>
    </form>
    @endif

    @if($entries->isEmpty())
        <div class="py-16 px-6 text-center bg-slate-50 border border-slate-200 border-dashed rounded-2xl flex flex-col items-center justify-center gap-3">
            <span class="text-5xl opacity-50 grayscale">{{ $icon }}</span>
            <p class="text-sm text-slate-500 font-medium">Belum ada entri. Tulis jawaban pertamamu di atas.</p>
        </div>
    @else
        <div class="mt-4">
            <p class="text-sm font-bold text-slate-700 mb-4 border-b border-slate-200 pb-2">Riwayat Entri ({{ $entries->count() }})</p>
            
            <div class="flex flex-col gap-5">
                @foreach($entries as $entri)
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                    
                    {{-- Header Entri --}}
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $entri->created_at->format('d M H:i') }}
                        </span>
                        
                        @php
                            $skorColor = 'bg-red-100 text-red-700';
                            if($entri->skor_keyakinan >= 5) $skorColor = 'bg-amber-100 text-amber-700';
                            if($entri->skor_keyakinan >= 7) $skorColor = 'bg-emerald-100 text-emerald-700';
                        @endphp
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $skorColor }}" data-skor="{{ $entri->skor_keyakinan }}">
                            Skor: {{ $entri->skor_keyakinan }}/100
                        </span>
                    </div>
                    
                    {{-- Konten Jawaban --}}
                    <div class="bg-slate-50 p-4 rounded-xl text-sm text-slate-700 leading-relaxed whitespace-pre-wrap max-h-48 overflow-y-auto mb-4 border border-slate-100">{{ $entri->konten }}</div>
                    
                    {{-- Kotak Analisis --}}
                    <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4">
                        <div class="flex items-center gap-1.5 mb-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Analisis Sistem</p>
                        </div>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $entri->analisis_sistem }}</p>
                        
                        @if($entri->kata_kunci_cocok && count($entri->kata_kunci_cocok) > 0)
                        <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-amber-100/50">
                            @foreach($entri->kata_kunci_cocok as $kw)
                                <span class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-md">{{ $kw }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    @if($sesi->status !== 'selesai')
                    <div class="flex justify-end mt-4 pt-4 border-t border-slate-100">
                        <form method="POST" action="{{ route('notebook.destroy', $entri) }}" onsubmit="return confirm('Hapus entri ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 rounded-lg text-xs font-semibold transition-colors">
                                Hapus Entri
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
