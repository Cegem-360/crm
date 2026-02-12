<?php

declare(strict_types=1);

namespace App\Filament\Resources\Companies\Pages;

use Override;
use App\Filament\Resources\Companies\CompanyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

final class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

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
