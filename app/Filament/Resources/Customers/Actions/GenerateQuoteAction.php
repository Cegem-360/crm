<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Enums\QuoteStatus;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Opportunity;
use App\Models\Quote;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Number;

final class GenerateQuoteAction
{
    public static function make(): Action
    {
        return Action::make('generateQuote')
            ->label(__('Generate Quote'))
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading(__('Generate Quote from Opportunity'))
            ->modalDescription(__('This will create a new quote based on this opportunity data.'))
            ->modalSubmitActionLabel(__('Generate Quote'))
            ->action(function (Opportunity $record): void {
                $subtotal = $record->value ?? 0;
                $taxAmount = $subtotal * 0.27;
                $total = $subtotal + $taxAmount;

                $quote = Quote::query()->create([
                    'team_id' => $record->team_id,
                    'customer_id' => $record->customer_id,
                    'opportunity_id' => $record->id,
                    'issue_date' => now(),
                    'valid_until' => now()->addDays(30),
                    'status' => QuoteStatus::Draft,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'notes' => 'Generated from Opportunity: '.$record->title.($record->description ? ' '.$record->description : ''),
                ]);

                Notification::make()
                    ->success()
                    ->title(__('Quote Generated Successfully'))
                    ->body(sprintf(
                        __('Quote #%s has been created with a value of %s.'),
                        $quote->quote_number,
                        Number::currency($total, 'HUF', 'hu', 0),
                    ))
                    ->actions([
                        Action::make('viewQuote')
                            ->label(__('View quote'))
                            ->button()
                            ->url(QuoteResource::getUrl('view', ['record' => $quote])),
                    ])
                    ->send();
            })
            ->visible(fn (Opportunity $record): bool => ! $record->quotes()->exists());
    }
}
