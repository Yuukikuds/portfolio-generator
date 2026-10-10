@extends('layouts.app')

@section('title', 'PortfolioCraft - Craft your portfolio, showcase your best work')

@section('content')
@php
    $features = [
        ['profile', 'Complete Profile Builder', 'Add your details, education, skills, projects, experience, and links in one organized form.'],
        ['layout', 'Three Polished Templates', 'Switch between Simple, Modern, and Creative. Your information always stays the same.'],
        ['cloud', 'Saved Online', 'Your portfolio lives in a cloud database, so it is there whenever you come back.'],
        ['edit', 'Edit Any Time', 'Update your details or change the template whenever you like, in a few clicks.'],
        ['device', 'Looks Good Everywhere', 'Layouts adapt to desktop, tablet, and mobile screens without extra work.'],
        ['check', 'Light and Dark Mode', 'Pick the look you prefer. Your choice is remembered on your next visit.'],
    ];

    $steps = [
        ['Enter your information', 'Fill in one organized form: details, photo, education, skills, projects, experience, and links.'],
        ['Choose a template', 'Compare Simple, Modern, and Creative using your own information, then pick your favorite.'],
        ['Preview and manage', 'See your finished portfolio, switch templates, edit it, or delete it whenever you want.'],
    ];

    $perks = [
        'Add every section of your story in one form',
        'Preview three designs with your own content',
        'Switch templates without retyping anything',
        'Saved safely in an online database',
        'Edit or delete your portfolio any time',
        'Works on phones, tablets, and desktops',
    ];

    // Sample feedback for the design. Replace with real quotes when you have them.
    $quotes = [
        ['The form is so clear. I had a finished portfolio in one sitting.', 'Alfina R.', 'Entrepreneur'],
        ['I love comparing the three templates with my own content.', 'Sarah E.', 'Student'],
        ['Dark mode and the clean layout make it a joy to use.', 'Ridan S.', 'Designer'],
        ['Simple, fast, and it looks professional on my phone too.', 'Dhimas A.', 'Developer'],
    ];

    $orbs = [
        ['layout', 20],
        ['cloud', 80],
        ['device', 140],
        ['edit', 200],
        ['profile', 260],
        ['check', 320],
    ];
@endphp

