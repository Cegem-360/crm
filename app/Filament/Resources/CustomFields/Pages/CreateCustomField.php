<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomFields\Pages;

use App\Filament\Resources\CustomFields\CustomFieldResource;
use App\Services\CustomFieldService;
use Filament\Resources\Pages\CreateRecord;

final class CreateCustomField extends CreateRecord
{
    protected static string $resource = CustomFieldResource::class;

    protected function afterCreate(): void
    {
        resolve(CustomFieldService::class)->clearCache($this->record->model_type);
    }
}
