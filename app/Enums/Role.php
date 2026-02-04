<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Role: string implements HasColor, HasLabel
{
    case Admin = 'Admin';
    case Manager = 'Manager';
    case SalesRepresentative = 'Sales Representative';
    case Support = 'Support';
    case Subscriber = 'Subscriber';

    /**
     * Get all role values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $role) => $role->value, self::cases());
    }

    /**
     * Get permissions for this role.
     *
     * @return array<Permission>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::Admin => Permission::cases(),
            self::Manager => [
                Permission::ViewAnyCustomer,
                Permission::ViewCustomer,
                Permission::CreateCustomer,
                Permission::UpdateCustomer,
                Permission::ViewAnyOrder,
                Permission::ViewOrder,
                Permission::CreateOrder,
                Permission::UpdateOrder,
                Permission::ViewAnyInvoice,
                Permission::ViewInvoice,
                Permission::CreateInvoice,
                Permission::UpdateInvoice,
                Permission::ViewAnyOpportunity,
                Permission::ViewOpportunity,
                Permission::CreateOpportunity,
                Permission::UpdateOpportunity,
                Permission::ViewAnyQuote,
                Permission::ViewQuote,
                Permission::CreateQuote,
                Permission::UpdateQuote,
                Permission::ViewAnyCampaign,
                Permission::ViewCampaign,
                Permission::ViewAnyProduct,
                Permission::ViewProduct,
                ...Permission::tasks(),
                ...Permission::complaints(),
            ],
            self::SalesRepresentative => [
                Permission::ViewAnyCustomer,
                Permission::ViewCustomer,
                Permission::CreateCustomer,
                Permission::UpdateCustomer,
                Permission::ViewAnyOpportunity,
                Permission::ViewOpportunity,
                Permission::CreateOpportunity,
                Permission::UpdateOpportunity,
                Permission::ViewAnyQuote,
                Permission::ViewQuote,
                Permission::CreateQuote,
                Permission::UpdateQuote,
                Permission::ViewAnyOrder,
                Permission::ViewOrder,
                Permission::CreateOrder,
                Permission::UpdateOrder,
                Permission::ViewAnyProduct,
                Permission::ViewProduct,
                ...Permission::tasks(),
                ...Permission::interactions(),
            ],
            self::Support => [
                Permission::ViewAnyCustomer,
                Permission::ViewCustomer,
                ...Permission::complaints(),
                ...Permission::tasks(),
                ...Permission::interactions(),
            ],
            self::Subscriber => [],
        };
    }

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Admin => __('Admin'),
            self::Manager => __('Manager'),
            self::SalesRepresentative => __('Sales Representative'),
            self::Support => __('Support'),
            self::Subscriber => __('Subscriber'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Manager => 'warning',
            self::SalesRepresentative => 'info',
            self::Support => 'success',
            self::Subscriber => 'gray',
        };
    }
}
