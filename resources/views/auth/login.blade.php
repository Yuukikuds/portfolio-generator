@extends('layouts.app')

@section('title', 'Log in - PortfolioCraft')

@section('content')
<section class="card auth-card">
    <header class="auth-head">
        <span class="eyebrow">Welcome back</span>
        <h1>Log in</h1>
        <p>Log in with your email and password to create and manage your portfolios.</p>
    </header>

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf

        <x-input name="email" label="Email" type="email" :value="old('email')" :required="true" autocomplete="email" autofocus />
        <x-input name="password" label="Password" type="password" :required="true" autocomplete="current-password" />

        <div class="auth-row">
            <label class="check-row">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                <span>Keep me logged in</span>
            </label>
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-large auth-submit">Log in</button>
    </form>

    <p class="auth-foot">New to PortfolioCraft? <a href="{{ route('register') }}">Get Started - create an account</a></p>
</section>
@endsection
