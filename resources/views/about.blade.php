@extends('layouts.app')

@section('title', 'Tentang Kami — LearnFit')
@section('meta_description', 'Kenali LearnFit: platform belajar personal yang membantu setiap pelajar Indonesia menemukan metode belajar paling efektif.')

@section('content')
    @include('partials._public_navbar', ['active' => 'about'])

    {{-- PAGE HERO --}}
    <div class="page-hero">
        <div class="dots-deco" style="top:30px;right:40px;opacity:.12">
            @for($r = 0; $r < 4; $r++)
                @for($c = 0; $c < 5; $c++)<span></span>@endfor
            @endfor
        </div>
        <div class="dots-deco" style="bottom:20px;left:30px;opacity:.10">
            @for($r = 0; $r < 4; $r++)
                @for($c = 0; $c < 5; $c++)<span></span>@endfor
            @endfor
        </div>
        <div class="container-page" data-animate="fade-up">
            <div class="page-hero__breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <x-icon name="chevron-right" class="h-3.5 w-3.5 text-ink-400" />
                <span>Tentang Kami</span>
            </div>
            <span class="section-badge">Tentang Kami</span>
            <h1 class="page-hero__title">Kami Ada untuk<br><em>Masa Depanmu</em></h1>
            <p class="page-hero__desc">
                LearnFit lahir dari keyakinan bahwa setiap orang memiliki cara belajar yang unik.
                Kami hadir untuk membantu kamu menemukan metode yang paling efektif dan personal.
            </p>
        </div>
    </div>

    {{-- ABOUT MAIN --}}
    <section class="bg-white py-20" id="tentang">
        <div class="dots-deco" style="bottom:80px;left:-10px;opacity:.25">
            @for($r = 0; $r < 4; $r++)
                @for($c = 0; $c < 5; $c++)<span></span>@endfor
            @endfor
        </div>

        <div class="container-page grid items-center gap-16 lg:grid-cols-2 mb-16">
            {{-- Kiri: Nilai --}}
            <div data-animate="fade-up">
                <span class="section-badge">Nilai Kami</span>
                <h2 class="font-display mb-4 text-[clamp(1.75rem,3.5vw,2.5rem)] font-bold leading-tight text-ink-900">
                    Mengapa Memilih<br>LearnFit?
                </h2>
                <p class="mb-9 max-w-[460px] text-[15px] leading-relaxed text-ink-600">
                    Kami percaya pendidikan yang baik dimulai dari memahami diri sendiri.
                    LearnFit menggabungkan ilmu kognitif dengan teknologi modern.
                </p>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="flex items-start gap-3 rounded-2xl border border-ink-200 bg-ink-100 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-10 w-10 min-w-[40px] items-center justify-center rounded-[10px] bg-brand-50">
                            <x-icon name="puzzle" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Berbasis Riset</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">Metode kami dirancang dari penelitian ilmu kognitif dan psikologi pendidikan.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-ink-200 bg-ink-100 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-10 w-10 min-w-[40px] items-center justify-center rounded-[10px] bg-brand-50">
                            <x-icon name="globe" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Personal &amp; Adaptif</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">Rekomendasi belajar yang terus berkembang sesuai progres dan kebutuhanmu.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-ink-200 bg-ink-100 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-10 w-10 min-w-[40px] items-center justify-center rounded-[10px] bg-brand-50">
                            <x-icon name="shield-check" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Terpercaya</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">Dipercaya lebih dari 12.000 pelajar aktif di seluruh Indonesia.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-ink-200 bg-ink-100 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-10 w-10 min-w-[40px] items-center justify-center rounded-[10px] bg-brand-50">
                            <x-icon name="chart" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Hasil Nyata</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">98% pengguna melaporkan peningkatan nilai dan motivasi belajar.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Stats & Misi --}}
            <div data-animate="fade-left">
                <div class="flex flex-col gap-3.5 rounded-3xl bg-gradient-to-br from-brand-50 to-brand-100 p-7">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-ink-200 bg-white p-4 transition-transform duration-200 hover:-translate-y-0.5">
                            <span class="font-display block text-[28px] font-bold leading-none text-brand-600">2021</span>
                            <span class="mt-1.5 block text-[11px] font-medium tracking-wide text-ink-400">Tahun Berdiri</span>
                        </div>
                        <div class="rounded-2xl border border-ink-200 bg-white p-4 transition-transform duration-200 hover:-translate-y-0.5">
                            <span class="font-display block text-[28px] font-bold leading-none text-brand-600">12K+</span>
                            <span class="mt-1.5 block text-[11px] font-medium tracking-wide text-ink-400">Pelajar Aktif</span>
                        </div>
                        <div class="rounded-2xl border border-ink-200 bg-white p-4 transition-transform duration-200 hover:-translate-y-0.5">
                            <span class="font-display block text-[28px] font-bold leading-none text-brand-600">50+</span>
                            <span class="mt-1.5 block text-[11px] font-medium tracking-wide text-ink-400">Kota di Indonesia</span>
                        </div>
                        <div class="rounded-2xl border border-ink-200 bg-white p-4 transition-transform duration-200 hover:-translate-y-0.5">
                            <span class="font-display block text-[28px] font-bold leading-none text-brand-600">4.9 ★</span>
                            <span class="mt-1.5 block text-[11px] font-medium tracking-wide text-ink-400">Rating Pengguna</span>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-brand-600 p-5 text-white">
                        <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.12em] opacity-75">MISI KAMI</p>
                        <p class="text-sm italic leading-relaxed">
                            "Memberdayakan setiap pelajar Indonesia untuk mencapai potensi terbaiknya
                            melalui pendidikan yang personal dan berbasis data."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Story & Timeline --}}
        <div class="container-page" data-animate="fade-up">
            <div class="mb-16 grid items-center gap-12 rounded-3xl bg-ink-100 p-12 md:grid-cols-2">
                <div>
                    <span class="section-badge">Kisah Kami</span>
                    <h2 class="font-display mb-4 text-[clamp(1.4rem,2.5vw,2rem)] font-bold leading-tight text-ink-900">
                        Berawal dari<br>Sebuah Pertanyaan
                    </h2>
                    <p class="mb-4 text-[15px] leading-relaxed text-ink-600">
                        LearnFit lahir ketika dua mahasiswa bertanya: "Mengapa cara belajar yang sama tidak bekerja
                        untuk semua orang?" Dari pertanyaan sederhana itu, kami membangun platform yang memahami
                        bahwa setiap pelajar itu unik.
                    </p>
                    <p class="text-[15px] leading-relaxed text-ink-600">
                        Dengan dukungan riset psikologi pendidikan dan teknologi AI, kami terus berkembang
                        menjadi platform belajar personal terpercaya di Indonesia.
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">21</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">2021 — Lahirnya Ide</p>
                            <p class="text-sm leading-relaxed text-ink-600">LearnFit didirikan oleh dua mahasiswa UI dengan visi pendidikan personal.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">22</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">2022 — Beta Launch</p>
                            <p class="text-sm leading-relaxed text-ink-600">Diluncurkan ke 500 pelajar pertama dan mendapat respons luar biasa.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">23</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">2023 — Ekspansi Nasional</p>
                            <p class="text-sm leading-relaxed text-ink-600">Berkembang ke 50+ kota dengan 10.000+ pengguna aktif.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">24</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">2024 — AI Integration</p>
                            <p class="text-sm leading-relaxed text-ink-600">Mengintegrasikan AI untuk rekomendasi yang semakin personal dan akurat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tim --}}
        <div class="container-page border-t border-ink-200 pt-14">
            <div class="section-header mb-10" data-animate="fade-up">
                <span class="section-badge">Tim Kami</span>
                <h2 class="section-title">Orang-orang di Balik LearnFit</h2>
                <p class="section-desc">Kami adalah tim yang bersemangat membangun pendidikan yang lebih baik untuk Indonesia.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $team = [
                        ['initials' => 'AR', 'name' => 'Andi Rachman',    'role' => 'CEO & Co-founder', 'gradient' => 'linear-gradient(135deg,#667eea,#764ba2)', 'delay' => 0],
                        ['initials' => 'SP', 'name' => 'Siti Pratiwi',    'role' => 'Head of Product',   'gradient' => 'linear-gradient(135deg,#2563EB,#0891b2)', 'delay' => 100],
                        ['initials' => 'DH', 'name' => 'Dika Hermawan',   'role' => 'Lead Engineer',     'gradient' => 'linear-gradient(135deg,#10b981,#0891b2)', 'delay' => 200],
                        ['initials' => 'NR', 'name' => 'Nina Rahayu',     'role' => 'UX Designer',       'gradient' => 'linear-gradient(135deg,#f093fb,#f5576c)', 'delay' => 300],
                        ['initials' => 'BW', 'name' => 'Bayu Wicaksono',  'role' => 'Data Scientist',    'gradient' => 'linear-gradient(135deg,#4facfe,#00f2fe)', 'delay' => 400],
                        ['initials' => 'FA', 'name' => 'Fira Aulia',      'role' => 'Marketing Lead',    'gradient' => 'linear-gradient(135deg,#fa709a,#fee140)', 'delay' => 500],
                    ];
                @endphp

                @foreach($team as $member)
                    <div class="rounded-3xl border border-ink-200 bg-white p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-brand-md"
                         data-animate="fade-up" style="--delay: {{ $member['delay'] }}ms">
                        <div class="mx-auto mb-3.5 flex h-16 w-16 items-center justify-center rounded-full text-xl font-bold text-white"
                             style="background: {{ $member['gradient'] }}">
                            {{ $member['initials'] }}
                        </div>
                        <p class="mb-1 text-[15px] font-semibold text-ink-900">{{ $member['name'] }}</p>
                        <p class="text-[13px] text-ink-400">{{ $member['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cta-bottom">
        <div class="cta-bottom__inner" data-animate="fade-up">
            <div>
                <h2 class="cta-bottom__title">Siap Menemukan Cara Belajarmu?</h2>
                <p class="cta-bottom__desc">Bergabunglah dengan ribuan pelajar yang sudah merasakan manfaatnya. Gratis untuk memulai.</p>
            </div>
            <a href="{{ route('register') }}" class="btn-white btn-lg">
                Mulai Gratis Sekarang
                <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </section>

    @include('partials._public_footer')

    @vite(['resources/js/views/public.js'])
@endsection
