<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\CustomFieldModel;
use App\Enums\InvoiceStatus;
use App\Filament\Concerns\HasCustomFieldsSchema;
use App\Filament\Schemas\Components\DocumentChain;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class InvoiceForm
{
    use HasCustomFieldsSchema;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                DocumentChain::make()
                    ->columnSpanFull(),
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('order_id')
                    ->label(__('Order'))
                    ->relationship('order', 'order_number')
                    ->searchable()
                    ->preload(),
                TextInput::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->disabled()
                    ->dehydrated()
                    ->placeholder(__('Auto-generated')),
                DatePicker::make('issue_date')
                    ->label(__('Issue Date'))
                    ->required(),
                DatePicker::make('due_date')
                    ->label(__('Due Date'))
                    ->helperText(__('Payment deadline for this invoice'))
                    ->required(),
                Select::make('status')
                    ->required()
                    ->enum(InvoiceStatus::class)
                    ->options(InvoiceStatus::class)
                    ->default(InvoiceStatus::Draft),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                TextInput::make('discount_amount')
                    ->label(__('Total Discount'))
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                TextInput::make('tax_amount')
                    ->label(__('Tax Amount'))
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                TextInput::make('total')
                    ->label(__('Grand Total'))
                    ->required()
                    ->numeric()
                    ->prefix('Ft')
                    ->default(0),
                Textarea::make('notes')
                    ->placeholder(__('Payment terms, special instructions...'))
                    ->columnSpanFull(),
                DateTimePicker::make('paid_at')
                    ->label(__('Paid At'))
                    ->helperText(__('Leave empty if unpaid. Set when payment is received.')),
                FileUpload::make('files')
                    ->label(__('Attachments'))
                    ->helperText(__('Attach receipts, supporting documents, etc.'))
                    ->directory('invoices')
                    ->panelLayout('grid')
                    ->multiple()
                    ->columnSpanFull(),
                ...self::getCustomFieldsFormSection(CustomFieldModel::Invoice),
            ]);
    }
}
