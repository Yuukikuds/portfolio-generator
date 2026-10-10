<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

// Portfolio ids are plain whole numbers.
Route::pattern('id', '[0-9]{1,9}');

// ---------- Pages ----------
Route::view('/', 'home')->name('home');

// Light / dark mode: the choice is saved in a cookie (no JavaScript needed).
Route::post('/theme', [PageController::class, 'theme'])->name('theme');

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

// ---------- Read-only JSON endpoints (handy for testing the database) ----------
Route::get('/api/health', [PageController::class, 'health'])->name('api.health');

Route::get('/api/portfolios', [PortfolioController::class, 'apiIndex'])->name('api.portfolios');
Route::get('/api/portfolios/{id}', [PortfolioController::class, 'apiShow'])->name('api.portfolio');
