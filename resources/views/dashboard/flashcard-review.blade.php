@extends('layouts.app')
@section('content')

@php
    /** @var \App\Models\SesiBelajar $sesi */
    /** @var \Illuminate\Database\Eloquent\Collection $cards */
    /** @var array $stats */
@endphp

<div class="review-page">
    <div class="review-topbar">
        <a href="{{ route('sesi.index', ['metode' => 'active_recall']) }}" class="review-back">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8l4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Kembali ke Sesi
        </a>
        <div class="review-title">
            <h1>🃏 Mode Review</h1>
            <p>{{ $sesi->judul ?: 'Tanpa judul' }} · {{ $cards->count() }} kartu</p>
        </div>
        <div class="review-stats">
            <span class="review-stat" data-tone="ok">✓ {{ $stats['benar'] }}</span>
            <span class="review-stat" data-tone="err">✗ {{ $stats['salah'] }}</span>
            <span class="review-stat" data-tone="primary">{{ $stats['percent'] }}%</span>
        </div>
    </div>

    <div class="review-card-wrap">
        <div class="review-progress">
            <span id="reviewPosition">1</span> / <span id="reviewTotal">{{ $cards->count() }}</span>
            <div class="review-progress-bar"><div class="review-progress-fill" id="reviewProgressFill"></div></div>
        </div>

        <div class="review-card-stack" id="reviewCardStack">
            @foreach($cards as $i => $card)
            <div class="review-card" data-index="{{ $i }}" data-card-id="{{ $card->id }}" style="display: {{ $i === 0 ? 'block' : 'none' }}">
                <div class="review-card__inner" id="reviewCardInner{{ $i }}">
                    <div class="review-card__face review-card__face--front">
                        <p class="review-card__label">Pertanyaan</p>
                        <p class="review-card__q">{{ $card->pertanyaan }}</p>
                        <button type="button" class="review-card__flip" onclick="flipCard({{ $i }})">Lihat Jawaban ↻</button>
                    </div>
                    <div class="review-card__face review-card__face--back" style="display:none">
                        <p class="review-card__label">Jawaban</p>
                        <p class="review-card__a">{{ $card->jawaban }}</p>
                        <p class="review-card__ask">Apakah kamu mengingatnya dengan benar?</p>
                        <div class="review-card__actions">
                            <button type="button" class="review-btn review-btn--err" onclick="submitAnswer(this, {{ $card->id }}, 0)">✗ Salah</button>
                            <button type="button" class="review-btn review-btn--ok" onclick="submitAnswer(this, {{ $card->id }}, 1)">✓ Benar</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="review-controls">
            <button type="button" class="review-nav review-nav--prev" id="btnPrev" disabled onclick="navigateCard(-1)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> Sebelumnya</button>
            <span class="review-hint" id="reviewHint">Klik "Lihat Jawaban" untuk membalik kartu</span>
            <button type="button" class="review-nav review-nav--next" id="btnNext" onclick="navigateCard(1)">Selanjutnya →</button>
        </div>
    </div>

    @if($stats['total'] > 0)
    <div class="review-summary">
        <h2>📊 Ringkasan Review</h2>
        <div class="summary-stats">
            <div class="summary-stat"><span class="summary-stat__num">{{ $stats['total'] }}</span><span class="summary-stat__label">Total Review</span></div>
            <div class="summary-stat" data-tone="ok"><span class="summary-stat__num">{{ $stats['benar'] }}</span><span class="summary-stat__label">Benar</span></div>
            <div class="summary-stat" data-tone="err"><span class="summary-stat__num">{{ $stats['salah'] }}</span><span class="summary-stat__label">Salah</span></div>
            <div class="summary-stat" data-tone="primary"><span class="summary-stat__num">{{ $stats['percent'] }}%</span><span class="summary-stat__label">Akurasi</span></div>
        </div>
    </div>
    @endif
