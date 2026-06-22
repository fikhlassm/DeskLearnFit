@extends('layouts.app')

@section('title', 'Selamat Bergabung — LearnFit')
@section('meta_description', 'Akun LearnFit berhasil dibuat. Mulai perjalanan belajarmu dengan mengikuti quiz gaya belajar.')

@section('content')
    {{-- Minimal navbar (logo only) --}}
    <nav class="public-nav">
        <div class="container-page flex h-[68px] items-center">
            <a href="{{ route('home') }}" class="public-nav__brand">
                <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand-600 text-white">
                    <svg viewBox="0 0 28 28" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M8 10h12M8 14h8M8 18h10"/>
                    </svg>
                </span>
                <span class="public-nav__brand-name">LearnFit</span>
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
                <p class="max-w-[400px] text-[14px] leading-relaxed text-ink-500"
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
                            <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                        </a>
                        <a href="{{ route('dashboard.siswa') }}" class="btn-ghost no-underline">Lakukan Nanti</a>
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="quick-links" data-animate style="--delay:270ms">
                    <a href="#" class="quick-link">
                        <div class="quick-link__icon bg-brand-50">
                            <x-icon name="book-open" class="h-[18px] w-[18px] text-brand-600" />
                        </div>
                        <div>
                            <p class="quick-link__title">Materi Belajar</p>
                            <p class="quick-link__sub">200+ topik</p>
                        </div>
                        <x-icon name="chevron-right" class="quick-link__arr h-3.5 w-3.5 text-ink-300" />
                    </a>
                    <a href="#" class="quick-link">
                        <div class="quick-link__icon bg-emerald-50">
                            <x-icon name="clock" class="h-[18px] w-[18px] text-emerald-600" />
                        </div>
                        <div>
                            <p class="quick-link__title">Jadwal Belajar</p>
                            <p class="quick-link__sub">Atur waktumu</p>
                        </div>
                        <x-icon name="chevron-right" class="quick-link__arr h-3.5 w-3.5 text-ink-300" />
                    </a>
                    <a href="#" class="quick-link">
                        <div class="quick-link__icon bg-orange-50">
                            <x-icon name="star" style="solid" class="h-[18px] w-[18px] text-orange-500" />
                        </div>
                        <div>
                            <p class="quick-link__title">Pencapaian</p>
                            <p class="quick-link__sub">Badge &amp; sertifikat</p>
                        </div>
                        <x-icon name="chevron-right" class="quick-link__arr h-3.5 w-3.5 text-ink-300" />
                    </a>
                    <a href="#" class="quick-link">
                        <div class="quick-link__icon bg-purple-50">
                            <x-icon name="rectangle-stack" class="h-[18px] w-[18px] text-purple-600" />
                        </div>
                        <div>
                            <p class="quick-link__title">Forum Diskusi</p>
                            <p class="quick-link__sub">Tanya &amp; jawab</p>
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
                    <svg class="illus-card__fig" viewBox="0 0 260 300" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="85" y="155" width="90" height="95" rx="20" fill="#4EB89D"/>
                        <rect x="112" y="140" width="36" height="24" rx="8" fill="#F5C5A3"/>
                        <ellipse cx="130" cy="112" rx="38" ry="38" fill="#F5C5A3"/>
                        <path d="M92 102c0-28 76-36 76-9" fill="#3D2B1F"/>
                        <ellipse cx="130" cy="78" rx="38" ry="17" fill="#3D2B1F"/>
                        <ellipse cx="117" cy="112" rx="5" ry="5.5" fill="#3D2B1F"/>
                        <ellipse cx="143" cy="112" rx="5" ry="5.5" fill="#3D2B1F"/>
                        <circle cx="119" cy="110" r="1.5" fill="white"/>
                        <circle cx="145" cy="110" r="1.5" fill="white"/>
                        <path d="M118 124 q12 9 24 0" stroke="#C87137" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                        <path d="M85 165 Q55 142 48 108" stroke="#4EB89D" stroke-width="22" stroke-linecap="round"/>
                        <ellipse cx="46" cy="100" rx="12" ry="13" fill="#F5C5A3"/>
                        <path d="M175 165 Q205 142 212 108" stroke="#4EB89D" stroke-width="22" stroke-linecap="round"/>
                        <ellipse cx="214" cy="100" rx="12" ry="13" fill="#F5C5A3"/>
                        <rect x="85" y="218" width="42" height="60" rx="10" fill="#2C3E6B"/>
                        <rect x="133" y="218" width="42" height="60" rx="10" fill="#2C3E6B"/>
                        <rect x="80" y="270" width="50" height="16" rx="8" fill="#1E2A45"/>
                        <rect x="130" y="270" width="50" height="16" rx="8" fill="#1E2A45"/>
                        <path d="M112 155 l18-14 18 14" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                    <span class="badge-float badge-float--tl">
                        <x-icon name="star" style="solid" class="h-3 w-3 text-amber-400" />
                        Mulai Belajar Hari Ini!
                    </span>
                    <span class="badge-float badge-float--br">
                        <x-icon name="check-circle" style="solid" class="h-3.5 w-3.5 text-brand-600" />
                        Akun berhasil dibuat
                    </span>
                </div>

                {{-- Steps --}}
                <div class="steps-card" data-animate="fade-right" style="--delay:200ms">
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

                {{-- Tip box --}}
                <div class="tip-box" data-animate="fade-right" style="--delay:300ms">
                    <x-icon name="information-circle" class="mt-px h-4 w-4 flex-shrink-0 text-brand-600" />
                    <p class="tip-box__text">
                        <strong>Tips:</strong> Selesaikan quiz gaya belajar untuk mendapatkan rekomendasi materi yang personal.
                    </p>
                </div>
            </div>

        </div>
    </main>

    @include('partials._public_footer')

    @vite(['resources/js/views/public.js'])
@endsection
