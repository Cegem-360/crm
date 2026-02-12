<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\Pages;

use Override;
use App\Filament\Resources\Teams\TeamResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
