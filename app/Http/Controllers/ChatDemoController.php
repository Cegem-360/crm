<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CustomerType;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

final class ChatDemoController extends Controller
{
    public function index(): View
    {
        $existingContact = CustomerContact::query()
            ->where('email', 'demo@example.com')
            ->first();

        if ($existingContact) {
            $customer = $existingContact->customer;
        } else {
            $customer = Customer::query()->create([
                'unique_identifier' => 'DEMO-'.Str::random(10),
                'name' => 'Demo Customer',
                'phone' => '+36 20 123 4567',
                'type' => CustomerType::Individual,
                'is_active' => true,
            ]);

            $customer->contacts()->create([
                'name' => 'Demo Customer',
                'email' => 'demo@example.com',
                'phone' => '+36 20 123 4567',
                'is_primary' => true,
            ]);
        }

        return view('chat-demo', ['customer' => $customer]);
    }
}
