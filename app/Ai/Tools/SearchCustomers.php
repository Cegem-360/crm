<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\Customer;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class SearchCustomers implements Tool
{
    public function description(): Stringable|string
    {
        return 'Search for customers (clients/companies) in the CRM by name, email, phone, or tax number. Returns matching customers with their contact details and status.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'];

        $customers = Customer::query()
            ->where(function ($builder) use ($query): void {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('tax_number', 'like', "%{$query}%")
                    ->orWhere('unique_identifier', 'like', "%{$query}%");
            })
            ->with(['contacts', 'addresses'])
            ->limit(8)
            ->get(['id', 'unique_identifier', 'name', 'type', 'email', 'phone', 'tax_number', 'is_active', 'notes']);

        if ($customers->isEmpty()) {
            return "No customers found matching '{$query}'.";
        }

        return $customers->map(function (Customer $customer): string {
            $lines = [
                "**{$customer->name}** ({$customer->unique_identifier})",
                "Type: {$customer->type->getLabel()}",
                'Status: '.($customer->is_active ? 'Active' : 'Inactive'),
            ];

            if ($customer->email) {
                $lines[] = "Email: {$customer->email}";
            }

            if ($customer->phone) {
                $lines[] = "Phone: {$customer->phone}";
            }

            if ($customer->tax_number) {
                $lines[] = "Tax number: {$customer->tax_number}";
            }

            if ($customer->notes) {
                $lines[] = 'Notes: '.str($customer->notes)->limit(150);
            }

            $primaryContact = $customer->contacts->first();

            if ($primaryContact) {
                $lines[] = "Primary contact: {$primaryContact->name}".($primaryContact->email ? " ({$primaryContact->email})" : '');
            }

            return implode("\n", $lines);
        })->implode("\n\n---\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->required()->description('Search term to find customers by name, email, phone, or tax number'),
        ];
    }
}