<div class="home">

    {{-- ================= Hero ================= --}}
    <section class="hm-hero" aria-labelledby="hero-title">
        <span class="hm-badge">Free portfolio builder</span>
        <h1 id="hero-title" class="hm-title">
            Craft Your Portfolio,
            <span class="hm-grad">Showcase Your Best Work</span>
        </h1>
        <p class="hm-sub">The all-in-one portfolio platform: from your details and photo to three beautiful templates, all in one place.</p>
        <div class="hm-actions">
            <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-glow">Create Portfolio</a>
            <a href="#features" class="btn btn-secondary btn-glow">Learn More</a>
        </div>

        <div class="hm-stage" aria-hidden="true">
            <span class="hm-streak hm-s1"></span><span class="hm-streak hm-s2"></span>
            <span class="hm-streak hm-s3"></span><span class="hm-streak hm-s4"></span>
            <span class="hm-beam"></span>

            <div class="hm-phone">
                <div class="hm-phone-notch"></div>
                <div class="hm-screen">
                    <div class="ph-top"><small>Good morning</small><b>Alex Rivera</b></div>
                    <div class="ph-card">
                        <span class="ph-label">Your Portfolio</span>
                        <div class="ph-row">
                            <span class="ph-thumb"></span>
                            <span class="ph-thumb ph-on"></span>
                            <span class="ph-thumb"></span>
                        </div>
                        <span class="ph-name">Modern template</span>
                    </div>
                    <div class="ph-progress">
                        <span>Profile complete</span><b>86%</b>
                        <i><u></u></i>
                    </div>
                    <div class="ph-chips"><span>PHP</span><span>Laravel</span><span>Design</span></div>
                    <div class="ph-card ph-mini"><b></b><b></b></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= Features ================= --}}
    <section class="hm-section" id="features" aria-labelledby="features-title">
        <div class="hm-head">
            <h2 id="features-title">Every Detail, Designed</h2>
            <p>We built PortfolioCraft to be more than a form. It is a calm, clear way to present your work, anytime, anywhere.</p>
        </div>
        <div class="hm-features">
            @foreach ($features as $index => $feature)
                <article class="hm-fcard {{ $index === 0 ? 'hm-fcard-lit' : '' }}">
                    <span class="hm-ficon">{!! $icons->svg($feature[0]) !!}</span>
                    <h3>{{ $feature[1] }}</h3>
                    <p>{{ $feature[2] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ================= Showcase / templates ================= --}}
    <section class="hm-section" id="templates" aria-labelledby="showcase-title">
        <div class="hm-showcase">
            <div class="hm-showcase-head">
                <h2 id="showcase-title">Your Work <span class="hm-grad">Beautifully Visualized</span></h2>
                <p>Experience a portfolio builder that looks as good as it feels. Pick the template that fits you.</p>
            </div>

            <div class="hm-showcase-body">
                <div class="hm-rail" aria-hidden="true"><i class="on"></i><i></i><i></i></div>

                <div class="hm-tilt" aria-hidden="true">
                    <span class="hm-tilt-title">Your template gallery</span>
                    <div class="hm-thumbs">
                        <div class="hm-thumb">
                            <div class="hm-art hm-art-simple">
                                <div class="a-head"><i></i><div><b></b><b></b></div></div>
                                <div class="a-rule"></div>
                                @for ($row = 0; $row < 3; $row++)
                                    <div class="a-row"><b></b><div><b></b><b></b></div></div>
                                @endfor
                            </div>
                        </div>
                        <div class="hm-thumb hm-thumb-main">
                            <div class="hm-art hm-art-modern">
                                <div class="a-hero"><i></i><div><b></b><b></b></div></div>
                                <div class="a-stats"><span></span><span></span><span></span></div>
                                <div class="a-cards"><span></span><span></span></div>
                            </div>
                        </div>
                        <div class="hm-thumb">
                            <div class="hm-art hm-art-creative">
                                <div class="a-side"><i></i><b></b><div class="a-rings"><span></span><span></span><span></span></div></div>
                                <div class="a-main"><em></em><b></b><b></b><div class="a-cards"><span></span><span></span></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hm-stack">
                    <article class="hm-stack-card">
                        <span class="hm-ficon">{!! $icons->svg('layout') !!}</span>
                        <h3>Three Templates</h3>
                        <p>Simple, Modern, and Creative. Compare them with your own information, then keep the one you love.</p>
                        <ul class="hm-tags">
                            @foreach ($tpl->templates() as $info)
                                <li>{{ $info['name'] }}</li>
                            @endforeach
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= Tools / stack ================= --}}
    <section class="hm-section hm-tools" aria-labelledby="tools-title">
        <div class="hm-tools-copy">
            <h2 id="tools-title">Built on Tools <span class="hm-grad">You Can Trust</span></h2>
            <p>Your portfolio is stored in an online PostgreSQL database and served by a fast PHP and Laravel application, so it is always ready when you are.</p>
            <dl class="hm-stats">
                <div><dt>3</dt><dd>Templates</dd></div>
                <div><dt>7</dt><dd>Sections</dd></div>
                <div><dt>0</dt><dd>Sign-ups needed</dd></div>
            </dl>
        </div>

        <div class="hm-orbit" aria-hidden="true">
            <span class="hm-ring hm-ring-1"></span>
            <span class="hm-ring hm-ring-2"></span>
            <span class="hm-ring hm-ring-3"></span>
            <div class="hm-hub"><span>{!! $icons->svg('layout') !!}</span></div>
            <div class="hm-orbs">
                @foreach ($orbs as $orb)
                    <span class="hm-orb" style="--a: {{ $orb[1] }}deg"><span>{!! $icons->svg($orb[0]) !!}</span></span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= How it works ================= --}}
    <section class="hm-section" id="how" aria-labelledby="how-title">
        <div class="hm-head">
            <h2 id="how-title">How It <span class="hm-grad">Works</span></h2>
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

    {{-- ================= Promo card ================= --}}
    <section class="hm-promo" aria-labelledby="promo-title">
        <div class="hm-promo-copy">
            <p class="hm-promo-kicker"><span class="hm-ficon hm-ficon-sm">{!! $icons->svg('edit') !!}</span> Unlock a better first impression</p>
            <h2 id="promo-title" class="hm-grad">Launch your portfolio</h2>
            <p class="hm-promo-text">Free to create and edit. No sign-up required.</p>
            <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-glow">Start Now</a>
        </div>
        <ul class="hm-checks">
            @foreach ($perks as $perk)
                <li>{!! $icons->svg('tick') !!}<span>{{ $perk }}</span></li>
            @endforeach
        </ul>
    </section>

    {{-- ================= Reviews ================= --}}
    <section class="hm-section" id="reviews" aria-labelledby="reviews-title">
        <div class="hm-head">
            <h2 id="reviews-title">Loved by Creators <span class="hm-grad">Who Care About Craft</span></h2>
            <p>Sample feedback shown for design purposes. Swipe or scroll to read more.</p>
        </div>
        <div class="hm-quotes" tabindex="0" role="region" aria-label="Sample feedback, scrollable">
            @foreach ($quotes as $quote)
                <figure class="hm-quote">
                    <div class="hm-photo" aria-hidden="true">
                        <svg viewBox="0 0 120 120"><circle cx="60" cy="44" r="22"/><path d="M16 120c0-26 20-44 44-44s44 18 44 44z"/></svg>
                    </div>
                    <blockquote>&ldquo;{{ $quote[0] }}&rdquo;</blockquote>
                    <figcaption><strong>{{ $quote[1] }}</strong><span>{{ $quote[2] }}</span></figcaption>
                </figure>
            @endforeach
        </div>
        <div class="hm-dots" aria-hidden="true"><span class="on"></span><span></span><span></span><span></span></div>
    </section>

    {{-- ================= Final call to action ================= --}}
    <section class="hm-final" aria-labelledby="final-title">
        <h2 id="final-title" class="hm-grad">Start Your Journey Today</h2>
        <p>Join creators who present their work with confidence, and build a portfolio you are proud of.</p>
        <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-glow">Create Portfolio</a>
    </section>

</div>
@endsection
