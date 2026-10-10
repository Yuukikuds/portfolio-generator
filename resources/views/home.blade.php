@extends('layouts.app')

@section('title', 'PortfolioCraft - Create your professional portfolio')

@section('content')
@php
    $stats = [
        ['3', 'Professional templates'],
        ['7', 'Portfolio sections'],
        ['0', 'Sign-ups needed'],
        ['Free', 'To create and edit'],
    ];

    $steps = [
        ['Enter your information', 'Fill in one organized form: your details, photo, education, skills, projects, experience, and links.'],
        ['Choose a template', 'Compare Simple, Modern, and Creative using your own information, then pick the one you like.'],
        ['Preview and manage', 'See your finished portfolio, switch templates, edit it, or delete it whenever you want.'],
    ];

    $showcase = [
        'simple' => 'A white, single-column resume layout. Calm, easy to read, and perfect for a classic first impression.',
        'modern' => 'A bold header, skill badges, and project cards. A polished look with clear structure.',
        'creative' => 'A dark sidebar, skill rings, and case-study projects. Distinctive, yet still clean and professional.',
    ];

    $faq = [
        ['Is PortfolioCraft free to use?', 'Yes. There are no payments or subscriptions in this project. You can create, edit, and delete portfolios at no cost.'],
        ['Do I need to create an account?', 'No. Fill in the form and your portfolio is saved online. You can find it again any time on the Manage page.'],
        ['Can I change my template later?', 'Yes. Open the preview and use the template buttons, or choose Change Template from the Manage page. Your information stays exactly the same.'],
        ['Does it work on my phone?', 'Yes. The forms, the preview, and all three templates adjust to phones, tablets, and desktop screens.'],
        ['Is my information private?', 'Portfolios are stored in an online database and listed on the Manage page. Because this is a school project without sign-in, please avoid entering private details you would not want others to see.'],
    ];
@endphp

<div class="home">

    {{-- ================= Hero ================= --}}
    <section class="hm-hero" aria-labelledby="hero-title">
        <div class="hm-hero-copy">
            <span class="hm-badge"><span class="hm-dot" aria-hidden="true"></span>Free portfolio builder</span>
            <h1 id="hero-title" class="hm-title">Build a portfolio that shows your <span class="hm-accent">best work</span></h1>
            <p class="hm-sub">Create your professional portfolio, choose your style, and showcase your work. Fill in one simple form, pick a template, and see a finished portfolio in minutes.</p>
            <div class="hm-actions">
                <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-large">Create Portfolio {!! $icons->svg('arrow') !!}</a>
                <a href="#templates" class="btn btn-secondary btn-large">View Templates</a>
            </div>
            <ul class="hm-trust">
                <li>No sign-up needed</li>
                <li>Saved online</li>
                <li>Edit any time</li>
            </ul>
        </div>

        <div class="hm-visual" aria-hidden="true">
            <div class="hm-browser">
                <div class="hm-browser-bar"><span></span><span></span><span></span><div class="hm-url"></div></div>
                <div class="hm-site">
                    <div class="hm-site-hero">
                        <div class="hm-site-avatar"></div>
                        <div class="hm-site-lines"><b></b><b></b></div>
                    </div>
                    <div class="hm-site-chips"><span></span><span></span><span></span><span></span></div>
                    <div class="hm-site-cards"><div></div><div></div><div></div></div>
                </div>
            </div>
            <div class="hm-chip hm-chip-a">{!! $icons->svg('check') !!} Modern template</div>
            <div class="hm-chip hm-chip-b">{!! $icons->svg('cloud') !!} Saved online</div>
        </div>
    </section>

    {{-- ================= Quick facts ================= --}}
    <section class="hm-stats" aria-label="Quick facts">
        @foreach ($stats as $stat)
            <div class="hm-stat"><strong>{{ $stat[0] }}</strong><span>{{ $stat[1] }}</span></div>
        @endforeach
    </section>

    {{-- ================= Features ================= --}}
    <section class="hm-section" aria-labelledby="features-title">
        <div class="hm-head">
            <span class="eyebrow">Features</span>
            <h2 id="features-title">Everything you need to present your work</h2>
            <p>A simple tool that turns your information into a finished, professional portfolio.</p>
        </div>

        <div class="hm-bento">
            <article class="hm-card hm-a">
                <div class="icon-box">{!! $icons->svg('profile') !!}</div>
                <h3>Complete profile builder</h3>
                <p>Add your details, education, skills, projects, work experience, and links in one organized form, with helpful messages along the way.</p>
                <div class="hm-ill-form" aria-hidden="true"><span></span><span></span><span class="hm-ill-btn"></span></div>
            </article>

            <article class="hm-card hm-b">
                <div class="icon-box">{!! $icons->svg('layout') !!}</div>
                <h3>Three polished templates</h3>
                <p>Switch between Simple, Modern, and Creative. Your information stays the same.</p>
                <div class="hm-ill-tpl" aria-hidden="true"><span></span><span></span><span></span></div>
            </article>

            <article class="hm-card hm-c">
                <div class="icon-box">{!! $icons->svg('cloud') !!}</div>
                <h3>Saved online</h3>
                <p>Your portfolio lives in a cloud database, so it is still there when you come back.</p>
            </article>

            <article class="hm-card hm-d">
                <div class="icon-box">{!! $icons->svg('edit') !!}</div>
                <h3>Edit any time</h3>
                <p>Update your details or change the template whenever you like.</p>
            </article>

            <article class="hm-card hm-e">
                <div class="icon-box">{!! $icons->svg('device') !!}</div>
                <h3>Looks good everywhere</h3>
                <p>Layouts adapt to desktop, tablet, and mobile, in both light and dark mode.</p>
            </article>
        </div>
    </section>

    {{-- ================= How it works ================= --}}
    <section class="hm-section" aria-labelledby="how-title">
        <div class="hm-head">
            <span class="eyebrow">How it works</span>
            <h2 id="how-title">From empty page to finished portfolio</h2>
            <p>Three simple steps. You are always one click away from editing.</p>
        </div>
        <ol class="hm-steps">
            @foreach ($steps as $index => $step)
                <li class="hm-step">
                    <span class="hm-step-no" aria-hidden="true">{{ $index + 1 }}</span>
                    <h3>{{ $step[0] }}</h3>
                    <p>{{ $step[1] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- ================= Template showcase ================= --}}
    <section class="hm-section" id="templates" aria-labelledby="templates-title">
        <div class="hm-head">
            <span class="eyebrow">Templates</span>
            <h2 id="templates-title">Three styles, one set of information</h2>
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
                        <a href="{{ route('portfolios.create') }}" class="hm-link">Start creating {!! $icons->svg('arrow') !!}</a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ================= FAQ ================= --}}
    <section class="hm-section" aria-labelledby="faq-title">
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
    </section>

    {{-- ================= Final call to action ================= --}}
    <section class="hm-cta" aria-labelledby="cta-title">
        <h2 id="cta-title">Ready to build your portfolio?</h2>
        <p>It only takes a few minutes, and you can edit everything later.</p>
        <div class="hm-actions hm-actions-center">
            <a href="{{ route('portfolios.create') }}" class="btn btn-light btn-large">Create Portfolio {!! $icons->svg('arrow') !!}</a>
            <a href="{{ route('portfolios.index') }}" class="btn btn-outline-light btn-large">Manage Portfolios</a>
        </div>
    </section>

</div>
@endsection
