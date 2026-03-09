<?php

declare(strict_types=1);

namespace App\Filament\Resources\Quotes\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Customers\Actions\AcceptQuoteAction;
use App\Filament\Resources\Customers\Actions\GenerateOrderAction;
use App\Filament\Resources\Customers\Actions\ViewGeneratedOrderAction;
use App\Filament\Resources\Quotes\QuoteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditQuote extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = QuoteResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            AcceptQuoteAction::make(),
            GenerateOrderAction::make(),
            ViewGeneratedOrderAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            ViewAction::make(),
        ];
    }
}
