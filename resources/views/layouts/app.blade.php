@php
    $cookieTheme = request()->cookie('theme');
    // Dark is the default look; the sun/moon button saves the visitor's choice in a cookie.
    $theme = in_array($cookieTheme, ['light', 'dark'], true) ? $cookieTheme : 'dark';
@endphp
<!doctype html>
<html lang="en" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PortfolioCraft - Create your professional portfolio, choose your style, and showcase your work.">
    <meta name="color-scheme" content="dark light">
    <meta name="theme-color" content="{{ $theme === 'light' ? '#fbf6f9' : '#0b0b0c' }}">
    <title>@yield('title', 'PortfolioCraft')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='0' y2='1'%3E%3Cstop offset='0' stop-color='%23f84fa6'/%3E%3Cstop offset='1' stop-color='%23d6217f'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='64' height='64' rx='16' fill='url(%23g)'/%3E%3Ctext x='32' y='45' font-size='36' font-family='Arial' font-weight='700' text-anchor='middle' fill='white'%3EP%3C/text%3E%3C/svg%3E">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/templates.css') }}?v={{ @filemtime(public_path('css/templates.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ @filemtime(public_path('css/auth.css')) }}">
    <script src="{{ asset('js/password-toggle.js') }}?v={{ @filemtime(public_path('js/password-toggle.js')) }}" defer></script>
</head>
<body>
    <a href="#main" class="skip-link">Skip to main content</a>

    @include('partials.navbar')

    {{-- Pop-up notification after "Save Portfolio" (fades out by itself, no JavaScript). --}}
    @if (session('saved'))
        <div class="toast" role="status" aria-live="polite">
            <span class="toast-icon" aria-hidden="true">&#10003;</span>
            <span>{{ session('saved') }}</span>
        </div>
    @endif

    {{-- Pages that paint edge to edge (the home page) set @section('bare'). --}}
    @hasSection('bare')
        <main id="main" class="main-area main-bare">
            @if (session('status') || session('error'))
                <div class="container">@include('partials.flash')</div>
            @endif
            @yield('content')
        </main>
    @else
        <main id="main" class="container main-area">
            @include('partials.flash')
            @yield('content')
        </main>
    @endif

    @include('partials.footer')
</body>
</html>
