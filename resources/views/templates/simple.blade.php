{{-- TEMPLATE 1 - SIMPLE: minimal resume layout, white, one column, label column on the left. --}}
@php
    $sub = $tpl->tagline($p);
    $education = $p->education ?? [];
    $skills = $p->skills ?? [];
    $projects = $p->projects ?? [];
    $jobs = $p->work_experience ?? [];
    $links = $p->social_links ?? [];
@endphp
<article class="tpl tpl-simple">
    <header class="s-header">
        @include('partials.avatar', ['class' => 's-avatar'])
        <div>
            <h1>{{ $p->full_name }}</h1>
            @if ($sub !== '')
                <p class="s-tagline">{{ $sub }}</p>
            @endif
        </div>
    </header>

    @if (! empty($p->about_me))
        <section class="s-section">
            <h2>About Me</h2>
            <div><p class="pre">{{ $p->about_me }}</p></div>
        </section>
    @endif

    <section class="s-section">
        <h2>Contact Information</h2>
        <dl class="s-contact">
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

    @if (count($education) > 0)
        <section class="s-section">
            <h2>Education</h2>
            <div>
                @foreach ($education as $e)
                    <div class="s-entry">
                        <h3>{{ $e['school'] ?? '' }}</h3>
                        <p class="s-meta">{{ implode(' | ', array_filter([$e['degree'] ?? '', $tpl->range($e['startYear'] ?? '', $e['endYear'] ?? '')])) }}</p>
                        @if (! empty($e['description']))
                            <p class="pre">{{ $e['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (count($skills) > 0)
        <section class="s-section">
            <h2>Skills</h2>
            <ul class="s-skills">
                @foreach ($skills as $s)
                    <li><strong>{{ $s['name'] ?? '' }}</strong> <span>{{ $s['level'] ?? '' }}</span></li>
                @endforeach
            </ul>
        </section>
    @endif

    @if (count($projects) > 0)
        <section class="s-section">
            <h2>Projects</h2>
            <div>
                @foreach ($projects as $pr)
                    <div class="s-entry">
                        <h3>{{ $pr['title'] ?? '' }}</h3>
                        @if (! empty($pr['technologies']))
                            <p class="s-meta">{{ $pr['technologies'] }}</p>
                        @endif
                        @if (! empty($pr['description']))
                            <p class="pre">{{ $pr['description'] }}</p>
                        @endif
                        @if (! empty($pr['link']))
                            <p><a href="{{ $pr['link'] }}" target="_blank" rel="noopener noreferrer">View project</a></p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (count($jobs) > 0)
        <section class="s-section">
            <h2>Work Experience</h2>
            <div>
                @foreach ($jobs as $w)
                    <div class="s-entry">
                        <h3>{{ $w['jobTitle'] ?? '' }}, {{ $w['company'] ?? '' }}</h3>
                        <p class="s-meta">{{ $tpl->range($w['startDate'] ?? '', $w['endDate'] ?? '') }}</p>
                        @if (! empty($w['description']))
                            <p class="pre">{{ $w['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (count($links) > 0)
        <section class="s-section">
            <h2>Social Links</h2>
            <ul class="s-links">
                @foreach ($links as $s)
                    <li>
                        <strong>{{ $tpl->platform($s['platform'] ?? '') }}</strong>
                        <a href="{{ $s['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $s['url'] ?? '' }}</a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
</article>
