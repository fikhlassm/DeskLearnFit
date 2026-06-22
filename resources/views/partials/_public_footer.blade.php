<footer class="public-footer">
    <div class="public-footer__inner">
        <a href="{{ route('home') }}" class="public-footer__brand">
            <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand-600 text-white">
                <svg viewBox="0 0 28 28" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M8 10h12M8 14h8M8 18h10"/>
                </svg>
            </span>
            <span class="public-footer__brand-name">LearnFit</span>
        </a>

        <p class="public-footer__tagline">Temukan gaya belajarmu yang paling efektif bersama kami.</p>

        <nav class="public-footer__links" aria-label="Footer">
            <a href="{{ route('home') }}" class="public-footer__link">Beranda</a>
            <a href="{{ route('about') }}" class="public-footer__link">Tentang Kami</a>
            <a href="{{ route('contact') }}" class="public-footer__link">Kontak</a>
        </nav>

        <p class="public-footer__copy">&copy; {{ date('Y') }} LearnFit. Hak Cipta Dilindungi.</p>
    </div>
</footer>
