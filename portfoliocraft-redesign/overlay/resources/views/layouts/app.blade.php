@php
    $cookieTheme = request()->cookie('theme');
    $theme = in_array($cookieTheme, ['light', 'dark'], true) ? $cookieTheme : 'dark';
@endphp
<!doctype html>
<html lang="en" data-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PortfolioCraft - Create your professional portfolio, choose your style, and showcase your work.">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#141414">
    <title>@yield('title', 'PortfolioCraft')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23db2777'/%3E%3Ctext x='32' y='44' font-size='36' font-family='Arial' font-weight='700' text-anchor='middle' fill='white'%3EP%3C/text%3E%3C/svg%3E">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/templates.css') }}?v={{ @filemtime(public_path('css/templates.css')) }}">
</head>
<body>
    <a href="#main" class="skip-link">Skip to main content</a>

    @include('partials.navbar')

    <main id="main" class="container main-area">
        @include('partials.flash')
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
