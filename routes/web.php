<?php

declare(strict_types=1);

use App\Http\Controllers\ChatDemoController;
use App\Livewire\ComplaintSubmission;
use App\Livewire\Dashboard;
use App\Livewire\Pages\Crm\Companies;
use App\Livewire\Pages\Crm\Contacts;
use App\Livewire\Pages\Crm\Customers;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('home'))->name('home');

// CRM Dashboard routes
Route::prefix('crm')->name('crm.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/customers', Customers::class)->name('customers');
    Route::get('/companies', Companies::class)->name('companies');
    Route::get('/contacts', Contacts::class)->name('contacts');
});

// Chat demo route
Route::get('/chat-demo', [ChatDemoController::class, 'index'])->name('chat.demo');

// Complaint submission route
Route::get('/complaints/submit', ComplaintSubmission::class)->name('complaints.submit');
