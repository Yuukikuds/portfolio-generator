@extends('layouts.app')

@section('title', 'Forgot password - PortfolioCraft')

@section('content')
<section class="card auth-card">
    <header class="auth-head">
        <span class="eyebrow">Account help</span>
        <h1>Forgot your password?</h1>
        <p>Enter your account email. We will send you a link to choose a new password.</p>
    </header>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <x-input name="email" label="Email" type="email" :value="old('email')" :required="true" autocomplete="email" autofocus />

        <button type="submit" class="btn btn-primary btn-large auth-submit">Send reset link</button>
    </form>

    <p class="auth-foot"><a href="{{ route('login') }}">Back to Log in</a></p>
</section>
@endsection
