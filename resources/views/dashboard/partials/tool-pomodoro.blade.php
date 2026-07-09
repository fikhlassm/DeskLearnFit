@php
    /** @var \App\Models\SesiBelajar $sesi */
@endphp
<div class="p-6 md:p-8 flex flex-col items-center gap-6" data-sesi-id="{{ $sesi->id }}">
    <p class="text-xs font-bold tracking-widest text-slate-400 uppercase">Timer Fokus</p>
    
    <div class="text-6xl md:text-7xl font-extrabold tracking-tighter tabular-nums text-slate-800" id="timerDisplay">
        --:--
    </div>
    
    <div class="w-full max-w-sm h-2 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full bg-blue-600 rounded-full transition-all duration-500 ease-linear" id="timerBar" style="width:100%"></div>
    </div>
    
    <div class="flex flex-wrap justify-center gap-4 text-xs font-medium text-slate-500">
        <span class="flex items-center gap-1 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            {{ $sesi->durasi_fokus_menit }}m fokus
        </span>
        <span class="flex items-center gap-1 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
            {{ $sesi->durasi_istirahat_menit }}m istirahat
        </span>
        <span class="flex items-center gap-1 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
            {{ $sesi->jumlah_siklus }} siklus
        </span>
    </div>
    
    @if($sesi->status !== 'selesai')
    <div class="flex gap-3 w-full max-w-xs mt-2">
        <button type="button" class="flex-1 py-2.5 {{ $metodeInfo['btn'] ?? 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-xl font-bold transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shadow-sm" id="btnTimerStart">Mulai</button>
        <button type="button" class="flex-1 py-2.5 {{ $metodeInfo['btn'] ?? 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-xl font-bold transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shadow-sm" id="btnTimerPause" style="display:none">Jeda</button>
        <button type="button" class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 rounded-xl font-semibold transition-all duration-300 hover:-translate-y-1 hover:shadow-md shadow-sm" id="btnTimerReset">Reset</button>
    </div>
    <p class="text-sm text-slate-500 font-medium" id="timerHint">Siap untuk fokus belajar?</p>
    @endif

    <form method="POST" action="{{ route('sesi.catatan', $sesi) }}" class="w-full mt-6 pt-6 border-t border-slate-100 flex flex-col gap-3">
        @csrf @method('PATCH')
        <label class="text-xs font-bold tracking-widest text-slate-500 uppercase">Catatan Sesi Ini</label>
        <textarea name="catatan" rows="3" maxlength="2000" placeholder="cth: Hari ini bahas integral parsial, masih bingung bagian trigonometric substitution..." 
                  class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow resize-y" 
                  {{ $sesi->status === 'selesai' ? 'readonly' : '' }}>{{ $sesi->catatan }}</textarea>
        @if($sesi->status !== 'selesai')
        <button type="submit" class="self-end px-5 py-2.5 {{ $metodeInfo['btn'] ?? 'bg-slate-900 hover:bg-slate-800' }} text-white font-semibold rounded-xl text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shadow-sm">
            Simpan Catatan
        </button>
        @endif
    </form>
</div>
