@extends('layouts.app')

@section('content')

@php
$methods = [
    'pomodoro' => [
        'name'        => 'Pomodoro Technique',
        'tagline'     => 'Fokus penuh dalam sesi pendek yang terstruktur.',
        'desc'        => 'Kamu bekerja paling baik dalam sprint terkonsentrasi. Teknik Pomodoro membagi sesi belajar menjadi 25 menit fokus penuh diikuti 5 menit istirahat, menjaga otakmu tetap segar dan terhindar dari burnout.',
        'color_main'  => '#2563EB', 'color_soft' => '#EFF6FF', 'color_mid' => '#DBEAFE',
        'color_bar'   => 'linear-gradient(90deg,#1d4ed8,#60a5fa)', 'color_dark' => '#1e40af',
        'badge_color' => '#1e40af', 'badge_bg' => '#EFF6FF', 'icon_emoji' => '⏱️', 'bar_color' => '#2563EB',
        'advantages'  => [
            ['icon'=>'⚡','title'=>'Anti-Burnout','desc'=>'Istirahat terstruktur mencegah kelelahan mental setelah belajar lama.'],
            ['icon'=>'📈','title'=>'Produktivitas Terukur','desc'=>'Kamu bisa melacak berapa "tomat" yang sudah diselesaikan per hari.'],
        ],
        'steps' => [
            ['num'=>'01','icon'=>'⏱️','title'=>'Set Timer 25 Menit','desc'=>'Pilih satu tugas belajar spesifik, singkirkan semua distraksi, dan mulai timer.'],
            ['num'=>'02','icon'=>'🎯','title'=>'Belajar Penuh Fokus','desc'=>'Selama 25 menit, hanya fokus pada satu topik. Jika ada gangguan, catat dan abaikan dulu.'],
            ['num'=>'03','icon'=>'☕','title'=>'Istirahat 5 Menit','desc'=>'Berdiri, regangkan badan, atau minum air. Hindari layar selama jeda singkat ini.'],
        ],
    ],
    'active_recall' => [
        'name'        => 'Active Recall',
        'tagline'     => 'Uji dirimu sendiri untuk memperkuat memori jangka panjang.',
        'desc'        => 'Kamu memproses informasi paling dalam saat otak dipaksa mengambil kembali memori tanpa bantuan. Active Recall terbukti secara ilmiah meningkatkan retensi hingga 3x lebih baik dibanding membaca ulang.',
        'color_main'  => '#2E7D32', 'color_soft' => '#F0FDF4', 'color_mid' => '#BBF7D0',
        'color_bar'   => 'linear-gradient(90deg,#166534,#4ade80)', 'color_dark' => '#166534',
        'badge_color' => '#166534', 'badge_bg' => '#DCFCE7', 'icon_emoji' => '🧠', 'bar_color' => '#2E7D32',
        'advantages'  => [
            ['icon'=>'⚡','title'=>'Retensi Cepat','desc'=>'Ingatan bertahan 3x lebih lama dibanding metode membaca ulang biasa.'],
            ['icon'=>'🔍','title'=>'Identifikasi Gap','desc'=>'Menemukan bagian materi yang belum benar-benar dikuasai dengan akurat.'],
        ],
        'steps' => [
            ['num'=>'01','icon'=>'📖','title'=>'Pelajari Materi','desc'=>'Pahami konsep dasar secara mendalam dari bacaan. Jangan hanya menghafal, tapi mengertilah konteksnya.'],
            ['num'=>'02','icon'=>'🗒️','title'=>'Buat Flashcard','desc'=>'Tulis pertanyaan dan jawaban di sisi berbeda. Gunakan bahasa yang sederhana dan mudah diingat.'],
            ['num'=>'03','icon'=>'⏰','title'=>'Kuis Berkala','desc'=>'Lakukan tes mandiri secara berkala untuk memperkuat ingatan jangka panjang.'],
        ],
    ],
    'feynman' => [
        'name'        => 'Feynman Technique',
        'tagline'     => 'Ajarkan konsep sederhana seolah kamu gurunya.',
        'desc'        => 'Kamu memahami sesuatu paling dalam saat menjelaskannya ke orang lain. Teknik Feynman mengharuskan kamu menjelaskan konsep dengan bahasa sesederhana mungkin, sehingga celah pemahamanmu langsung terungkap.',
        'color_main'  => '#D32F2F', 'color_soft' => '#FFF5F5', 'color_mid' => '#FECACA',
        'color_bar'   => 'linear-gradient(90deg,#b91c1c,#f87171)', 'color_dark' => '#991b1b',
        'badge_color' => '#991b1b', 'badge_bg' => '#FEE2E2', 'icon_emoji' => '🏫', 'bar_color' => '#D32F2F',
        'advantages'  => [
            ['icon'=>'💡','title'=>'Pemahaman Mendalam','desc'=>'Kamu tidak bisa menjelaskan sesuatu yang tidak benar-benar kamu pahami.'],
            ['icon'=>'🗣️','title'=>'Komunikasi Efektif','desc'=>'Melatih kemampuan menjelaskan konsep kompleks dengan cara yang mudah dicerna.'],
        ],
        'steps' => [
            ['num'=>'01','icon'=>'📖','title'=>'Pelajari Konsepnya','desc'=>'Baca dan pahami topik yang ingin dikuasai. Catat poin-poin penting dengan bahasamu sendiri.'],
            ['num'=>'02','icon'=>'🧑‍🏫','title'=>'Jelaskan seperti Guru','desc'=>'Coba jelaskan konsep itu seolah kamu mengajar anak SD. Gunakan analogi dan contoh nyata.'],
            ['num'=>'03','icon'=>'🔄','title'=>'Temukan dan Perbaiki','desc'=>'Di mana kamu gagap atau bingung? Itu titik lemahmu. Kembali ke sumber dan pelajari ulang bagian itu.'],
        ],
    ],
    'blurting' => [
        'name'        => 'Blurting Method',
        'tagline'     => 'Tuangkan semua yang kamu tahu di kertas kosong.',
        'desc'        => 'Kamu paling efektif saat menulis dan merangkum secara mandiri. Metode Blurting melatih otakmu untuk mengambil informasi secara aktif: baca materi, tutup buku, lalu tulis semua yang kamu ingat di kertas kosong.',
        'color_main'  => '#E65100', 'color_soft' => '#FFF8F0', 'color_mid' => '#FED7AA',
        'color_bar'   => 'linear-gradient(90deg,#c2410c,#fb923c)', 'color_dark' => '#9a3412',
        'badge_color' => '#9a3412', 'badge_bg' => '#FFEDD5', 'icon_emoji' => '✍️', 'bar_color' => '#E65100',
        'advantages'  => [
            ['icon'=>'✍️','title'=>'Aktif & Mandiri','desc'=>'Menulis sendiri jauh lebih efektif daripada membaca ulang secara pasif.'],
            ['icon'=>'🎯','title'=>'Temukan Celah','desc'=>'Bagian yang tidak bisa kamu tulis adalah bagian yang perlu dipelajari ulang.'],
        ],
        'steps' => [
            ['num'=>'01','icon'=>'📚','title'=>'Baca Materi Sekali','desc'=>'Baca topik yang ingin dipelajari secara seksama. Fokus pada pemahaman, bukan hafalan kata per kata.'],
            ['num'=>'02','icon'=>'📄','title'=>'Tutup Buku, Blurt!','desc'=>'Ambil kertas kosong dan tulis semua yang kamu ingat tanpa melihat sumber. Jangan sensor dirimu.'],
            ['num'=>'03','icon'=>'🔍','title'=>'Bandingkan dan Ulang','desc'=>'Buka buku kembali, temukan apa yang terlewat, lalu pelajari bagian itu lebih mendalam.'],
        ],
    ],
];

