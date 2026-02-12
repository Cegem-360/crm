<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Pages;

use Override;
use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

final class EditCustomer extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = CustomerResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
