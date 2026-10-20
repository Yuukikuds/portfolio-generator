<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/** Login, Get Started (register) and Logout. */
class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:150'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $email = Str::lower(trim($data['email']));

        // Brute-force protection: 5 wrong tries per email + IP, then a one-minute wait.
        $key = 'login:'.sha1($email.'|'.$request->ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withInput($request->only('email'))->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        if (! Auth::attempt(['email' => $email, 'password' => $data['password']], $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);

            // One message for "no such email" and "wrong password", so nobody can probe for accounts.
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'The email or password is incorrect.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate(); // protects against session fixation

        return redirect()->intended(route('portfolios.index'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\p{M}][\p{L}\p{M} .\x27’-]*$/u'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'name.required' => 'Please enter your name.',
            'name.regex' => 'Only letters are allowed in the name. Please remove any numbers or symbols.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An account with this email already exists. Try logging in instead.',
            'password.required' => 'Please choose a password.',
            'password.confirmed' => 'The two passwords do not match.',
            'password.min' => 'Your password must be at least 8 characters.',
            'password.letters' => 'Your password must contain at least one letter.',
            'password.numbers' => 'Your password must contain at least one number.',
        ]);

        // The "hashed" cast on the User model stores a bcrypt hash, never the plain password.
        $user = User::create([
            'name' => trim($data['name']),
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Welcome to PortfolioCraft! Your account is ready. Click Create Portfolio whenever you are ready to start.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out.');
    }
}
