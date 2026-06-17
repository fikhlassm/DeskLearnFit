@extends('layouts.app')

@section('content')
<style>
*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ─── NAVBAR ─── */
.qz-nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,255,255,.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid #e2e8f0;
}
.qz-nav__inner {
    display: flex; align-items: center; justify-content: space-between;
    height: 64px; max-width: 1180px; margin: 0 auto; padding: 0 24px;
}
.qz-nav__brand { display: flex; align-items: center; gap: 9px; text-decoration: none; }
.qz-nav__name  { font-size: 18px; font-weight: 700; color: #0f172a; }
.qz-nav__close {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: #f1f5f9; border: none; cursor: pointer; color: #475569;
    transition: background .2s, color .2s, transform .15s;
    text-decoration: none;
}
.qz-nav__close:hover { background: #fee2e2; color: #dc2626; transform: rotate(90deg); }

/* ─── PROGRESS ─── */
.qz-progress-wrap {
    background: #fff; border-bottom: 1px solid #f1f5f9;
    padding: .6rem 0;
}
.qz-progress-inner { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
.qz-progress-meta {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: .35rem;
}
.qz-progress-label { font-size: .72rem; font-weight: 600; color: #64748b; letter-spacing: .04em; }
.qz-progress-pct   { font-size: .72rem; font-weight: 700; color: #2563eb; }
.qz-progress-track { height: 6px; background: #e2e8f0; border-radius: 99px; overflow: hidden; }
.qz-progress-bar {
    height: 100%; border-radius: 99px;
    background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
    transition: width .5s cubic-bezier(.4,0,.2,1);
}

/* ─── PAGE ─── */
.qz-page {
    min-height: calc(100vh - 130px);
    background: #f8fafc;
    padding-block: 2.5rem 6rem;
}
.qz-wrap { max-width: 860px; margin: 0 auto; padding: 0 24px; }

/* ─── STEP (question) ─── */
.qz-step { display: none; }
.qz-step.active {
    display: block;
    animation: stepIn .35s cubic-bezier(.4,0,.2,1) both;
}
@keyframes stepIn {
    from { opacity: 0; transform: translateX(32px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* ─── DIMENSI BADGE ─── */
.qz-dimensi-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .65rem; font-weight: 700; letter-spacing: .07em;
    color: #2563eb; background: #dbeafe; border-radius: 6px;
    padding: .2rem .65rem; margin-bottom: .85rem; width: fit-content;
    text-transform: uppercase;
}

/* ─── QUESTION CARD ─── */
.qz-qcard {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 20px;
    overflow: hidden; margin-bottom: 1.25rem;
    box-shadow: 0 2px 12px rgba(15,23,42,.06);
    padding: 1.5rem 1.75rem;
}
.qz-step-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .65rem; font-weight: 700; letter-spacing: .07em;
    color: #2563eb; background: #dbeafe; border-radius: 99px;
    padding: .2rem .65rem; margin-bottom: .7rem; width: fit-content;
}
.qz-qcard__q    { font-size: 1.05rem; font-weight: 600; color: #0f172a; line-height: 1.5; margin-bottom: .35rem; }
.qz-qcard__hint { font-size: .8rem; color: #64748b; line-height: 1.5; }

/* ─── OPTIONS LIST ─── */
.qz-options { display: flex; flex-direction: column; gap: .65rem; }
.qz-opt {
    display: flex; align-items: center; gap: .85rem;
    background: #fff; border: 1.5px solid #e2e8f0;
    border-radius: 50px; padding: .85rem 1.1rem;
    cursor: pointer; text-align: left;
    transition: border-color .2s, box-shadow .2s, transform .15s, background .2s;
}
.qz-opt:hover {
    border-color: #93c5fd; box-shadow: 0 4px 16px rgba(37,99,235,.08); transform: translateY(-1px);
}
.qz-opt.selected {
    border-color: #2563eb; background: #eff6ff;
    box-shadow: 0 4px 20px rgba(37,99,235,.12); transform: translateY(-1px);
}
.qz-opt__icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 1.2rem; background: #f1f5f9;
    transition: background .2s;
}
.qz-opt.selected .qz-opt__icon { background: #dbeafe; }
.qz-opt__label { flex: 1; font-size: .88rem; font-weight: 500; color: #0f172a; line-height: 1.4; }
.qz-opt.selected .qz-opt__label { color: #1d4ed8; font-weight: 600; }
.qz-opt__radio {
    width: 22px; height: 22px; border-radius: 50%;
    border: 1.5px solid #cbd5e1; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: border-color .2s, border-width .2s;
    background: #fff;
}
.qz-opt.selected .qz-opt__radio {
    border-color: #2563eb;
    border-width: 6px;
}

/* ─── BOTTOM BAR ─── */
.qz-bottom {
    position: fixed; bottom: 0; left: 0; right: 0;
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    border-top: 1px solid #e2e8f0; padding: 1rem 0; z-index: 50;
}
.qz-bottom__inner {
    max-width: 860px; margin: 0 auto; padding: 0 24px;
    display: flex; flex-direction: column; align-items: center; gap: .6rem;
}
.qz-btn-next {
    display: inline-flex; align-items: center; gap: .5rem;
    background: #2563eb; color: #fff; font-size: .92rem; font-weight: 600;
    padding: .8rem 2.5rem; border-radius: 99px; border: none; cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s, opacity .2s;
    box-shadow: 0 4px 16px rgba(37,99,235,.25); width: 100%; justify-content: center;
    max-width: 420px;
}
.qz-btn-next:disabled { opacity: .4; cursor: not-allowed; box-shadow: none; transform: none !important; }
.qz-btn-next:not(:disabled):hover {
    background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 6px 24px rgba(37,99,235,.35);
}
.qz-hint-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .73rem; color: #94a3b8; text-align: center; line-height: 1.5;
}

/* ─── RESPONSIVE ─── */
@media(max-width:640px){
    .qz-page { padding-block: 1.5rem 8rem; }
    .qz-qcard { padding: 1.25rem; }
    .qz-opt__label { font-size: .83rem; }
}
</style>

<nav class="qz-nav">
    <div class="qz-nav__inner">
        <a href="/" class="qz-nav__brand">
            <svg width="26" height="26" viewBox="0 0 28 28" fill="none">
                <rect width="28" height="28" rx="8" fill="#2563EB"/>
                <path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="qz-nav__name">LearnFit</span>
        </a>
        <a href="{{ route('welcome') }}" class="qz-nav__close" title="Keluar Quiz">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </a>
    </div>
</nav>

<div class="qz-progress-wrap">
    <div class="qz-progress-inner">
        <div class="qz-progress-meta">
            <span class="qz-progress-label">PROGRES QUIZ</span>
            <span class="qz-progress-pct" id="pctLabel">1 / 7</span>
        </div>
        <div class="qz-progress-track">
            <div class="qz-progress-bar" id="progressBar" style="width:14.28%"></div>
        </div>
    </div>
</div>

<div class="qz-page">
<div class="qz-wrap">
<form id="quizForm" method="POST" action="{{ route('quiz.submit') }}">
@csrf

{{--
    FORMAT JAWABAN (sesuai scoreMap di QuizController):
    name="answers[q1]"  value="visual|auditori|membaca|kinestetik"
    name="answers[q2]"  value="15menit|30menit|1jam|lebih"
    name="answers[q3]"  value="baca_ulang|rangkum|tanya|latihan"
    name="answers[q4]"  value="tenang|musik|ramai|alam"
    name="answers[q5]"  value="latihan|tutor|tulis_ulang|jadwal"
    name="answers[q6]"  value="pagi|siang|sore|malam"
    name="answers[q7]"  value="ujian|pemahaman|skill|hafal"
--}}

<!-- Q1 – Gaya Belajar -->
<div class="qz-step active" data-step="1">
    <div class="qz-dimensi-badge">Gaya Belajar</div>
    <div class="qz-qcard">
        <span class="qz-step-badge">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="#2563eb"/></svg>
            Soal 1 dari 7
        </span>
        <h2 class="qz-qcard__q">Saat belajar materi baru, Anda paling cepat mengerti jika melalui...</h2>
        <p class="qz-qcard__hint">Pilih cara yang paling cocok dengan gaya belajarmu.</p>
    </div>
    <div class="qz-options">
        <label class="qz-opt" data-q="q1">
            <div class="qz-opt__icon">🖼️</div>
            <div class="qz-opt__label">Gambar, grafik, atau diagram visual</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q1]" value="visual" style="display:none">
        </label>
        <label class="qz-opt" data-q="q1">
            <div class="qz-opt__icon">🎧</div>
            <div class="qz-opt__label">Mendengarkan penjelasan atau ceramah</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q1]" value="auditori" style="display:none">
        </label>
        <label class="qz-opt" data-q="q1">
            <div class="qz-opt__icon">📖</div>
            <div class="qz-opt__label">Membaca teks dan catatan sendiri</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q1]" value="membaca" style="display:none">
        </label>
        <label class="qz-opt" data-q="q1">
            <div class="qz-opt__icon">🤸</div>
            <div class="qz-opt__label">Latihan soal atau praktik langsung</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q1]" value="kinestetik" style="display:none">
        </label>
    </div>
</div>

<!-- Q2 – Durasi Fokus -->
<div class="qz-step" data-step="2">
    <div class="qz-dimensi-badge">Durasi Fokus</div>
    <div class="qz-qcard">
        <span class="qz-step-badge">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="#2563eb"/></svg>
            Soal 2 dari 7
        </span>
        <h2 class="qz-qcard__q">Berapa lama Anda bisa bertahan belajar tanpa kehilangan konsentrasi?</h2>
        <p class="qz-qcard__hint">Pilih durasi yang paling sering Anda alami secara alami.</p>
    </div>
    <div class="qz-options">
        <label class="qz-opt" data-q="q2">
            <div class="qz-opt__icon">⚡</div>
            <div class="qz-opt__label">Kurang dari 15 menit</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q2]" value="15menit" style="display:none">
        </label>
        <label class="qz-opt" data-q="q2">
            <div class="qz-opt__icon">⏳</div>
            <div class="qz-opt__label">Sekitar 15–30 menit</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q2]" value="30menit" style="display:none">
        </label>
        <label class="qz-opt" data-q="q2">
            <div class="qz-opt__icon">🔥</div>
            <div class="qz-opt__label">Sekitar 30–60 menit</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q2]" value="1jam" style="display:none">
        </label>
        <label class="qz-opt" data-q="q2">
            <div class="qz-opt__icon">🏆</div>
            <div class="qz-opt__label">Lebih dari 1 jam tanpa henti</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q2]" value="lebih" style="display:none">
        </label>
    </div>
</div>

<!-- Q3 – Cara Mengatasi Kesulitan -->
<div class="qz-step" data-step="3">
    <div class="qz-dimensi-badge">Cara Mengatasi Kesulitan</div>
    <div class="qz-qcard">
        <span class="qz-step-badge">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="#2563eb"/></svg>
            Soal 3 dari 7
        </span>
        <h2 class="qz-qcard__q">Jika ada materi yang sulit dimengerti, apa yang Anda lakukan?</h2>
        <p class="qz-qcard__hint">Reaksi spontanmu saat kesulitan mencerminkan strategi belajarmu.</p>
    </div>
    <div class="qz-options">
        <label class="qz-opt" data-q="q3">
            <div class="qz-opt__icon">📚</div>
            <div class="qz-opt__label">Membaca ulang teks di buku berkali-kali</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q3]" value="baca_ulang" style="display:none">
        </label>
        <label class="qz-opt" data-q="q3">
            <div class="qz-opt__icon">✏️</div>
            <div class="qz-opt__label">Merangkum dan menulis ulang dengan bahasa sendiri</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q3]" value="rangkum" style="display:none">
        </label>
        <label class="qz-opt" data-q="q3">
            <div class="qz-opt__icon">🙋</div>
            <div class="qz-opt__label">Bertanya ke teman atau mencari penjelasan lain</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q3]" value="tanya" style="display:none">
        </label>
        <label class="qz-opt" data-q="q3">
            <div class="qz-opt__icon">📝</div>
            <div class="qz-opt__label">Mengerjakan soal latihan sebanyak mungkin</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q3]" value="latihan" style="display:none">
        </label>
    </div>
