<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Enums\OpportunityStage;
use App\Enums\QuoteStatus;
use App\Models\Opportunity;
use App\Models\Quote;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

final class GenerateQuoteAction
{
    public static function make(): Action
    {
        return Action::make('generate_quote')
            ->label('Generate Quote')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Generate Quote from Opportunity')
            ->modalDescription('This will create a new quote based on this opportunity data.')
            ->modalSubmitActionLabel('Generate Quote')
            ->action(function (Opportunity $record): void {
                $lastQuote = Quote::query()
                    ->whereYear('created_at', now()->year)
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = $lastQuote ? ((int) mb_substr((string) $lastQuote->quote_number, -4)) + 1 : 1;
                $quoteNumber = 'QUO-'.now()->year.'-'.mb_str_pad(
                    (string) $nextNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

                $subtotal = $record->value ?? 0;
                $taxAmount = $subtotal * 0.27;
                $total = $subtotal + $taxAmount;

                $quote = Quote::query()->create([
                    'customer_id' => $record->customer_id,
                    'opportunity_id' => $record->id,
                    'quote_number' => $quoteNumber,
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
                    ->title('Quote Generated Successfully')
                    ->body(sprintf(
                        'Quote #%s has been created with a value of %s HUF.',
                        $quote->quote_number,
                        number_format($total, 2),
                    ))
                    ->send();
            })
            ->visible(fn (Opportunity $record): bool => $record->stage === OpportunityStage::Lead);
    }
}
