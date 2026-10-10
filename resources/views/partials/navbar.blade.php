<header class="navbar">
    <div class="navbar-inner container">
        <a href="{{ route('home') }}" class="brand" aria-label="PortfolioCraft home">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span class="brand-text">PortfolioCraft</span>
        </a>

        <nav aria-label="Main" class="nav">
            <ul class="nav-links">
                <li class="nav-home">
                    <a href="{{ route('home') }}" @if (request()->routeIs('home')) class="active" aria-current="page" @endif>Home</a>
                </li>
                <li>
                    <a href="{{ route('portfolios.index') }}" @if (request()->routeIs('portfolios.index')) class="active" aria-current="page" @endif>Manage</a>
                </li>
            </ul>

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
        </nav>
    </div>
</header>