</div>

<!-- Q4 – Lingkungan Belajar -->
<div class="qz-step" data-step="4">
    <div class="qz-dimensi-badge">Lingkungan Belajar</div>
    <div class="qz-qcard">
        <span class="qz-step-badge">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="#2563eb"/></svg>
            Soal 4 dari 7
        </span>
        <h2 class="qz-qcard__q">Di mana Anda paling nyaman dan produktif saat belajar?</h2>
        <p class="qz-qcard__hint">Lingkungan belajar yang tepat sangat mempengaruhi efektivitas.</p>
    </div>
    <div class="qz-options">
        <label class="qz-opt" data-q="q4">
            <div class="qz-opt__icon">🤫</div>
            <div class="qz-opt__label">Tempat tenang tanpa suara apapun</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q4]" value="tenang" style="display:none">
        </label>
        <label class="qz-opt" data-q="q4">
            <div class="qz-opt__icon">🎵</div>
            <div class="qz-opt__label">Sambil mendengarkan musik atau white noise</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q4]" value="musik" style="display:none">
        </label>
        <label class="qz-opt" data-q="q4">
            <div class="qz-opt__icon">☕</div>
            <div class="qz-opt__label">Tempat ramai seperti kafe atau perpustakaan</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q4]" value="ramai" style="display:none">
        </label>
        <label class="qz-opt" data-q="q4">
            <div class="qz-opt__icon">🌿</div>
            <div class="qz-opt__label">Di luar ruangan atau dekat alam terbuka</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q4]" value="alam" style="display:none">
        </label>
    </div>
