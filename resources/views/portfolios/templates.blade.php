@extends('layouts.app')

@section('title', 'Choose a Template - PortfolioCraft')

@section('content')
@include('partials.stepper', ['current' => 2])

<header class="page-header">
    <h1>Choose Your Template</h1>
    <p class="lead">Pick one design. Every preview below uses your own information.</p>
</header>

@error('template')
    <div class="message message-error" role="alert">{{ $message }}</div>
@enderror

<form method="POST" action="{{ route('templates.save', $portfolio->id) }}">
    @csrf

    <div class="template-grid" role="radiogroup" aria-label="Portfolio templates">
        @foreach ($tpl->templates() as $id => $info)
            <div class="template-option">
                {{-- The radio button comes first so the CSS can style the card next to it. --}}
                <input class="tpl-radio" type="radio" id="tpl-{{ $id }}" name="template" value="{{ $id }}" @checked($selected === $id)>
                <span class="check-badge">{!! $icons->svg('tick') !!} Selected</span>

                <article class="template-card">
                    <div class="preview-frame" role="img" aria-label="Preview of the {{ $info['name'] }} template using your information">
                        <div class="preview-inner" inert>
                            <div class="tpl-wrap">@include('templates.' . $id, ['p' => $portfolio])</div>
                        </div>
                    </div>
                    <div class="template-body">
                        <h2>{{ $info['name'] }}</h2>
                        <ul class="tag-list">
                            @foreach ($info['tags'] as $tag)
                                <li>{{ $tag }}</li>
                            @endforeach
                        </ul>
                        <p>{{ $info['description'] }}</p>
                        <label for="tpl-{{ $id }}" class="btn btn-secondary template-pick">Select {{ $info['name'] }}</label>
                    </div>
                </article>
            </div>
        @endforeach
    </div>

    <div class="action-bar">
        <p class="action-note">Pick a template, then continue to the preview.</p>
        <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="btn btn-secondary btn-large">Edit Information</a>
        <button type="submit" class="btn btn-primary btn-large">Preview Portfolio</button>
    </div>
</form>
@endsection
