<?php

declare(strict_types=1);

use App\Http\Controllers\ChatDemoController;
use App\Livewire\ComplaintSubmission;
use App\Livewire\Dashboard;
use App\Livewire\Pages\Crm\Companies\EditCompany;
use App\Livewire\Pages\Crm\Companies\ListCompanies;
use App\Livewire\Pages\Crm\Companies\ViewCompany;
use App\Livewire\Pages\Crm\Contacts\EditContact;
use App\Livewire\Pages\Crm\Contacts\ListContacts;
use App\Livewire\Pages\Crm\Contacts\ViewContact;
use App\Livewire\Pages\Crm\Customers\EditCustomer;
use App\Livewire\Pages\Crm\Customers\ListCustomers;
use App\Livewire\Pages\Crm\Customers\ViewCustomer;
use App\Livewire\Pages\Interactions\ChatSessions\ListChatSessions;
use App\Livewire\Pages\Interactions\Interactions\ListInteractions;
use App\Livewire\Pages\Marketing\Campaigns;
use App\Livewire\Pages\Products\Discounts;
use App\Livewire\Pages\Products\ProductCategories;
use App\Livewire\Pages\Products\Products;
use App\Livewire\Pages\Sales\Invoices\EditInvoice;
use App\Livewire\Pages\Sales\Invoices\ListInvoices;
use App\Livewire\Pages\Sales\Invoices\ViewInvoice;
use App\Livewire\Pages\Sales\Opportunities\EditOpportunity;
use App\Livewire\Pages\Sales\Opportunities\ListOpportunities;
use App\Livewire\Pages\Sales\Opportunities\ViewOpportunity;
use App\Livewire\Pages\Sales\Orders\EditOrder;
use App\Livewire\Pages\Sales\Orders\ListOrders;
use App\Livewire\Pages\Sales\Orders\ViewOrder;
use App\Livewire\Pages\Sales\Quotes\EditQuote;
use App\Livewire\Pages\Sales\Quotes\ListQuotes;
use App\Livewire\Pages\Sales\Quotes\ViewQuote;
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

        // Companies
        Route::get('/companies', ListCompanies::class)->name('companies');
        Route::get('/companies/create', EditCompany::class)->name('companies.create');
        Route::get('/companies/{company}', ViewCompany::class)->name('companies.view');
        Route::get('/companies/{company}/edit', EditCompany::class)->name('companies.edit');

        // Customers
        Route::get('/customers', ListCustomers::class)->name('customers');
        Route::get('/customers/create', EditCustomer::class)->name('customers.create');
        Route::get('/customers/{customer}', ViewCustomer::class)->name('customers.view');
        Route::get('/customers/{customer}/edit', EditCustomer::class)->name('customers.edit');

        // Contacts
        Route::get('/contacts', ListContacts::class)->name('contacts');
        Route::get('/contacts/create', EditContact::class)->name('contacts.create');
        Route::get('/contacts/{contact}', ViewContact::class)->name('contacts.view');
        Route::get('/contacts/{contact}/edit', EditContact::class)->name('contacts.edit');

        // Opportunities
        Route::get('/opportunities', ListOpportunities::class)->name('opportunities');
        Route::get('/opportunities/create', EditOpportunity::class)->name('opportunities.create');
        Route::get('/opportunities/{opportunity}', ViewOpportunity::class)->name('opportunities.view');
        Route::get('/opportunities/{opportunity}/edit', EditOpportunity::class)->name('opportunities.edit');

        // Quotes
        Route::get('/quotes', ListQuotes::class)->name('quotes');
        Route::get('/quotes/create', EditQuote::class)->name('quotes.create');
        Route::get('/quotes/{quote}', ViewQuote::class)->name('quotes.view');
        Route::get('/quotes/{quote}/edit', EditQuote::class)->name('quotes.edit');

        // Orders
        Route::get('/orders', ListOrders::class)->name('orders');
        Route::get('/orders/create', EditOrder::class)->name('orders.create');
        Route::get('/orders/{order}', ViewOrder::class)->name('orders.view');
        Route::get('/orders/{order}/edit', EditOrder::class)->name('orders.edit');

        // Invoices
        Route::get('/invoices', ListInvoices::class)->name('invoices');
        Route::get('/invoices/create', EditInvoice::class)->name('invoices.create');
        Route::get('/invoices/{invoice}', ViewInvoice::class)->name('invoices.view');
        Route::get('/invoices/{invoice}/edit', EditInvoice::class)->name('invoices.edit');

        // Products
        Route::get('/products', Products::class)->name('products');
        Route::get('/product-categories', ProductCategories::class)->name('product-categories');
        Route::get('/discounts', Discounts::class)->name('discounts');

        // Support
        Route::get('/tasks', Tasks::class)->name('tasks');
        Route::get('/complaints', Complaints::class)->name('complaints');

        // Interactions
        Route::get('/interactions', ListInteractions::class)->name('interactions');
        Route::get('/chat-sessions', ListChatSessions::class)->name('chat-sessions');

        // Marketing
        Route::get('/campaigns', Campaigns::class)->name('campaigns');
    });

    // Chat demo route
    Route::get('/chat-demo', [ChatDemoController::class, 'index'])->name('chat.demo');
});
