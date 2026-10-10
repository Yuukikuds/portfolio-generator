@php
    $home = route('home');
    $links = [
        ['Features', $home . '#features'],
        ['Templates', $home . '#templates'],
        ['Reviews', $home . '#reviews'],
        ['How it works', $home . '#how'],
    ];
@endphp
<header class="navbar">
    <div class="navbar-inner container">
        <a href="{{ $home }}" class="brand" aria-label="PortfolioCraft home">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="m7 6 6 6-6 6"/><path d="m13 6 6 6-6 6"/></svg>
            </span>
            <span class="brand-text">PortfolioCraft</span>
        </a>

        <nav aria-label="Main" class="nav">
            <ul class="nav-pill">
                @foreach ($links as $link)
                    <li><a href="{{ $link[1] }}">{{ $link[0] }}</a></li>
                @endforeach
                <li>
                    <a href="{{ route('portfolios.index') }}" @if (request()->routeIs('portfolios.index')) class="active" aria-current="page" @endif>Manage</a>
                </li>
            </ul>

            <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-glow btn-small" aria-label="Create Portfolio">
                <span class="cta-long">Create Portfolio</span>
                <span class="cta-short">Create</span>
            </a>

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

            {{-- Phone menu (no JavaScript: a native details element) --}}
            <details class="nav-menu">
                <summary aria-label="Open menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </summary>
                <div class="nav-menu-panel">
                    @foreach ($links as $link)
                        <a href="{{ $link[1] }}">{{ $link[0] }}</a>
                    @endforeach
                    <a href="{{ route('portfolios.index') }}">Manage</a>
                    <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-glow">Create Portfolio</a>
                </div>
            </details>
        </nav>
    </div>
</header>
