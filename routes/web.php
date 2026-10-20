<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

// Portfolio ids are plain whole numbers.
Route::pattern('id', '[0-9]{1,9}');

// ---------- Public pages ----------
Route::view('/', 'home')->name('home');

// Light / dark mode: the choice is saved in a cookie (no JavaScript needed).
Route::post('/theme', [PageController::class, 'theme'])->name('theme');

// Footer feedback box (guests and logged-in users; limited to 5 messages per minute).
Route::post('/feedback', [FeedbackController::class, 'store'])->middleware('throttle:5,1')->name('feedback.store');

// Quick check that the site can reach the database (reveals no data).
Route::get('/api/health', [PageController::class, 'health'])->name('api.health');

// ---------- Guests only: Log in, Get Started (register), Forgot password ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:20,1')->name('login.store');

    Route::get('/get-started', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/get-started', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->middleware('throttle:5,1')->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:10,1')->name('password.update');
});

// ---------- Logged-in users only ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/manage', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('/create', [PortfolioController::class, 'create'])->name('portfolios.create');
    Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
    Route::get('/edit/{id}', [PortfolioController::class, 'edit'])->name('portfolios.edit');
    Route::put('/portfolios/{id}', [PortfolioController::class, 'update'])->name('portfolios.update');
    Route::get('/portfolios/{id}/delete', [PortfolioController::class, 'confirmDelete'])->name('portfolios.confirm-delete');
    Route::delete('/portfolios/{id}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');

    Route::get('/templates', [PortfolioController::class, 'templatesRedirect'])->name('templates.redirect');
    Route::get('/templates/{id}', [PortfolioController::class, 'templates'])->name('templates');
    Route::post('/templates/{id}', [PortfolioController::class, 'saveTemplate'])->name('templates.save');

    Route::get('/preview/{id}', [PortfolioController::class, 'preview'])->name('preview');
    Route::patch('/preview/{id}', [PortfolioController::class, 'savePreview'])->name('preview.save');

    // Read-only JSON (only YOUR portfolios).
    Route::get('/api/portfolios', [PortfolioController::class, 'apiIndex'])->name('api.portfolios');
    Route::get('/api/portfolios/{id}', [PortfolioController::class, 'apiShow'])->name('api.portfolio');
});
