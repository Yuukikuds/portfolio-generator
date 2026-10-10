@extends('layouts.app')

@section('title', 'Page not found - PortfolioCraft')

@section('content')
<section class="card center narrow">
    <h1>Page not found</h1>
    <p>Sorry, this page does not exist, or the portfolio may have been deleted.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
</section>
@endsection
