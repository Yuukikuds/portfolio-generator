@extends('layouts.app')

@section('title', 'Page expired - PortfolioCraft')

@section('content')
<section class="card center narrow">
    <h1>This page expired</h1>
    <p>For your safety the form timed out. Please go back, reload the page, and try again.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
</section>
@endsection
