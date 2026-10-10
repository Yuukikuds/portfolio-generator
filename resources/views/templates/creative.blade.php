{{-- TEMPLATE 3 - CREATIVE: dark gradient sidebar with skill rings, a big editorial "hello",
     case-study project cards, and a gradient timeline. Creative but clean. --}}
@php
    $sub = $tpl->tagline($p);
    $education = $p->education ?? [];
    $skills = $p->skills ?? [];
    $projects = $p->projects ?? [];
    $jobs = $p->work_experience ?? [];
    $links = $p->social_links ?? [];
    $number = 0;
@endphp
<article class="tpl tpl-creative">
    <aside class="c-side">
        <div class="c-id">
            <div class="c-avatar-wrap">
                @include('partials.avatar', ['class' => 'c-avatar'])
            </div>
            <h1>{{ $p->full_name }}</h1>
            @if ($sub !== '')
                <p class="c-role">{{ $sub }}</p>
            @endif
        </div>

        <section class="c-block">
            <h2>Contact</h2>
            <ul class="c-contact">
                <li><a href="mailto:{{ $p->email }}">{{ $p->email }}</a></li>
                @if (! empty($p->contact_number))
                    <li><a href="{{ $tpl->telHref($p->contact_number) }}">{{ $p->contact_number }}</a></li>
                @endif
                @if (! empty($p->address))
                    <li>{{ $p->address }}</li>
                @endif
            </ul>
        </section>

        @if (count($skills) > 0)
            <section class="c-block">
                <h2>Skills</h2>
                <ul class="c-rings">
                    @foreach ($skills as $s)
                        <li>
                            <div class="c-ring" style="--p: {{ $tpl->levelPercent($s['level'] ?? '') }}" aria-hidden="true">
                                <span>{{ mb_strtoupper(mb_substr((string) ($s['name'] ?? '?'), 0, 1)) }}</span>
                            </div>
                            <div class="c-ring-text">
                                <strong>{{ $s['name'] ?? '' }}</strong>
                                <small>{{ $s['level'] ?? '' }}</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if (count($links) > 0)
            <section class="c-block">
                <h2>Find me online</h2>
                <ul class="c-links">
                    @foreach ($links as $s)
                        <li><a href="{{ $s['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $tpl->platform($s['platform'] ?? '') }}</a></li>
                    @endforeach
                </ul>
            </section>
        @endif
    </aside>

    <div class="c-main">
        <header class="c-intro">
            <p class="c-kicker">Hello, I am</p>
            <p class="c-headline"><span class="c-name">{{ $tpl->firstName($p->full_name) }}</span><span class="c-stop">.</span></p>
            @if (! empty($p->about_me))
                <p class="c-lead pre">{{ $p->about_me }}</p>
            @endif
        </header>

        @if (count($projects) > 0)
            @php $number++; @endphp
            <section class="c-section">
                <h2><span class="c-sec-num">{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span> Selected projects</h2>
                <ol class="c-projects">
                    @foreach ($projects as $index => $pr)
                        <li class="c-project">
                            <span class="c-bignum" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $pr['title'] ?? '' }}</h3>
                            @if (count($tpl->split($pr['technologies'] ?? '')) > 0)
                                <ul class="c-chips">
                                    @foreach ($tpl->split($pr['technologies'] ?? '') as $chip)
                                        <li>{{ $chip }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if (! empty($pr['description']))
                                <p class="pre">{{ $pr['description'] }}</p>
                            @endif
                            @if (! empty($pr['link']))
                                <a class="c-link" href="{{ $pr['link'] }}" target="_blank" rel="noopener noreferrer">View project</a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        @if (count($jobs) > 0)
            @php $number++; @endphp
            <section class="c-section">
                <h2><span class="c-sec-num">{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span> Experience</h2>
                <ol class="c-timeline">
                    @foreach ($jobs as $w)
                        <li>
                            <span class="c-dot" aria-hidden="true"></span>
                            <h3>{{ $w['jobTitle'] ?? '' }} <em>at {{ $w['company'] ?? '' }}</em></h3>
                            <p class="c-meta">{{ $tpl->range($w['startDate'] ?? '', $w['endDate'] ?? '') }}</p>
                            @if (! empty($w['description']))
                                <p class="pre">{{ $w['description'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        @if (count($education) > 0)
            @php $number++; @endphp
            <section class="c-section">
                <h2><span class="c-sec-num">{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span> Education</h2>
                <ul class="c-edu">
                    @foreach ($education as $e)
                        <li class="c-edu-card">
                            <h3>{{ $e['school'] ?? '' }}</h3>
                            <p class="c-meta">{{ implode(' | ', array_filter([$e['degree'] ?? '', $tpl->range($e['startYear'] ?? '', $e['endYear'] ?? '')])) }}</p>
                            @if (! empty($e['description']))
                                <p class="pre">{{ $e['description'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
</article>
