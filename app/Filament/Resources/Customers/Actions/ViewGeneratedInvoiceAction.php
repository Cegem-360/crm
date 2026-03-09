<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Order;
use Filament\Actions\Action;

final class ViewGeneratedInvoiceAction
{
    public static function make(): Action
    {
        return Action::make('viewGeneratedInvoice')
            ->label(__('View invoice'))
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->url(fn (Order $record): string => InvoiceResource::getUrl('view', ['record' => $record->invoices()->first()]))
            ->visible(fn (Order $record): bool => $record->invoices()->exists());
    }
}
