@extends('layouts.app')

@section('title', $portfolio->full_name . ' - Portfolio Preview')

@section('content')
@include('partials.stepper', ['current' => 3])

@error('template')
    <div class="message message-error" role="alert">{{ $message }}</div>
@enderror

<div class="preview-bar">
    <a href="{{ route('portfolios.index') }}" class="btn btn-ghost btn-back">Back to Manage</a>
    <div class="preview-bar-actions">
        <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="btn btn-secondary">Edit Information</a>
        <a href="{{ route('templates', $portfolio->id) }}" class="btn btn-secondary">Change Template</a>
        <form method="POST" action="{{ route('preview.save', $portfolio->id) }}" class="inline-form">
            @csrf
            @method('PATCH')
            <input type="hidden" name="template" value="{{ $view }}">
            <button type="submit" class="btn btn-primary" @disabled(! $changed)>Save / Update</button>
        </form>
        <a href="{{ route('portfolios.confirm-delete', $portfolio->id) }}" class="btn btn-danger-outline">Delete Portfolio</a>
    </div>
</div>

<div class="switcher-row">
    <div class="switcher" role="group" aria-label="Try a template">
        @foreach ($tpl->templates() as $id => $info)
            <a href="{{ route('preview', ['id' => $portfolio->id, 'view' => $id]) }}" @if ($view === $id) aria-current="true" @endif>{{ $info['name'] }}</a>
        @endforeach
    </div>
    <span class="switcher-status" role="status">
        @if ($changed)
            Showing {{ $tpl->name($view) }} (not saved yet). Click Save / Update to keep it.
        @else
            Saved template: {{ $tpl->name($portfolio->selected_template) }}
        @endif
    </span>
</div>

<div class="browser">
    <div class="browser-bar" aria-hidden="true">
        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
        <span class="browser-url">{{ $portfolio->full_name }} - {{ $tpl->name($view) }} template</span>
    </div>
    <div class="tpl-wrap">
        @include('templates.' . $view, ['p' => $portfolio])
    </div>
</div>
@endsection
