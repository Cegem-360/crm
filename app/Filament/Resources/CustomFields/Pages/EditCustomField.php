<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomFields\Pages;

use Override;
use App\Filament\Resources\CustomFields\CustomFieldResource;
use App\Services\CustomFieldService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditCustomField extends EditRecord
{
    protected static string $resource = CustomFieldResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        resolve(CustomFieldService::class)->clearCache($this->record->model_type);
    }

    protected function afterDelete(): void
    {
        resolve(CustomFieldService::class)->clearCache($this->record->model_type);
    }
}
