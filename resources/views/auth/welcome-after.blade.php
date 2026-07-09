@extends('layouts.app')

@section('title', 'Selamat Bergabung — LearnFit')
@section('meta_description', 'Akun LearnFit berhasil dibuat. Mulai perjalanan belajarmu dengan mengikuti quiz gaya belajar.')

@section('content')
    {{-- Minimal navbar (logo only) --}}
    <nav class="public-nav">
        <div class="container-page flex h-[68px] items-center">
            <a href="{{ route('home') }}" class="public-nav__brand">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" class="shrink-0">
                    <rect width="28" height="28" rx="8" fill="#2563EB"/>
                    <path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="font-display text-[22px] font-bold tracking-tight text-ink-900">LearnFit</span>
            </a>
        </div>
    </nav>

    <main class="public-page">
        <div class="public-page__body">

            {{-- LEFT COLUMN --}}
            <div class="public-col-left">
                <span class="chip" data-animate style="--delay:0ms">
                    <x-icon name="star" style="solid" class="h-3 w-3 text-amber-400" />
                    Akun berhasil dibuat
                </span>

                <h1 class="font-display text-[clamp(2rem,4vw,3rem)] font-extrabold leading-[1.1] tracking-tight text-ink-900"
                    data-animate style="--delay:80ms">
                    Selamat<br>Bergabung!
                </h1>
                <p class="text-[14px] leading-relaxed text-ink-500"
                   data-animate style="--delay:140ms">
                    Langkah awal menuju cara belajar yang lebih cerdas dan efektif.
                </p>

                {{-- Recommend card --}}
                <div class="rec-card" data-animate style="--delay:200ms">
                    <div class="rec-card__top">
                        <span class="badge-soft">Direkomendasikan</span>
                        <span class="rec-card__time">
                            <x-icon name="clock" class="h-3 w-3" />
                            5 Menit
                        </span>
                    </div>
                    <h2 class="rec-card__title">Cari Metode Belajarmu</h2>
                    <p class="rec-card__desc">Ambil tes singkat untuk menemukan gaya belajar yang paling sesuai dengan kepribadian dan kebutuhan unikmu.</p>
                    <div class="rec-card__actions">
                        <a href="{{ route('quiz') }}" class="btn-primary">
                            Mulai Quiz
                        </a>
                        <a href="{{ route('dashboard.siswa') }}" class="btn-ghost no-underline">Lakukan Nanti</a>
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="quick-links mt-auto" data-animate style="--delay:270ms">
                    <a href="{{ route('siswa.kelas.index') }}" class="quick-link">
                        <div class="quick-link__icon bg-brand-50">
                            <x-icon name="book-open" class="h-[18px] w-[18px] text-brand-600" />
                        </div>
                        <div>
                            <p class="quick-link__title">Kelas Saya</p>
                            <p class="quick-link__sub">Akses materi &amp; tugas</p>
                        </div>
                        <x-icon name="chevron-right" class="quick-link__arr h-3.5 w-3.5 text-ink-300" />
                    </a>
                    <a href="{{ route('sesi.index') }}" class="quick-link">
                        <div class="quick-link__icon bg-emerald-50">
                            <x-icon name="clock" class="h-[18px] w-[18px] text-emerald-600" />
                        </div>
                        <div>
                            <p class="quick-link__title">Sesi Belajar</p>
                            <p class="quick-link__sub">Mulai waktu fokusmu</p>
                        </div>
                        <x-icon name="chevron-right" class="quick-link__arr h-3.5 w-3.5 text-ink-300" />
                    </a>
                    <a href="{{ route('catatan.index') }}" class="quick-link">
                        <div class="quick-link__icon bg-orange-50">
                            <x-icon name="document-text" class="h-[18px] w-[18px] text-orange-500" />
                        </div>
                        <div>
                            <p class="quick-link__title">Catatan Belajar</p>
                            <p class="quick-link__sub">Jurnal &amp; riwayat</p>
                        </div>
                        <x-icon name="chevron-right" class="quick-link__arr h-3.5 w-3.5 text-ink-300" />
                    </a>
                    <a href="{{ route('notebook.index') }}" class="quick-link">
                        <div class="quick-link__icon bg-purple-50">
                            <x-icon name="book-open" class="h-[18px] w-[18px] text-purple-600" />
                        </div>
                        <div>
                            <p class="quick-link__title">Riwayat Sesi</p>
                            <p class="quick-link__sub">Notebook & evaluasi</p>
                        </div>
                        <x-icon name="chevron-right" class="quick-link__arr h-3.5 w-3.5 text-ink-300" />
                    </a>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="public-col-right">
                {{-- Illustration --}}
                <div class="illus-card" data-animate="fade-right" style="--delay:100ms">
                    <span class="illus-card__blob illus-card__blob--1"></span>
                    <span class="illus-card__blob illus-card__blob--2"></span>
                    <img src="{{ asset('images/welcome-illustration.png') }}" alt="Ilustrasi Belajar" class="relative z-10 w-full max-w-[280px]" style="mix-blend-mode: darken;">
                    <span class="badge-float badge-float--tl">
                        <x-icon name="rocket" class="h-3.5 w-3.5 text-brand-500" />
                        Mulai Belajar Hari Ini!
                    </span>
                    <span class="badge-float badge-float--br">
                        <x-icon name="sparkles" class="h-3.5 w-3.5 text-amber-500" />
                        Gaya Belajar Personal
                    </span>
                </div>

                {{-- Steps --}}
                <div class="steps-card mt-auto" data-animate="fade-right" style="--delay:200ms">
                    <p class="steps-card__head">Mulai dari sini 👇</p>
                    <div class="step">
                        <span class="step__dot step__dot--done">
                            <x-icon name="check" class="h-2.5 w-2.5 text-white" />
                        </span>
                        <div>
                            <p class="step__t step__t--done">Buat akun</p>
                            <p class="step__s">Selesai!</p>
                        </div>
                    </div>
                    <div class="step__line"></div>
                    <div class="step">
                        <span class="step__dot step__dot--active">2</span>
                        <div>
                            <p class="step__t step__t--active">Temukan gaya belajar</p>
                            <p class="step__s">Ikuti quiz singkat 5 menit</p>
                        </div>
                    </div>
                    <div class="step__line"></div>
                    <div class="step">
                        <span class="step__dot">3</span>
                        <div>
                            <p class="step__t">Mulai belajar</p>
                            <p class="step__s">Akses ratusan materi</p>
                        </div>
                    </div>
                </div>

            </div> {{-- End public-col-right --}}

        </div> {{-- End public-page__body --}}

        {{-- Tip text (centered vertically and horizontally) --}}
        <div id="tip-text-anim" class="-mt-4 mb-6 flex justify-center text-center px-4" data-animate="fade-up" style="--delay:300ms">
            <p class="text-[13px] text-ink-500 max-w-[600px] flex items-start sm:items-center justify-center gap-1.5">
                <x-icon name="information-circle" class="h-4 w-4 flex-shrink-0 text-brand-600" />
                <span><strong>Tips:</strong> Selesaikan quiz gaya belajar untuk mendapatkan rekomendasi materi yang personal.</span>
            </p>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    const tip = document.getElementById('tip-text-anim');
                    if (tip) tip.classList.add('is-visible');
                }, 50);
            });
        </script>
    </main>

    @include('partials._public_footer')

    @vite(['resources/js/views/public.js'])
@endsection
