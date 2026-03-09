<?php

declare(strict_types=1);

use App\Http\Controllers\GoogleCalendarController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', fn (): Factory|View => view('home'))->name('home');

// Guest routes (redirect to Filament auth)
Route::middleware(['guest'])->group(function (): void {
    Route::get('/login', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.login'))->name('login');
});

// Language switch route
Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'hu'], true), 400);

    $cookie = cookie('locale', $locale, 60 * 24 * 365);

    $referer = request()->headers->get('referer');
    $redirectUrl = $referer ?: url()->previous();

    return redirect($redirectUrl)->withCookie($cookie);
})->name('language.switch');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function (): void {
    // Google Calendar OAuth
    Route::get('/google/redirect', [GoogleCalendarController::class, 'redirect'])->name('google.redirect');
    Route::get('/google/callback', [GoogleCalendarController::class, 'callback'])->name('google.callback');
});
