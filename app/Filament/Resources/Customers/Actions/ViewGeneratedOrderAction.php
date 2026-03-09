<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Quote;
use Filament\Actions\Action;

final class ViewGeneratedOrderAction
{
    public static function make(): Action
    {
        return Action::make('viewGeneratedOrder')
            ->label(__('View order'))
            ->icon('heroicon-o-shopping-cart')
            ->color('info')
            ->url(fn (Quote $record): string => OrderResource::getUrl('view', ['record' => $record->orders()->first()]))
            ->visible(fn (Quote $record): bool => $record->orders()->exists());
    }
}
