<?php

declare(strict_types=1);

namespace App\Filament\Resources\Companies\Pages;

use Override;
use App\Filament\Resources\Companies\CompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
