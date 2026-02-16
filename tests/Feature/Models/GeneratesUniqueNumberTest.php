<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Team;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    app()->instance('current_team', $this->team);
});

it('auto-generates a unique identifier for customers', function (): void {
    $customer = Customer::factory()->create([
        'team_id' => $this->team->id,
        'unique_identifier' => null,
    ]);

    expect($customer->unique_identifier)->toStartWith('CUST-'.now()->year.'-')
        ->and($customer->unique_identifier)->toMatch('/^CUST-\d{4}-\d{4}$/');
});

it('auto-generates a quote number', function (): void {
    $quote = Quote::factory()->create([
        'team_id' => $this->team->id,
        'quote_number' => null,
    ]);

    expect($quote->quote_number)->toStartWith('QUO-'.now()->year.'-')
        ->and($quote->quote_number)->toMatch('/^QUO-\d{4}-\d{4}$/');
});

it('auto-generates an order number', function (): void {
    $order = Order::factory()->create([
        'team_id' => $this->team->id,
        'order_number' => null,
    ]);

    expect($order->order_number)->toStartWith('ORD-'.now()->year.'-')
        ->and($order->order_number)->toMatch('/^ORD-\d{4}-\d{4}$/');
});

it('auto-generates an invoice number', function (): void {
    $invoice = Invoice::factory()->create([
        'team_id' => $this->team->id,
        'invoice_number' => null,
    ]);

    expect($invoice->invoice_number)->toStartWith('INV-'.now()->year.'-')
        ->and($invoice->invoice_number)->toMatch('/^INV-\d{4}-\d{4}$/');
});

it('generates sequential numbers', function (): void {
    $customer1 = Customer::factory()->create([
        'team_id' => $this->team->id,
        'unique_identifier' => null,
    ]);

    $customer2 = Customer::factory()->create([
        'team_id' => $this->team->id,
        'unique_identifier' => null,
    ]);

    $year = now()->year;

    expect($customer1->unique_identifier)->toBe(sprintf('CUST-%d-0001', $year))
        ->and($customer2->unique_identifier)->toBe(sprintf('CUST-%d-0002', $year));
});

it('does not overwrite pre-set identifiers', function (): void {
    $customer = Customer::factory()->create([
        'team_id' => $this->team->id,
        'unique_identifier' => 'CUSTOM-ID-123',
    ]);

    expect($customer->unique_identifier)->toBe('CUSTOM-ID-123');
});

it('uses correct prefix for each model', function (): void {
    expect(Customer::uniqueNumberPrefix())->toBe('CUST')
        ->and(Quote::uniqueNumberPrefix())->toBe('QUO')
        ->and(Order::uniqueNumberPrefix())->toBe('ORD')
        ->and(Invoice::uniqueNumberPrefix())->toBe('INV');
});
