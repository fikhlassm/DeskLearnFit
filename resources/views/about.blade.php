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
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Kolaborasi Tim</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">Sinergi yang kuat antar anggota kelompok untuk mencapai hasil yang maksimal.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-ink-200 bg-ink-100 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-10 w-10 min-w-[40px] items-center justify-center rounded-[10px] bg-brand-50">
                            <x-icon name="globe" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Fokus Pembelajaran</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">Dibuat dengan tujuan untuk mendalami pengembangan web tingkat lanjut.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-ink-200 bg-ink-100 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-10 w-10 min-w-[40px] items-center justify-center rounded-[10px] bg-brand-50">
                            <x-icon name="shield-check" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Inovasi Teknologi</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">Eksplorasi menggunakan kerangka kerja modern untuk menciptakan solusi yang efisien.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-ink-200 bg-ink-100 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-10 w-10 min-w-[40px] items-center justify-center rounded-[10px] bg-brand-50">
                            <x-icon name="chart" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-semibold text-ink-900">Pemecahan Masalah</h4>
                            <p class="text-[13px] leading-relaxed text-ink-600">Menciptakan alur aplikasi yang benar-benar bermanfaat bagi penggunanya.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Stats & Misi --}}
            <div data-animate="fade-left" class="flex h-full flex-col">
                <div class="flex h-full flex-col gap-3.5 rounded-3xl bg-gradient-to-br from-brand-50 to-brand-100 p-7">
                    <div class="mb-4 flex flex-1 flex-col">
                        <p class="mb-3 text-[11px] font-bold uppercase tracking-wider text-brand-600">Teknologi Utama</p>
                        <div class="grid flex-1 grid-cols-2 gap-3">
                            <span class="flex items-center justify-center gap-2.5 rounded-2xl border border-ink-200 bg-white p-3 text-[14px] font-semibold text-ink-700 transition-all hover:-translate-y-0.5 hover:shadow-sm">
                                <img src="https://cdn.simpleicons.org/laravel/FF2D20" class="h-5 w-5" alt="Laravel"> Laravel 12
                            </span>
                            <span class="flex items-center justify-center gap-2.5 rounded-2xl border border-ink-200 bg-white p-3 text-[14px] font-semibold text-ink-700 transition-all hover:-translate-y-0.5 hover:shadow-sm">
                                <img src="https://cdn.simpleicons.org/php/777BB4" class="h-5 w-5" alt="PHP"> PHP 8
                            </span>
                            <span class="flex items-center justify-center gap-2.5 rounded-2xl border border-ink-200 bg-white p-3 text-[14px] font-semibold text-ink-700 transition-all hover:-translate-y-0.5 hover:shadow-sm">
                                <img src="https://cdn.simpleicons.org/sqlite/003B57" class="h-5 w-5" alt="SQLite"> SQLite
                            </span>
                            <span class="flex items-center justify-center gap-2.5 rounded-2xl border border-ink-200 bg-white p-3 text-[14px] font-semibold text-ink-700 transition-all hover:-translate-y-0.5 hover:shadow-sm">
                                <img src="https://cdn.simpleicons.org/tailwindcss/06B6D4" class="h-5 w-5" alt="Tailwind"> Tailwind CSS
                            </span>
                            <span class="col-span-2 flex items-center justify-center gap-2.5 rounded-2xl border border-ink-200 bg-white p-3 text-[14px] font-semibold text-ink-700 transition-all hover:-translate-y-0.5 hover:shadow-sm">
                                <img src="https://cdn.simpleicons.org/vite/646CFF" class="h-5 w-5" alt="Vite"> Vite
                            </span>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-brand-600 p-5 text-white">
                        <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.12em] opacity-75">TUJUAN PROYEK</p>
                        <p class="text-sm italic leading-relaxed">
                            "Menerapkan pembelajaran full-stack web development secara praktikal untuk 
                            membangun platform pencatatan belajar yang modern dan interaktif."
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
                        Proyek Praktikum<br>Pemrograman Web
                    </h2>
                    <p class="mb-4 text-[15px] leading-relaxed text-ink-600">
                        LearnFit dibangun sebagai bagian dari tugas akhir Praktikum Pemrograman Web oleh Kelompok 8 di Universitas Airlangga. Kami tidak hanya ingin membuat sekadar "tugas", tapi kami ingin menciptakan sesuatu yang fungsional.
                    </p>
                    <p class="text-[15px] leading-relaxed text-ink-600">
                        Melalui dedikasi dan kerja sama tim, kami merancang platform yang bertujuan untuk membantu mahasiswa/siswa mengenali gaya belajar yang paling cocok bagi mereka.
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">M1</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">Fase 1 — Ideasi & Desain</p>
                            <p class="text-sm leading-relaxed text-ink-600">Menentukan konsep aplikasi dan merancang antarmuka pengguna.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">M2</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">Fase 2 — Pengembangan Tampilan</p>
                            <p class="text-sm leading-relaxed text-ink-600">Membangun kerangka frontend interaktif untuk pengalaman pengguna terbaik.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">M3</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">Fase 3 — Integrasi Sistem</p>
                            <p class="text-sm leading-relaxed text-ink-600">Mengembangkan backend dan mengintegrasikan basis data.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex h-9 w-9 min-w-[36px] items-center justify-center rounded-full bg-brand-600 text-[11px] font-bold text-white">M4</div>
                        <div>
                            <p class="mb-0.5 text-xs font-bold text-brand-600">Fase 4 — Evaluasi Akhir</p>
                            <p class="text-sm leading-relaxed text-ink-600">Melakukan pengujian fitur sebelum dipresentasikan sebagai hasil praktikum.</p>
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
                        ['initials' => 'MF', 'name' => 'Muhammad Fikhlas Hakim Arya Maulana<br><span class="text-sm font-normal text-ink-500">(187241034)</span>', 'gradient' => 'linear-gradient(135deg, #818CF8, #6366F1)', 'delay' => 0],
                        ['initials' => 'MY', 'name' => 'Muhammad Yasiir Arafat<br><span class="text-sm font-normal text-ink-500">(187241061)</span>', 'gradient' => 'linear-gradient(135deg, #38BDF8, #0EA5E9)', 'delay' => 100],
                        ['initials' => 'FZ',  'name' => 'Farhan Zuso Putra Jaya<br><span class="text-sm font-normal text-ink-500">(187241102)</span>',           'gradient' => 'linear-gradient(135deg,#10b981,#0891b2)', 'delay' => 200],
                    ];
                @endphp

                @foreach($team as $member)
                    <div class="rounded-3xl border border-ink-200 bg-white p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-brand-md"
                         data-animate="fade-up" style="--delay: {{ $member['delay'] }}ms">
                        <div class="mx-auto mb-3.5 flex h-16 w-16 items-center justify-center rounded-full text-xl font-bold text-white"
                             style="background: {{ $member['gradient'] }}">
                            {{ $member['initials'] }}
                        </div>
                        <p class="mb-1 text-[15px] font-semibold text-ink-900">{!! $member['name'] !!}</p>
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
            </a>
        </div>
    </section>

    @include('partials._public_footer')

    @vite(['resources/js/views/public.js'])
@endsection