</div>

<!-- Q5 – Metode Belajar Favorit -->
<div class="qz-step" data-step="5">
    <div class="qz-dimensi-badge">Metode Belajar Favorit</div>
    <div class="qz-qcard">
        <span class="qz-step-badge">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="#2563eb"/></svg>
            Soal 5 dari 7
        </span>
        <h2 class="qz-qcard__q">Saat mempersiapkan ujian, cara apa yang paling sering Anda lakukan?</h2>
        <p class="qz-qcard__hint">Kebiasaan belajarmu menunjukkan metode yang paling cocok untukmu.</p>
    </div>
    <div class="qz-options">
        <label class="qz-opt" data-q="q5">
            <div class="qz-opt__icon">📋</div>
            <div class="qz-opt__label">Mengerjakan soal latihan dan ujian tahun lalu</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q5]" value="latihan" style="display:none">
        </label>
        <label class="qz-opt" data-q="q5">
            <div class="qz-opt__icon">🧑‍🏫</div>
            <div class="qz-opt__label">Belajar bersama teman atau mengikuti tutor</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q5]" value="tutor" style="display:none">
        </label>
        <label class="qz-opt" data-q="q5">
            <div class="qz-opt__icon">📄</div>
            <div class="qz-opt__label">Menulis ulang catatan dari awal tanpa melihat buku</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q5]" value="tulis_ulang" style="display:none">
        </label>
        <label class="qz-opt" data-q="q5">
            <div class="qz-opt__icon">🗓️</div>
            <div class="qz-opt__label">Membuat jadwal belajar terstruktur per topik</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q5]" value="jadwal" style="display:none">
        </label>
    </div>
