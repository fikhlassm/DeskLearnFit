<footer class="public-footer">
    <div class="public-footer__inner">
        <a href="{{ route('home') }}" class="public-footer__brand">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" class="shrink-0">
                <rect width="28" height="28" rx="8" fill="#2563EB"/>
                <path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="font-display text-[22px] font-bold tracking-tight text-white">LearnFit</span>
        </a>

        <p class="public-footer__tagline">Temukan gaya belajarmu yang paling efektif bersama kami.</p>

        <nav class="public-footer__links" aria-label="Footer">
            <a href="{{ route('home') }}" class="public-footer__link">Beranda</a>
            <a href="{{ route('about') }}" class="public-footer__link">Tentang Kami</a>
            <a href="{{ route('contact') }}" class="public-footer__link">Kontak</a>
        </nav>

        <p class="public-footer__copy">&copy; {{ date('Y') }} LearnFit (Kelompok 8). Hak Cipta Dilindungi.</p>
    </div>
</footer>