</div>

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.review-page{min-height:100vh;background:linear-gradient(135deg,#F5F3FF,#EFF6FF);font-family:'Plus Jakarta Sans',sans-serif;padding:1.5rem 2rem;display:flex;flex-direction:column;gap:1.5rem;max-width:920px;margin:0 auto;}
.review-topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;}
.review-back{display:inline-flex;align-items:center;gap:.4rem;color:#475569;text-decoration:none;font-size:.85rem;font-weight:600;padding:.5rem .8rem;border-radius:8px;background:#fff;border:1px solid #E2E8F0;}
.review-back:hover{background:#F8FAFC;}
.review-title h1{font-size:1.3rem;font-weight:800;color:#0F172A;letter-spacing:-.02em;}
.review-title p{font-size:.78rem;color:#64748B;margin-top:.1rem;}
.review-stats{display:flex;gap:.5rem;}
.review-stat{font-size:.78rem;font-weight:700;padding:.4rem .75rem;border-radius:99px;background:#fff;border:1px solid #E2E8F0;color:#0F172A;}
.review-stat[data-tone="ok"]{background:#DCFCE7;color:#15803D;border-color:#86EFAC;}
.review-stat[data-tone="err"]{background:#FEE2E2;color:#991B1B;border-color:#FCA5A5;}
.review-stat[data-tone="primary"]{background:#7C3AED;color:#fff;border-color:#7C3AED;}

.review-card-wrap{background:#fff;border-radius:20px;padding:2rem;box-shadow:0 10px 40px rgba(15,23,42,.08);}
.review-progress{text-align:center;margin-bottom:1.5rem;font-size:.78rem;color:#64748B;font-weight:600;}
.review-progress-bar{height:6px;background:#F1F5F9;border-radius:99px;overflow:hidden;margin-top:.5rem;}
.review-progress-fill{height:100%;background:linear-gradient(90deg,#7C3AED,#A78BFA);width:0%;transition:width .3s;}

.review-card-stack{position:relative;min-height:280px;display:flex;align-items:center;justify-content:center;}
.review-card{width:100%;max-width:560px;background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#fff;border-radius:18px;padding:2.5rem 2rem;text-align:center;box-shadow:0 14px 40px rgba(124,58,237,.3);transition:transform .3s;}
.review-card__label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;opacity:.75;margin-bottom:.75rem;}
.review-card__q{font-size:1.4rem;font-weight:700;line-height:1.4;margin-bottom:1.5rem;}
.review-card__a{font-size:1.15rem;line-height:1.55;margin-bottom:1rem;white-space:pre-wrap;background:rgba(255,255,255,.1);padding:1rem;border-radius:12px;}
.review-card__ask{font-size:.85rem;opacity:.9;margin-bottom:1.25rem;font-style:italic;}
.review-card__flip{background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);color:#fff;padding:.6rem 1.4rem;border-radius:10px;font-size:.85rem;font-weight:600;cursor:pointer;font-family:inherit;}
.review-card__flip:hover{background:rgba(255,255,255,.25);}

.review-card__actions{display:flex;gap:.75rem;justify-content:center;}
.review-btn{padding:.7rem 1.5rem;border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:inherit;transition:transform .15s,opacity .15s;}
.review-btn--ok{background:#15803D;color:#fff;}
.review-btn--err{background:#fff;color:#991B1B;}
.review-btn:hover{transform:translateY(-2px);opacity:.92;}

.review-controls{display:flex;align-items:center;justify-content:space-between;margin-top:1.5rem;gap:1rem;flex-wrap:wrap;}
.review-nav{padding:.6rem 1.2rem;background:#F8FAFC;color:#475569;border:1px solid #E2E8F0;border-radius:10px;font-size:.85rem;font-weight:600;cursor:pointer;font-family:inherit;}
.review-nav:hover:not(:disabled){background:#fff;border-color:#94A3B8;}
.review-nav:disabled{opacity:.4;cursor:not-allowed;}
.review-hint{font-size:.78rem;color:#94A3B8;font-style:italic;}

.review-summary{background:#fff;border-radius:16px;padding:1.5rem;box-shadow:0 4px 16px rgba(15,23,42,.05);}
.review-summary h2{font-size:1rem;font-weight:700;color:#0F172A;margin-bottom:1rem;}
.summary-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;}
.summary-stat{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:1rem;text-align:center;display:flex;flex-direction:column;gap:.3rem;}
.summary-stat[data-tone="ok"]{background:#F0FDF4;border-color:#86EFAC;}
.summary-stat[data-tone="err"]{background:#FEF2F2;border-color:#FCA5A5;}
.summary-stat[data-tone="primary"]{background:#F5F3FF;border-color:#C4B5FD;}
.summary-stat__num{font-size:1.5rem;font-weight:800;color:#0F172A;}
.summary-stat__label{font-size:.72rem;color:#64748B;font-weight:600;}
@media(max-width:600px){.summary-stats{grid-template-columns:1fr 1fr;}}
</style>

<script>
const cards = document.querySelectorAll('.review-card');
const total = cards.length;
let current = 0;

function showCard(i) {
    cards.forEach((c, idx) => c.style.display = idx === i ? 'block' : 'none');
    document.getElementById('reviewPosition').textContent = i + 1;
    document.getElementById('reviewProgressFill').style.width = ((i + 1) / total * 100) + '%';
    document.getElementById('btnPrev').disabled = i === 0;
    document.getElementById('btnNext').disabled = i === total - 1;
    document.getElementById('reviewHint').textContent = i === total - 1
        ? 'Kartu terakhir — review selesai setelah ini'
        : 'Klik "Lihat Jawaban" untuk membalik kartu';
}

function navigateCard(delta) {
    const next = current + delta;
    if (next >= 0 && next < total) {
        current = next;
        showCard(current);
    }
}

function flipCard(i) {
    const inner = document.getElementById('reviewCardInner' + i);
    const front = inner.querySelector('.review-card__face--front');
    const back  = inner.querySelector('.review-card__face--back');
    if (front.style.display !== 'none') {
        front.style.display = 'none';
        back.style.display = 'block';
    } else {
        front.style.display = 'block';
        back.style.display = 'none';
    }
}

async function submitAnswer(btn, cardId, benar) {
    const originalText = btn.innerHTML;
    btn.innerHTML = '...';
    btn.disabled = true;

    try {
        const response = await fetch("{{ route('flashcard.answer', $sesi) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                flashcard_id: cardId,
                benar: benar
            })
        });

        if (response.ok) {
            if (current < total - 1) {
                navigateCard(1);
            } else {
                window.location.reload();
            }
        } else {
            alert('Gagal menyimpan jawaban.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    } catch (e) {
        alert('Terjadi kesalahan.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

showCard(0);
</script>
@endsection