</div>

<!-- Q6 – Waktu Belajar -->
<div class="qz-step" data-step="6">
    <div class="qz-dimensi-badge">Waktu Belajar</div>
    <div class="qz-qcard">
        <span class="qz-step-badge">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="#2563eb"/></svg>
            Soal 6 dari 7
        </span>
        <h2 class="qz-qcard__q">Di waktu mana Anda merasa paling fokus dan produktif untuk belajar?</h2>
        <p class="qz-qcard__hint">Ritme alami tubuhmu mempengaruhi efektivitas belajar.</p>
    </div>
    <div class="qz-options">
        <label class="qz-opt" data-q="q6">
            <div class="qz-opt__icon">🌅</div>
            <div class="qz-opt__label">Pagi hari (sebelum jam 10)</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q6]" value="pagi" style="display:none">
        </label>
        <label class="qz-opt" data-q="q6">
            <div class="qz-opt__icon">☀️</div>
            <div class="qz-opt__label">Siang hari (jam 10–14)</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q6]" value="siang" style="display:none">
        </label>
        <label class="qz-opt" data-q="q6">
            <div class="qz-opt__icon">🌇</div>
            <div class="qz-opt__label">Sore hari (jam 14–18)</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q6]" value="sore" style="display:none">
        </label>
        <label class="qz-opt" data-q="q6">
            <div class="qz-opt__icon">🌙</div>
            <div class="qz-opt__label">Malam hari (setelah jam 18)</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q6]" value="malam" style="display:none">
        </label>
    </div>
