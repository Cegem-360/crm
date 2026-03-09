<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Actions;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Number;

final class GenerateInvoiceAction
{
    public static function make(): Action
    {
        return Action::make('generateInvoice')
            ->label(__('Generate Invoice'))
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading(__('Generate Invoice from Order'))
            ->modalDescription(__('This will create a new invoice based on this order data. Are you sure you want to continue?'))
            ->modalSubmitActionLabel(__('Generate Invoice'))
            ->action(function (Order $record): void {
                if ($record->invoices()->exists()) {
                    Notification::make()
                        ->warning()
                        ->title(__('Invoice Already Exists'))
                        ->body(__('An invoice has already been generated for this order.'))
                        ->send();

                    return;
                }

                $invoice = Invoice::query()->create([
                    'team_id' => $record->team_id,
                    'customer_id' => $record->customer_id,
                    'order_id' => $record->id,
                    'issue_date' => now(),
                    'due_date' => now()->addDays(30),
                    'status' => InvoiceStatus::Draft,
                    'subtotal' => $record->subtotal,
                    'discount_amount' => $record->discount_amount,
                    'tax_amount' => $record->tax_amount,
                    'total' => $record->total,
                    'notes' => $record->notes
                        ? __('Generated from Order #:number', ['number' => $record->order_number]).('

'.$record->notes)
                        : __('Generated from Order #:number', ['number' => $record->order_number]),
                ]);

                Notification::make()
                    ->success()
                    ->title(__('Invoice Generated Successfully'))
                    ->body(sprintf(
                        __('Invoice #%s has been created with a total value of %s.'),
                        $invoice->invoice_number,
                        Number::currency((float) $record->total, 'HUF', 'hu', 0),
                    ))
                    ->actions([
                        Action::make('viewInvoice')
                            ->label(__('View invoice'))
                            ->button()
                            ->url(InvoiceResource::getUrl('view', ['record' => $invoice])),
                    ])
                    ->send();
            })
            ->visible(fn (Order $record): bool => ! $record->invoices()->exists());
    }
}
