<?php

declare(strict_types=1);

namespace App\Filament\Resources\Contacts\Pages;

use Override;
use App\Filament\Resources\Contacts\ContactResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditContact extends EditRecord
{
    protected static string $resource = ContactResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
