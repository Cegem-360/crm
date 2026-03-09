<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Opportunity;
use Filament\Actions\Action;

final class ViewGeneratedQuoteAction
{
    public static function make(): Action
    {
        return Action::make('viewGeneratedQuote')
            ->label(__('View quote'))
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->url(fn (Opportunity $record): string => QuoteResource::getUrl('view', ['record' => $record->quotes()->first()]))
            ->visible(fn (Opportunity $record): bool => $record->quotes()->exists());
    }
}
