@php
    /** @var \App\Models\SesiBelajar $sesi */
@endphp
<div class="tool-pomodoro" data-sesi-id="{{ $sesi->id }}">
    <p class="tool-pomodoro__label">Timer Fokus</p>
    <div class="tool-pomodoro__display" id="timerDisplay">--:--</div>
    <div class="tool-pomodoro__progress-wrap">
        <div class="tool-pomodoro__progress-bar" id="timerBar" style="width:100%"></div>
    </div>
    <div class="tool-pomodoro__meta">
        <span>⏱ {{ $sesi->durasi_fokus_menit }}m fokus</span>
        <span>☕ {{ $sesi->durasi_istirahat_menit }}m istirahat</span>
        <span>🔁 {{ $sesi->jumlah_siklus }} siklus</span>
    </div>
    <div class="tool-pomodoro__controls">
        <button type="button" class="tool-pomodoro__btn tool-pomodoro__btn--start" id="btnTimerStart">▶ Mulai</button>
        <button type="button" class="tool-pomodoro__btn tool-pomodoro__btn--pause" id="btnTimerPause" style="display:none">⏸ Jeda</button>
        <button type="button" class="tool-pomodoro__btn tool-pomodoro__btn--reset" id="btnTimerReset">↺ Reset</button>
    </div>
    <p class="tool-pomodoro__hint" id="timerHint">Siap untuk fokus belajar?</p>

    <form method="POST" action="{{ route('sesi.catatan', $sesi) }}" class="tool-pomodoro__catatan">
        @csrf @method('PATCH')
        <label class="tool-pomodoro__catatan-label">📝 Catatan Sesi Ini</label>
        <textarea name="catatan" rows="3" maxlength="2000" placeholder="cth: Hari ini bahas integral parsial, masih bingung bagian trigonometric substitution...">{{ $sesi->catatan }}</textarea>
        <button type="submit" class="tool-pomodoro__catatan-save">Simpan Catatan</button>
    </form>
</div>
