<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Customers\Actions\GenerateInvoiceAction;
use App\Filament\Resources\Customers\Actions\ViewGeneratedInvoiceAction;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditOrder extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = OrderResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            GenerateInvoiceAction::make(),
            ViewGeneratedInvoiceAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            ViewAction::make(),
        ];
    }
}
