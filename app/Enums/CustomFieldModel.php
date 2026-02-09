<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Task;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CustomFieldModel: string implements HasColor, HasLabel
{
    case Customer = 'customer';
    case Order = 'order';
    case Quote = 'quote';
    case Invoice = 'invoice';
    case Opportunity = 'opportunity';
    case Task = 'task';
    case Complaint = 'complaint';
    case Product = 'product';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Customer => __('Customer'),
            self::Order => __('Order'),
            self::Quote => __('Quote'),
            self::Invoice => __('Invoice'),
            self::Opportunity => __('Opportunity'),
            self::Task => __('Task'),
            self::Complaint => __('Complaint'),
            self::Product => __('Product'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Customer => 'primary',
            self::Order => 'success',
            self::Quote => 'info',
            self::Invoice => 'warning',
            self::Opportunity => 'danger',
            self::Task => 'gray',
            self::Complaint => 'danger',
            self::Product => 'info',
        };
    }

    /**
     * @return class-string
     */
    public function getModelClass(): string
    {
        return match ($this) {
            self::Customer => Customer::class,
            self::Order => Order::class,
            self::Quote => Quote::class,
            self::Invoice => Invoice::class,
            self::Opportunity => Opportunity::class,
            self::Task => Task::class,
            self::Complaint => Complaint::class,
            self::Product => Product::class,
        };
    }
}
