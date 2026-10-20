@extends('layouts.app')

@section('title', 'Get Started - PortfolioCraft')

@section('content')
<section class="card auth-card">
    <header class="auth-head">
        <span class="eyebrow">Get Started</span>
        <h1>Create your account</h1>
        <p>It takes less than a minute. Your portfolios stay private to your account.</p>
    </header>

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf

        <x-input name="name" label="Full name" :value="old('name')" :required="true" autocomplete="name" autofocus />
        <x-input name="email" label="Email" type="email" :value="old('email')" :required="true" autocomplete="email" />
        <x-input name="password" label="Password" type="password" :required="true" autocomplete="new-password"
                 hint="At least 8 characters, with at least one letter and one number." />
        <x-input name="password_confirmation" label="Confirm password" type="password" :required="true" autocomplete="new-password" />

        <button type="submit" class="btn btn-primary btn-large auth-submit">Create account</button>
    </form>

    <p class="auth-foot">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
</section>
@endsection
