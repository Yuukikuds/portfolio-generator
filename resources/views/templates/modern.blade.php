{{-- TEMPLATE 2 - MODERN: blue hero, stat cards, skill badges, project cards. --}}
@php
    $sub = $tpl->tagline($p);
    $education = $p->education ?? [];
    $skills = $p->skills ?? [];
    $projects = $p->projects ?? [];
    $jobs = $p->work_experience ?? [];
    $links = $p->social_links ?? [];
    $stats = array_filter([
        ['Projects', count($projects)],
        ['Skills', count($skills)],
        ['Experience', count($jobs)],
    ], fn ($stat) => $stat[1] > 0);
@endphp
<article class="tpl tpl-modern">
    <header class="m-hero">
        @include('partials.avatar', ['class' => 'm-avatar'])
        <div class="m-hero-text">
            <p class="m-eyebrow">Portfolio</p>
            <h1>{{ $p->full_name }}</h1>
            @if ($sub !== '')
                <p class="m-sub">{{ $sub }}</p>
            @endif
            @if (! empty($p->about_me))
                <p class="m-intro">{{ $tpl->shorten($p->about_me) }}</p>
            @endif
            <div class="m-pills">
                <a class="m-pill m-pill-main" href="mailto:{{ $p->email }}">Email me</a>
                @if (! empty($p->contact_number))
                    <a class="m-pill" href="{{ $tpl->telHref($p->contact_number) }}">Call</a>
                @endif
                @foreach ($links as $s)
                    <a class="m-pill" href="{{ $s['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $tpl->platform($s['platform'] ?? '') }}</a>
                @endforeach
            </div>
        </div>
    </header>

    @if (count($stats) > 0)
        <ul class="m-stats">
            @foreach ($stats as $stat)
                <li><strong>{{ $stat[1] }}</strong><span>{{ $stat[0] }}</span></li>
            @endforeach
        </ul>
    @endif

    <div class="m-body">
        <div class="m-grid">
            @if (! empty($p->about_me))
                <section class="m-card m-span">
                    <h2>About Me</h2>
                    <p class="pre">{{ $p->about_me }}</p>
                </section>
            @endif

            @if (count($education) > 0)
                <section class="m-card">
                    <h2>Education</h2>
                    @foreach ($education as $e)
                        <div class="m-item">
                            <h3>{{ $e['school'] ?? '' }}</h3>
                            <p class="m-meta">{{ implode(' | ', array_filter([$e['degree'] ?? '', $tpl->range($e['startYear'] ?? '', $e['endYear'] ?? '')])) }}</p>
                            @if (! empty($e['description']))
                                <p class="pre">{{ $e['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </section>
            @endif

            <section class="m-card">
                <h2>Contact</h2>
                <dl class="m-contact">
                    <dt>Email</dt>
                    <dd><a href="mailto:{{ $p->email }}">{{ $p->email }}</a></dd>
                    @if (! empty($p->contact_number))
                        <dt>Phone</dt>
                        <dd><a href="{{ $tpl->telHref($p->contact_number) }}">{{ $p->contact_number }}</a></dd>
                    @endif
                    @if (! empty($p->address))
                        <dt>Address</dt>
                        <dd>{{ $p->address }}</dd>
                    @endif
                </dl>
            </section>

            @if (count($skills) > 0)
                <section class="m-card m-span">
                    <h2>Skills</h2>
                    <ul class="m-badges">
                        @foreach ($skills as $s)
                            <li class="m-badge" data-level="{{ $s['level'] ?? '' }}">{{ $s['name'] ?? '' }}<small>{{ $s['level'] ?? '' }}</small></li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if (count($projects) > 0)
                <section class="m-card m-span">
                    <h2>Projects</h2>
                    <div class="m-projects">
                        @foreach ($projects as $pr)
                            <div class="m-project">
                                <h3>{{ $pr['title'] ?? '' }}</h3>
                                @if (count($tpl->split($pr['technologies'] ?? '')) > 0)
                                    <ul class="m-chips">
                                        @foreach ($tpl->split($pr['technologies'] ?? '') as $chip)
                                            <li>{{ $chip }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if (! empty($pr['description']))
                                    <p class="pre">{{ $pr['description'] }}</p>
                                @endif
                                @if (! empty($pr['link']))
                                    <a class="m-link" href="{{ $pr['link'] }}" target="_blank" rel="noopener noreferrer">View project</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (count($jobs) > 0)
                <section class="m-card m-span">
                    <h2>Work Experience</h2>
                    @foreach ($jobs as $w)
                        <div class="m-job">
                            <h3>{{ $w['jobTitle'] ?? '' }}</h3>
                            <p class="m-meta">{{ implode(' | ', array_filter([$w['company'] ?? '', $tpl->range($w['startDate'] ?? '', $w['endDate'] ?? '')])) }}</p>
                            @if (! empty($w['description']))
                                <p class="pre">{{ $w['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </section>
            @endif

            @if (count($links) > 0)
                <section class="m-card m-span">
                    <h2>Social Links</h2>
                    <ul class="m-social">
                        @foreach ($links as $s)
                            <li>
                                <strong>{{ $tpl->platform($s['platform'] ?? '') }}</strong>
                                <a href="{{ $s['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $s['url'] ?? '' }}</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</article>
