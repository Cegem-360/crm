<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProductCategories\Pages;

use Override;
use App\Filament\Resources\ProductCategories\ProductCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditProductCategory extends EditRecord
{
    protected static string $resource = ProductCategoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
