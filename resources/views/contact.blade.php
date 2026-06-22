@extends('layouts.app')

@section('title', 'Hubungi Kami — LearnFit')
@section('meta_description', 'Punya pertanyaan atau masukan? Tim LearnFit siap membantu. Hubungi kami melalui email, WhatsApp, atau kirim pesan langsung.')

@section('content')
    @include('partials._public_navbar', ['active' => 'contact'])

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
                <span>Kontak</span>
            </div>
            <span class="section-badge">Hubungi Kami</span>
            <h1 class="page-hero__title">Ada yang Bisa<br><em>Kami Bantu?</em></h1>
            <p class="page-hero__desc">
                Tim kami siap menjawab pertanyaanmu. Kirim pesan dan kami akan
                merespons dalam waktu 1×24 jam kerja.
            </p>
        </div>
    </div>

    {{-- CONTACT MAIN --}}
    <section class="py-20 lg:py-24">
        <div class="container-page grid items-start gap-14 lg:grid-cols-[1fr_1.6fr]">

            {{-- KIRI: Info Kontak --}}
            <div data-animate="fade-up">
                <span class="section-badge">Info Kontak</span>
                <h2 class="font-display mb-3.5 text-[clamp(1.4rem,2.5vw,1.875rem)] font-bold leading-tight text-ink-900">
                    Kami Senang<br>Mendengarmu
                </h2>
                <p class="mb-9 text-[15px] leading-relaxed text-ink-600">
                    Punya pertanyaan, masukan, atau butuh bantuan? Pilih salah satu cara
                    di bawah ini untuk menghubungi kami.
                </p>

                <div class="mb-10 flex flex-col gap-3.5">
                    <a href="mailto:halo@learnfit.id" class="flex items-center gap-3.5 rounded-2xl border border-ink-200 bg-ink-100 p-4 no-underline transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-11 w-11 min-w-[44px] items-center justify-center rounded-xl bg-brand-50">
                            <x-icon name="envelope" class="h-5 w-5 text-brand-600" />
                        </div>
                        <div>
                            <p class="mb-0.5 text-[11px] font-bold uppercase tracking-wider text-ink-400">Email</p>
                            <p class="text-sm font-semibold text-ink-900">halo@learnfit.id</p>
                        </div>
                    </a>

                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="flex items-center gap-3.5 rounded-2xl border border-ink-200 bg-ink-100 p-4 no-underline transition-all duration-200 hover:-translate-y-0.5 hover:bg-white hover:shadow-brand-sm">
                        <div class="flex h-11 w-11 min-w-[44px] items-center justify-center rounded-xl bg-emerald-50">
                            <x-icon name="whatsapp" class="h-5 w-5 text-emerald-600" />
                        </div>
                        <div>
                            <p class="mb-0.5 text-[11px] font-bold uppercase tracking-wider text-ink-400">WhatsApp</p>
                            <p class="text-sm font-semibold text-ink-900">+62 812-3456-7890</p>
                        </div>
                    </a>

                    <div class="flex items-center gap-3.5 rounded-2xl border border-ink-200 bg-ink-100 p-4">
                        <div class="flex h-11 w-11 min-w-[44px] items-center justify-center rounded-xl bg-orange-50">
                            <x-icon name="clock" class="h-5 w-5 text-orange-500" />
                        </div>
                        <div>
                            <p class="mb-0.5 text-[11px] font-bold uppercase tracking-wider text-ink-400">Jam Operasional</p>
                            <p class="text-sm font-semibold text-ink-900">Senin–Jumat, 09.00–17.00 WIB</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 rounded-2xl border border-ink-200 bg-ink-100 p-4">
                        <div class="flex h-11 w-11 min-w-[44px] items-center justify-center rounded-xl bg-rose-50">
                            <x-icon name="location" class="h-5 w-5 text-rose-500" />
                        </div>
                        <div>
                            <p class="mb-0.5 text-[11px] font-bold uppercase tracking-wider text-ink-400">Alamat</p>
                            <p class="text-sm font-semibold text-ink-900">Jl. Sudirman No. 12, Jakarta Pusat</p>
                        </div>
                    </div>
                </div>

                <p class="mb-3 text-[13px] font-bold uppercase tracking-wider text-ink-400">Ikuti Kami</p>
                <div class="mb-8 flex gap-2.5">
                    <a href="#" title="Instagram" class="flex h-10 w-10 items-center justify-center rounded-xl border border-ink-200 bg-ink-100 text-ink-500 no-underline transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                        <x-icon name="instagram" class="h-[18px] w-[18px]" />
                    </a>
                    <a href="#" title="Twitter / X" class="flex h-10 w-10 items-center justify-center rounded-xl border border-ink-200 bg-ink-100 text-ink-500 no-underline transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                        <x-icon name="twitter" class="h-[18px] w-[18px]" />
                    </a>
                    <a href="#" title="LinkedIn" class="flex h-10 w-10 items-center justify-center rounded-xl border border-ink-200 bg-ink-100 text-ink-500 no-underline transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                        <x-icon name="linkedin" class="h-[18px] w-[18px]" />
                    </a>
                    <a href="#" title="YouTube" class="flex h-10 w-10 items-center justify-center rounded-xl border border-ink-200 bg-ink-100 text-ink-500 no-underline transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                        <x-icon name="youtube" class="h-[18px] w-[18px]" />
                    </a>
                    <a href="#" title="TikTok" class="flex h-10 w-10 items-center justify-center rounded-xl border border-ink-200 bg-ink-100 text-ink-500 no-underline transition-all duration-200 hover:-translate-y-0.5 hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                        <x-icon name="tiktok" class="h-[18px] w-[18px]" />
                    </a>
                </div>

                {{-- Map card --}}
                <div class="rounded-3xl bg-gradient-to-br from-brand-50 to-brand-100 p-7">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-600">Lokasi Kami</p>
                    <p class="mb-1 text-base font-bold text-ink-900">Kantor LearnFit</p>
                    <p class="mb-4 text-[13px] leading-relaxed text-ink-600">
                        Jl. Jend. Sudirman No. 12,<br>Jakarta Pusat, DKI Jakarta 10220
                    </p>
                    <div class="h-40 overflow-hidden rounded-xl bg-ink-200">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613!3d-6.208763!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJl.%20Jend.%20Sudirman%2C%20Jakarta!5e0!3m2!1sid!2sid!4v1710000000000"
                            class="h-full w-full border-0 block"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            {{-- KANAN: Form --}}
            <div data-animate="fade-left">
                <div class="form-card">
                    <h3 class="font-display mb-1.5 text-[22px] font-bold text-ink-900">Kirim Pesan</h3>
                    <p class="mb-7 text-sm leading-relaxed text-ink-600">Isi formulir di bawah dan tim kami akan menghubungimu segera.</p>

                    <form id="contactForm" data-contact-form novalidate>
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="nama">Nama Lengkap <span>*</span></label>
                                <input class="form-input" type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email <span>*</span></label>
                                <input class="form-input" type="email" id="email" name="email" placeholder="kamu@email.com" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="telepon">Nomor WhatsApp</label>
                                <input class="form-input" type="tel" id="telepon" name="telepon" placeholder="+62 812 xxxx xxxx">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="topik">Topik <span>*</span></label>
                                <div class="relative">
                                    <select class="form-select" id="topik" name="topik" required>
                                        <option value="" disabled selected>Pilih topik...</option>
                                        <option value="pertanyaan">Pertanyaan Umum</option>
                                        <option value="teknis">Bantuan Teknis</option>
                                        <option value="kerjasama">Kerja Sama / Partnership</option>
                                        <option value="media">Media &amp; Pers</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subjek">Subjek <span>*</span></label>
                            <input class="form-input" type="text" id="subjek" name="subjek" placeholder="Tuliskan subjek pesanmu..." required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="pesan">Pesan <span>*</span></label>
                            <textarea class="form-textarea" id="pesan" name="pesan" placeholder="Tuliskan pesanmu di sini..." required></textarea>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn-primary btn-lg w-full justify-center" data-contact-submit>
                                <x-icon name="paper-airplane" class="h-4 w-4" />
                                Kirim Pesan
                            </button>
                        </div>
                    </form>

                    <div class="hidden flex-col items-center gap-4 pt-6 text-center" data-contact-success id="formSuccess">
                        <div class="grid h-[72px] w-[72px] place-items-center rounded-full bg-emerald-100 animate-popIn">
                            <x-icon name="check" style="solid" class="h-9 w-9 text-emerald-600" />
                        </div>
                        <h4 class="font-display text-[22px] font-bold text-ink-900">Pesan Terkirim!</h4>
                        <p class="max-w-[320px] text-sm leading-relaxed text-ink-600">Terima kasih sudah menghubungi kami. Tim LearnFit akan merespons dalam 1×24 jam kerja.</p>
                        <button type="button" class="btn-ghost btn-lg mt-2" data-contact-reset>Kirim Pesan Lain</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="faq">
        <div class="container-page">
            <div class="mb-12 text-center" data-animate="fade-up">
                <span class="section-badge">FAQ</span>
                <h2 class="font-display mb-3 text-[clamp(1.5rem,3vw,2.25rem)] font-bold text-ink-900">Pertanyaan yang Sering Ditanyakan</h2>
                <p class="text-[15px] text-ink-600">Tidak menemukan jawabanmu? Kirim pesan langsung ke kami di atas.</p>
            </div>

            <div class="mx-auto flex max-w-[720px] flex-col gap-3" data-animate="fade-up">
                @php
                    $faqs = [
                        ['q' => 'Apakah LearnFit gratis untuk digunakan?', 'a' => 'Ya! LearnFit memiliki paket gratis yang sudah cukup lengkap untuk memulai perjalanan belajarmu. Untuk fitur premium seperti analisis mendalam dan sesi bimbingan, tersedia paket berbayar yang sangat terjangkau.'],
                        ['q' => 'Bagaimana cara LearnFit menentukan gaya belajarku?', 'a' => 'LearnFit menggunakan tes singkat berbasis psikologi kognitif yang hanya membutuhkan sekitar 10–15 menit. Hasilnya akan memberikan profil belajarmu secara detail beserta rekomendasi metode yang paling efektif.'],
                        ['q' => 'Apakah LearnFit cocok untuk semua usia?', 'a' => 'LearnFit dirancang untuk pelajar SMP, SMA, mahasiswa, hingga profesional yang ingin meningkatkan kemampuan belajarnya. Konten dan rekomendasi disesuaikan dengan jenjang dan tujuan belajarmu.'],
                        ['q' => 'Berapa lama saya akan mendapat respons setelah mengirim pesan?', 'a' => 'Tim kami berkomitmen untuk merespons setiap pesan dalam waktu 1×24 jam kerja (Senin–Jumat, 09.00–17.00 WIB). Untuk pertanyaan mendesak, kamu bisa menghubungi kami melalui WhatsApp.'],
                        ['q' => 'Apakah data saya aman di LearnFit?', 'a' => 'Keamanan data pengguna adalah prioritas utama kami. Seluruh data disimpan dengan enkripsi dan tidak pernah dibagikan kepada pihak ketiga tanpa izin eksplisit dari pengguna.'],
                    ];
                @endphp

                @foreach($faqs as $i => $faq)
                    <div class="faq-item" id="faq-{{ $i }}">
                        <button type="button" class="faq-question" data-faq-toggle>
                            {{ $faq['q'] }}
                            <span class="faq-icon">
                                <x-icon name="plus" class="h-3.5 w-3.5" />
                            </span>
                        </button>
                        <div class="faq-answer">{{ $faq['a'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials._public_footer')

    @vite(['resources/js/views/public.js'])
@endsection
