@extends('layouts.app')

@section('title', 'Delete Portfolio - PortfolioCraft')

@section('content')
<section class="card narrow center confirm-card">
    <h1>Delete this portfolio?</h1>
    <p>
        This will permanently delete the portfolio of <strong>{{ $portfolio->full_name }}</strong>
        from the database. This cannot be undone.
    </p>
    <form method="POST" action="{{ route('portfolios.destroy', $portfolio->id) }}" class="confirm-actions">
        @csrf
        @method('DELETE')
        <a href="{{ route('portfolios.index') }}" class="btn btn-secondary btn-large">Cancel</a>
        <button type="submit" class="btn btn-danger btn-large">Delete portfolio</button>
    </form>
</section>
@endsection
