@extends('layouts.app')

@section('title', 'Choose a new password - PortfolioCraft')

@section('content')
<section class="card auth-card">
    <header class="auth-head">
        <span class="eyebrow">Account help</span>
        <h1>Choose a new password</h1>
        <p>Pick a new password for your account.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-input name="email" label="Email" type="email" :value="old('email', $email)" :required="true" autocomplete="email" />
        <x-input name="password" label="New password" type="password" :required="true" autocomplete="new-password" autofocus
                 hint="At least 8 characters, with at least one letter and one number." />
        <x-input name="password_confirmation" label="Confirm new password" type="password" :required="true" autocomplete="new-password" />

        <button type="submit" class="btn btn-primary btn-large auth-submit">Change password</button>
    </form>

    <p class="auth-foot"><a href="{{ route('login') }}">Back to Log in</a></p>
</section>
@endsection