</div>

<!-- Q7 – Tujuan Belajar -->
<div class="qz-step" data-step="7">
    <div class="qz-dimensi-badge">Tujuan Belajar</div>
    <div class="qz-qcard">
        <span class="qz-step-badge">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="#2563eb"/></svg>
            Soal 7 dari 7 — Terakhir!
        </span>
        <h2 class="qz-qcard__q">Apa target utama Anda saat sedang belajar?</h2>
        <p class="qz-qcard__hint">Soal terakhir! Tujuan belajarmu menentukan metode yang paling efektif.</p>
    </div>
    <div class="qz-options">
        <label class="qz-opt" data-q="q7">
            <div class="qz-opt__icon">📊</div>
            <div class="qz-opt__label">Lulus ujian dengan nilai tinggi</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q7]" value="ujian" style="display:none">
        </label>
        <label class="qz-opt" data-q="q7">
            <div class="qz-opt__icon">💡</div>
            <div class="qz-opt__label">Benar-benar memahami konsep secara mendalam</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q7]" value="pemahaman" style="display:none">
        </label>
        <label class="qz-opt" data-q="q7">
            <div class="qz-opt__icon">🛠️</div>
            <div class="qz-opt__label">Menguasai skill atau kemampuan praktis</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q7]" value="skill" style="display:none">
        </label>
        <label class="qz-opt" data-q="q7">
            <div class="qz-opt__icon">🧠</div>
            <div class="qz-opt__label">Menghafalkan fakta, istilah, dan rumus</div>
            <div class="qz-opt__radio"></div>
            <input type="radio" name="answers[q7]" value="hafal" style="display:none">
        </label>
    </div>
</div>

</form>
</div>
</div>

<div class="qz-bottom">
    <div class="qz-bottom__inner">
        <button class="qz-btn-next" id="btnNext" disabled>
            Selanjutnya
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                <path d="M3 8h10M9 4l4 4-4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <p class="qz-hint-pill">Jawabanmu membantu LearnFit menyesuaikan metode belajarmu.</p>
    </div>
</div>

<script>
(function () {
    var TOTAL   = 7;
    var current = 1;
    var answered = {};

    var progressBar = document.getElementById('progressBar');
    var pctLabel    = document.getElementById('pctLabel');
    var btnNext     = document.getElementById('btnNext');
    var form        = document.getElementById('quizForm');

    function updateProgress() {
        progressBar.style.width = (current / TOTAL * 100) + '%';
        pctLabel.textContent = current + ' / ' + TOTAL;
    }

    function getStep(n) {
        return document.querySelector('.qz-step[data-step="' + n + '"]');
    }

    function enableNext() {
        btnNext.disabled = !answered['q' + current];
    }

    // Klik opsi
    document.querySelectorAll('.qz-opt').forEach(function (opt) {
        opt.addEventListener('click', function () {
            var q = this.dataset.q;

            document.querySelectorAll('.qz-opt[data-q="' + q + '"]').forEach(function (o) {
                o.classList.remove('selected');
            });
            this.classList.add('selected');
            this.querySelector('input').checked = true;

            answered[q] = true;
            enableNext();
        });
    });

    // Tombol Selanjutnya / Submit
    btnNext.addEventListener('click', function () {
        if (!answered['q' + current]) return;

        if (current === TOTAL) {
            form.submit();
            return;
        }

        var curStep  = getStep(current);
        var nextStep = getStep(current + 1);
        curStep.classList.remove('active');
        current++;
        nextStep.classList.add('active');

        updateProgress();
        enableNext();
        window.scrollTo({ top: 0, behavior: 'smooth' });

        if (current === TOTAL) {
            btnNext.innerHTML = 'Lihat Hasilku <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        }
    });

    updateProgress();
    enableNext();
})();
</script>
@endsection