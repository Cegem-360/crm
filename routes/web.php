<?php

declare(strict_types=1);

use App\Http\Controllers\ChatDemoController;
use App\Livewire\ComplaintSubmission;
use App\Livewire\Dashboard;
use App\Livewire\Pages\Crm\Companies;
use App\Livewire\Pages\Crm\Contacts;
use App\Livewire\Pages\Crm\Customers;
use App\Livewire\Pages\Interactions\ChatSessions;
use App\Livewire\Pages\Interactions\Interactions;
use App\Livewire\Pages\Marketing\Campaigns;
use App\Livewire\Pages\Products\Discounts;
use App\Livewire\Pages\Products\ProductCategories;
use App\Livewire\Pages\Products\Products;
use App\Livewire\Pages\Sales\Invoices;
use App\Livewire\Pages\Sales\Opportunities;
use App\Livewire\Pages\Sales\Orders;
use App\Livewire\Pages\Sales\Quotes;
use App\Livewire\Pages\Support\Complaints;
use App\Livewire\Pages\Support\Tasks;
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

// Complaint submission route (public - for customers to submit complaints)
Route::get('/complaints/submit', ComplaintSubmission::class)->name('complaints.submit');

// Language switch route
Route::get('/language/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'hu'], true)) {
        abort(400);
    }

    $cookie = cookie('locale', $locale, 60 * 24 * 365);

    $referer = request()->headers->get('referer');
    $redirectUrl = $referer ?: url()->previous();

    return redirect($redirectUrl)->withCookie($cookie);
})->name('language.switch');

// CRM Dashboard routes (authenticated users only)
Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::prefix('dashboard')->name('dashboard.')->group(function (): void {
        Route::get('/', Dashboard::class)->name('dashboard');

        // Customers
        Route::get('/customers', Customers::class)->name('customers');
        Route::get('/companies', Companies::class)->name('companies');
        Route::get('/contacts', Contacts::class)->name('contacts');

        // Sales
        Route::get('/opportunities', Opportunities::class)->name('opportunities');
        Route::get('/quotes', Quotes::class)->name('quotes');
        Route::get('/orders', Orders::class)->name('orders');
        Route::get('/invoices', Invoices::class)->name('invoices');

        // Products
        Route::get('/products', Products::class)->name('products');
        Route::get('/product-categories', ProductCategories::class)->name('product-categories');
        Route::get('/discounts', Discounts::class)->name('discounts');

        // Support
        Route::get('/tasks', Tasks::class)->name('tasks');
        Route::get('/complaints', Complaints::class)->name('complaints');

        // Interactions
        Route::get('/interactions', Interactions::class)->name('interactions');
        Route::get('/chat-sessions', ChatSessions::class)->name('chat-sessions');

        // Marketing
        Route::get('/campaigns', Campaigns::class)->name('campaigns');
    });

    // Chat demo route
    Route::get('/chat-demo', [ChatDemoController::class, 'index'])->name('chat.demo');
});
