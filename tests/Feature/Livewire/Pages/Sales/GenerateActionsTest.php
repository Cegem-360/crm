<?php

declare(strict_types=1);

use App\Livewire\Pages\Sales\Opportunities\ListOpportunities;
use App\Livewire\Pages\Sales\Opportunities\ViewOpportunity;
use App\Livewire\Pages\Sales\Orders\ListOrders;
use App\Livewire\Pages\Sales\Orders\ViewOrder;
use App\Livewire\Pages\Sales\Quotes\ListQuotes;
use App\Livewire\Pages\Sales\Quotes\ViewQuote;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->team = setUpFrontendTenant($this->user);
    $this->customer = Customer::factory()->for($this->team)->create();
});

// --- Opportunity → Quote ---

it('can generate a quote from opportunity view page', function (): void {
    $opportunity = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
        'value' => 10000,
    ]);

    Livewire::test(ViewOpportunity::class, ['opportunity' => $opportunity])
        ->callAction('generateQuote')
        ->assertNotified();

    expect(Quote::query()->where('opportunity_id', $opportunity->id)->exists())->toBeTrue();
});

it('hides generate quote action when quote already exists', function (): void {
    $opportunity = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
    ]);
    Quote::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
        'opportunity_id' => $opportunity->id,
    ]);

    Livewire::test(ViewOpportunity::class, ['opportunity' => $opportunity])
        ->assertActionHidden('generateQuote');
});

it('can generate a quote from opportunities list page', function (): void {
    $opportunity = Opportunity::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
        'value' => 5000,
    ]);

    Livewire::test(ListOpportunities::class)
        ->callTableAction('generateQuote', $opportunity)
        ->assertNotified();

    expect(Quote::query()->where('opportunity_id', $opportunity->id)->exists())->toBeTrue();
});

// --- Quote → Order ---

it('can generate an order from quote view page', function (): void {
    $quote = Quote::factory()->for($this->team)->accepted()->create([
        'customer_id' => $this->customer->id,
        'subtotal' => 8000,
        'discount_amount' => 500,
        'tax_amount' => 2025,
        'total' => 9525,
    ]);

    Livewire::test(ViewQuote::class, ['quote' => $quote])
        ->callAction('generateOrder')
        ->assertNotified();

    $order = Order::query()->where('quote_id', $quote->id)->first();
    expect($order)->not->toBeNull()
        ->and($order->team_id)->toBe($this->team->id)
        ->and($order->customer_id)->toBe($this->customer->id)
        ->and((float) $order->subtotal)->toBe(8000.0)
        ->and((float) $order->total)->toBe(9525.0);
});

it('hides generate order action when quote is not accepted', function (): void {
    $quote = Quote::factory()->for($this->team)->draft()->create([
        'customer_id' => $this->customer->id,
    ]);

    Livewire::test(ViewQuote::class, ['quote' => $quote])
        ->assertActionHidden('generateOrder');
});

it('hides generate order action when order already exists', function (): void {
    $quote = Quote::factory()->for($this->team)->accepted()->create([
        'customer_id' => $this->customer->id,
    ]);
    Order::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
        'quote_id' => $quote->id,
    ]);

    Livewire::test(ViewQuote::class, ['quote' => $quote])
        ->assertActionHidden('generateOrder');
});

it('can generate an order from quotes list page', function (): void {
    $quote = Quote::factory()->for($this->team)->accepted()->create([
        'customer_id' => $this->customer->id,
    ]);

    Livewire::test(ListQuotes::class)
        ->callTableAction('generateOrder', $quote)
        ->assertNotified();

    expect(Order::query()->where('quote_id', $quote->id)->exists())->toBeTrue();
});

// --- Order → Invoice ---

it('can generate an invoice from order view page', function (): void {
    $order = Order::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
        'subtotal' => 6000,
        'discount_amount' => 300,
        'tax_amount' => 1539,
        'total' => 7239,
    ]);

    Livewire::test(ViewOrder::class, ['order' => $order])
        ->callAction('generateInvoice')
        ->assertNotified();

    $invoice = Invoice::query()->where('order_id', $order->id)->first();
    expect($invoice)->not->toBeNull()
        ->and($invoice->team_id)->toBe($this->team->id)
        ->and($invoice->customer_id)->toBe($this->customer->id)
        ->and((float) $invoice->total)->toBe(7239.0);
});

it('hides generate invoice action when invoice already exists', function (): void {
    $order = Order::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
    ]);
    Invoice::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
        'order_id' => $order->id,
    ]);

    Livewire::test(ViewOrder::class, ['order' => $order])
        ->assertActionHidden('generateInvoice');
});

it('can generate an invoice from orders list page', function (): void {
    $order = Order::factory()->for($this->team)->create([
        'customer_id' => $this->customer->id,
    ]);

    Livewire::test(ListOrders::class)
        ->callTableAction('generateInvoice', $order)
        ->assertNotified();

    expect(Invoice::query()->where('order_id', $order->id)->exists())->toBeTrue();
});
