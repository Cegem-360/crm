<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmailTemplates\Pages;

use Override;
use App\Filament\Resources\EmailTemplates\EmailTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
