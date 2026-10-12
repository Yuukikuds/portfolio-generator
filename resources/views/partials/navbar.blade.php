@php
    $links = [
        ['Features', route('home') . '#features', false],
        ['How it works', route('home') . '#how', false],
        ['Templates', route('home') . '#templates', false],
        ['Manage', route('portfolios.index'), request()->routeIs('portfolios.index')],
    ];
@endphp
<header class="navbar">
    <div class="navbar-inner container">
        <a href="{{ route('home') }}" class="brand" aria-label="PortfolioCraft home">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span class="brand-text">PortfolioCraft</span>
        </a>

        <nav aria-label="Main" class="nav-pill">
            <ul class="nav-links">
                @foreach ($links as [$label, $url, $active])
                    <li><a href="{{ $url }}" @if ($active) class="active" aria-current="page" @endif>{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="nav-actions">
            <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-small" aria-label="Create Portfolio">
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

            {{-- Phone menu: a plain <details>, so it works without JavaScript. --}}
            <details class="menu">
                <summary class="theme-toggle" aria-label="Open menu">{!! $icons->svg('menu') !!}</summary>
                <div class="menu-panel">
                    @foreach ($links as [$label, $url, $active])
                        <a href="{{ $url }}" @if ($active) class="active" aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </div>
            </details>
        </div>
    </div>
</header>