$resultKey = $result ?? 'active_recall';
$m         = $methods[$resultKey] ?? $methods['active_recall'];

// Normalisasi scores agar selalu integer
// $scores sudah dikirim dari controller sebagai array
$normalizedScores = [
    'pomodoro'      => (int) ($scores['pomodoro']      ?? 0),
    'active_recall' => (int) ($scores['active_recall'] ?? 0),
    'feynman'       => (int) ($scores['feynman']       ?? 0),
    'blurting'      => (int) ($scores['blurting']      ?? 0),
];

$totalScore = array_sum($normalizedScores);

$scoreBreakdown = [
    ['key'=>'pomodoro',      'label'=>'Pomodoro',          'color'=>$methods['pomodoro']['bar_color'],      'score'=>$normalizedScores['pomodoro']],
    ['key'=>'active_recall', 'label'=>'Active Recall',     'color'=>$methods['active_recall']['bar_color'], 'score'=>$normalizedScores['active_recall']],
    ['key'=>'feynman',       'label'=>'Feynman Technique', 'color'=>$methods['feynman']['bar_color'],       'score'=>$normalizedScores['feynman']],
    ['key'=>'blurting',      'label'=>'Blurting',          'color'=>$methods['blurting']['bar_color'],      'score'=>$normalizedScores['blurting']],
];
@endphp

