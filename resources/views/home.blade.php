@extends('layouts.app')

@section('title', 'PortfolioCraft - Create your professional portfolio')
@section('bare', '1')

@section('content')
@php
    // Guests start by creating an account; logged-in users go straight to the form.
    $startUrl = auth()->check() ? route('portfolios.create') : route('register');
    $startLabel = auth()->check() ? 'Create Portfolio' : 'Get Started';
    $stats = [
        ['3', 'Professional templates'],
        ['7', 'Portfolio sections'],
        ['1', 'Private account'],
        ['Free', 'To create and edit'],
    ];

    $features = [
        ['profile', 'Complete profile builder', 'Add your details, education, skills, projects, work experience, and links in one organized form.'],
        ['layout', 'Three polished templates', 'Switch between Simple, Modern, and Creative. Your information stays exactly the same.'],
        ['cloud', 'Saved online', 'Your portfolio lives in a cloud database, so it is still there when you log back in.'],
        ['edit', 'Edit any time', 'Update your details or change the template whenever you like.'],
        ['device', 'Looks good everywhere', 'Layouts adapt to desktop, tablet, and mobile, in both light and dark mode.'],
        ['bolt', 'Ready in minutes', 'Fill in one form, pick a style, and see a finished portfolio right away.'],
    ];

    $steps = [
        ['Enter your information', 'Fill in one organized form: your details, photo, education, skills, projects, experience, and links.'],
        ['Choose a template', 'Compare Simple, Modern, and Creative using your own information, then pick the one you like.'],
        ['Preview and manage', 'See your finished portfolio, switch templates, edit it, or delete it whenever you want.'],
    ];

    $sections = ['Personal', 'About', 'Education', 'Skills', 'Projects', 'Experience', 'Links'];

    $showcase = [
        'simple' => 'A white, single-column resume layout. Calm, easy to read, and perfect for a classic first impression.',
        'modern' => 'A bold header, skill badges, and project cards. A polished look with clear structure.',
        'creative' => 'A dark sidebar, skill rings, and case-study projects. Distinctive, yet still clean and professional.',
    ];

    $faq = [
        ['Is PortfolioCraft free to use?', 'Yes. There are no payments or subscriptions in this project. You can create, edit, and delete portfolios at no cost.'],
        ['Do I need to create an account?', 'Yes. Create a free account with your email and a password, then log in any time to find your portfolios on the Manage page.'],
        ['Can I change my template later?', 'Yes. Open the preview and use the template buttons, or choose Change Template from the Manage page. Your information stays exactly the same.'],
        ['Does it work on my phone?', 'Yes. The forms, the preview, and all three templates adjust to phones, tablets, and desktop screens.'],
        ['Is my information private?', 'Yes. Your password is stored only as a secure hash, and every portfolio belongs to one account. Other people cannot open, edit, or delete your portfolios.'],
    ];
@endphp

