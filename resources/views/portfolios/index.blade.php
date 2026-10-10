@extends('layouts.app')

@section('title', 'Manage Portfolios - PortfolioCraft')

@section('content')
<div class="page-head">
    <div>
        <h1>Manage Portfolios</h1>
        <p>{{ $portfolios->count() }} saved {{ \Illuminate\Support\Str::plural('portfolio', $portfolios->count()) }}</p>
    </div>
    <a href="{{ route('portfolios.create') }}" class="btn btn-primary">Create New Portfolio</a>
</div>

@if ($portfolios->isEmpty())
    <section class="empty-state">
        <div class="icon-box" aria-hidden="true">{!! $icons->svg('plus') !!}</div>
        <h2>No portfolios yet</h2>
        <p>Create your first portfolio and it will appear here, ready to view, edit, or share.</p>
        <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-large">Create Portfolio</a>
    </section>
@else
    <ul class="manage-grid">
        @foreach ($portfolios as $item)
            <li class="card manage-card">
                <div class="manage-top">
                    <span class="avatar-chip" aria-hidden="true">{{ $tpl->initials($item->full_name) }}</span>
                    <div>
                        <h2>{{ $item->full_name }}</h2>
                        <p class="manage-email">{{ $item->email }}</p>
                    </div>
                    <span class="badge">{{ $tpl->name($item->selected_template) }}</span>
                </div>
                <dl class="meta-list">
                    <div>
                        <dt>Created</dt>
                        <dd>{{ $item->created_at?->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt>Last updated</dt>
                        <dd>{{ $item->updated_at?->format('M j, Y g:i A') }}</dd>
                    </div>
                </dl>
                <div class="card-actions">
                    <a href="{{ route('preview', $item->id) }}" class="btn btn-primary btn-small">View</a>
                    <a href="{{ route('portfolios.edit', $item->id) }}" class="btn btn-secondary btn-small">Edit</a>
                    <a href="{{ route('templates', $item->id) }}" class="btn btn-ghost btn-small">Change Template</a>
                    <a href="{{ route('portfolios.confirm-delete', $item->id) }}" class="btn btn-danger-outline btn-small" aria-label="Delete portfolio of {{ $item->full_name }}">Delete</a>
                </div>
            </li>
        @endforeach
    </ul>
@endif
@endsection
