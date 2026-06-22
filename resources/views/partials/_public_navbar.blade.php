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
            <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand-600 text-white">
                <svg viewBox="0 0 28 28" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M8 10h12M8 14h8M8 18h10"/>
                </svg>
            </span>
            <span class="public-nav__brand-name">LearnFit</span>
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
                <a href="{{ url('/welcome') }}" class="btn-ghost">Dashboard</a>
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
                <a href="{{ url('/welcome') }}" class="btn-ghost">Dashboard</a>
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