<div class="home">

    {{-- ================= Hero ================= --}}
    <section class="hm-hero" aria-labelledby="hero-title">
        <div class="hm-hero-copy">
            <span class="hm-badge"><span class="hm-dot" aria-hidden="true"></span>Free portfolio builder</span>
            <h1 id="hero-title" class="hm-title">Build a portfolio that shows <span class="grad">your best work</span></h1>
            <p class="hm-sub">Create your professional portfolio, choose your style, and showcase your work. Fill in one simple form, pick a template, and see a finished portfolio in minutes.</p>
            <div class="hm-actions">
                <a href="{{ $startUrl }}" class="btn btn-primary btn-large">{{ $startLabel }} {!! $icons->svg('arrow') !!}</a>
                <a href="#templates" class="btn btn-secondary btn-large">View Templates</a>
            </div>
            <ul class="hm-trust">
                <li>Private to your account</li>
                <li>Saved online</li>
                <li>Edit any time</li>
            </ul>
        </div>

        <div class="hero-stage" aria-hidden="true">
            <span class="streak s1"></span><span class="streak s2"></span><span class="streak s3"></span>
            <span class="streak s4"></span><span class="streak s5"></span><span class="streak s6"></span>
            <div class="glow-beam"></div>
            <div class="device">
                <div class="screen">
                    <div class="scr-top"><span>9:41</span><span>100%</span></div>
                    <div class="scr-body">
                        <div class="scr-hero">
                            <div class="scr-avatar"></div>
                            <div class="scr-lines"><b></b><b></b></div>
                        </div>
                        <p class="scr-label">Skills</p>
                        <div class="scr-chips"><span>Design</span><span>Laravel</span><span>JavaScript</span><span>UI/UX</span></div>
                        <p class="scr-label">Projects</p>
                        <div class="scr-card"><i></i><div><b></b><b></b></div></div>
                        <div class="scr-card"><i></i><div><b></b><b></b></div></div>
                        <div class="scr-card"><i></i><div><b></b><b></b></div></div>
                    </div>
                </div>
            </div>
            <div class="hero-fade"></div>
        </div>
    </section>

    {{-- ================= Features ================= --}}
    <section class="hm-section" id="features" aria-labelledby="features-title">
        <div class="container">
            <div class="hm-head">
                <span class="eyebrow">Features</span>
                <h2 id="features-title">Everything you need to present your work</h2>
                <p>A simple tool that turns your information into a finished, professional portfolio.</p>
            </div>

            <div class="fx-grid">
                @foreach ($features as $i => [$icon, $title, $text])
                    <article class="fx-card {{ $i === 0 ? 'is-lit' : '' }}">
                        <div class="icon-box">{!! $icons->svg($icon) !!}</div>
                        <h3>{{ $title }}</h3>
                        <p>{{ $text }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= Template showcase ================= --}}
    <section class="hm-section" id="templates" aria-labelledby="templates-title">
        <div class="container">
            <div class="panel">
                <div class="hm-head">
                    <span class="eyebrow">Templates</span>
                    <h2 id="templates-title">Your story,<br><span class="grad">beautifully presented</span></h2>
                    <p>Write your content once, then choose the style that fits you best.</p>
                </div>
                <div class="hm-tpl-grid">
                    @foreach ($tpl->templates() as $id => $info)
                        <article class="hm-tpl">
                            @if ($id === 'simple')
                                <div class="hm-art hm-art-simple" aria-hidden="true">
                                    <div class="a-head"><i></i><div><b></b><b></b></div></div>
                                    <div class="a-rule"></div>
                                    @for ($row = 0; $row < 3; $row++)
                                        <div class="a-row"><b></b><div><b></b><b></b></div></div>
                                    @endfor
                                </div>
                            @elseif ($id === 'modern')
                                <div class="hm-art hm-art-modern" aria-hidden="true">
                                    <div class="a-hero"><i></i><div><b></b><b></b></div></div>
                                    <div class="a-stats"><span></span><span></span><span></span></div>
                                    <div class="a-cards"><span></span><span></span></div>
                                </div>
                            @else
                                <div class="hm-art hm-art-creative" aria-hidden="true">
                                    <div class="a-side"><i></i><b></b><div class="a-rings"><span></span><span></span><span></span></div></div>
                                    <div class="a-main"><em></em><b></b><b></b><div class="a-cards"><span></span><span></span></div></div>
                                </div>
                            @endif
                            <div class="hm-tpl-body">
                                <h3>{{ $info['name'] }}</h3>
                                <ul class="hm-tags">
                                    @foreach ($info['tags'] as $tag)
                                        <li>{{ $tag }}</li>
                                    @endforeach
                                </ul>
                                <p>{{ $showcase[$id] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ================= Sections orbit + quick facts ================= --}}
    <section class="hm-section" aria-labelledby="sections-title">
        <div class="container orbit-wrap">
            <div class="orbit-copy">
                <span class="eyebrow">One form</span>
                <h2 id="sections-title">Seven sections. <span class="grad">One clean form.</span></h2>
                <p>Everything a good portfolio needs, organized in the order people read it. Fill in what you have; leave out what you don't.</p>
                <div class="hm-stats" aria-label="Quick facts">
                    @foreach ($stats as $stat)
                        <div class="hm-stat"><strong>{{ $stat[0] }}</strong><span>{{ $stat[1] }}</span></div>
                    @endforeach
                </div>
            </div>

            <div class="orbit" aria-hidden="true">
                <div class="orbit-ring r1"></div>
                <div class="orbit-ring r2"></div>
                <div class="orbit-ring r3"></div>
                <div class="orbit-spin">
                    @foreach ($sections as $i => $name)
                        <div class="orb-item" style="--a: {{ round($i * 360 / count($sections), 2) }}deg"><span>{{ $name }}</span></div>
                    @endforeach
                </div>
                <div class="orbit-core">P</div>
            </div>
        </div>
    </section>

    {{-- ================= How it works ================= --}}
    <section class="hm-section" id="how" aria-labelledby="how-title">
        <div class="container">
            <div class="cta-card">
                <div class="cta-copy">
                    <span class="eyebrow">How it works</span>
                    <h2 id="how-title">From empty page to <span class="grad">finished portfolio</span></h2>
                    <p>Three simple steps. You are always one click away from editing.</p>
                </div>
                <ol class="checks">
                    @foreach ($steps as $step)
                        <li><div><strong>{{ $step[0] }}</strong><span>{{ $step[1] }}</span></div></li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- ================= FAQ ================= --}}
    <section class="hm-section" aria-labelledby="faq-title">
        <div class="container">
            <div class="hm-head">
                <span class="eyebrow">FAQ</span>
                <h2 id="faq-title">Questions, answered</h2>
            </div>
            <div class="hm-faq">
                @foreach ($faq as $item)
                    <details>
                        <summary>{{ $item[0] }}</summary>
                        <p>{{ $item[1] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= Final call to action ================= --}}
    <section class="hm-final" aria-labelledby="cta-title">
        <h2 id="cta-title">Start your <span class="grad">portfolio today</span></h2>
        <p>It only takes a few minutes, and you can edit everything later.</p>
    </section>

</div>

{{-- Highlights the menu link of the section you are looking at (Home / Features / Templates / How it works). --}}
<script>
(function () {
    var ids = ['features', 'templates', 'how'];
    var links = document.querySelectorAll('[data-nav]');
    var current = null;
    var waiting = false;

    function mark(key) {
        if (key === current) { return; }
        current = key;
        links.forEach(function (a) {
            var on = a.getAttribute('data-nav') === key;
            a.classList.toggle('active', on);
            if (on) { a.setAttribute('aria-current', key === 'home' ? 'page' : 'location'); }
            else { a.removeAttribute('aria-current'); }
        });
    }

    function update() {
        waiting = false;
        var line = window.innerHeight * 0.35;
        var key = 'home';
        var best = -Infinity;
        ids.forEach(function (id) {
            var el = document.getElementById(id);
            if (!el) { return; }
            var top = el.getBoundingClientRect().top;
            if (top <= line && top > best) { best = top; key = id; }
        });
        mark(key);
    }

    function onScroll() {
        if (!waiting) { waiting = true; window.requestAnimationFrame(update); }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
})();
</script>
@endsection
