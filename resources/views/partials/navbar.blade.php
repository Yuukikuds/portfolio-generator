@php
    // Order in the menu: Home, Features, Templates, How it works (and Manage when logged in).
    // The 4th value is a short key used by the little scroll highlighter on the home page.
    $links = [
        ['Home', route('home'), request()->routeIs('home'), 'home'],
        ['Features', route('home') . '#features', false, 'features'],
        ['Templates', route('home') . '#templates', false, 'templates'],
        ['How it works', route('home') . '#how', false, 'how'],
    ];
    // "Manage" is only for logged-in users.
    if (auth()->check()) {
        $links[] = ['Manage', route('portfolios.index'), request()->routeIs('portfolios.index'), 'manage'];
    }
@endphp
<header class="navbar">
    <div class="navbar-inner container">
        <a href="{{ route('home') }}" class="brand" aria-label="PortfolioCraft home">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span class="brand-text">PortfolioCraft</span>
        </a>

        <nav aria-label="Main" class="nav-pill">
            <ul class="nav-links">
                @foreach ($links as [$label, $url, $active, $key])
                    <li><a href="{{ $url }}" data-nav="{{ $key }}" @if ($active) class="active" aria-current="page" @endif>{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="nav-actions">
            @auth
                <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-small" aria-label="Create Portfolio">
                    <span class="cta-long">Create Portfolio</span>
                    <span class="cta-short">Create</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-small">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-small nav-login">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-small" aria-label="Get Started">
                    <span class="cta-long">Get Started</span>
                    <span class="cta-short">Start</span>
                </a>
            @endauth

            {{-- Light / dark mode. Both buttons exist; the stylesheet shows the right one. --}}
            <form method="POST" action="{{ route('theme') }}" class="theme-form">
                @csrf
                <button type="submit" name="theme" value="dark" class="theme-toggle theme-to-dark" aria-label="Switch to dark mode" title="Switch to dark mode">
                    {!! $icons->svg('moon') !!}
                </button>
                <button type="submit" name="theme" value="light" class="theme-toggle theme-to-light" aria-label="Switch to light mode" title="Switch to light mode">
                    {!! $icons->svg('sun') !!}
                </button>
            </form>

            {{-- Phone menu: a plain <details>, so it works without JavaScript. --}}
            <details class="menu">
                <summary class="theme-toggle" aria-label="Open menu">{!! $icons->svg('menu') !!}</summary>
                <div class="menu-panel">
                    @auth
                        <p class="menu-who">{{ auth()->user()->email }}</p>
                    @endauth
                    @foreach ($links as [$label, $url, $active, $key])
                        <a href="{{ $url }}" data-nav="{{ $key }}" @if ($active) class="active" aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                    @auth
                        <a href="{{ route('portfolios.create') }}">Create Portfolio</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="menu-link">Log out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        <a href="{{ route('register') }}">Get Started</a>
                    @endauth
                </div>
            </details>
        </div>
    </div>
</header>
