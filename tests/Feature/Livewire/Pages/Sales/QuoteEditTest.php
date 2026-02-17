<?php

declare(strict_types=1);

use App\Enums\QuoteStatus;
use App\Livewire\Pages\Sales\Quotes\EditQuote;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->team = setUpFrontendTenant($this->user);
});

it('can render create page', function (): void {
    Livewire::test(EditQuote::class)
        ->assertSuccessful();
});

it('can create a quote without items', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    Livewire::test(EditQuote::class)
        ->set('team', $this->team)
        ->fillForm([
            'customer_id' => $customer->id,
            'issue_date' => now()->toDateString(),
            'valid_until' => now()->addDays(30)->toDateString(),
            'status' => QuoteStatus::Draft,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect();

    $quote = Quote::query()->where('customer_id', $customer->id)->first();
    expect($quote)->not->toBeNull()
        ->and($quote->subtotal)->toBe('0.00')
        ->and($quote->discount_amount)->toBe('0.00')
        ->and($quote->tax_amount)->toBe('0.00')
        ->and($quote->total)->toBe('0.00');
});

it('can create a quote with items and calculated totals', function (): void {
    $customer = Customer::factory()->for($this->team)->create();

    Livewire::test(EditQuote::class)
        ->set('team', $this->team)
        ->fillForm([
            'customer_id' => $customer->id,
            'issue_date' => now()->toDateString(),
            'valid_until' => now()->addDays(30)->toDateString(),
            'status' => QuoteStatus::Draft,
            'items' => [
                [
                    'description' => 'Test Product',
                    'quantity' => 2,
                    'unit_price' => 1000,
                    'discount_percent' => 10,
                    'discount_amount' => 200,
                    'tax_rate' => 27,
                    'total' => 2286,
                ],
            ],
            'subtotal' => 2000,
            'discount_amount' => 200,
            'tax_amount' => 486,
            'total' => 2286,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect();

    $quote = Quote::query()->where('customer_id', $customer->id)->first();
    expect($quote)->not->toBeNull()
        ->and((float) $quote->subtotal)->toBe(2000.00)
        ->and((float) $quote->discount_amount)->toBe(200.00)
        ->and((float) $quote->tax_amount)->toBe(486.00)
        ->and((float) $quote->total)->toBe(2286.00)
        ->and($quote->items)->toHaveCount(1);
});
