@extends('layouts.app')
@section('content')

@php
$metodeMap = [
    'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB','bg'=>'#EFF6FF','icon'=>'⏱️','desc'=>'25 menit fokus + 5 menit istirahat'],
    'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED','bg'=>'#F5F3FF','icon'=>'🧠','desc'=>'Uji dirimu sendiri tanpa melihat catatan'],
    'blurting'     => ['label'=>'Blurting',       'color'=>'#059669','bg'=>'#ECFDF5','icon'=>'✍️','desc'=>'Tulis semua yang kamu ingat di kertas kosong'],
    'feynman'      => ['label'=>'Feynman',         'color'=>'#D97706','bg'=>'#FFFBEB','icon'=>'🏫','desc'=>'Jelaskan konsep seolah mengajar orang lain'],
];
$metodeInfo = $metodeMap[$sesi->metode] ?? $metodeMap['pomodoro'];
@endphp

<div class="dash-page" style="background:#0F172A; color:#fff;">
    <main class="dash-main" style="align-items:center; justify-content:center; padding-top:40px;">
        @if(session('success'))<div class="alert-success" id="flashMsg">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error" id="flashMsg">{{ session('error') }}</div>@endif

        <div class="tool-card" style="width:100%; max-width:800px; margin:0 auto; background:#1E293B; border-color:#334155; color:#F8FAFC;">
            <div class="tool-card__head" style="background:{{ $metodeInfo['bg'] }}; color:#0F172A; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:.75rem;">
                    <span class="tool-card__icon" style="color:{{ $metodeInfo['color'] }}">{{ $metodeInfo['icon'] }}</span>
                    <div>
                        <p class="tool-card__label" style="color:{{ $metodeInfo['color'] }}">Tool {{ $sesi->status === 'selesai' ? 'Selesai' : 'Aktif' }}: {{ $metodeInfo['label'] }}</p>
                        <p class="tool-card__judul" style="color:#0F172A;">{{ $sesi->judul ?: 'Tanpa judul' }}</p>
                    </div>
                </div>
                <a href="{{ route('sesi.index') }}" style="font-size:14px; color:#2563EB; font-weight:600; text-decoration:none;">← Kembali</a>
            </div>

            @if($sesi->metode === 'pomodoro')
                @include('dashboard.partials.tool-pomodoro', ['sesi' => $sesi])
            @elseif($sesi->metode === 'active_recall')
                @include('dashboard.partials.tool-flashcard', ['sesi' => $sesi])
            @elseif(in_array($sesi->metode, ['blurting', 'feynman']))
                @include('dashboard.partials.tool-notebook', ['sesi' => $sesi])
            @endif

            <div class="tool-card__footer" style="background:#0F172A; border-color:#334155;">
                @if($sesi->status !== 'selesai')
                <form method="POST" action="{{ route('sesi.complete', $sesi) }}" onsubmit="return confirm('Tandai sesi ini selesai?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-selesai-sesi">✓ Tandai Selesai</button>
                </form>
                @endif
                <form method="POST" action="{{ route('sesi.destroy', $sesi) }}" onsubmit="return confirm('Hapus sesi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-hapus-tool" style="background:#1E293B; color:#F87171; border-color:#7F1D1D;">🗑 Hapus Sesi</button>
                </form>
            </div>
        </div>
    </main>
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.25rem;overflow-x:hidden;}
.tool-card{background:#fff;border:1px solid #E2E8F0;border-radius:16px;padding:0;display:flex;flex-direction:column;overflow:hidden;}
.tool-card__head{padding:1rem 1.25rem;display:flex;align-items:center;gap:.75rem;border-bottom:1px solid #E2E8F0;}
.tool-card__icon{font-size:1.5rem;line-height:1;}
.tool-card__label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;}
.tool-card__judul{font-size:.95rem;font-weight:700;color:#0F172A;margin-top:.1rem;}
.tool-card__footer{padding:1rem 1.25rem;border-top:1px solid #E2E8F0;display:flex;gap:.65rem;background:#FAFBFC;}
.btn-selesai-sesi{flex:1;padding:.65rem 1.2rem;background:#15803D;color:#fff;border:none;border-radius:10px;font-size:.85rem;font-weight:700;cursor:pointer;font-family:inherit;}
.btn-selesai-sesi:hover{background:#166534;}
.btn-hapus-tool{padding:.65rem 1rem;background:#fff;color:#991B1B;border:1px solid #FECACA;border-radius:10px;font-size:.82rem;font-weight:600;cursor:pointer;font-family:inherit;}
.btn-hapus-tool:hover{background:#FEF2F2;}

/* ── Tool Pomodoro ── */
.tool-pomodoro{background:#0F172A;color:#fff;padding:1.75rem;display:flex;flex-direction:column;align-items:center;gap:1rem;}
.tool-pomodoro__label{font-size:.72rem;font-weight:700;letter-spacing:.06em;color:#64748B;text-transform:uppercase;}
.tool-pomodoro__display{font-size:3.5rem;font-weight:800;letter-spacing:-.04em;font-variant-numeric:tabular-nums;color:#fff;}
.tool-pomodoro__progress-wrap{width:100%;height:6px;background:rgba(255,255,255,.1);border-radius:99px;overflow:hidden;}
.tool-pomodoro__progress-bar{height:100%;background:linear-gradient(90deg,#2563EB,#60A5FA);border-radius:99px;transition:width .5s linear;}
.tool-pomodoro__meta{display:flex;gap:.8rem;font-size:.72rem;color:#94A3B8;}
.tool-pomodoro__controls{display:flex;gap:.65rem;}
.tool-pomodoro__btn{padding:.6rem 1.2rem;border-radius:10px;border:none;cursor:pointer;font-size:.83rem;font-weight:600;font-family:inherit;transition:opacity .18s,transform .15s;}
.tool-pomodoro__btn--start{background:#2563EB;color:#fff;}
.tool-pomodoro__btn--pause{background:#F59E0B;color:#fff;}
.tool-pomodoro__btn--reset{background:rgba(255,255,255,.1);color:#94A3B8;}
.tool-pomodoro__btn:hover{opacity:.88;transform:translateY(-1px);}
.tool-pomodoro__hint{font-size:.75rem;color:#64748B;}
.tool-pomodoro__catatan{margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,.1);display:flex;flex-direction:column;gap:.4rem;width:100%;}
.tool-pomodoro__catatan-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94A3B8;text-align:left;}
.tool-pomodoro__catatan textarea{padding:.6rem .75rem;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:8px;color:#fff;font-size:.82rem;font-family:inherit;resize:vertical;outline:none;}
.tool-pomodoro__catatan textarea:focus{background:rgba(255,255,255,.12);border-color:#60A5FA;}
.tool-pomodoro__catatan-save{padding:.5rem .9rem;background:#2563EB;color:#fff;border:none;border-radius:8px;font-size:.78rem;font-weight:600;cursor:pointer;font-family:inherit;align-self:flex-end;transition:background .15s;}
.tool-pomodoro__catatan-save:hover{background:#1d4ed8;}

/* ── Tool Flashcard ── */
.tool-flashcard{padding:1.25rem;display:flex;flex-direction:column;gap:1rem; color:#F8FAFC;}
.tool-flashcard__title{font-size:1rem;font-weight:700;color:#F8FAFC;}
.tool-flashcard__sub{font-size:.78rem;color:#94A3B8;}
.tool-flashcard__form{display:flex;flex-direction:column;gap:.65rem;background:#1E293B;padding:1rem;border-radius:12px;border:1px dashed #475569;}
.tool-flashcard__field{display:flex;flex-direction:column;gap:.25rem;}
.tool-flashcard__field label{font-size:.75rem;font-weight:600;color:#CBD5E1;}
.tool-flashcard__field textarea{padding:.55rem .75rem;border:1px solid #475569;border-radius:8px;font-size:.85rem;font-family:inherit;resize:vertical;outline:none;background:#0F172A; color:#fff;}
.tool-flashcard__field textarea:focus{border-color:#7C3AED;box-shadow:0 0 0 3px rgba(124,58,237,.20);}
.tool-flashcard__add{padding:.6rem;background:#7C3AED;color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;font-family:inherit;}
.tool-flashcard__add:hover{background:#6D28D9;}
.tool-flashcard__empty{padding:2rem 1rem;text-align:center;color:#94A3B8;font-size:.85rem;background:#0F172A;border-radius:12px;}
.tool-flashcard__empty p:first-child{font-size:2rem;margin-bottom:.4rem;}
.tool-flashcard__deck{display:flex;flex-direction:column;gap:.5rem;}
.tool-flashcard__card{background:#0F172A;border:1px solid #334155;border-radius:10px;padding:.65rem .9rem;color:#F8FAFC;}
.tool-flashcard__card[open]{background:#1E293B;}
.tool-flashcard__card summary{cursor:pointer;display:flex;align-items:center;gap:.6rem;list-style:none;}
.tool-flashcard__card summary::-webkit-details-marker{display:none;}
.tool-flashcard__num{font-size:.7rem;font-weight:700;background:#7C3AED;color:#fff;padding:.1rem .45rem;border-radius:6px;}
.tool-flashcard__q{font-size:.85rem;font-weight:500;flex:1;}
.tool-flashcard__answer{margin-top:.65rem;padding:.65rem;background:rgba(124,58,237,.1);border-left:3px solid #7C3AED;border-radius:6px;}
.tool-flashcard__a-label{font-size:.7rem;font-weight:700;color:#A78BFA;text-transform:uppercase;letter-spacing:.04em;}
.tool-flashcard__a-text{font-size:.82rem;color:#F1F5F9;margin-top:.2rem;white-space:pre-wrap;}
.tool-flashcard__actions{display:flex;gap:.5rem;margin-top:.5rem;justify-content:flex-end;}
.tool-flashcard__edit,.tool-flashcard__delete{background:none;border:1px solid #334155;padding:.3rem .7rem;border-radius:6px;font-size:.75rem;cursor:pointer;font-family:inherit; color:#F8FAFC;}
.tool-flashcard__delete{color:#F87171;border-color:#7F1D1D;}

/* ── Tool Notebook ── */
.tool-notebook{padding:1.25rem;display:flex;flex-direction:column;gap:1rem;}
.tool-notebook__title{font-size:1rem;font-weight:700;color:#F8FAFC;}
.tool-notebook__sub{font-size:.78rem;color:#94A3B8;font-style:italic;}
.tool-notebook__form{display:flex;flex-direction:column;gap:.65rem;background:#0F172A;padding:1rem;border-radius:12px;border:1px dashed #475569;}
.tool-notebook__form textarea{padding:.75rem;border:1px solid #334155;border-radius:8px;font-size:.85rem;font-family:inherit;resize:vertical;outline:none;background:#1E293B;color:#fff;line-height:1.5;}
.tool-notebook__form textarea:focus{border-color:#D97706;box-shadow:0 0 0 3px rgba(217,119,6,.20);}
.tool-notebook__submit{padding:.65rem;background:#D97706;color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer;font-family:inherit;align-self:flex-end;padding-left:1.2rem;padding-right:1.2rem;}
.tool-notebook__submit:hover{background:#B45309;}
.tool-notebook__empty{padding:2rem 1rem;text-align:center;color:#94A3B8;font-size:.85rem;background:#0F172A;border-radius:12px;}
.tool-notebook__empty p:first-child{font-size:2rem;margin-bottom:.4rem;}
.tool-notebook__section-label{font-size:.8rem;font-weight:700;color:#F8FAFC;margin-top:.5rem;}
.tool-notebook__list{display:flex;flex-direction:column;gap:.75rem;}
.tool-notebook__entry{background:#0F172A;border:1px solid #334155;border-radius:12px;padding:1rem;display:flex;flex-direction:column;gap:.65rem;}
.tool-notebook__entry-head{display:flex;align-items:center;justify-content:space-between;}
.tool-notebook__time{font-size:.72rem;color:#94A3B8;}
.tool-notebook__score{font-size:.78rem;font-weight:700;padding:.2rem .55rem;border-radius:6px;}
.tool-notebook__score[data-skor="0"],.tool-notebook__score[data-skor="1"],.tool-notebook__score[data-skor="2"],.tool-notebook__score[data-skor="3"],.tool-notebook__score[data-skor="4"]{background:#7F1D1D;color:#FECACA;}
.tool-notebook__score[data-skor="5"],.tool-notebook__score[data-skor="6"]{background:#78350F;color:#FDE68A;}
.tool-notebook__score[data-skor="7"],.tool-notebook__score[data-skor="8"]{background:#14532D;color:#BBF7D0;}
.tool-notebook__score[data-skor="9"],.tool-notebook__score[data-skor="100"]{background:#14532D;color:#BBF7D0;}
.tool-notebook__konten{font-size:.85rem;color:#F8FAFC;line-height:1.55;white-space:pre-wrap;background:#1E293B;padding:.65rem;border-radius:8px;max-height:120px;overflow-y:auto;}
.tool-notebook__analisis{background:rgba(253,230,138,.1);border:1px solid rgba(253,230,138,.2);border-radius:10px;padding:.75rem;}
.tool-notebook__a-title{font-size:.72rem;font-weight:700;color:#FBBF24;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.25rem;}
.tool-notebook__a-text{font-size:.8rem;color:#FEF3C7;line-height:1.5;}
.tool-notebook__keywords{display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.5rem;}
.tool-notebook__kw{font-size:.7rem;font-weight:600;background:#D97706;color:#fff;padding:.15rem .5rem;border-radius:6px;}
.tool-notebook__entry-actions{display:flex;justify-content:flex-end;}
.tool-notebook__entry-actions button{background:none;border:1px solid #7F1D1D;color:#F87171;padding:.3rem .65rem;border-radius:6px;font-size:.75rem;cursor:pointer;font-family:inherit;}
</style>

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
