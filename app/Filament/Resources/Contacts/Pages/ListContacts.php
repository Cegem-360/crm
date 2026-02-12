<?php

declare(strict_types=1);

namespace App\Filament\Resources\Contacts\Pages;

use Override;
use App\Filament\Resources\Contacts\ContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
