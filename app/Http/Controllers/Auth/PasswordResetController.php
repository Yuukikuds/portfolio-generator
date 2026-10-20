<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

/** Forgot password: email a one-time link (valid 60 minutes), then choose a new password. */
class PasswordResetController extends Controller
{
    public function showForgot(): View
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request): RedirectResponse
    {
        $data = $request->validate(
            ['email' => ['required', 'string', 'email', 'max:150']],
            ['email.required' => 'Please enter your email address.', 'email.email' => 'Please enter a valid email address.']
        );

        try {
            Password::sendResetLink(['email' => Str::lower(trim($data['email']))]);
        } catch (\Throwable $e) {
            // Most likely the email service is not set up yet. The details go to storage/logs/laravel.log.
            report($e);
        }

        // Always the same answer, so nobody can find out which emails have accounts.
        return back()->with('status', 'If an account exists for that email, we have sent a password reset link. Please check your inbox (and spam folder). The link works for 60 minutes.');
    }

    public function showReset(Request $request, string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => (string) $request->query('email', '')]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ], [
            'password.required' => 'Please choose a new password.',
            'password.confirmed' => 'The two passwords do not match.',
            'password.min' => 'Your password must be at least 8 characters.',
            'password.letters' => 'Your password must contain at least one letter.',
            'password.numbers' => 'Your password must contain at least one number.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => $password, // hashed by the User model
                    'remember_token' => Str::random(60),
                ])->save();

                // Log the account out everywhere (any old session or stolen cookie stops working).
                DB::table('sessions')->where('user_id', $user->id)->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'This reset link is invalid or has expired. Please request a new one.',
            ]);
        }

        return redirect()->route('login')->with('status', 'Your password was changed. Please log in with your new password.');
    }
}