<style>
:root {
    --color-main:  {{ $m['color_main'] }};
    --color-soft:  {{ $m['color_soft'] }};
    --color-mid:   {{ $m['color_mid'] }};
    --color-bar:   {{ $m['color_bar'] }};
    --color-dark:  {{ $m['color_dark'] }};
    --badge-color: {{ $m['badge_color'] }};
    --badge-bg:    {{ $m['badge_bg'] }};
}
*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
.rs-nav { position: sticky; top: 0; z-index: 100; background: rgba(255,255,255,.95); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid #e2e8f0; }
.rs-nav__inner { display: flex; align-items: center; justify-content: space-between; height: 64px; max-width: 860px; margin: 0 auto; padding: 0 24px; }
.rs-nav__brand { display: flex; align-items: center; gap: 9px; text-decoration: none; }
.rs-nav__name  { font-size: 18px; font-weight: 700; color: #0f172a; }
.rs-btn-share { display: inline-flex; align-items: center; gap: .4rem; background: #f1f5f9; color: #475569; font-size: .78rem; font-weight: 600; padding: .4rem .85rem; border-radius: 99px; border: none; cursor: pointer; transition: background .2s, transform .15s; }
.rs-btn-share:hover { background: #e2e8f0; transform: translateY(-1px); }
.rs-page { background: #f8fafc; min-height: calc(100vh - 64px); padding-block: 2rem 4rem; }
.rs-wrap { max-width: 860px; margin: 0 auto; padding: 0 24px; }
.rs-header { text-align: center; margin-bottom: 1.5rem; }
.rs-header__label { font-size: .8rem; color: #94a3b8; margin-bottom: .3rem; }
.rs-header__title { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
.rs-banner { position: relative; border-radius: 20px; overflow: hidden; height: 160px; margin-bottom: 1.25rem; background: var(--color-soft); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(15,23,42,.08); }
.rs-banner__blob { position: absolute; border-radius: 50%; opacity: .15; }
.rs-banner__blob--1 { width:160px;height:160px;background:var(--color-main);top:-50px;right:-40px; }
.rs-banner__blob--2 { width:100px;height:100px;background:var(--color-main);bottom:-30px;left:-20px; }
.rs-banner__icon { position: relative; z-index: 1; width: 72px; height: 72px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; box-shadow: 0 4px 16px rgba(15,23,42,.12); }
.rs-banner__badge { position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,.9); border-radius: 20px; padding: .25rem .7rem; font-size: .7rem; font-weight: 600; color: #0f172a; }
.rs-method-name { text-align: center; margin-bottom: 1.5rem; }
.rs-method-name h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: .2rem; }
.rs-method-name p  { font-size: .82rem; color: #94a3b8; }
.rs-score-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem 1.4rem; margin-bottom: 1.25rem; box-shadow: 0 2px 10px rgba(15,23,42,.05); }
.rs-score-card__title { font-size: .78rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: .05em; }
.rs-score-row { margin-bottom: .85rem; }
.rs-score-row:last-child { margin-bottom: 0; }
.rs-score-row__meta { display: flex; align-items: center; gap: .3rem; margin-bottom: .35rem; }
.rs-score-row__star { color: var(--color-main); font-size: .8rem; }
.rs-score-row__label { flex: 1; font-size: .8rem; color: #64748b; }
.rs-score-row__label.winner { font-weight: 700; color: var(--color-main); }
.rs-score-row__pts { font-size: .75rem; color: #94a3b8; }
.rs-score-row__pts.winner { font-weight: 700; color: var(--color-main); }
.rs-bar-track { height: 5px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
.rs-bar-fill { height: 100%; border-radius: 99px; transition: width 1s cubic-bezier(.4,0,.2,1) .3s; }
.rs-section-title { font-size: .78rem; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .85rem; }
.rs-step-list { display: flex; flex-direction: column; gap: .75rem; margin-bottom: 1.5rem; }
.rs-step-item { display: flex; align-items: flex-start; gap: 1rem; background: #fff; border: 1px solid #f1f5f9; border-radius: 14px; padding: 1rem 1.1rem; box-shadow: 0 1px 6px rgba(15,23,42,.04); transition: box-shadow .2s, transform .2s, border-color .2s; }
.rs-step-item:hover { box-shadow: 0 4px 16px rgba(15,23,42,.08); transform: translateY(-2px); border-color: var(--color-mid); }
.rs-step-num { width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0; background: var(--color-soft); border: 1px solid var(--color-mid); display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 700; color: var(--color-main); }
.rs-step-body h3 { font-size: .88rem; font-weight: 700; color: #0f172a; margin-bottom: .2rem; }
.rs-step-body p  { font-size: .78rem; color: #64748b; line-height: 1.55; }
.rs-adv-card { background: #0f172a; border-radius: 16px; padding: 1.25rem 1.4rem; margin-bottom: 1.5rem; box-shadow: 0 4px 24px rgba(15,23,42,.18); }
.rs-adv-card__head { font-size: .65rem; font-weight: 700; letter-spacing: .1em; color: #64748b; text-transform: uppercase; margin-bottom: 1rem; display: flex; align-items: center; gap: .4rem; }
.rs-adv-row { display: flex; align-items: flex-start; gap: .75rem; margin-bottom: .85rem; }
.rs-adv-row:last-of-type { margin-bottom: 0; }
.rs-adv-row__icon { font-size: 1.1rem; flex-shrink: 0; margin-top: .05rem; }
.rs-adv-row__title { font-size: .82rem; font-weight: 700; color: #f1f5f9; margin-bottom: .2rem; }
.rs-adv-row__desc  { font-size: .72rem; color: #94a3b8; line-height: 1.5; }
.rs-cta { display: flex; flex-direction: column; gap: .65rem; }
.rs-btn-primary { display: flex; align-items: center; justify-content: center; gap: .5rem; background: var(--color-main); color: #fff; font-size: .92rem; font-weight: 600; padding: .9rem 1.5rem; border-radius: 99px; text-decoration: none; border: none; cursor: pointer; transition: filter .2s, transform .15s, box-shadow .2s; box-shadow: 0 4px 14px color-mix(in srgb, var(--color-main) 35%, transparent); }
.rs-btn-primary:hover { filter: brightness(1.1); transform: translateY(-2px); }
.rs-btn-secondary { display: flex; align-items: center; justify-content: center; gap: .5rem; background: #fff; color: #475569; font-size: .88rem; font-weight: 600; padding: .85rem 1.5rem; border-radius: 99px; text-decoration: none; border: 1.5px solid #e2e8f0; cursor: pointer; transition: border-color .2s, color .2s, transform .15s; }
.rs-btn-secondary:hover { border-color: #94a3b8; color: #0f172a; transform: translateY(-1px); }
.rs-footer-badge { text-align: center; padding-top: 2rem; color: #94a3b8; font-size: .72rem; }
[data-rs] { opacity: 0; transform: translateY(18px); transition: opacity .5s ease, transform .5s ease; transition-delay: var(--delay, 0ms); }
[data-rs].visible { opacity: 1; transform: none; }
@media(max-width:640px){ .rs-banner { height: 130px; } .rs-method-name h1 { font-size: 1.4rem; } }
</style>

<nav class="rs-nav">
    <div class="rs-nav__inner">
        <a href="/" class="rs-nav__brand">
            <svg width="26" height="26" viewBox="0 0 28 28" fill="none"><rect width="28" height="28" rx="8" fill="#2563EB"/><path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
            <span class="rs-nav__name">LearnFit</span>
        </a>
        <button class="rs-btn-share" onclick="shareResult()">
            <svg width="13" height="13" viewBox="0 0 14 14" fill="none"><circle cx="11" cy="2.5" r="1.8" stroke="currentColor" stroke-width="1.3"/><circle cx="2.5" cy="7" r="1.8" stroke="currentColor" stroke-width="1.3"/><circle cx="11" cy="11.5" r="1.8" stroke="currentColor" stroke-width="1.3"/><path d="M4.2 6.1 L9.3 3.4 M4.2 7.9 L9.3 10.6" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
            Bagikan
        </button>
    </div>
</nav>

<div class="rs-page">
<div class="rs-wrap">
    <div class="rs-header" data-rs style="--delay:0ms">
        <p class="rs-header__label">Metode Belajar</p>
        <h2 class="rs-header__title">Hasil Quizmu</h2>
    </div>

    <div class="rs-banner" data-rs style="--delay:60ms">
        <div class="rs-banner__blob rs-banner__blob--1"></div>
        <div class="rs-banner__blob rs-banner__blob--2"></div>
        <div class="rs-banner__icon">{{ $m['icon_emoji'] }}</div>
        <div class="rs-banner__badge">✦ Rekomendasimu</div>
    </div>

    <div class="rs-method-name" data-rs style="--delay:100ms">
        <h1>{{ $m['name'] }}</h1>
        <p>{{ $m['tagline'] }}</p>
    </div>

    <div class="rs-score-card" data-rs style="--delay:150ms">
        <p class="rs-score-card__title">Skor Kesesuaian</p>
        @foreach($scoreBreakdown as $item)
        @php
            $isWinner = ($item['key'] === $resultKey);
            $pct = $totalScore > 0 ? round(($item['score'] / $totalScore) * 100) : 0;
        @endphp
        <div class="rs-score-row">
            <div class="rs-score-row__meta">
                @if($isWinner)<span class="rs-score-row__star">★</span>@endif
                <span class="rs-score-row__label {{ $isWinner ? 'winner' : '' }}">{{ $item['label'] }}</span>
                <span class="rs-score-row__pts {{ $isWinner ? 'winner' : '' }}">{{ $item['score'] }} poin</span>
            </div>
            <div class="rs-bar-track">
                <div class="rs-bar-fill"
                     style="width:0%; background:{{ $isWinner ? $item['color'] : $item['color'].'99' }};"
                     data-target="{{ $pct }}">
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <p class="rs-section-title" data-rs style="--delay:200ms">Cara Kerjanya</p>
    <div class="rs-step-list">
        @foreach($m['steps'] as $i => $step)
        <div class="rs-step-item" data-rs style="--delay:{{ 230 + $i * 60 }}ms">
            <div class="rs-step-num">{{ $i + 1 }}</div>
            <div class="rs-step-body">
                <h3>{{ $step['icon'] }} {{ $step['title'] }}</h3>
                <p>{{ $step['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="rs-adv-card" data-rs style="--delay:420ms">
        <div class="rs-adv-card__head">
            <svg width="13" height="13" viewBox="0 0 14 14" fill="none"><rect x="1" y="1" width="12" height="12" rx="2" stroke="#64748b" stroke-width="1.2"/><path d="M4 10 V7 M7 10 V4 M10 10 V6" stroke="#64748b" stroke-width="1.3" stroke-linecap="round"/></svg>
            Analisis Keunggulan
        </div>
        @foreach($m['advantages'] as $adv)
        <div class="rs-adv-row">
            <div class="rs-adv-row__icon">{{ $adv['icon'] }}</div>
            <div><p class="rs-adv-row__title">{{ $adv['title'] }}</p><p class="rs-adv-row__desc">{{ $adv['desc'] }}</p></div>
        </div>
        @endforeach
    </div>

    <div class="rs-cta" data-rs style="--delay:480ms">
        <a href="{{ route('dashboard.siswa') }}" class="rs-btn-primary">
            Mulai Belajar
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="{{ route('quiz.retake') }}" class="rs-btn-secondary">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M13 8A5 5 0 1 1 8 3M13 3v3h-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Ikut Quiz Ulang
        </a>
    </div>

    <div class="rs-footer-badge" data-rs style="--delay:540ms">
        <p>Berdasarkan Hasil Analisis LearnFit AI<br>Diperbarui: {{ now()->format('d M Y') }} &bull; Versi Algoritma v2.4</p>
    </div>
</div>
</div>

<script>
(function () {
    var els = document.querySelectorAll('[data-rs]');
    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.05 });
    els.forEach(function (el) { obs.observe(el); });
    setTimeout(function () {
        document.querySelectorAll('.rs-bar-fill').forEach(function (bar) {
            bar.style.width = (bar.dataset.target || '0') + '%';
        });
    }, 500);
})();

function shareResult() {
    var name = '{{ $m["name"] }}';
    var txt  = 'Metode belajar terbaikku adalah ' + name + ' berdasarkan analisis LearnFit AI! Temukan metode belajarmu di LearnFit.';
    if (navigator.share) {
        navigator.share({ title: 'Hasil Quiz LearnFit', text: txt, url: window.location.href });
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(txt + ' ' + window.location.href);
        alert('Tautan dan hasil disalin ke clipboard!');
    }
}
</script>

@endsection