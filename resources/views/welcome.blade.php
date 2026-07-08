@extends('layouts.app')

@section('title', 'LearnFit — Temukan Gaya Belajarmu')
@section('meta_description', 'LearnFit membantu kamu menemukan metode belajar yang paling efektif dan personal sesuai kepribadianmu.')

@section('content')
    @include('partials._public_navbar', ['active' => 'home'])

    {{-- HERO --}}
    <section class="hero">
        <div class="dots-deco" style="bottom:60px;left:-10px;">
            @for($r = 0; $r < 4; $r++)
                @for($c = 0; $c < 5; $c++)<span></span>@endfor
            @endfor
        </div>
        <div class="dots-deco" style="top:40px;right:20px;opacity:.13">
            @for($r = 0; $r < 4; $r++)
                @for($c = 0; $c < 5; $c++)<span></span>@endfor
            @endfor
        </div>

        <div class="hero__inner">
            <div data-animate="fade-up">
                <span class="hero__badge">
                    <x-icon name="sparkles" class="h-3.5 w-3.5" />
                    PERSONALIZED EDUCATION
                </span>
                <h1 class="hero__title">Temukan <em>Gaya</em><br>Belajarmu</h1>
                <p class="hero__desc">
                    LearnFit membantu kamu menemukan metode belajar
                    yang paling efektif dan personal sesuai kepribadianmu.
                </p>
                <a href="{{ route('register') }}" class="btn-primary btn-lg hero__cta">
                    Mulai Sekarang
                </a>
                <div class="hero__dots">
                    <span class="inline-block h-2 w-6 rounded-sm bg-brand-600"></span>
                    <span class="inline-block h-2 w-2 rounded-full bg-ink-200"></span>
                    <span class="inline-block h-2 w-2 rounded-full bg-ink-200"></span>
                </div>
            </div>

            <div class="hero__visual" data-animate="fade-left">
                <div class="hero__img-wrapper">
                    @if(file_exists(public_path('images/hero-student.png')))
                        <img src="{{ asset('images/hero-student.png') }}" alt="Student studying" class="hero__img">
                    @elseif(file_exists(public_path('images/hero-student.jpg')))
                        <img src="{{ asset('images/hero-student.jpg') }}" alt="Student studying" class="hero__img">
                    @else
                        <div class="hero__img-fallback">
                            <x-icon name="academic-cap" class="mx-auto mb-4 h-12 w-12 text-brand-600" />
                            <p>Letakkan foto hero di<br><strong class="font-semibold text-ink-700">public/images/hero-student.png</strong></p>
                        </div>
                    @endif

                    <div class="hero__badge-card">
                        <span class="grid h-6 w-6 place-items-center rounded-full bg-brand-600 text-white">
                            <x-icon name="check" class="h-3.5 w-3.5" style="solid" />
                        </span>
                        <div>
                            <p class="badge-card__title">Metode Ditemukan</p>
                            <p class="badge-card__sub">Visual Learner Profile</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="section bg-white" id="fitur">
        <div class="section-header" data-animate="fade-up">
            <span class="section-badge">Fitur Unggulan</span>
            <h2 class="section-title">Semua yang Kamu Butuhkan</h2>
            <p class="section-desc">Dirancang untuk memaksimalkan potensi belajarmu dengan teknologi yang cerdas.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card" data-animate="fade-up" style="--delay:0ms">
                <div class="feature-card__icon">
                    <x-icon name="sparkles-2" class="h-6 w-6 text-brand-600" />
                </div>
                <h3 class="feature-card__title">Metode Belajar Personal</h3>
                <p class="feature-card__desc">Analisis cara efektifmu belajar berdasarkan kepribadian dan preferensi unikmu.</p>
            </div>

            <div class="feature-card" data-animate="fade-up" style="--delay:100ms">
                <div class="feature-card__icon">
                    <x-icon name="bolt" class="h-6 w-6 text-brand-600" />
                </div>
                <h3 class="feature-card__title">Belajar 2× Lebih Cepat</h3>
                <p class="feature-card__desc">Tingkatkan efisiensi belajarmu dengan teknik dan strategi yang tepat sasaran.</p>
            </div>

            <div class="feature-card" data-animate="fade-up" style="--delay:200ms">
                <div class="feature-card__icon">
                    <x-icon name="chart" class="h-6 w-6 text-brand-600" />
                </div>
                <h3 class="feature-card__title">Analisis Progres Nyata</h3>
                <p class="feature-card__desc">Pantau perkembangan belajarmu secara real-time dengan beranda yang intuitif.</p>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="testimonials" id="testimoni">
        <div class="section-header" data-animate="fade-up">
            <span class="section-badge">Testimoni</span>
            <h2 class="section-title">Apa Kata Mereka?</h2>
            <p class="section-desc">Ribuan pelajar sudah menemukan metode belajar terbaik mereka bersama LearnFit.</p>
        </div>

        <div class="testimonials-grid">
            @forelse($testimonis as $testi)
            <div class="testi-card" data-animate="fade-up" style="--delay:{{ $loop->index * 100 }}ms">
                <div class="testi-card__stars text-amber-400">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < $testi->rating)
                            <x-icon name="star" style="solid" class="h-4 w-4 inline-block" />
                        @else
                            <x-icon name="star" class="h-4 w-4 inline-block text-ink-200" />
                        @endif
                    @endfor
                </div>
                <p class="testi-card__text">"{{ $testi->komentar }}"</p>
                <div class="testi-card__author">
                    @php
                        $colors = ['linear-gradient(135deg,#667eea,#764ba2)', 'linear-gradient(135deg,#f093fb,#f5576c)', 'linear-gradient(135deg,#4facfe,#00f2fe)', 'linear-gradient(135deg,#43e97b,#38f9d7)', 'linear-gradient(135deg,#fa709a,#fee140)'];
                        $bg = $colors[$loop->index % count($colors)];
                        $initial = strtoupper(collect(explode(' ', trim($testi->user->name)))->map(fn($w) => mb_substr($w,0,1))->take(2)->join(''));
                    @endphp
                    <div class="testi-card__avatar" style="background:{{ $bg }}">{{ $initial }}</div>
                    <div>
                        <p class="testi-card__name">{{ $testi->user->name }}</p>
                        <p class="testi-card__role">{{ ucfirst($testi->user->role) }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-10 text-center">
                <p class="text-ink-500 mb-2">Belum ada testimoni dari siswa saat ini.</p>
                <p class="text-sm text-ink-400">Jadilah yang pertama membagikan pengalaman belajarmu di LearnFit!</p>
            </div>
            @endforelse
        </div>

        <div class="testi-stats" data-animate="fade-up">
            <div class="testi-stat">
                <span class="testi-stat__num">{{ number_format($stats['totalSiswa']) }}</span>
                <span class="testi-stat__label">Siswa Terdaftar</span>
            </div>
            <div class="testi-stat__divider"></div>
            <div class="testi-stat">
                <span class="testi-stat__num">{{ number_format($stats['totalKelas']) }}</span>
                <span class="testi-stat__label">Kelas Tersedia</span>
            </div>
            <div class="testi-stat__divider"></div>
            <div class="testi-stat">
                <span class="testi-stat__num">{{ number_format($stats['totalSesi']) }}</span>
                <span class="testi-stat__label">Sesi Belajar Selesai</span>
            </div>
            <div class="testi-stat__divider"></div>
            <div class="testi-stat">
                <span class="testi-stat__num">{{ number_format($stats['totalCatatan']) }}</span>
                <span class="testi-stat__label">Catatan & Jurnal</span>
            </div>
        </div>
    </section>

    {{-- CTA BOTTOM --}}
    <section class="cta-bottom" id="kontak">
        <div class="cta-bottom__inner" data-animate="fade-up">
            <div>
                <h2 class="cta-bottom__title">Siap Menemukan Cara Belajarmu?</h2>
                <p class="cta-bottom__desc">Bergabunglah dengan ribuan pelajar yang sudah merasakan manfaatnya. Gratis untuk memulai.</p>
            </div>
            <a href="{{ route('register') }}" class="btn-white btn-lg">
                Mulai Gratis Sekarang
            </a>
        </div>
    </section>

    @include('partials._public_footer')

    @vite(['resources/js/views/public.js'])
@endsection
