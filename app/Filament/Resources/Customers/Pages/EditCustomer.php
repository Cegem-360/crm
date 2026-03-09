<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Concerns\ManagesCustomFields;
use App\Filament\Resources\Customers\Actions\AnonymizeCustomerAction;
use App\Filament\Resources\Customers\Actions\CreateOpportunityAction;
use App\Filament\Resources\Customers\Actions\ExportCustomerDataAction;
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditCustomer extends EditRecord
{
    use ManagesCustomFields;

    protected static string $resource = CustomerResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateOpportunityAction::make(),
            ExportCustomerDataAction::make(),
            AnonymizeCustomerAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            ViewAction::make(),
        ];
    }
}
