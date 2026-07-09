@extends('layouts.app')
@section('content')

@php
$metodeMap = [
    'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'text-blue-600', 'bg'=>'bg-blue-50', 'icon'=>'⌚', 'desc'=>'25 menit fokus + 5 menit istirahat', 'border'=>'border-blue-200', 'btn'=>'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500', 'ring'=>'focus:ring-blue-500'],
    'active_recall'=> ['label'=>'Active Recall',  'color'=>'text-violet-600', 'bg'=>'bg-violet-50', 'icon'=>'🧠', 'desc'=>'Uji dirimu sendiri tanpa melihat catatan', 'border'=>'border-violet-200', 'btn'=>'bg-violet-600 hover:bg-violet-700 focus:ring-violet-500', 'ring'=>'focus:ring-violet-500'],
    'blurting'     => ['label'=>'Blurting',       'color'=>'text-emerald-600', 'bg'=>'bg-emerald-50', 'icon'=>'✍️', 'desc'=>'Tulis semua yang kamu ingat di kertas kosong', 'border'=>'border-emerald-200', 'btn'=>'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500', 'ring'=>'focus:ring-emerald-500'],
    'feynman'      => ['label'=>'Feynman',        'color'=>'text-amber-600', 'bg'=>'bg-amber-50', 'icon'=>'🏫', 'desc'=>'Jelaskan konsep seolah mengajar orang lain', 'border'=>'border-amber-200', 'btn'=>'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500', 'ring'=>'focus:ring-amber-500'],
];
$metodeInfo = $metodeMap[$sesi->metode] ?? $metodeMap['pomodoro'];
@endphp

<div class="dash-page flex min-h-screen bg-slate-100 text-slate-900 font-sans">
    @include('dashboard._sidebar_siswa', ['active' => 'sesi'])

    <main class="dash-main flex-1 flex flex-col p-6 md:py-6 md:px-8 gap-6 overflow-x-hidden">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm" id="flashMsg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm" id="flashMsg">
                {{ session('error') }}
            </div>
        @endif

        <div class="w-full max-w-4xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
            {{-- Header Card --}}
            <div class="flex items-center justify-between p-5 md:p-6 border-b {{ $metodeInfo['border'] }} {{ $metodeInfo['bg'] }}">
                <div class="flex items-center gap-4">
                    <span class="text-3xl">{{ $metodeInfo['icon'] }}</span>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider {{ $metodeInfo['color'] }}">
                            Tool {{ $sesi->status === 'selesai' ? 'Selesai' : 'Aktif' }}: {{ $metodeInfo['label'] }}
                        </p>
                        <h2 class="text-lg font-bold text-slate-900 mt-0.5">{{ $sesi->judul ?: 'Tanpa judul' }}</h2>
                    </div>
                </div>
                <a href="{{ route('sesi.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white text-slate-500 border border-slate-200 rounded-full text-[13px] font-medium transition-all duration-200 hover:text-blue-600 hover:border-blue-600 hover:shadow-[0_2px_8px_rgba(37,99,235,0.1)]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> 
                    Kembali
                </a>
            </div>

            {{-- Content Partials --}}
            @if($sesi->metode === 'pomodoro')
                @include('dashboard.partials.tool-pomodoro', ['sesi' => $sesi, 'metodeInfo' => $metodeInfo])
            @elseif($sesi->metode === 'active_recall')
                @include('dashboard.partials.tool-flashcard', ['sesi' => $sesi, 'metodeInfo' => $metodeInfo])
            @elseif(in_array($sesi->metode, ['blurting', 'feynman']))
                @include('dashboard.partials.tool-notebook', ['sesi' => $sesi, 'metodeInfo' => $metodeInfo])
            @endif

            {{-- Footer Card --}}
            <div class="p-5 md:p-6 border-t border-slate-200 bg-slate-50 flex flex-wrap gap-3 mt-auto">
                @if($sesi->status !== 'selesai')
                <form method="POST" action="{{ route('sesi.complete', $sesi) }}" onsubmit="return confirm('Tandai sesi ini selesai?')" class="flex-1 min-w-[200px]">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full py-2.5 px-5 {{ $metodeInfo['btn'] }} text-white font-bold rounded-xl text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg shadow-sm">
                        Tandai Selesai
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('sesi.destroy', $sesi) }}" onsubmit="return confirm('Hapus sesi ini?')" class="{{ $sesi->status === 'selesai' ? 'w-full' : 'w-auto' }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2.5 px-5 bg-white hover:bg-red-50 border border-red-200 text-red-600 font-semibold rounded-xl text-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                        Hapus Sesi
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
setTimeout(()=>{const f=document.getElementById('flashMsg');if(f)f.style.transition='opacity .5s',f.style.opacity='0',setTimeout(()=>f&&f.remove(),500);},3000);

@if($sesi && $sesi->metode === 'pomodoro')
// --- POMODORO TIMER ---
let totalSeconds = {{ (int) $sesi->durasi_fokus_menit }} * 60;
let remaining = totalSeconds;
let running = false;
let interval = null;

function pad(n){return String(n).padStart(2,'0');}
function updateDisplay(){
    const m=Math.floor(remaining/60), s=remaining%60;
    const display=document.getElementById('timerDisplay');
    const bar=document.getElementById('timerBar');
    const hint=document.getElementById('timerHint');
    if(display)display.textContent=pad(m)+':'+pad(s);
    if(bar)bar.style.width=(remaining/totalSeconds*100)+'%';
    if(hint)hint.textContent=running?'Fokus! Jangan terganggu sekarang.':'Siap untuk fokus belajar?';
}

function startTimer(){
    if(running)return;
    running=true;
    document.getElementById('btnTimerStart').style.display='none';
    document.getElementById('btnTimerPause').style.display='';
    interval=setInterval(()=>{
        remaining--;
        updateDisplay();
        if(remaining<=0){
            clearInterval(interval);running=false;
            document.getElementById('btnTimerPause').style.display='none';
            document.getElementById('btnTimerStart').style.display='';
            document.getElementById('timerHint').textContent='Sesi fokus selesai! Istirahat sebentar.';
            document.getElementById('timerDisplay').textContent='00:00';
            if(window.Notification&&Notification.permission==='granted')new Notification('LearnFit',{body:'Sesi fokus selesai! Istirahat sebentar.'});
        }
    },1000);
    if(window.Notification&&Notification.permission==='default')Notification.requestPermission();
}

function pauseTimer(){
    if(!running)return;
    clearInterval(interval);running=false;
    document.getElementById('btnTimerPause').style.display='none';
    document.getElementById('btnTimerStart').style.display='';
    document.getElementById('timerHint').textContent='Dijeda. Klik Mulai untuk lanjutkan.';
}

function resetTimer(){
    clearInterval(interval);running=false;remaining=totalSeconds;
    document.getElementById('btnTimerPause').style.display='none';
    document.getElementById('btnTimerStart').style.display='';
    updateDisplay();
}

document.getElementById('btnTimerStart')?.addEventListener('click', startTimer);
document.getElementById('btnTimerPause')?.addEventListener('click', pauseTimer);
document.getElementById('btnTimerReset')?.addEventListener('click', resetTimer);

updateDisplay();
@endif
</script>
@endsection
