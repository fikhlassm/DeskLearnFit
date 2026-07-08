@props([
    'active' => null,
    'authed' => false,
])

@php
    $links = [
        ['key' => 'home',   'href' => '/',                            'label' => 'Beranda'],
        ['key' => 'fitur',  'href' => '/#fitur',                       'label' => 'Fitur'],
        ['key' => 'about',  'href' => route('about'),                 'label' => 'Tentang Kami'],
        ['key' => 'contact','href' => route('contact'),               'label' => 'Kontak'],
    ];
@endphp

<nav class="public-nav">
    <div class="public-nav__inner">
        <a href="{{ route('home') }}" class="public-nav__brand">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" class="shrink-0">
                <rect width="28" height="28" rx="8" fill="#2563EB"/>
                <path d="M8 10h12M8 14h8M8 18h10" stroke="white" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="font-display text-[22px] font-bold tracking-tight text-ink-900">LearnFit</span>
        </a>

        <ul class="public-nav__links">
            @foreach($links as $link)
                <li>
                    <a href="{{ $link['href'] }}"
                       class="public-nav__link {{ $active === $link['key'] ? 'public-nav__link--active' : '' }}">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="public-nav__actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-ghost">Beranda</a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn-primary">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
            @endauth
        </div>

        <button class="public-nav__hamburger" id="hamburger" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu">
            <span></span><span></span><span></span>
        </button>
    </div>

    <div class="public-nav__mobile" id="mobileMenu">
        @foreach($links as $link)
            <a href="{{ $link['href'] }}" class="public-nav__link {{ $active === $link['key'] ? 'public-nav__link--active' : '' }}">{{ $link['label'] }}</a>
        @endforeach
        <div class="public-nav__mobile-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-ghost">Beranda</a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn-primary">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
            @endauth
        </div>
    </div>
</nav>
